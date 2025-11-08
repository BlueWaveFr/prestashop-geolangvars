<?php
/**
 * Admin Controller for Geo + Lang Variables
 *
 * @author    Stephane Geraut
 * @copyright Copyright (c) 2025 Stephane Geraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
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
     * Affichage principal
     */
    public function initContent()
    {
        parent::initContent();

        // Récupérer les informations de détection
        $detectionInfo = $this->getDetectionInfo();

        // Assigner au template
        $this->context->smarty->assign([
            'module_dir' => $this->module->getPathUri(),
            'current_country' => $detectionInfo['country'],
            'current_lang' => $detectionInfo['language'],
            'detection_method' => $detectionInfo['method'],
            'geolocation_enabled' => Configuration::get('PS_GEOLOCATION_ENABLED'),
            'cloudflare_detected' => $detectionInfo['cloudflare'],
            'module_version' => $this->module->version,
        ]);

        // Afficher le contenu
        $this->content = $this->context->smarty->fetch(
            $this->module->getLocalPath() . 'views/templates/admin/configure.tpl'
        );

        $this->context->smarty->assign('content', $this->content);
    }

    /**
     * Récupère les informations de détection actuelles
     *
     * @return array
     */
    protected function getDetectionInfo()
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
            'country' => $country ?: $this->module->l('Not detected'),
            'language' => $this->context->language->iso_code,
            'method' => $method,
            'cloudflare' => $cloudflare,
        ];
    }

    /**
     * Aide et documentation
     */
    public function renderView()
    {
        $this->initContent();
    }
}