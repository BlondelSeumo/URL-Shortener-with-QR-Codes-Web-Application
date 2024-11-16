<?php defined('ALTUMCODE') || die() ?>

<div>
    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#biolinks_container" aria-expanded="false" aria-controls="biolinks_container">
        <i class="fas fa-fw fa-hashtag fa-sm mr-1"></i> <?= l('admin_settings.links.biolinks') ?>
    </button>

    <div class="collapse" id="biolinks_container">
        <div class="form-group custom-control custom-switch">
            <input id="biolinks_is_enabled" name="biolinks_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->biolinks_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="biolinks_is_enabled"><?= l('admin_settings.links.biolinks_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.biolinks_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="example_url"><?= l('admin_settings.links.example_url') ?></label>
            <input id="example_url" type="url" name="example_url" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" value="<?= settings()->links->example_url ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.links.example_url_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="biolinks_templates_is_enabled" name="biolinks_templates_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->biolinks_templates_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="biolinks_templates_is_enabled"><?= l('admin_settings.links.biolinks_templates_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.biolinks_templates_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="biolinks_themes_is_enabled" name="biolinks_themes_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->biolinks_themes_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="biolinks_themes_is_enabled"><?= l('admin_settings.links.biolinks_themes_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.biolinks_themes_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="biolinks_default_active_tab"><?= l('admin_settings.links.biolinks_default_active_tab') ?></label>
            <select id="biolinks_default_active_tab" name="biolinks_default_active_tab" class="custom-select">
                <option value="settings" <?= settings()->links->biolinks_default_active_tab == 'settings' ? 'selected="selected"' : null ?>><?= l('link.header.settings_tab') ?></option>
                <option value="blocks" <?= settings()->links->biolinks_default_active_tab == 'blocks' ? 'selected="selected"' : null ?>><?= l('link.header.blocks_tab') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="default_biolink_theme_id"><?= l('admin_settings.links.default_biolink_theme_id') ?></label>
            <select id="default_biolink_theme_id" name="default_biolink_theme_id" class="custom-select">
                <option value="" <?= settings()->links->default_biolink_theme_id == '' ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach((new \Altum\Models\BiolinksThemes())->get_biolinks_themes() as $biolink_theme_id => $biolink_theme): ?>
                    <option value="<?= $biolink_theme_id ?>" <?= settings()->links->default_biolink_theme_id == $biolink_theme_id ? 'selected="selected"' : null ?>><?= $biolink_theme->name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.links.default_biolink_theme_id_help') ?></small>
            <small class="form-text text-muted"><?= l('admin_settings.links.default_biolink_theme_id_help2') ?></small>
        </div>

        <div class="form-group">
            <label for="default_biolink_template_id"><?= l('admin_settings.links.default_biolink_template_id') ?></label>
            <select id="default_biolink_template_id" name="default_biolink_template_id" class="custom-select">
                <option value="" <?= settings()->links->default_biolink_theme_id == '' ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach((new \Altum\Models\BiolinksTemplates())->get_biolinks_templates() as $biolink_template_id => $biolink_template): ?>
                    <option value="<?= $biolink_template_id ?>" <?= settings()->links->default_biolink_template_id == $biolink_template_id ? 'selected="selected"' : null ?>><?= $biolink_template->name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.links.default_biolink_template_id_help') ?></small>
        </div>

        <div class="form-group">
            <label for="biolinks_new_blocks_position"><?= l('admin_settings.links.biolinks_new_blocks_position') ?></label>
            <select id="biolinks_new_blocks_position" name="biolinks_new_blocks_position" class="custom-select">
                <option value="top" <?= settings()->links->biolinks_new_blocks_position == 'top' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.biolinks_new_blocks_position.top') ?></option>
                <option value="bottom" <?= settings()->links->biolinks_new_blocks_position == 'bottom' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.biolinks_new_blocks_position.bottom') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="branding"><?= l('admin_settings.links.branding') ?></label>
            <textarea id="branding" name="branding" class="form-control"><?= settings()->links->branding ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.links.branding_help') ?></small>
            <small class="form-text text-muted"><?= l('admin_settings.links.branding_help2') ?></small>
        </div>

        <div class="form-group mt-5">
            <?php $biolink_blocks = require APP_PATH . 'includes/biolink_blocks.php'; ?>

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3 class="h5"><?= l('admin_settings.links.available_biolink_blocks') . ' (' . count($biolink_blocks) . ')' ?></h3>

                <div>
                    <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_biolink_blocks[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                    <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_biolink_blocks[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
                </div>
            </div>

            <div class="row">
                <?php foreach($biolink_blocks as $key => $value): ?>
                    <div class="col-12 col-lg-6">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'available_biolink_blocks_' . $key ?>" name="available_biolink_blocks[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= settings()->links->available_biolink_blocks->{$key} ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label d-flex align-items-center" for="<?= 'available_biolink_blocks_' . $key ?>">
                                <?= l('link.biolink.blocks.' . mb_strtolower($key)) ?>
                            </label>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#links_container" aria-expanded="false" aria-controls="links_container">
        <i class="fas fa-fw fa-link fa-sm mr-1"></i> <?= l('admin_settings.links.links') ?>
    </button>

    <div class="collapse" id="links_container">
        <div class="form-group">
            <label for="random_url_length"><?= l('admin_settings.links.random_url_length') ?></label>
            <input id="random_url_length" type="number" min="4" step="1" name="random_url_length" class="form-control" value="<?= settings()->links->random_url_length ?? 7 ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.links.random_url_length_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="shortener_is_enabled" name="shortener_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->shortener_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="shortener_is_enabled"><?= l('admin_settings.links.shortener_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.shortener_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="files_is_enabled" name="files_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->files_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="files_is_enabled"><?= l('admin_settings.links.files_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.files_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="vcards_is_enabled" name="vcards_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->vcards_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="vcards_is_enabled"><?= l('admin_settings.links.vcards_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.vcards_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="events_is_enabled" name="events_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->events_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="events_is_enabled"><?= l('admin_settings.links.events_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.events_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="static_is_enabled" name="static_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->static_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="static_is_enabled"><?= l('admin_settings.links.static_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.static_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="blacklisted_domains"><?= l('admin_settings.links.blacklisted_domains') ?></label>
            <textarea id="blacklisted_domains" class="form-control" name="blacklisted_domains"><?= implode(',', settings()->links->blacklisted_domains) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.links.blacklisted_domains_help') ?></small>
        </div>

        <div class="form-group">
            <label for="blacklisted_keywords"><?= l('admin_settings.links.blacklisted_keywords') ?></label>
            <textarea id="blacklisted_keywords" class="form-control" name="blacklisted_keywords"><?= implode(',', settings()->links->blacklisted_keywords) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.links.blacklisted_keywords_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#splash_pages_container" aria-expanded="false" aria-controls="splash_pages_container">
        <i class="fas fa-fw fa-droplet fa-sm mr-1"></i> <?= l('admin_settings.links.splash_pages') ?>
    </button>

    <div class="collapse" id="splash_pages_container">
        <div class="form-group custom-control custom-switch">
            <input id="splash_page_is_enabled" name="splash_page_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->splash_page_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="splash_page_is_enabled"><?= l('admin_settings.links.splash_page_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.splash_page_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="splash_page_auto_redirect" name="splash_page_auto_redirect" type="checkbox" class="custom-control-input" <?= settings()->links->splash_page_auto_redirect ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="splash_page_auto_redirect"><?= l('admin_settings.links.splash_page_auto_redirect') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.splash_page_auto_redirect_help') ?></small>
        </div>

        <div class="form-group">
            <label for="splash_page_link_unlock_seconds"><?= l('admin_settings.links.splash_page_link_unlock_seconds') ?></label>
            <div class="input-group">
                <input id="splash_page_link_unlock_seconds" type="number" min="0" step="1" name="splash_page_link_unlock_seconds" class="form-control" value="<?= settings()->links->splash_page_link_unlock_seconds ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.seconds') ?></span>
                </div>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#pixels_container" aria-expanded="false" aria-controls="pixels_container">
        <i class="fas fa-fw fa-adjust fa-sm mr-1"></i> <?= l('admin_settings.links.pixels') ?>
    </button>

    <div class="collapse" id="pixels_container">
        <div class="form-group custom-control custom-switch">
            <input id="pixels_is_enabled" name="pixels_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->pixels_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="pixels_is_enabled"><?= l('admin_settings.links.pixels_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.pixels_is_enabled_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#directory_container" aria-expanded="false" aria-controls="directory_container">
        <i class="fas fa-fw fa-sitemap fa-sm mr-1"></i> <?= l('admin_settings.links.directory') ?>
    </button>

    <div class="collapse" id="directory_container">
        <div class="form-group custom-control custom-switch">
            <input id="directory_is_enabled" name="directory_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->directory_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="directory_is_enabled"><?= l('admin_settings.links.directory_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.directory_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="directory_access"><?= l('admin_settings.links.directory_access') ?></label>
            <select id="directory_access" name="directory_access" class="custom-select">
                <option value="everyone" <?= settings()->links->directory_access == 'everyone' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.directory_access_everyone') ?></option>
                <option value="users" <?= settings()->links->directory_access == 'users' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.directory_access_users') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="directory_display"><?= l('admin_settings.links.directory_display') ?></label>
            <select id="directory_display" name="directory_display" class="custom-select">
                <option value="all" <?= settings()->links->directory_display == 'all' ? 'selected="selected"' : null ?>><?= l('global.all') ?></option>
                <option value="verified" <?= settings()->links->directory_display == 'verified' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.directory_display_verified') ?></option>
            </select>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#domains_container" aria-expanded="false" aria-controls="domains_container">
        <i class="fas fa-fw fa-globe fa-sm mr-1"></i> <?= l('admin_settings.links.domains') ?>
    </button>

    <div class="collapse" id="domains_container">
        <div class="form-group custom-control custom-switch">
            <input id="domains_is_enabled" name="domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->domains_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="domains_is_enabled"><?= l('admin_settings.links.domains_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.domains_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="additional_domains_is_enabled" name="additional_domains_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->additional_domains_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="additional_domains_is_enabled"><?= l('admin_settings.links.additional_domains_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.additional_domains_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="main_domain_is_enabled" name="main_domain_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->main_domain_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="main_domain_is_enabled"><?= l('admin_settings.links.main_domain_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.main_domain_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="domains_custom_main_ip"><?= l('admin_settings.links.domains_custom_main_ip') ?></label>
            <input id="domains_custom_main_ip" name="domains_custom_main_ip" type="text" class="form-control" value="<?= settings()->links->domains_custom_main_ip ?>" placeholder="<?= $_SERVER['SERVER_ADDR'] ?>">
            <small class="form-text text-muted"><?= l('admin_settings.links.domains_custom_main_ip_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#file_size_limits_container" aria-expanded="false" aria-controls="file_size_limits_container">
        <i class="fas fa-fw fa-file fa-sm mr-1"></i> <?= l('admin_settings.links.file_size_limits') ?>
    </button>

    <div class="collapse" id="file_size_limits_container">
        <?php foreach(['avatar', 'background', 'favicon', 'seo_image', 'thumbnail_image', 'image', 'audio', 'video', 'file', 'product_file', 'static'] as $key): ?>
            <div class="form-group">
                <label for="<?= $key . '_size_limit' ?>"><?= l('admin_settings.links.' . $key . '_size_limit') ?></label>
                <div class="input-group">
                    <input id="<?= $key . '_size_limit' ?>" type="number" min="0" max="<?= get_max_upload() ?>" step="any" name="<?= $key . '_size_limit' ?>" class="form-control" value="<?= settings()->links->{$key . '_size_limit'} ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= l('global.mb') ?></span>
                    </div>
                </div>
                <small class="form-text text-muted"><?= l('global.accessibility.admin_file_size_limit_help') ?></small>
            </div>
        <?php endforeach ?>
    </div>

    <button class="btn btn-block btn-gray-200 mb-4" type="button" data-toggle="collapse" data-target="#others_container" aria-expanded="false" aria-controls="others_container">
        <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('admin_settings.links.others') ?>
    </button>

    <div class="collapse" id="others_container">
        <div class="form-group custom-control custom-switch">
            <input id="google_safe_browsing_is_enabled" name="google_safe_browsing_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->google_safe_browsing_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="google_safe_browsing_is_enabled"><?= l('admin_settings.links.google_safe_browsing_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.google_safe_browsing_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="google_safe_browsing_api_key"><?= l('admin_settings.links.google_safe_browsing_api_key') ?></label>
            <input id="google_safe_browsing_api_key" type="text" name="google_safe_browsing_api_key" class="form-control" value="<?= settings()->links->google_safe_browsing_api_key ?>" />
        </div>


        <div class="form-group custom-control custom-switch">
            <input id="google_static_maps_is_enabled" name="google_static_maps_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->links->google_static_maps_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="google_static_maps_is_enabled"><?= l('admin_settings.links.google_static_maps_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.links.google_static_maps_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="google_static_maps_api_key"><?= l('admin_settings.links.google_static_maps_api_key') ?></label>
            <input id="google_static_maps_api_key" type="text" name="google_static_maps_api_key" class="form-control" value="<?= settings()->links->google_static_maps_api_key ?>" />
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
