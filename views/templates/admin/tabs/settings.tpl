{*
 * Settings Tab Template
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}

<form method="post" action="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=settings" class="form-horizontal">

    <div class="panel">
        <div class="panel-heading">
            <i class="icon-bar-chart"></i> {l s='Statistics Settings' mod='geolangvars'}
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <i class="icon-check"></i> {l s='Enable Statistics' mod='geolangvars'}
                </label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="enable_stats" id="enable_stats_on" value="1"
                               {if $enable_stats}checked="checked"{/if}>
                        <label for="enable_stats_on">{l s='Yes' mod='geolangvars'}</label>
                        <input type="radio" name="enable_stats" id="enable_stats_off" value="0"
                               {if !$enable_stats}checked="checked"{/if}>
                        <label for="enable_stats_off">{l s='No' mod='geolangvars'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                    <p class="help-block">
                        {l s='Collect anonymous statistics about country detection.' mod='geolangvars'}
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">
                    <i class="icon-calendar"></i> {l s='Retention Period (days)' mod='geolangvars'}
                </label>
                <div class="col-lg-9">
                    <input type="number" name="stats_retention" value="{$stats_retention}"
                           class="form-control fixed-width-sm" min="1" max="365">
                    <p class="help-block">
                        {l s='Statistics older than this will be automatically deleted.' mod='geolangvars'}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-heading">
            <i class="icon-cogs"></i> {l s='Detection Methods' mod='geolangvars'}
        </div>
        <div class="panel-body">
            <div class="form-group">
                <label class="control-label col-lg-3">
                    <i class="icon-cloud"></i> {l s='Enable Cloudflare' mod='geolangvars'}
                </label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="enable_cloudflare" id="enable_cloudflare_on" value="1"
                               {if $enable_cloudflare}checked="checked"{/if}>
                        <label for="enable_cloudflare_on">{l s='Yes' mod='geolangvars'}</label>
                        <input type="radio" name="enable_cloudflare" id="enable_cloudflare_off" value="0"
                               {if !$enable_cloudflare}checked="checked"{/if}>
                        <label for="enable_cloudflare_off">{l s='No' mod='geolangvars'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                    <p class="help-block">
                        {l s='Use Cloudflare CF-IPCountry header (fastest and most accurate).' mod='geolangvars'}
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">
                    <i class="icon-map-marker"></i> {l s='Enable PrestaShop GeoIP' mod='geolangvars'}
                </label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="enable_geoip" id="enable_geoip_on" value="1"
                               {if $enable_geoip}checked="checked"{/if}>
                        <label for="enable_geoip_on">{l s='Yes' mod='geolangvars'}</label>
                        <input type="radio" name="enable_geoip" id="enable_geoip_off" value="0"
                               {if !$enable_geoip}checked="checked"{/if}>
                        <label for="enable_geoip_off">{l s='No' mod='geolangvars'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                    <p class="help-block">
                        {l s='Use PrestaShop built-in GeoIP (fallback if Cloudflare unavailable).' mod='geolangvars'}
                    </p>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-lg-3">
                    <i class="icon-globe"></i> {l s='Enable Default Country Fallback' mod='geolangvars'}
                </label>
                <div class="col-lg-9">
                    <span class="switch prestashop-switch fixed-width-lg">
                        <input type="radio" name="enable_fallback" id="enable_fallback_on" value="1"
                               {if $enable_fallback}checked="checked"{/if}>
                        <label for="enable_fallback_on">{l s='Yes' mod='geolangvars'}</label>
                        <input type="radio" name="enable_fallback" id="enable_fallback_off" value="0"
                               {if !$enable_fallback}checked="checked"{/if}>
                        <label for="enable_fallback_off">{l s='No' mod='geolangvars'}</label>
                        <a class="slide-button btn"></a>
                    </span>
                    <p class="help-block">
                        {l s='Use shop default country if no other detection method works.' mod='geolangvars'}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel-footer">
        <button type="submit" name="submitSettings" class="btn btn-default pull-right">
            <i class="process-icon-save"></i> {l s='Save Settings' mod='geolangvars'}
        </button>
    </div>
</form>

<div class="alert alert-info">
    <h4><i class="icon-info-circle"></i> {l s='Detection Priority Order' mod='geolangvars'}</h4>
    <ol>
        <li><strong>Cloudflare</strong> - {l s='Fastest, most accurate (if enabled)' mod='geolangvars'}</li>
        <li><strong>PrestaShop GeoIP</strong> - {l s='Fallback method (if enabled)' mod='geolangvars'}</li>
        <li><strong>Default Country</strong> - {l s='Last resort (if enabled)' mod='geolangvars'}</li>
    </ol>
</div>