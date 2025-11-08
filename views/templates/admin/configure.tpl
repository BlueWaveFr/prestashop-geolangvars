<div class="panel">
    <div class="panel-heading">
        <i class="icon-globe"></i> {l s='Geo + Lang Variables - Status' mod='geolangvars'}
        <span class="badge badge-success pull-right">{l s='Version' mod='geolangvars'} {$module_version}</span>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h3>{l s='Current Detection' mod='geolangvars'}</h3>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 40%;">{l s='Country ISO' mod='geolangvars'}</th>
                        <td>
                            <strong style="font-size: 18px; color: #00aff0;">{$current_country}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>{l s='Language ISO' mod='geolangvars'}</th>
                        <td>
                            <strong style="font-size: 18px; color: #00aff0;">{$current_lang}</strong>
                        </td>
                    </tr>
                    <tr>
                        <th>{l s='Detection method' mod='geolangvars'}</th>
                        <td>{$detection_method}</td>
                    </tr>
                    </tbody>
                </table>

                <h3>{l s='Services Status' mod='geolangvars'}</h3>
                <table class="table table-bordered">
                    <tbody>
                    <tr>
                        <th style="width: 40%;">{l s='Cloudflare' mod='geolangvars'}</th>
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
                        <th>{l s='PrestaShop GeoIP' mod='geolangvars'}</th>
                        <td>
                            {if $geolocation_enabled}
                                <span class="badge badge-success">
                                        <i class="icon-check"></i> {l s='Enabled' mod='geolangvars'}
                                    </span>
                            {else}
                                <span class="badge badge-danger">
                                        <i class="icon-remove"></i> {l s='Disabled' mod='geolangvars'}
                                    </span>
                                <br>
                                <small>
                                    <a href="{$link->getAdminLink('AdminLocalization')}" target="_blank">
                                        {l s='Enable in Localization settings' mod='geolangvars'} →
                                    </a>
                                </small>
                            {/if}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-6">
                <h3>{l s='Available Smarty Variables' mod='geolangvars'}</h3>

                <div class="alert alert-info">
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

                <h3>{l s='Usage Example' mod='geolangvars'}</h3>
                <pre style="background: #f5f5f5; padding: 15px; border-radius: 4px; border: 1px solid #ddd;">{literal}
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

                <div class="alert alert-warning">
                    <h4><i class="icon-lightbulb-o"></i> {l s='Recommendations' mod='geolangvars'}</h4>
                    <ul>
                        <li>
                            <strong>{l s='Best option:' mod='geolangvars'}</strong>
                            {l s='Use Cloudflare with IP Geolocation enabled for optimal performance.' mod='geolangvars'}
                        </li>
                        <li>
                            <strong>{l s='Alternative:' mod='geolangvars'}</strong>
                            {l s='Enable PrestaShop Geolocation in' mod='geolangvars'}
                            <a href="{$link->getAdminLink('AdminLocalization')}" target="_blank">
                                {l s='International > Localization' mod='geolangvars'}
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
    </div>
</div>

{if !$cloudflare_detected && !$geolocation_enabled}
    <div class="alert alert-warning">
        <i class="icon-warning-sign"></i>
        <strong>{l s='Warning:' mod='geolangvars'}</strong>
        {l s='Neither Cloudflare nor PrestaShop Geolocation is detected. The module will use the default shop country.' mod='geolangvars'}
    </div>
{/if}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-question-circle"></i> {l s='Documentation & Support' mod='geolangvars'}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h4>{l s='Quick Links' mod='geolangvars'}</h4>
                <ul>
                    <li>
                        <a href="https://github.com/votre-username/prestashop-geolangvars" target="_blank">
                            <i class="icon-github"></i> {l s='GitHub Repository' mod='geolangvars'}
                        </a>
                    </li>
                    <li>
                        <a href="https://www.cloudflare.com" target="_blank">
                            <i class="icon-cloud"></i> {l s='Setup Cloudflare (Free)' mod='geolangvars'}
                        </a>
                    </li>
                    <li>
                        <a href="{$link->getAdminLink('AdminLocalization')}" target="_blank">
                            <i class="icon-globe"></i> {l s='PrestaShop Localization Settings' mod='geolangvars'}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h4>{l s='Module Information' mod='geolangvars'}</h4>
                <p><strong>{l s='Version:' mod='geolangvars'}</strong> {$module_version}</p>
                <p><strong>{l s='Author:' mod='geolangvars'}</strong> Stephane Geraut</p>
                <p><strong>{l s='License:' mod='geolangvars'}</strong> AFL 3.0</p>
            </div>
        </div>
    </div>
</div>