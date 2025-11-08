<?php
/**
 * Geo + Lang Variables Module
 *
 * @author    Stephane Geraut
 * @copyright Copyright (c) 2025
 * @license   AFL - Academic Free License 3.0
 * @version   2.0.0
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
        $this->version = '2.0.0';
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
            && $this->registerHook('actionFrontControllerSetVariables') // Hook principal PS 8-9
            && $this->registerHook('displayHeader'); // Fallback pour compatibilité
    }

    /**
     * Désinstallation du module
     */
    public function uninstall()
    {
        return parent::uninstall();
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

        // Méthode recommandée pour PS 8-9
        if (isset($params['templateVars'])) {
            $params['templateVars']['visitor_country_iso'] = $countryIso;
            $params['templateVars']['visitor_lang_iso'] = $languageIso;
        }

        // Fallback pour compatibilité
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

        // 1. Vérifier le header Cloudflare (méthode la plus rapide)
        $countryIso = $this->getCloudflareCountry();

        if ($countryIso) {
            return $countryIso;
        }

        // 2. Fallback sur GeoIP PrestaShop (si Cloudflare indisponible)
        $countryIso = $this->getGeoIpCountry();

        // 3. Fallback ultime : pays par défaut de la boutique (optionnel)
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
        // Vérifier HTTP_CF_IPCOUNTRY (le plus commun)
        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['HTTP_CF_IPCOUNTRY']));
            if ($this->isValidCountryIso($iso)) {
                return $iso;
            }
        }

        // Vérifier CF_IPCOUNTRY (variante)
        if (!empty($_SERVER['CF_IPCOUNTRY'])) {
            $iso = strtoupper(trim($_SERVER['CF_IPCOUNTRY']));
            if ($this->isValidCountryIso($iso)) {
                return $iso;
            }
        }

        // Fallback via getallheaders()
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
            // Vérifier si la géolocalisation est activée
            if (!Configuration::get('PS_GEOLOCATION_ENABLED')) {
                return '';
            }

            // Méthode pour PS 8-9
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
                    // Log pour debug si nécessaire
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

        // Fallback sur la langue par défaut
        try {
            $defaultLangId = (int)Configuration::get('PS_LANG_DEFAULT');
            $defaultLang = new Language($defaultLangId);
            if (Validate::isLoadedObject($defaultLang)) {
                return (string)$defaultLang->iso_code;
            }
        } catch (\Throwable $e) {
            // Erreur silencieuse
        }

        return 'en'; // Fallback ultime
    }

    /**
     * Valide un code pays ISO
     *
     * @param string $iso
     * @return bool
     */
    protected function isValidCountryIso($iso)
    {
        // Vérifier le format (2 lettres majuscules)
        if (!preg_match('/^[A-Z]{2}$/', $iso)) {
            return false;
        }

        // Exclure les codes spéciaux Cloudflare
        $excludedCodes = ['XX', 'T1']; // XX = inconnu, T1 = Tor
        if (in_array($iso, $excludedCodes)) {
            return false;
        }

        return true;
    }

    /**
     * Configuration du module (page d'info)
     *
     * @return string
     */
    public function getContent()
    {
        $output = '';

        // Message de confirmation si besoin
        if (Tools::isSubmit('submit' . $this->name)) {
            $output .= $this->displayConfirmation($this->l('Settings saved successfully.'));
        }

        // Afficher les informations d'utilisation
        $output .= $this->renderInfo();

        return $output;
    }

    /**
     * Affiche les informations d'utilisation du module
     *
     * @return string
     */
    protected function renderInfo()
    {
        // Détecter les valeurs actuelles pour l'affichage
        $currentCountry = $this->getCountryIso();
        $currentLang = $this->getLanguageIso();
        $detectionMethod = '';

        if (!empty($_SERVER['HTTP_CF_IPCOUNTRY'])) {
            $detectionMethod = $this->l('Cloudflare CF-IPCountry header');
        } elseif ($currentCountry) {
            $detectionMethod = $this->l('PrestaShop GeoIP');
        } else {
            $detectionMethod = $this->l('Default shop country');
        }

        $this->context->smarty->assign([
            'module_dir' => $this->_path,
            'current_country' => $currentCountry ?: $this->l('Not detected'),
            'current_lang' => $currentLang,
            'detection_method' => $detectionMethod,
            'geolocation_enabled' => Configuration::get('PS_GEOLOCATION_ENABLED'),
            'cloudflare_detected' => !empty($_SERVER['HTTP_CF_IPCOUNTRY']),
        ]);

        return $this->display(__FILE__, 'views/templates/admin/info.tpl');
    }
}