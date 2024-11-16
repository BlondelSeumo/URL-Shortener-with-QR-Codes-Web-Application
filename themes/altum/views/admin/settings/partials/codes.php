<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="qr_codes_is_enabled" name="qr_codes_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->codes->qr_codes_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="qr_codes_is_enabled"><i class="fas fa-fw fa-sm fa-qrcode text-muted mr-1"></i> <?= l('admin_settings.codes.qr_codes_is_enabled') ?></label>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="qr_codes_branding_logo"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('admin_settings.codes.qr_codes_branding_logo') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'qr_code_logo', 'file_key' => 'qr_codes_branding_logo', 'already_existing_image' => settings()->codes->qr_codes_branding_logo]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('qr_code_logo')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="qr_codes_default_image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('admin_settings.codes.qr_codes_default_image') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'qr_code_default_image', 'file_key' => 'qr_codes_default_image', 'already_existing_image' => settings()->codes->qr_codes_default_image]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('qr_code_default_image')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <?php foreach(['logo', 'background'] as $key): ?>
        <div class="form-group">
            <label for="<?= $key . '_size_limit' ?>"><?= l('admin_settings.codes.' . $key . '_size_limit') ?></label>
            <div class="input-group">
                <input id="<?= $key . '_size_limit' ?>" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="<?= $key . '_size_limit' ?>" class="form-control" value="<?= settings()->codes->{$key . '_size_limit'} ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.mb') ?></span>
                </div>
            </div>
            <small class="form-text text-muted"><?= l('global.accessibility.admin_file_size_limit_help') ?></small>
        </div>
    <?php endforeach ?>

    <div class="form-group mt-5">
        <?php $available_qr_codes = require APP_PATH . 'includes/qr_codes.php'; ?>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="h5"><?= l('admin_settings.codes.available_qr_codes') . ' (' . count($available_qr_codes) . ')' ?></h3>

            <div>
                <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_qr_codes[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_qr_codes[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
            </div>
        </div>

        <div class="row">
            <?php foreach($available_qr_codes as $key => $value): ?>
                <div class="col-12 col-lg-6">
                    <div class="custom-control custom-checkbox my-2">
                        <input id="<?= 'qr_code_' . $key ?>" name="available_qr_codes[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= settings()->codes->available_qr_codes->{$key} ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label d-flex align-items-center" for="<?= 'qr_code_' . $key ?>">
                            <?= l('qr_codes.type.' . $key) ?>
                        </label>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
