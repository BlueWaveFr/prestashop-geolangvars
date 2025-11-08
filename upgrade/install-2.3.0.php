<?php
/**
 * Upgrade script vers 2.3.0 (Statistics and Advanced Options)
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Mise à jour vers la version 2.3.0
 *
 * @param Module $module Instance du module
 * @return bool
 */
function upgrade_module_2_3_0($module)
{
    $sql = [];

    // Créer la table de statistiques
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

    // Exécuter les requêtes SQL
    foreach ($sql as $query) {
        if (!Db::getInstance()->execute($query)) {
            return false;
        }
    }

    // Installer les configurations par défaut
    Configuration::updateValue('GEOLANGVARS_ENABLE_STATS', 1);
    Configuration::updateValue('GEOLANGVARS_STATS_RETENTION', 30);
    Configuration::updateValue('GEOLANGVARS_ENABLE_CLOUDFLARE', 1);
    Configuration::updateValue('GEOLANGVARS_ENABLE_GEOIP', 1);
    Configuration::updateValue('GEOLANGVARS_ENABLE_FALLBACK', 1);

    // Nettoyer le cache
    Tools::clearSmartyCache();
    Tools::clearXMLCache();

    return true;
}