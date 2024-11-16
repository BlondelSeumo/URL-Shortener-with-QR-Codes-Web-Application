<?php defined('ALTUMCODE') || die() ?>

<div>
    <div <?= !\Altum\Plugin::is_active('offload') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('offload')->name ?? 'offload') . '"' : null ?>>
        <div class="<?= !\Altum\Plugin::is_active('offload') ? 'container-disabled' : null ?>">

            <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#offload_container" aria-expanded="false" aria-controls="offload_container">
                <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('admin_settings.offload.offload') ?>
            </button>

            <div class="collapse" id="offload_container">
                <div class="form-group">
                    <label for="uploads_url"><?= l('admin_settings.offload.uploads_url') ?></label>
                    <input id="uploads_url" type="url" name="uploads_url" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->uploads_url : null ?>" />
                    <small class="form-text text-muted"><?= l('admin_settings.offload.uploads_url_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="assets_url"><?= l('admin_settings.offload.assets_url') ?></label>
                    <input id="assets_url" type="url" name="assets_url" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->assets_url : null ?>" />
                    <small class="form-text text-muted"><?= l('admin_settings.offload.assets_url_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="provider"><?= l('admin_settings.offload.provider') ?></label>
                    <select id="provider" name="provider" class="custom-select">
                        <option value="aws-s3" <?= \Altum\Plugin::is_active('offload') && settings()->offload->provider == 'aws-s3' ? 'selected="selected"' : null ?>>AWS S3</option>
                        <option value="digitalocean-spaces" <?= \Altum\Plugin::is_active('offload') && settings()->offload->provider == 'digitalocean-spaces' ? 'selected="selected"' : null ?>>DigitalOcean Spaces</option>
                        <option value="vultr-objects" <?= \Altum\Plugin::is_active('offload') && settings()->offload->provider == 'vultr-objects' ? 'selected="selected"' : null ?>>Vultr Objects</option>
                        <option value="wasabi" <?= \Altum\Plugin::is_active('offload') && settings()->offload->provider == 'wasabi' ? 'selected="selected"' : null ?>>Wasabi</option>
                        <option value="other-s3" <?= \Altum\Plugin::is_active('offload') && settings()->offload->provider == 'other-s3' ? 'selected="selected"' : null ?>>Other storage compatible with S3 SDK</option>
                    </select>
                </div>

                <div id="provider_others" class="form-group">
                    <label for="endpoint_url"><?= l('admin_settings.offload.endpoint_url') ?></label>
                    <input id="endpoint_url" type="url" name="endpoint_url" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->endpoint_url : null ?>" />
                </div>

                <div class="form-group">
                    <label for="access_key"><?= l('admin_settings.offload.access_key') ?></label>
                    <input id="access_key" type="text" name="access_key" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->access_key : null ?>" />
                </div>

                <div class="form-group">
                    <label for="secret_access_key"><?= l('admin_settings.offload.secret_access_key') ?></label>
                    <input id="secret_access_key" type="text" name="secret_access_key" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->secret_access_key : null ?>" />
                </div>

                <div class="form-group">
                    <label for="storage_name"><?= l('admin_settings.offload.storage_name') ?></label>
                    <input id="storage_name" type="text" name="storage_name" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->storage_name : null ?>" />
                </div>

                <div class="form-group">
                    <label for="region"><?= l('admin_settings.offload.region') ?></label>
                    <input id="region" type="text" name="region" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->region : null ?>" />
                </div>
            </div>

            <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#cdn_container" aria-expanded="false" aria-controls="cdn_container">
                <i class="fas fa-fw fa-bolt fa-sm mr-1"></i> <?= l('admin_settings.offload.cdn') ?>
            </button>

            <div class="collapse" id="cdn_container">
                <div class="form-group">
                    <label for="cdn_uploads_url"><?= l('admin_settings.offload.cdn_uploads_url') ?></label>
                    <input id="cdn_uploads_url" type="url" name="cdn_uploads_url" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->cdn_uploads_url : null ?>" placeholder="<?= l('global.url_placeholder') ?>" />
                    <small class="form-text text-muted"><?= l('admin_settings.offload.cdn_url_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="cdn_assets_url"><?= l('admin_settings.offload.cdn_assets_url') ?></label>
                    <input id="cdn_assets_url" type="url" name="cdn_assets_url" class="form-control" value="<?= \Altum\Plugin::is_active('offload') ? settings()->offload->cdn_assets_url : null ?>" placeholder="<?= l('global.url_placeholder') ?>" />
                    <small class="form-text text-muted"><?= l('admin_settings.offload.cdn_url_help') ?></small>
                </div>

                <?php
                $origin_url = SITE_URL;

                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url && settings()->offload->assets_url) {
                    $parsed_url = parse_url(settings()->offload->uploads_url);
                    $origin_url = $parsed_url['scheme'] . '://' . $parsed_url['host'] . '/';
                }
                ?>

                <div class="form-group">
                    <label for="cdn_origin_url"><?= l('admin_settings.offload.cdn_origin_url') ?></label>
                    <input id="cdn_origin_url" type="text" name="cdn_origin_url" class="form-control" value="<?= $origin_url ?>" onclick="this.select();" readonly="readonly" />
                    <small class="form-text text-muted"><?= l('admin_settings.offload.cdn_url_help') ?></small>
                </div>
            </div>

        </div>
    </div>
</div>

<?php if(\Altum\Plugin::is_active('offload')): ?>
    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
<?php endif ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Offload */
    let initiate_offload_provider = () => {
        switch(document.querySelector('select[name="provider"]').value) {
            case 'aws-s3':
                document.querySelector('#provider_others').classList.add('d-none');
                break;

            /* Other providers */
            default:
                document.querySelector('#provider_others').classList.remove('d-none');
                break;
        }
    }

    initiate_offload_provider();
    document.querySelector('select[name="provider"]').addEventListener('change', initiate_offload_provider);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
