{*
 * Admin Configuration Template
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 * @version   2.3.0
 *}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-globe"></i> {l s='Geo + Lang Variables' mod='geolangvars'}
        <span class="badge badge-success pull-right">
            {l s='Version' mod='geolangvars'} {$module_version}
        </span>
    </div>

    {* Navigation par onglets *}
    <div class="panel-body">
        <ul class="nav nav-tabs" role="tablist">
            <li class="{if $active_tab == 'status'}active{/if}">
                <a href="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=status">
                    <i class="icon-info-circle"></i> {l s='Status' mod='geolangvars'}
                </a>
            </li>
            <li class="{if $active_tab == 'stats'}active{/if}">
                <a href="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=stats">
                    <i class="icon-bar-chart"></i> {l s='Statistics' mod='geolangvars'}
                </a>
            </li>
            <li class="{if $active_tab == 'settings'}active{/if}">
                <a href="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=settings">
                    <i class="icon-cogs"></i> {l s='Settings' mod='geolangvars'}
                </a>
            </li>
            <li class="{if $active_tab == 'geoip'}active{/if}">
                <a href="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=geoip">
                    <i class="icon-map-marker"></i> {l s='GeoIP Setup' mod='geolangvars'}
                </a>
            </li>
        </ul>

        {* Contenu des onglets *}
        <div class="tab-content" style="padding-top: 20px;">

            {* ONGLET STATUS *}
            {if $active_tab == 'status'}
            {include file="./tabs/status.tpl"}
{/if}