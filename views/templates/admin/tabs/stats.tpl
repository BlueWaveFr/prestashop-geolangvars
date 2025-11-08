{*
 * Statistics Tab Template
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}

<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="icon-info-circle"></i>
            {l s='Statistics are collected anonymously to help you understand visitor detection patterns.' mod='geolangvars'}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="panel">
            <div class="panel-body text-center">
                <h3 style="margin: 0; color: #00aff0;">
                    <i class="icon-eye"></i><br>
                    {$total_detections|number_format:0:',':' '}
                </h3>
                <p>{l s='Total Detections' mod='geolangvars'}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel">
            <div class="panel-body text-center">
                <h3 style="margin: 0; color: #00aff0;">
                    <i class="icon-globe"></i><br>
                    {if isset($stats_by_country)}{count($stats_by_country)}{else}0{/if}
                </h3>
                <p>{l s='Countries Detected' mod='geolangvars'}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel">
            <div class="panel-body text-center">
                <h3 style="margin: 0; color: #00aff0;">
                    <i class="icon-calendar"></i><br>
                    30 {l s='days' mod='geolangvars'}
                </h3>
                <p>{l s='Retention Period' mod='geolangvars'}</p>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="panel">
            <div class="panel-body text-center">
                <form method="post" action="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=stats">
                    <button type="submit" name="submitClearStats" class="btn btn-warning btn-block">
                        <i class="icon-trash"></i> {l s='Clean Old Stats' mod='geolangvars'}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-bar-chart"></i> {l s='Top 10 Countries (Last 30 days)' mod='geolangvars'}
            </div>
            <div class="panel-body">
                {if isset($stats_by_country) && count($stats_by_country) > 0}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{l s='Country' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Detections' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Percentage' mod='geolangvars'}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$stats_by_country item=stat}
                            {assign var="percentage" value=($stat.total / $total_detections * 100)}
                            <tr>
                                <td>
                                    <strong>{$stat.country_iso}</strong>
                                </td>
                                <td class="text-right">
                                    {$stat.total|number_format:0:',':' '}
                                </td>
                                <td class="text-right">
                                    <div class="progress" style="margin-bottom: 0;">
                                        <div class="progress-bar progress-bar-success"
                                             role="progressbar"
                                             style="width: {$percentage}%;">
                                            {$percentage|string_format:"%.1f"}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <p class="alert alert-warning">
                        {l s='No statistics available yet.' mod='geolangvars'}
                    </p>
                {/if}
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-cogs"></i> {l s='Detection Methods (Last 30 days)' mod='geolangvars'}
            </div>
            <div class="panel-body">
                {if isset($stats_by_method) && count($stats_by_method) > 0}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{l s='Method' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Detections' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Percentage' mod='geolangvars'}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$stats_by_method item=stat}
                            {assign var="percentage" value=($stat.total / $total_detections * 100)}
                            <tr>
                                <td>
                                    {if $stat.detection_method == 'Cloudflare'}
                                        <i class="icon-cloud"></i>
                                    {elseif $stat.detection_method == 'PrestaShop GeoIP'}
                                        <i class="icon-map-marker"></i>
                                    {else}
                                        <i class="icon-globe"></i>
                                    {/if}
                                    {$stat.detection_method}
                                </td>
                                <td class="text-right">
                                    {$stat.total|number_format:0:',':' '}
                                </td>
                                <td class="text-right">
                                    <div class="progress" style="margin-bottom: 0;">
                                        <div class="progress-bar {if $stat.detection_method == 'Cloudflare'}progress-bar-success{else}progress-bar-info{/if}"
                                             role="progressbar"
                                             style="width: {$percentage}%;">
                                            {$percentage|string_format:"%.1f"}%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <p class="alert alert-warning">
                        {l s='No statistics available yet.' mod='geolangvars'}
                    </p>
                {/if}
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <i class="icon-calendar"></i> {l s='Daily Detections (Last 7 days)' mod='geolangvars'}
            </div>
            <div class="panel-body">
                {if isset($stats_by_day) && count($stats_by_day) > 0}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{l s='Date' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Detections' mod='geolangvars'}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$stats_by_day item=stat}
                            <tr>
                                <td>{$stat.date}</td>
                                <td class="text-right">
                                    <strong>{$stat.total|number_format:0:',':' '}</strong>
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <p class="alert alert-warning">
                        {l s='No statistics available yet.' mod='geolangvars'}
                    </p>
                {/if}
            </div>
        </div>
    </div>
</div>