<?php
/**
 * Geo + Lang Variables Module
 *
 * @author    Bluewave - Stephane Geraut
 * @copyright Copyright (c) 2025 Stephane Geraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * @version   2.2.0
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
        $this->version = '2.2.0';
        $this->author = 'Stephane Geraut';
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

    // ... [le reste du code reste identique]

    /**
     * Hook principal pour PS 8-9 (meilleure performance)
     *
     * @param array $params
     */
    public function hookActionFrontControllerSetVariables($params)
    {
        $countryIso = $this->getCountryIso();
        $languageIso = $this->getLanguageIso();

        if (isset($params['templateVars'])) {
            $params['templateVars']['visitor_country_iso'] = $countryIso;
            $params['templateVars']['visitor_lang_iso'] = $languageIso;
        }

        $this->context->smarty->assign([
            'visitor_country_iso' => $countryIso,
            'visitor_lang_iso' => $languageIso,
        ]);
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
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['HTTP_CF_IPCOUNTRY']));
            if ($this->isValidCountryIso($iso)) {
                return $iso;
            }
        }

        if (!empty($_SERVER['CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['CF_IPCOUNTRY']));
            if ($this->isValidCountryIso($iso)) {
                return $iso;
            }
        }

        if (function_exists('getallheaders')) {
            $headers = getallheaders();
            if (!empty($headers['CF-IPCountry'])) {
                $iso = strtoupper(trim($headers['CF-IPCountry']));
                if ($this->isValidCountryIso($iso)) {
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