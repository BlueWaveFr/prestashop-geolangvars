<?php
/**
 * Upgrade script vers 2.0.0 (PS 8-9 compatibility)
 *
 * @author Stephane Geraut
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Mise à jour vers la version 2.0.0
 *
 * @param Module $module Instance du module
 * @return bool
 */
function upgrade_module_2_0_0($module)
{
    // Enregistrer le nouveau hook pour PS 8-9
    $module->registerHook('actionFrontControllerSetVariables');

    // Nettoyer le cache
    Tools::clearSmartyCache();
    Tools::clearXMLCache();

    return true;
}