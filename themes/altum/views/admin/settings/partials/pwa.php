<?php defined('ALTUMCODE') || die() ?>

<div>
    <div <?= !\Altum\Plugin::is_active('pwa') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('pwa')->name ?? 'pwa') . '"' : null ?>>
        <div class="<?= !\Altum\Plugin::is_active('pwa') ? 'container-disabled' : null ?>">
            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('admin_settings.pwa.is_enabled') ?></label>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="display_install_bar" name="display_install_bar" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('pwa') && settings()->pwa->display_install_bar ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="display_install_bar"><?= l('admin_settings.pwa.display_install_bar') ?></label>
            </div>

            <div class="form-group">
                <label for="display_install_bar_delay"><?= l('admin_settings.pwa.display_install_bar_delay') ?></label>
                <div class="input-group">
                    <input type="number" id="display_install_bar_delay" name="display_install_bar_delay" min="0" class="form-control" value="<?= settings()->pwa->display_install_bar_delay ?>" required="required" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="app_name"><?= l('admin_settings.pwa.app_name') ?></label>
                <input id="app_name" type="text" name="app_name" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->app_name : null ?>" />
            </div>

            <div class="form-group" data-character-counter="input">
                <label for="short_app_name" class="d-flex justify-content-between align-items-center">
                    <span><?= l('admin_settings.pwa.short_app_name') ?></span>
                    <small class="text-muted" data-character-counter-wrapper></small>
                </label>
                <input id="short_app_name" type="text" name="short_app_name" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->short_app_name : null ?>" maxlength="12" />
            </div>

            <div class="form-group">
                <label for="app_description"><?= l('admin_settings.pwa.app_description') ?></label>
                <input id="app_description" type="text" name="app_description" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->app_description : null ?>" />
            </div>

            <div class="form-group">
                <label for="app_start_url"><?= l('admin_settings.pwa.app_start_url') ?></label>
                <input id="app_start_url" type="text" name="app_start_url" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->app_start_url : null ?>" placeholder="<?= SITE_URL ?>" />
                <small class="form-text text-muted"><?= l('admin_settings.pwa.app_start_url_help') ?></small>
            </div>

            <div class="form-group">
                <label for="theme_color"><?= l('admin_settings.pwa.theme_color') ?></label>
                <input id="theme_color" type="hidden" name="theme_color" class="form-control" value="<?= settings()->pwa->theme_color ?>" data-color-picker />
            </div>

            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                <label for="app_icon"><?= l('admin_settings.pwa.app_icon') ?></label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'app_icon', 'file_key' => 'app_icon', 'already_existing_image' => settings()->pwa->app_icon]) ?>
                <small class="form-text text-muted"><?= l('admin_settings.pwa.app_icon_help') ?></small>
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('app_icon')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
            </div>

            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                <label for="app_icon_maskable"><?= l('admin_settings.pwa.app_icon_maskable') ?></label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'app_icon', 'file_key' => 'app_icon_maskable', 'already_existing_image' => settings()->pwa->app_icon_maskable]) ?>
                <small class="form-text text-muted"><?= l('admin_settings.pwa.app_icon_maskable_help') ?></small>
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('app_icon')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
            </div>

            <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#mobile_screenshots_container" aria-expanded="false" aria-controls="mobile_screenshots_container">
                <i class="fas fa-fw fa-mobile fa-sm mr-1"></i> <?= l('admin_settings.pwa.mobile_screenshots') ?>
            </button>

            <div class="collapse" id="mobile_screenshots_container">
                <div class="alert alert-info"><?= l('admin_settings.pwa.mobile_screenshots_help') ?></div>
                <div class="alert alert-info"><?= l('admin_settings.pwa.mobile_screenshots_help2') ?></div>

                <?php foreach([1, 2, 3, 4 ,5, 6] as $key): ?>
                    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                        <label for="<?= 'mobile_screenshot_' . $key ?>"><?= sprintf(l('admin_settings.pwa.screenshot_x'), $key) ?></label>
                        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'app_screenshots', 'file_key' => 'mobile_screenshot_' . $key, 'already_existing_image' => settings()->pwa->{'mobile_screenshot_' . $key}]) ?>
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('app_screenshots')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                    </div>
                <?php endforeach ?>
            </div>

            <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#desktop_screenshots_container" aria-expanded="false" aria-controls="desktop_screenshots_container">
                <i class="fas fa-fw fa-desktop fa-sm mr-1"></i> <?= l('admin_settings.pwa.desktop_screenshots') ?>
            </button>

            <div class="collapse" id="desktop_screenshots_container">
                <div class="alert alert-info"><?= l('admin_settings.pwa.desktop_screenshots_help') ?></div>
                <div class="alert alert-info"><?= l('admin_settings.pwa.desktop_screenshots_help2') ?></div>

                <?php foreach([1,2,3,4,5,6,7,8] as $key): ?>
                    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                        <label for="<?= 'desktop_screenshot_' . $key ?>"><?= sprintf(l('admin_settings.pwa.screenshot_x'), $key) ?></label>
                        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'app_screenshots', 'file_key' => 'desktop_screenshot_' . $key, 'already_existing_image' => settings()->pwa->{'desktop_screenshot_' . $key}]) ?>
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('app_screenshots')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                    </div>
                <?php endforeach ?>
            </div>

            <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#shortcuts_container" aria-expanded="false" aria-controls="shortcuts_container">
                <i class="fas fa-fw fa-wand-sparkles fa-sm mr-1"></i> <?= l('admin_settings.pwa.shortcuts') ?>
            </button>

            <div class="collapse" id="shortcuts_container">
                <?php foreach([1,2,3] as $key): ?>
                    <div class="form-group">
                        <label for="<?= 'shortcut_name_' . $key ?>"><?= sprintf(l('admin_settings.pwa.shortcut_name_x'), $key) ?></label>
                        <input id="<?= 'shortcut_name_' . $key ?>" type="text" name="<?= 'shortcut_name_' . $key ?>" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->{'shortcut_name_' . $key} : null ?>" />
                    </div>

                    <div class="form-group">
                        <label for="<?= 'shortcut_description_' . $key ?>"><?= sprintf(l('admin_settings.pwa.shortcut_description_x'), $key) ?></label>
                        <input id="<?= 'shortcut_description_' . $key ?>" type="text" name="<?= 'shortcut_description_' . $key ?>" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->{'shortcut_description_' . $key} : null ?>" />
                    </div>

                    <div class="form-group">
                        <label for="<?= 'shortcut_url_' . $key ?>"><?= sprintf(l('admin_settings.pwa.shortcut_url_x'), $key) ?></label>
                        <input id="<?= 'shortcut_url_' . $key ?>" type="url" name="<?= 'shortcut_url_' . $key ?>" class="form-control" value="<?= \Altum\Plugin::is_active('pwa') ? settings()->pwa->{'shortcut_url_' . $key} : null ?>" />
                    </div>

                    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                        <label for="<?= 'shortcut_icon_' . $key ?>"><?= sprintf(l('admin_settings.pwa.shortcut_icon_x'), $key) ?></label>
                        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'app_screenshots', 'file_key' => 'shortcut_icon_' . $key, 'already_existing_image' => settings()->pwa->{'shortcut_icon_' . $key}]) ?>
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('app_screenshots')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php if(\Altum\Plugin::is_active('pwa')): ?>
    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
<?php endif ?>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
