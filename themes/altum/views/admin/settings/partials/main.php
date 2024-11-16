<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="title"><i class="fas fa-fw fa-sm fa-heading text-muted mr-1"></i> <?= l('admin_settings.main.title') ?></label>
        <input id="title" type="text" name="title" class="form-control" value="<?= settings()->main->title ?>" required="required" />
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="logo_light"><i class="fas fa-fw fa-sm fa-sun text-muted mr-1"></i> <?= l('admin_settings.main.logo_light') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'logo_light', 'file_key' => 'logo_light', 'already_existing_image' => settings()->main->logo_light]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('logo_light')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="logo_dark"><i class="fas fa-fw fa-sm fa-moon text-muted mr-1"></i> <?= l('admin_settings.main.logo_dark') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'logo_dark', 'file_key' => 'logo_dark', 'already_existing_image' => settings()->main->logo_dark]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('logo_dark')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="logo_email"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.main.logo_email') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'logo_email', 'file_key' => 'logo_email', 'already_existing_image' => settings()->main->logo_email]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('logo_email')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="favicon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('admin_settings.main.favicon') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'favicon', 'file_key' => 'favicon', 'already_existing_image' => settings()->main->favicon]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('favicon')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
        <label for="opengraph"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('admin_settings.main.opengraph') ?></label>
        <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'opengraph', 'file_key' => 'opengraph', 'already_existing_image' => settings()->main->opengraph]) ?>
        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('opengraph')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
    </div>

    <div class="form-group">
        <label for="default_timezone"><i class="fas fa-fw fa-sm fa-atlas text-muted mr-1"></i> <?= l('admin_settings.main.default_timezone') ?></label>
        <select id="default_timezone" name="default_timezone" class="custom-select">
            <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . (settings()->main->default_timezone == $timezone ? 'selected="selected"' : null) . '>' . $timezone . '</option>' ?>
        </select>
        <small class="form-text text-muted"><?= l('admin_settings.main.default_timezone_help') ?></small>
    </div>

    <div class="form-group">
        <label for="default_theme_style"><i class="fas fa-fw fa-sm fa-fill-drip text-muted mr-1"></i> <?= l('admin_settings.main.default_theme_style') ?></label>
        <select id="default_theme_style" name="default_theme_style" class="custom-select">
            <?php foreach(\Altum\ThemeStyle::$themes as $key => $value) echo '<option value="' . $key . '" ' . (settings()->main->default_theme_style == $key ? 'selected="selected"' : null) . '>' . $key . '</option>' ?>
        </select>
    </div>

    <div class="form-group">
        <div class="d-flex flex-column flex-xl-row justify-content-between">
            <label for="default_language"><i class="fas fa-fw fa-sm fa-language text-muted mr-1"></i> <?= l('admin_settings.main.default_language') ?></label>
            <a href="<?= url('admin/languages') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('global.create') ?></a>
        </div>
        <select id="default_language" name="default_language" class="custom-select">
            <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code) echo '<option value="' . $language_name . '" ' . (settings()->main->default_language == $language_name ? 'selected="selected"' : null) . '>' . $language_name . ' - ' . $language_code . '</option>' ?>
        </select>
    </div>

    <div class="form-group">
        <label for="default_results_per_page"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('admin_settings.main.default_results_per_page') ?></label>
        <select id="default_results_per_page" name="default_results_per_page" class="custom-select">
            <?php foreach([10, 25, 50, 100, 250, 500, 1000] as $key): ?>
                <option value="<?= $key ?>" <?= settings()->main->default_results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
            <?php endforeach ?>
        </select>
    </div>

    <div class="form-group">
        <label for="default_order_type"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('admin_settings.main.default_order_type') ?></label>
        <select id="default_order_type" name="default_order_type" class="custom-select">
            <option value="ASC" <?= settings()->main->default_order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
            <option value="DESC" <?= settings()->main->default_order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
        </select>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#app_settings_container" aria-expanded="false" aria-controls="app_settings_container">
        <i class="fas fa-fw fa-sliders-h fa-sm mr-1"></i> <?= l('admin_settings.main.app_settings') ?>
    </button>

    <div class="collapse" id="app_settings_container">
        <div class="form-group custom-control custom-switch">
            <input id="theme_style_change_is_enabled" name="theme_style_change_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->main->theme_style_change_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="theme_style_change_is_enabled"><i class="fas fa-fw fa-sm fa-object-ungroup text-muted mr-1"></i> <?= l('admin_settings.main.theme_style_change_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.main.theme_style_change_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="auto_language_detection_is_enabled" name="auto_language_detection_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->main->auto_language_detection_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="auto_language_detection_is_enabled"><i class="fas fa-fw fa-sm fa-language text-muted mr-1"></i> <?= l('admin_settings.main.auto_language_detection_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.main.auto_language_detection_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->main->api_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="api_is_enabled"><i class="fas fa-fw fa-sm fa-code text-muted mr-1"></i> <?= l('admin_settings.main.api_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="broadcasts_statistics_is_enabled" name="broadcasts_statistics_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->main->broadcasts_statistics_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="broadcasts_statistics_is_enabled"><i class="fas fa-fw fa-sm fa-star text-muted mr-1"></i> <?= l('admin_settings.main.broadcasts_statistics_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.main.broadcasts_statistics_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="breadcrumbs_is_enabled" name="breadcrumbs_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->main->breadcrumbs_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="breadcrumbs_is_enabled"><i class="fas fa-fw fa-sm fa-shoe-prints text-muted mr-1"></i> <?= l('admin_settings.main.breadcrumbs_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.main.breadcrumbs_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="display_pagination_when_no_pages" name="display_pagination_when_no_pages" type="checkbox" class="custom-control-input" <?= settings()->main->display_pagination_when_no_pages ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="display_pagination_when_no_pages"><i class="fas fa-fw fa-sm fa-circle text-muted mr-1"></i> <?= l('admin_settings.main.display_pagination_when_no_pages') ?></label>
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.main.display_pagination_when_no_pages_help'), SITE_URL) ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="se_indexing" name="se_indexing" type="checkbox" class="custom-control-input" <?= settings()->main->se_indexing ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="se_indexing"><i class="fab fa-fw fa-sm fa-searchengin text-muted mr-1"></i> <?= l('admin_settings.main.se_indexing') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="ai_scraping_is_allowed" name="ai_scraping_is_allowed" type="checkbox" class="custom-control-input" <?= settings()->main->ai_scraping_is_allowed ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="ai_scraping_is_allowed"><i class="fas fa-fw fa-sm fa-spider text-muted mr-1"></i> <?= l('admin_settings.main.ai_scraping_is_allowed') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.main.ai_scraping_is_allowed_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="force_https_is_enabled" name="force_https_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->main->force_https_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="force_https_is_enabled"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('admin_settings.main.force_https_is_enabled') ?></label>
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.main.force_https_is_enabled_help'), SITE_URL) ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#index_settings_container" aria-expanded="false" aria-controls="index_settings_container">
        <i class="fas fa-fw fa-plane-arrival fa-sm mr-1"></i> <?= l('admin_settings.main.index_settings') ?>
    </button>

    <div class="collapse" id="index_settings_container">
        <div class="form-group custom-control custom-switch">
            <input id="display_index_plans" name="display_index_plans" type="checkbox" class="custom-control-input" <?= settings()->main->display_index_plans ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="display_index_plans"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i> <?= l('admin_settings.main.display_index_plans') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="display_index_testimonials" name="display_index_testimonials" type="checkbox" class="custom-control-input" <?= settings()->main->display_index_testimonials ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="display_index_testimonials"><i class="fas fa-fw fa-sm fa-users text-muted mr-1"></i> <?= l('admin_settings.main.display_index_testimonials') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="display_index_faq" name="display_index_faq" type="checkbox" class="custom-control-input" <?= settings()->main->display_index_faq ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="display_index_faq"><i class="fas fa-fw fa-sm fa-circle-question text-muted mr-1"></i> <?= l('admin_settings.main.display_index_faq') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="display_index_latest_blog_posts" name="display_index_latest_blog_posts" type="checkbox" class="custom-control-input" <?= settings()->main->display_index_latest_blog_posts ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="display_index_latest_blog_posts"><i class="fas fa-fw fa-sm fa-rss text-muted mr-1"></i> <?= l('admin_settings.main.display_index_latest_blog_posts') ?></label>
        </div>

        <div class="form-group">
            <label for="index_url"><i class="fas fa-fw fa-sm fa-plane-arrival text-muted mr-1"></i> <?= l('admin_settings.main.index_url') ?></label>
            <input id="index_url" type="url" name="index_url" class="form-control" value="<?= settings()->main->index_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.main.index_url_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#other_settings_container" aria-expanded="false" aria-controls="other_settings_container">
        <i class="fas fa-fw fa-tasks fa-sm mr-1"></i> <?= l('admin_settings.main.other_settings') ?>
    </button>

    <div class="collapse" id="other_settings_container">
        <div class="form-group">
            <label for="not_found_url"><i class="fas fa-fw fa-sm fa-compass text-muted mr-1"></i> <?= l('admin_settings.main.not_found_url') ?></label>
            <input id="not_found_url" type="url" name="not_found_url" class="form-control" value="<?= settings()->main->not_found_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.main.not_found_url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="terms_and_conditions_url"><i class="fas fa-fw fa-sm fa-file-word text-muted mr-1"></i> <?= l('admin_settings.main.terms_and_conditions_url') ?></label>
            <input id="terms_and_conditions_url" type="text" name="terms_and_conditions_url" class="form-control" value="<?= settings()->main->terms_and_conditions_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.main.terms_and_conditions_url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="privacy_policy_url"><i class="fas fa-fw fa-sm fa-file-word text-muted mr-1"></i> <?= l('admin_settings.main.privacy_policy_url') ?></label>
            <input id="privacy_policy_url" type="text" name="privacy_policy_url" class="form-control" value="<?= settings()->main->privacy_policy_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.main.privacy_policy_url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="chart_cache"><i class="fas fa-fw fa-sm fa-chart-bar text-muted mr-1"></i> <?= l('admin_settings.main.chart_cache') ?></label>
            <div class="input-group">
                <input id="chart_cache" type="number" min="0" max="24" name="chart_cache" class="form-control" value="<?= settings()->main->chart_cache ?? 12 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.hours') ?></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="chart_days"><i class="fas fa-fw fa-sm fa-stopwatch text-muted mr-1"></i> <?= l('admin_settings.main.chart_days') ?></label>
            <div class="input-group">
                <input id="chart_days" type="number" min="7" max="90" name="chart_days" class="form-control" value="<?= settings()->main->chart_days ?? 30 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.days') ?></span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="openai_api_key"><i class="fas fa-fw fa-sm fa-robot text-muted mr-1"></i> <?= l('admin_settings.main.openai_api_key') ?></label>
            <input id="openai_api_key" type="text" name="openai_api_key" class="form-control" value="<?= settings()->main->openai_api_key ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.main.openai_api_key_help') ?></small>
        </div>

        <div class="form-group">
            <label for="openai_model"><i class="fas fa-fw fa-sm fa-robot text-muted mr-1"></i> <?= l('admin_settings.main.openai_model') ?></label>
            <select id="openai_model" name="openai_model" class="custom-select">
                <?php foreach(['gpt-4o', 'gpt-4o-mini', 'gpt-4', 'gpt-4-turbo', 'gpt-3.5-turbo',] as $model): ?>
                    <option value="<?= $model ?>" <?= settings()->main->openai_model == $model ? 'selected="selected"' : null ?>><?= $model ?></option>
                <?php endforeach ?>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="sitemap_url"><i class="fas fa-fw fa-sm fa-sitemap text-muted mr-1"></i> <?= l('admin_settings.main.sitemap_url') ?></label>
        <input id="sitemap_url" type="text" name="sitemap_url" class="form-control" value="<?= SITE_URL . 'sitemap' ?>" onclick="this.select();" readonly="readonly" />
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
