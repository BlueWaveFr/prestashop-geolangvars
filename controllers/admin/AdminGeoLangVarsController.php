<?php
/**
 * Admin Controller for Geo + Lang Variables
 *
 * @author    Bluewave - Stephane Geraut
 * @copyright Copyright (c) 2025 Stephane Geraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * @version   2.3.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class AdminGeoLangVarsController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();

        parent::__construct();

        $this->meta_title = $this->module->l('Geo + Lang Variables Configuration');
    }

    /**
     * Affichage principal avec onglets
     */
    public function initContent()
    {
        parent::initContent();

        // Traiter les actions du formulaire
        $this->processForm();

        // Récupérer l'onglet actif
        $activeTab = Tools::getValue('active_tab', 'status');

        // Récupérer les informations selon l'onglet
        switch ($activeTab) {
            case 'stats':
                $templateVars = $this->getStatsData();
                break;
            case 'settings':
                $templateVars = $this->getSettingsData();
                break;
            case 'geoip':
                $templateVars = $this->getGeoIpData();
                break;
            default:
                $templateVars = $this->getStatusData();
        }

        $templateVars['active_tab'] = $activeTab;

        // Assigner au template
        $this->context->smarty->assign($templateVars);

        // Afficher le contenu
        $this->content = $this->context->smarty->fetch(
            $this->module->getLocalPath() . 'views/templates/admin/configure.tpl'
        );

        $this->context->smarty->assign('content', $this->content);
    }

    /**
     * Traite les formulaires soumis
     */
    protected function processForm()
    {
        // Activation de la géolocalisation PrestaShop
        if (Tools::isSubmit('submitEnableGeoIP')) {
            Configuration::updateValue('PS_GEOLOCATION_ENABLED', 1);
            Configuration::updateValue('PS_GEOLOCATION_BEHAVIOR', 0);

            $this->confirmations[] = $this->module->l('PrestaShop Geolocation has been enabled!');
        }

        // Sauvegarde des paramètres
        if (Tools::isSubmit('submitSettings')) {
            Configuration::updateValue('GEOLANGVARS_ENABLE_STATS', (int)Tools::getValue('enable_stats'));
            Configuration::updateValue('GEOLANGVARS_STATS_RETENTION', (int)Tools::getValue('stats_retention'));
            Configuration::updateValue('GEOLANGVARS_ENABLE_CLOUDFLARE', (int)Tools::getValue('enable_cloudflare'));
            Configuration::updateValue('GEOLANGVARS_ENABLE_GEOIP', (int)Tools::getValue('enable_geoip'));
            Configuration::updateValue('GEOLANGVARS_ENABLE_FALLBACK', (int)Tools::getValue('enable_fallback'));

            $this->confirmations[] = $this->module->l('Settings saved successfully!');
        }

        // Nettoyage des statistiques
        if (Tools::isSubmit('submitClearStats')) {
            $days = (int)Configuration::get('GEOLANGVARS_STATS_RETENTION');
            $sql = 'DELETE FROM `' . _DB_PREFIX_ . 'geolangvars_stats` 
                    WHERE `date_add` < DATE_SUB(NOW(), INTERVAL ' . $days . ' DAY)';
            Db::getInstance()->execute($sql);

            $this->confirmations[] = $this->module->l('Old statistics have been cleaned!');
        }

        // Upload du fichier GeoIP
        if (Tools::isSubmit('submitUploadGeoIP')) {
            $this->processGeoIPUpload();
        }
    }

    /**
     * Traite l'upload du fichier GeoIP
     */
    protected function processGeoIPUpload()
    {
        if (!isset($_FILES['geoip_file']) || $_FILES['geoip_file']['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = $this->module->l('No file uploaded or upload error.');
            return;
        }

        $file = $_FILES['geoip_file'];
        $fileName = $file['name'];
        $tmpName = $file['tmp_name'];

        // Vérifier l'extension
        $allowedExtensions = ['dat', 'mmdb'];
        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($extension, $allowedExtensions)) {
            $this->errors[] = $this->module->l('Invalid file type. Only .dat or .mmdb files are allowed.');
            return;
        }

        // Définir le chemin de destination
        $geoipPath = _PS_ROOT_DIR_ . '/app/Resources/geoip/';

        // Créer le dossier s'il n'existe pas
        if (!file_exists($geoipPath)) {
            mkdir($geoipPath, 0755, true);
        }

        // Nom du fichier de destination
        $destFile = $geoipPath . 'GeoLite2-Country.' . $extension;

        // Déplacer le fichier
        if (move_uploaded_file($tmpName, $destFile)) {
            chmod($destFile, 0644);
            $this->confirmations[] = $this->module->l('GeoIP database uploaded successfully!');

            // Activer la géolocalisation
            Configuration::updateValue('PS_GEOLOCATION_ENABLED', 1);
        } else {
            $this->errors[] = $this->module->l('Failed to move uploaded file.');
        }
    }

    /**
     * Récupère les données pour l'onglet Status
     */
    protected function getStatusData()
    {
        $country = '';
        $method = '';
        $cloudflare = false;

        // Vérifier Cloudflare
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['HTTP_CF_IPCOUNTRY']));
            if (preg_match('/^[A-Z]{2}$/', $iso) && !in_array($iso, ['XX', 'T1'])) {
                $country = $iso;
                $method = $this->module->l('Cloudflare CF-IPCountry header');
                $cloudflare = true;
            }
        }

        // Fallback GeoIP
        if (!$country && Configuration::get('PS_GEOLOCATION_ENABLED')) {
            try {
                if (class_exists('\PrestaShop\PrestaShop\Adapter\ServiceLocator')) {
                    $geolocator = \PrestaShop\PrestaShop\Adapter\ServiceLocator::get(
                        '\\PrestaShop\\PrestaShop\\Core\\Localization\\Geolocation\\Geolocator'
                    );
                    $location = $geolocator->getLocation();

                    if ($location && is_object($location)) {
                        $countryObj = $location->getCountry();
                        if ($countryObj && method_exists($countryObj, 'getIsoCode')) {
                            $country = strtoupper((string)$countryObj->getIsoCode());
                            $method = $this->module->l('PrestaShop GeoIP');
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Erreur silencieuse
            }
        }

        // Fallback pays par défaut
        if (!$country) {
            $defaultCountry = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
            if (Validate::isLoadedObject($defaultCountry)) {
                $country = strtoupper($defaultCountry->iso_code);
                $method = $this->module->l('Default shop country');
            }
        }

        return [
            'module_dir' => $this->module->getPathUri(),
            'current_country' => $country ?: $this->module->l('Not detected'),
            'current_lang' => $this->context->language->iso_code,
            'detection_method' => $method,
            'geolocation_enabled' => Configuration::get('PS_GEOLOCATION_ENABLED'),
            'cloudflare_detected' => $cloudflare,
            'module_version' => $this->module->version,
        ];
    }

    /**
     * Récupère les données pour l'onglet Statistiques
     */
    protected function getStatsData()
    {
        $data = $this->getStatusData();

        // Statistiques par pays (30 derniers jours)
        $sql = 'SELECT country_iso, COUNT(*) as total
                FROM `' . _DB_PREFIX_ . 'geolangvars_stats`
                WHERE date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY country_iso
                ORDER BY total DESC
                LIMIT 10';

        $data['stats_by_country'] = Db::getInstance()->executeS($sql);

        // Statistiques par méthode de détection
        $sql = 'SELECT detection_method, COUNT(*) as total
                FROM `' . _DB_PREFIX_ . 'geolangvars_stats`
                WHERE date_add >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY detection_method
                ORDER BY total DESC';

        $data['stats_by_method'] = Db::getInstance()->executeS($sql);

        // Statistiques par jour (7 derniers jours)
        $sql = 'SELECT DATE(date_add) as date, COUNT(*) as total
                FROM `' . _DB_PREFIX_ . 'geolangvars_stats`
                WHERE date_add >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                GROUP BY DATE(date_add)
                ORDER BY date ASC';

        $data['stats_by_day'] = Db::getInstance()->executeS($sql);

        // Total des détections
        $sql = 'SELECT COUNT(*) as total FROM `' . _DB_PREFIX_ . 'geolangvars_stats`';
        $total = Db::getInstance()->getValue($sql);
        $data['total_detections'] = $total;

        return $data;
    }

    /**
     * Récupère les données pour l'onglet Paramètres
     */
    protected function getSettingsData()
    {
        $data = $this->getStatusData();

        $data['enable_stats'] = Configuration::get('GEOLANGVARS_ENABLE_STATS');
        $data['stats_retention'] = Configuration::get('GEOLANGVARS_STATS_RETENTION');
        $data['enable_cloudflare'] = Configuration::get('GEOLANGVARS_ENABLE_CLOUDFLARE');
        $data['enable_geoip'] = Configuration::get('GEOLANGVARS_ENABLE_GEOIP');
        $data['enable_fallback'] = Configuration::get('GEOLANGVARS_ENABLE_FALLBACK');

        return $data;
    }

    /**
     * Récupère les données pour l'onglet GeoIP
     */
    protected function getGeoIpData()
    {
        $data = $this->getStatusData();

        // Vérifier si le fichier GeoIP existe
        $geoipPath = _PS_ROOT_DIR_ . '/app/Resources/geoip/';
        $geoipFiles = [];

        if (is_dir($geoipPath)) {
            $files = scandir($geoipPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    $geoipFiles[] = [
                        'name' => $file,
                        'size' => filesize($geoipPath . $file),
                        'date' => date('Y-m-d H:i:s', filemtime($geoipPath . $file)),
                    ];
                }
            }
        }

        $data['geoip_files'] = $geoipFiles;
        $data['geoip_path'] = $geoipPath;

        return $data;
    }
}