<?php
/**
 * Upgrade script vers 2.2.0 (Admin Tab under International)
 *
 * @author Bluewave - Stephane Geraut
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

/**
 * Mise à jour vers la version 2.2.0
 *
 * @param Module $module Instance du module
 * @return bool
 */
function upgrade_module_2_2_0($module)
{
    // Supprimer l'ancien onglet s'il existe (au cas où)
    $id_tab = (int)Tab::getIdFromClassName('AdminGeoLangVars');
    if ($id_tab) {
        $tab = new Tab($id_tab);
        $tab->delete();
    }

    // Créer le nouvel onglet
    $tab = new Tab();
    $tab->active = 1;
    $tab->class_name = 'AdminGeoLangVars';
    $tab->name = [];

    foreach (Language::getLanguages(true) as $lang) {
        $tab->name[$lang['id_lang']] = 'Geo + Lang Variables';
    }

    // Placer sous "International" (même niveau que Localisation)
    $tab->id_parent = (int)Tab::getIdFromClassName('AdminInternational');
    $tab->module = $module->name;

    $result = $tab->add();

    // Nettoyer le cache
    Tools::clearSmartyCache();
    Tools::clearXMLCache();

    return $result;
}