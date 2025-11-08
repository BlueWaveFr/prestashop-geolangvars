<?php
/**
 * Geo + Lang Variables Module
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * @version   2.3.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\ServiceLocator;

class Geolangvars extends Module
{
    public function __construct()
    {
        $this->name = 'geolangvars';
        $this->tab = 'front_office_features';
        $this->version = '2.3.0';
        $this->author = 'Bluewave - Stéphane Géraut';
        $this->need_instance = 0;
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Geo + Lang variables for Smarty (with Cloudflare support)');
        $this->description = $this->l('Assign country ISO (GeoIP or CF‑IPCountry header) and current language ISO to Smarty.');

        $this->ps_versions_compliancy = [
            'min' => '8.0.0',
            'max' => '9.99.99'
        ];

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall this module?');
    }

    /**
     * Installation du module
     */
    public function install()
    {
        return parent::install()
            && $this->registerHook('actionFrontControllerSetVariables')
            && $this->registerHook('displayHeader')
            && $this->installTab();
    }

    /**
     * Désinstallation du module
     */
    public function uninstall()
    {
        return $this->uninstallTab()
            && parent::uninstall();
    }

    /**
     * Installe l'onglet dans le back-office
     *
     * @return bool
     */
    protected function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminGeoLangVars';
        $tab->name = [];

        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Geo + Lang Variables';
        }

        // CORRECTION : Placer sous "International" (même niveau que Localisation)
        $tab->id_parent = (int)Tab::getIdFromClassName('AdminInternational');
        $tab->module = $this->name;

        return $tab->add();
    }

    /**
     * Désinstalle l'onglet du back-office
     *
     * @return bool
     */
    protected function uninstallTab()
    {
        $id_tab = (int)Tab::getIdFromClassName('AdminGeoLangVars');

        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }

        return true;
    }

    /**
     * Hook principal pour PS 8-9 (meilleure performance)
     *
     * @param array $params
     */
    public function hookActionFrontControllerSetVariables($params)
    {
        $countryIso = $this->getCountryIso();
        $languageIso = $this->getLanguageIso();
        $detectionMethod = $this->lastDetectionMethod; // Variable à ajouter

        if (isset($params['templateVars'])) {
            $params['templateVars']['visitor_country_iso'] = $countryIso;
            $params['templateVars']['visitor_lang_iso'] = $languageIso;
        }

        $this->context->smarty->assign([
            'visitor_country_iso' => $countryIso,
            'visitor_lang_iso' => $languageIso,
        ]);

        // Enregistrer les statistiques si activées
        if (Configuration::get('GEOLANGVARS_ENABLE_STATS')) {
            $this->recordStats($countryIso, $languageIso, $detectionMethod);
        }
    }

    /**
     * Variable pour stocker la méthode de détection utilisée
     */
    protected $lastDetectionMethod = '';

    /**
     * Enregistre les statistiques de détection
     *
     * @param string $countryIso
     * @param string $languageIso
     * @param string $method
     * @return bool
     */
    protected function recordStats($countryIso, $languageIso, $method)
    {
        // Enregistrer maximum 1 fois par session pour éviter la surcharge
        if (isset($this->context->cookie->geolangvars_recorded)) {
            return true;
        }

        try {
            $ip = Tools::getRemoteAddr();
            $userAgent = isset($_SERVER['HTTP_USER_AGENT']) ?
                pSQL($_SERVER['HTTP_USER_AGENT'], true) : '';

            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'geolangvars_stats` 
                (`country_iso`, `language_iso`, `detection_method`, `ip_address`, `user_agent`, `date_add`)
                VALUES (
                    "' . pSQL($countryIso) . '",
                    "' . pSQL($languageIso) . '",
                    "' . pSQL($method) . '",
                    "' . pSQL($ip) . '",
                    "' . $userAgent . '",
                    NOW()
                )';

            Db::getInstance()->execute($sql);

            // Marquer comme enregistré pour cette session
            $this->context->cookie->geolangvars_recorded = 1;

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
    /**
     * Hook displayHeader (fallback pour compatibilité)
     *
     * @param array $params
     * @return string
     */
    public function hookDisplayHeader($params)
    {
        $countryIso = $this->getCountryIso();
        $languageIso = $this->getLanguageIso();

        $this->context->smarty->assign([
            'visitor_country_iso' => $countryIso,
            'visitor_lang_iso' => $languageIso,
        ]);

        return '';
    }

    /**
     * Détecte le code pays ISO à partir de Cloudflare ou GeoIP
     *
     * @return string Code pays ISO (ex: FR, US, GB) ou chaîne vide
     */
    protected function getCountryIso()
    {
        $countryIso = '';

        $countryIso = $this->getCloudflareCountry();

        if ($countryIso) {
            return $countryIso;
        }

        $countryIso = $this->getGeoIpCountry();

        if (!$countryIso && Configuration::get('PS_GEOLOCATION_ENABLED')) {
            try {
                $defaultCountry = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
                if (Validate::isLoadedObject($defaultCountry)) {
                    $countryIso = strtoupper($defaultCountry->iso_code);
                }
            } catch (\Throwable $e) {
                // Ignorer les erreurs silencieusement
            }
        }

        return $countryIso;
    }

    /**
     * Récupère le code pays depuis Cloudflare
     *
     * @return string
     */
    protected function getCloudflareCountry()
    {
        if (!Configuration::get('GEOLANGVARS_ENABLE_CLOUDFLARE')) {
            return '';
        }

        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['HTTP_CF_IPCOUNTRY']));
            if ($this->isValidCountryIso($iso)) {
                $this->lastDetectionMethod = 'Cloudflare';
                return $iso;
            }
        }

        if (!empty($_SERVER['CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['CF_IPCOUNTRY']));
            if ($this->isValidCountryIso($iso)) {
                $this->lastDetectionMethod = 'Cloudflare';
                return $iso;
            }
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (!empty($headers['CF-IPCountry'])) {
                $iso = strtoupper(trim($headers['CF-IPCountry']));
                if ($this->isValidCountryIso($iso)) {
                    $this->lastDetectionMethod = 'Cloudflare';
                    return $iso;
                }
            }
        }

        return '';
    }

    /**
     * Récupère le code pays via GeoIP PrestaShop
     *
     * @return string
     */
    protected function getGeoIpCountry()
    {
        try {
            if (!Configuration::get('GEOLANGVARS_ENABLE_GEOIP')) {
                return '';
            }

            if (!Configuration::get('PS_GEOLOCATION_ENABLED')) {
                return '';
            }

            if (class_exists('\PrestaShop\PrestaShop\Adapter\ServiceLocator')) {
                try {
                    $geolocator = ServiceLocator::get('\\PrestaShop\\PrestaShop\\Core\\Localization\\Geolocation\\Geolocator');
                    $location = $geolocator->getLocation();

                    if ($location && is_object($location)) {
                        $countryObj = $location->getCountry();
                        if ($countryObj && method_exists($countryObj, 'getIsoCode')) {
                            $iso = (string)$countryObj->getIsoCode();
                            if ($this->isValidCountryIso($iso)) {
                                $this->lastDetectionMethod = 'PrestaShop GeoIP';
                                return strtoupper($iso);
                            }
                        }
                    }
                } catch (\Throwable $e) {
                    if (_PS_MODE_DEV_) {
                        PrestaShopLogger::addLog(
                            'GeoLangVars: GeoIP error - ' . $e->getMessage(),
                            2,
                            null,
                            'Module',
                            $this->id
                        );
                    }
                }
            }
        } catch (\Throwable $e) {
            // Erreur silencieuse
        }

        return '';
    }

    /**
     * Détecte le code pays ISO à partir de Cloudflare ou GeoIP
     *
     * @return string Code pays ISO (ex: FR, US, GB) ou chaîne vide
     */
    protected function getCountryIso()
    {
        $countryIso = '';

        $countryIso = $this->getCloudflareCountry();

        if ($countryIso) {
            return $countryIso;
        }

        $countryIso = $this->getGeoIpCountry();

        if (!$countryIso && Configuration::get('GEOLANGVARS_ENABLE_FALLBACK')) {
            try {
                $defaultCountry = new Country((int)Configuration::get('PS_COUNTRY_DEFAULT'));
                if (Validate::isLoadedObject($defaultCountry)) {
                    $countryIso = strtoupper($defaultCountry->iso_code);
                    $this->lastDetectionMethod = 'Default Country';
                }
            } catch (\Throwable $e) {
                // Ignorer les erreurs silencieusement
            }
        }

        return $countryIso;
    }

    /**
     * Récupère le code ISO de la langue courante
     *
     * @return string
     */
    protected function getLanguageIso()
    {
        try {
            if (isset($this->context->language) && Validate::isLoadedObject($this->context->language)) {
                return (string)$this->context->language->iso_code;
            }
        } catch (\Throwable $e) {
            // Erreur silencieuse
        }

        try {
            $defaultLangId = (int)Configuration::get('PS_LANG_DEFAULT');
            $defaultLang = new Language($defaultLangId);
            if (Validate::isLoadedObject($defaultLang)) {
                return (string)$defaultLang->iso_code;
            }
        } catch (\Throwable $e) {
            // Erreur silencieuse
        }

        return 'en';
    }

    /**
     * Valide un code pays ISO
     *
     * @param string $iso
     * @return bool
     */
    protected function isValidCountryIso($iso)
    {
        if (!preg_match('/^[A-Z]{2}$/', $iso)) {
            return false;
        }

        $excludedCodes = ['XX', 'T1'];
        if (in_array($iso, $excludedCodes)) {
            return false;
        }

        return true;
    }

    /**
     * Redirection vers l'onglet admin
     *
     * @return void
     */
    public function getContent()
    {
        Tools::redirectAdmin(
            $this->context->link->getAdminLink('AdminGeoLangVars')
        );
    }
}

/**
 * Installation du module
 */
public function install()
{
    return parent::install()
        && $this->registerHook('actionFrontControllerSetVariables')
        && $this->registerHook('displayHeader')
        && $this->installTab()
        && $this->installDb(); // ← NOUVEAU
}

/**
 * Désinstallation du module
 */
public function uninstall()
{
    return $this->uninstallTab()
        && $this->uninstallDb() // ← NOUVEAU
        && parent::uninstall();
}

/**
 * Crée les tables de base de données
 *
 * @return bool
 */
protected function installDb()
{
    $sql = [];

    // Table pour les statistiques de détection
    $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'geolangvars_stats` (
        `id_stat` int(11) NOT NULL AUTO_INCREMENT,
        `country_iso` varchar(2) NOT NULL,
        `language_iso` varchar(2) NOT NULL,
        `detection_method` varchar(50) NOT NULL,
        `ip_address` varchar(45) DEFAULT NULL,
        `user_agent` text DEFAULT NULL,
        `date_add` datetime NOT NULL,
        PRIMARY KEY (`id_stat`),
        KEY `country_iso` (`country_iso`),
        KEY `date_add` (`date_add`)
    ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

    foreach ($sql as $query) {
        if (!Db::getInstance()->execute($query)) {
            return false;
        }
    }

    // Installer les configurations par défaut
    Configuration::updateValue('GEOLANGVARS_ENABLE_STATS', 1);
    Configuration::updateValue('GEOLANGVARS_STATS_RETENTION', 30); // 30 jours
    Configuration::updateValue('GEOLANGVARS_ENABLE_CLOUDFLARE', 1);
    Configuration::updateValue('GEOLANGVARS_ENABLE_GEOIP', 1);
    Configuration::updateValue('GEOLANGVARS_ENABLE_FALLBACK', 1);

    return true;
}

/**
 * Supprime les tables de base de données
 *
 * @return bool
 */
protected function uninstallDb()
{
    $sql = [];

    $sql[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'geolangvars_stats`';

    foreach ($sql as $query) {
        if (!Db::getInstance()->execute($query)) {
            return false;
        }
    }

    // Supprimer les configurations
    Configuration::deleteByName('GEOLANGVARS_ENABLE_STATS');
    Configuration::deleteByName('GEOLANGVARS_STATS_RETENTION');
    Configuration::deleteByName('GEOLANGVARS_ENABLE_CLOUDFLARE');
    Configuration::deleteByName('GEOLANGVARS_ENABLE_GEOIP');
    Configuration::deleteByName('GEOLANGVARS_ENABLE_FALLBACK');

    return true;
}
