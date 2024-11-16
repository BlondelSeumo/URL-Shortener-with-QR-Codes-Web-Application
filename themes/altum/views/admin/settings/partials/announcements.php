<?php defined('ALTUMCODE') || die() ?>

<div>
    <ul class="nav nav-pills d-flex flex-fill flex-column flex-lg-row mb-3" role="tablist">
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link active" id="pills-guests-tab" data-toggle="pill" href="#pills-guests" role="tab" aria-controls="pills-home" aria-selected="true">
                <i class="fas fa-fw fa-sm fa-user-secret mr-1"></i>
                <?= l('admin_settings.announcements.guests') ?>
            </a>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link" id="pills-users-tab" data-toggle="pill" href="#pills-users" role="tab" aria-controls="pills-users" aria-selected="false">
                <i class="fas fa-fw fa-sm fa-user mr-1"></i>
                <?= l('admin_settings.announcements.users') ?>
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-guests" role="tabpanel" aria-labelledby="pills-guests-tab">
            <div class="form-group custom-control custom-switch">
                <input id="guests_is_enabled" name="guests_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->announcements->guests_is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="guests_is_enabled"><?= l('global.enable') ?></label>
            </div>

            <div class="form-group">
                <label for="guests_content" class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-fw fa-sm fa-bullhorn text-muted mr-1"></i> <?= l('admin_settings.announcements.content') ?></span>
                    <button class="btn btn-sm btn-dark" type="button" data-toggle="collapse" data-target="#guests_content_translate_container" aria-expanded="false" aria-controls="guests_content_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                </label>
                <textarea id="guests_content" name="guests_content" class="form-control"><?= settings()->announcements->guests_content ?></textarea>
                <small class="form-text text-muted"><?= l('admin_settings.announcements.content_help') ?></small>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="collapse" id="guests_content_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_guests_content' ?>"><i class="fas fa-fw fa-sm fa-bullhorn text-muted mr-1"></i> <?= l('admin_settings.announcements.content') ?> - <?= $language_name ?></label>
                            <textarea id="<?= 'translation_' . $language_name . '_guests_content' ?>" name="<?= 'translations[' . $language_name . '][guests_content]' ?>" class="form-control"><?= settings()->announcements->translations->{$language_name}->guests_content ?? null ?></textarea>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="guests_text_color"><i class="fas fa-fw fa-sm fa-palette text-muted mr-1"></i> <?= l('admin_settings.announcements.text_color') ?></label>
                <input id="guests_text_color" type="hidden" name="guests_text_color" class="form-control" value="<?= settings()->announcements->guests_text_color ?>" data-color-picker />
            </div>

            <div class="form-group">
                <label for="guests_background_color"><i class="fas fa-fw fa-sm fa-fill text-muted mr-1"></i> <?= l('admin_settings.announcements.background_color') ?></label>
                <input id="guests_background_color" type="hidden" name="guests_background_color" class="form-control" value="<?= settings()->announcements->guests_background_color ?>" data-color-picker />
            </div>
        </div>

        <div class="tab-pane fade" id="pills-users" role="tabpanel" aria-labelledby="pills-users-tab">
            <div class="form-group custom-control custom-switch">
                <input id="users_is_enabled" name="users_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->announcements->users_is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="users_is_enabled"><?= l('global.enable') ?></label>
            </div>

            <div class="form-group">
                <label for="users_content" class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-fw fa-sm fa-bullhorn text-muted mr-1"></i> <?= l('admin_settings.announcements.content') ?></span>
                    <button class="btn btn-sm btn-dark" type="button" data-toggle="collapse" data-target="#users_content_translate_container" aria-expanded="false" aria-controls="users_content_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                </label>
                <textarea id="users_content" name="users_content" class="form-control"><?= settings()->announcements->users_content ?></textarea>
                <small class="form-text text-muted"><?= l('admin_settings.announcements.content_help') ?></small>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="collapse" id="users_content_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_users_content' ?>"><i class="fas fa-fw fa-sm fa-bullhorn text-muted mr-1"></i> <?= l('admin_settings.announcements.content') ?> - <?= $language_name ?></label>
                            <textarea id="<?= 'translation_' . $language_name . '_users_content' ?>" name="<?= 'translations[' . $language_name . '][users_content]' ?>" class="form-control"><?= settings()->announcements->translations->{$language_name}->users_content ?? null ?></textarea>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="users_text_color"><i class="fas fa-fw fa-sm fa-palette text-muted mr-1"></i> <?= l('admin_settings.announcements.text_color') ?></label>
                <input id="users_text_color" type="hidden" name="users_text_color" class="form-control" value="<?= settings()->announcements->users_text_color ?>" data-color-picker />
            </div>

            <div class="form-group">
                <label for="users_background_color"><i class="fas fa-fw fa-sm fa-fill text-muted mr-1"></i> <?= l('admin_settings.announcements.background_color') ?></label>
                <input id="users_background_color" type="hidden" name="users_background_color" class="form-control" value="<?= settings()->announcements->users_background_color ?>" data-color-picker />
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
