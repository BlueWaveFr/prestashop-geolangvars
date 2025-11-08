{*
 * GeoIP Setup Tab Template
 *
 * @author    Bluewave - Stéphane Géraut
 * @copyright Copyright (c) 2025 Bluewave - Stéphane Géraut
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License version 3.0
 *}

<div class="row">
    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-cog"></i> {l s='Enable PrestaShop Geolocation' mod='geolangvars'}
            </div>
            <div class="panel-body">
                {if $geolocation_enabled}
                    <div class="alert alert-success">
                        <i class="icon-check"></i>
                        {l s='PrestaShop Geolocation is already enabled!' mod='geolangvars'}
                    </div>
                {else}
                    <div class="alert alert-warning">
                        <i class="icon-warning"></i>
                        {l s='PrestaShop Geolocation is currently disabled.' mod='geolangvars'}
                    </div>

                    <p>{l s='Click the button below to enable PrestaShop Geolocation feature.' mod='geolangvars'}</p>

                    <form method="post" action="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=geoip">
                        <button type="submit" name="submitEnableGeoIP" class="btn btn-primary btn-lg">
                            <i class="icon-check"></i> {l s='Enable Geolocation Now' mod='geolangvars'}
                        </button>
                    </form>
                {/if}

                <hr>

                <p>
                    <a href="{$link->getAdminLink('AdminLocalization')}" target="_blank" class="btn btn-default">
                        <i class="icon-external-link"></i> {l s='Open Localization Settings' mod='geolangvars'}
                    </a>
                </p>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <i class="icon-info-circle"></i> {l s='What is GeoIP?' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <p>
                    {l s='GeoIP is a database that maps IP addresses to geographic locations. PrestaShop uses this to detect visitor countries.' mod='geolangvars'}
                </p>
                <ul>
                    <li>{l s='Accurate country detection' mod='geolangvars'}</li>
                    <li>{l s='Works without Cloudflare' mod='geolangvars'}</li>
                    <li>{l s='Free database available (GeoLite2)' mod='geolangvars'}</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel">
            <div class="panel-heading">
                <i class="icon-upload"></i> {l s='Upload GeoIP Database' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <p>
                    {l s='Upload a GeoIP database file (.dat or .mmdb) to enable geolocation.' mod='geolangvars'}
                </p>

                <form method="post" action="{$link->getAdminLink('AdminGeoLangVars')}&active_tab=geoip"
                      enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="geoip_file">
                            {l s='Select GeoIP file' mod='geolangvars'}
                        </label>
                        <input type="file" name="geoip_file" id="geoip_file"
                               accept=".dat,.mmdb" class="form-control">
                        <p class="help-block">
                            {l s='Accepted formats: .dat, .mmdb' mod='geolangvars'}
                        </p>
                    </div>

                    <button type="submit" name="submitUploadGeoIP" class="btn btn-primary">
                        <i class="icon-upload"></i> {l s='Upload File' mod='geolangvars'}
                    </button>
                </form>

                <hr>

                <h4>{l s='Current GeoIP Files' mod='geolangvars'}</h4>
                {if isset($geoip_files) && count($geoip_files) > 0}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>{l s='File Name' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Size' mod='geolangvars'}</th>
                            <th class="text-right">{l s='Date' mod='geolangvars'}</th>
                        </tr>
                        </thead>
                        <tbody>
                        {foreach from=$geoip_files item=file}
                            <tr>
                                <td>
                                    <i class="icon-file"></i> {$file.name}
                                </td>
                                <td class="text-right">
                                    {($file.size / 1024 / 1024)|string_format:"%.2f"} MB
                                </td>
                                <td class="text-right">
                                    {$file.date}
                                </td>
                            </tr>
                        {/foreach}
                        </tbody>
                    </table>
                {else}
                    <div class="alert alert-warning">
                        {l s='No GeoIP database found.' mod='geolangvars'}
                    </div>
                {/if}

                <p class="help-block">
                    <i class="icon-folder"></i>
                    {l s='Files location:' mod='geolangvars'}
                    <code>{$geoip_path}</code>
                </p>
            </div>
        </div>

        <div class="panel">
            <div class="panel-heading">
                <i class="icon-download"></i> {l s='Download GeoIP Database' mod='geolangvars'}
            </div>
            <div class="panel-body">
                <p>
                    <strong>{l s='MaxMind GeoLite2' mod='geolangvars'}</strong>
                    {l s='(Free, updated monthly)' mod='geolangvars'}
                </p>
                <ol>
                    <li>
                        {l s='Create a free account on' mod='geolangvars'}
                        <a href="https://www.maxmind.com/en/geolite2/signup" target="_blank">
                            MaxMind
                        </a>
                    </li>
                    <li>{l s='Download GeoLite2 Country database' mod='geolangvars'}</li>
                    <li>{l s='Extract the .mmdb file' mod='geolangvars'}</li>
                    <li>{l s='Upload it using the form above' mod='geolangvars'}</li>
                </ol>

                <a href="https://www.maxmind.com/en/geolite2/signup"
                   target="_blank"
                   class="btn btn-success">
                    <i class="icon-external-link"></i> {l s='Get GeoLite2 Free' mod='geolangvars'}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="alert alert-info">
    <h4><i class="icon-lightbulb-o"></i> {l s='Recommendation' mod='geolangvars'}</h4>
    <p>
        {l s='For best performance, use Cloudflare (free) which provides instant geolocation without any database file.' mod='geolangvars'}
        {l s='GeoIP is only needed as a fallback if Cloudflare is not available.' mod='geolangvars'}
    </p>
</div>