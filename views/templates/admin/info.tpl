<div class="panel">
    <div class="panel-heading">
        <i class="icon-globe"></i> {l s='Geo + Lang Variables - Status' mod='geolangvars'}
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h4>{l s='Current Detection:' mod='geolangvars'}</h4>
                <table class="table">
                    <tr>
                        <th>{l s='Country ISO:' mod='geolangvars'}</th>
                        <td><strong>{$current_country}</strong></td>
                    </tr>
                    <tr>
                        <th>{l s='Language ISO:' mod='geolangvars'}</th>
                        <td><strong>{$current_lang}</strong></td>
                    </tr>
                    <tr>
                        <th>{l s='Detection method:' mod='geolangvars'}</th>
                        <td>{$detection_method}</td>
                    </tr>
                    <tr>
                        <th>{l s='Cloudflare:' mod='geolangvars'}</th>
                        <td>
                            {if $cloudflare_detected}
                                <span class="badge badge-success">{l s='Active' mod='geolangvars'}</span>
                            {else}
                                <span class="badge badge-warning">{l s='Not detected' mod='geolangvars'}</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <th>{l s='PrestaShop GeoIP:' mod='geolangvars'}</th>
                        <td>
                            {if $geolocation_enabled}
                                <span class="badge badge-success">{l s='Enabled' mod='geolangvars'}</span>
                            {else}
                                <span class="badge badge-danger">{l s='Disabled' mod='geolangvars'}</span>
                            {/if}
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-md-6">
                <h4>{l s='Available Smarty Variables:' mod='geolangvars'}</h4>
                <ul>
                    <li><code>{ldelim}$visitor_country_iso{rdelim}</code> - {l s='Visitor country code (e.g., FR, US, GB)' mod='geolangvars'}</li>
                    <li><code>{ldelim}$visitor_lang_iso{rdelim}</code> - {l s='Current language code (e.g., fr, en, es)' mod='geolangvars'}</li>
                </ul>

                <h4>{l s='Usage Example:' mod='geolangvars'}</h4>
                <pre style="background: #f5f5f5; padding: 10px; border-radius: 4px;">{literal}
{if $visitor_country_iso == 'FR'}
  <p>Livraison gratuite en France !</p>
{elseif $visitor_country_iso == 'BE'}
  <p>Gratis verzending in België!</p>
{/if}

{if $visitor_lang_iso == 'fr'}
  <p>Langue française</p>
{/if}{/literal}</pre>
            </div>
        </div>

        <hr>

        <div class="alert alert-info">
            <h4><i class="icon-lightbulb"></i> {l s='Recommendations:' mod='geolangvars'}</h4>
            <ul>
                <li><strong>{l s='Best option:' mod='geolangvars'}</strong> {l s='Use Cloudflare with IP Geolocation enabled for optimal performance and accuracy.' mod='geolangvars'}</li>
                <li><strong>{l s='Alternative:' mod='geolangvars'}</strong> {l s='Enable PrestaShop Geolocation in International > Localization.' mod='geolangvars'}</li>
                <li><strong>{l s='Priority:' mod='geolangvars'}</strong> {l s='Cloudflare → PrestaShop GeoIP → Default country' mod='geolangvars'}</li>
            </ul>
        </div>

        {if !$cloudflare_detected && !$geolocation_enabled}
            <div class="alert alert-warning">
                <i class="icon-warning"></i>
                {l s='Neither Cloudflare nor PrestaShop Geolocation is detected. The module will use the default shop country.' mod='geolangvars'}
            </div>
        {/if}
    </div>
</div>