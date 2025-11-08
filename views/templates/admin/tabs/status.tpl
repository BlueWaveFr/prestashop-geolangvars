{*
 * Status Tab Template
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-eye"></i> {l s='Current Detection' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 40%;">{l s='Country ISO' mod='geolangvars'}</th>
                        <td>
                            <strong style="font-size: 24px; color: #00aff0;">
                                {$current_country}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th>{l s='Language ISO' mod='geolangvars'}</th>
                        <td>
                            <strong style="font-size: 24px; color: #00aff0;">
                                {$current_lang}
                            </strong>
                        </td>
                    </tr>
                    <tr>
                        <th>{l s='Detection method' mod='geolangvars'}</th>
                        <td>
                                <span class="label label-info">
                                    {$detection_method}
                                </span>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <i class="icon-check-circle"></i> {l s='Services Status' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 40%;">
                            <i class="icon-cloud"></i> {l s='Cloudflare' mod='geolangvars'}
                        </th>
                        <td>
                            {if $cloudflare_detected}
                                <span class="badge badge-success">
                                        <i class="icon-check"></i> {l s='Active' mod='geolangvars'}
                                    </span>
                            {else}
                                <span class="badge badge-warning">
                                        <i class="icon-remove"></i> {l s='Not detected' mod='geolangvars'}
                                    </span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <i class="icon-map-marker"></i> {l s='PrestaShop GeoIP' mod='geolangvars'}
                        </th>
                        <td>
                            {if $geolocation_enabled}
                                <span class="badge badge-success">
                                        <i class="icon-check"></i> {l s='Enabled' mod='geolangvars'}
                                    </span>
                            {else}
                                <span class="badge badge-danger">
                                        <i class="icon-remove"></i> {l s='Disabled' mod='geolangvars'}
                                    </span>
                                <br><br>
                                <a href="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=geoip" class="btn btn-primary btn-sm">
                                    <i class="icon-cog"></i> {l s='Configure GeoIP' mod='geolangvars'}
                                </a>
                            {/if}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-code"></i> {l s='Available Smarty Variables' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <p><strong>{l s='Use these variables in your templates:' mod='geolangvars'}</strong></p>
                <ul style="margin: 10px 0;">
                    <li>
                        <code style="background: #f5f5f5; padding: 2px 5px; border-radius: 3px;">
                            {literal}{$visitor_country_iso}{/literal}
                        </code>
                        <br>
                        <small>{l s='Visitor country code (e.g., FR, US, GB)' mod='geolangvars'}</small>
                    </li>
                    <li style="margin-top: 10px;">
                        <code style="background: #f5f5f5; padding: 2px 5px; border-radius: 3px;">
                            {literal}{$visitor_lang_iso}{/literal}
                        </code>
                        <br>
                        <small>{l s='Current language code (e.g., fr, en, es)' mod='geolangvars'}</small>
                    </li>
                </ul>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <i class="icon-file-code-o"></i> {l s='Usage Example' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; border: 1px solid #ddd; font-size: 12px;">{literal}
{if $visitor_country_iso == 'FR'}
  <div class="banner">
    🇫🇷 Livraison gratuite en France !
  </div>
{elseif $visitor_country_iso == 'BE'}
  <div class="banner">
    🇧🇪 Gratis verzending in België!
  </div>
{elseif $visitor_country_iso == 'US'}
  <div class="banner">
    🇺🇸 Free shipping in USA!
  </div>
{/if}

{if $visitor_lang_iso == 'fr'}
  <p>Contenu en français</p>
{else}
  <p>English content</p>
{/if}{/literal}</pre>
            </div>
        </div>

        <div class="alert alert-info">
            <h4><i class="icon-lightbulb-o"></i> {l s='Recommendations' mod='geolangvars'}</h4>
            <ul>
                <li>
                    <strong>{l s='Best option:' mod='geolangvars'}</strong>
                    {l s='Use Cloudflare with IP Geolocation enabled for optimal performance.' mod='geolangvars'}
                </li>
                <li>
                    <strong>{l s='Alternative:' mod='geolangvars'}</strong>
                    {l s='Enable PrestaShop Geolocation' mod='geolangvars'}
                    <a href="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=geoip">
                        {l s='here' mod='geolangvars'}
                    </a>
                </li>
                <li>
                    <strong>{l s='Priority:' mod='geolangvars'}</strong>
                    Cloudflare → PrestaShop GeoIP → {l s='Default country' mod='geolangvars'}
                </li>
            </ul>
        </div>
    </div>
</div>

{if !$cloudflare_detected && !$geolocation_enabled}
    <div class="alert alert-warning">
        <i class="icon-warning-sign"></i>
        <strong>{l s='Warning:' mod='geolangvars'}</strong>
        {l s='Neither Cloudflare nor PrestaShop Geolocation is detected. The module will use the default shop country.' mod='geolangvars'}
    </div>
{/if}