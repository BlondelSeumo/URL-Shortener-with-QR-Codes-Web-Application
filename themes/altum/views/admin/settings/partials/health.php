<?php defined('ALTUMCODE') || die() ?>

<p class="text-muted"><?= l('admin_settings.health.help') ?></p>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th>Requirement</th>
            <th>Status</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                PHP Version
                <div class="small text-muted">8.1 - 8.3</div>
            </td>
            <td><?= PHP_VERSION ?></td>
            <td class="text-right">
                <?php if(version_compare(PHP_VERSION, '8.1.0', '>=') && version_compare(PHP_VERSION, '8.4', '<')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                OpenSSL
                <div class="small text-muted">Extension</div>
            </td>
            <td><?= extension_loaded('openssl') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(extension_loaded('openssl')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                mbstring
                <div class="small text-muted">Extension</div>
            </td>
            <td><?= extension_loaded('mbstring') && function_exists('mb_get_info') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(extension_loaded('mbstring') && function_exists('mb_get_info')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                GD
                <div class="small text-muted">Extension</div>
            </td>
            <td><?= extension_loaded('gd') && function_exists('gd_info') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(extension_loaded('gd') && function_exists('gd_info')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                MySQLi
                <div class="small text-muted">Extension</div>
            </td>
            <td><?= function_exists('mysqli_connect') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(function_exists('mysqli_connect')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                cURL
                <div class="small text-muted">Extension</div>
            </td>
            <td><?= function_exists('curl_version') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(function_exists('curl_version')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                INTL
                <div class="small text-muted">Extension</div>
            </td>
            <td><?= extension_loaded('intl') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(extension_loaded('intl')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                set_time_limit()
                <div class="small text-muted">Function</div>
            </td>
            <td><?= function_exists('set_time_limit') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(function_exists('set_time_limit')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                iconv()
                <div class="small text-muted">Function</div>
            </td>
            <td><?= function_exists('iconv') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(function_exists('iconv')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                get_headers()
                <div class="small text-muted">Function</div>
            </td>
            <td><?= function_exists('get_headers') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(function_exists('get_headers')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                mime_content_type()
                <div class="small text-muted">Function</div>
            </td>
            <td><?= function_exists('mime_content_type') ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(function_exists('mime_content_type')): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>

        <tr>
            <td>
                allow_url_fopen
                <div class="small text-muted">INI Configuration</div>
            </td>
            <td><?= filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN) ? 'Enabled' : 'Not Enabled' ?></td>
            <td class="text-right">
                <?php if(filter_var(ini_get('allow_url_fopen'), FILTER_VALIDATE_BOOLEAN)): ?>
                    ✅
                <?php else: ?>
                    ❌
                <?php endif ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
