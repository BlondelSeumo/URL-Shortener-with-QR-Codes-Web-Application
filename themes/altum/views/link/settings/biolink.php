<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="row">
    <div class="col-12 col-xl-6">

        <?php
        $active_tab = settings()->links->biolinks_default_active_tab ?? 'settings';
        if(isset($_GET['tab']) && in_array($_GET['tab'], ['settings', 'blocks'])) {
            $active_tab = $_GET['tab'];
        }
        ?>

        <div class="d-flex flex-column flex-lg-row justify-content-lg-between  mb-4">
            <ul class="nav nav-pills mb-3 mb-lg-0" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab == 'settings' ? 'active' : null ?>" id="settings-tab" data-toggle="pill" href="#settings" role="tab" aria-controls="settings" aria-selected="true">
                        <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('link.header.settings_tab') ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $active_tab == 'blocks' ? 'active' : null ?>" id="blocks-tab" data-toggle="pill" href="#biolink_blocks" role="tab" aria-controls="links" aria-selected="false">
                        <i class="fas fa-fw fa-th-large fa-sm mr-1"></i> <?= l('link.header.blocks_tab') ?>
                    </a>
                </li>
            </ul>

            <div>
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('links.create_biolink_block') ?></button>
            </div>
        </div>

        <div class="tab-content">
            <div class="tab-pane fade <?= $active_tab == 'settings' ? 'show active' : null ?>" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                <div class="card">
                    <div class="card-body">

                        <form id="update_biolink" name="update_biolink" action="" method="post" role="form" enctype="multipart/form-data">
                            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                            <input type="hidden" name="request_type" value="update" />
                            <input type="hidden" name="type" value="biolink" />
                            <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

                            <div class="notification-container"></div>

                            <div class="form-group">
                                <label for="url"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('link.settings.url') ?></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <?php if(count($data->domains)): ?>
                                            <select name="domain_id" class="appearance-none custom-select form-control input-group-text">
                                                <?php if(settings()->links->main_domain_is_enabled || \Altum\Authentication::is_admin()): ?>
                                                    <option value="" <?= $data->link->domain ? 'selected="selected"' : null ?> data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                                                <?php endif ?>

                                                <?php foreach($data->domains as $row): ?>
                                                    <option value="<?= $row->domain_id ?>" <?= $data->link->domain && $row->domain_id == $data->link->domain->domain_id ? 'selected="selected"' : null ?>  data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
                                                <?php endforeach ?>
                                            </select>
                                        <?php else: ?>
                                            <span class="input-group-text"><?= remove_url_protocol_from_url(SITE_URL) ?></span>
                                        <?php endif ?>
                                    </div>
                                    <input
                                            id="url"
                                            type="text"
                                            class="form-control"
                                            name="url"
                                            placeholder="<?= l('global.url_slug_placeholder') ?>"
                                            value="<?= $data->link->url ?>"
                                            maxlength="256"
                                            onchange="update_this_value(this, get_slug)"
                                            onkeyup="update_this_value(this, get_slug)"
                                        <?= !$this->user->plan_settings->custom_url ? 'readonly="readonly"' : null ?>
                                        <?= $this->user->plan_settings->custom_url ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>
                                    />
                                </div>
                                <small class="form-text text-muted"><?= l('link.settings.url_help') ?></small>
                            </div>

                            <?php if(count($data->domains)): ?>
                                <div id="is_main_link_wrapper" class="form-group custom-control custom-switch">
                                    <input id="is_main_link" name="is_main_link" type="checkbox" class="custom-control-input" <?= $data->link->domain_id && $data->domains[$data->link->domain_id]->link_id == $data->link->link_id ? 'checked="checked"' : null ?>>
                                    <label class="custom-control-label" for="is_main_link"><?= l('link.settings.is_main_link') ?></label>
                                    <small class="form-text text-muted"><?= l('link.settings.is_main_link_help') ?></small>
                                </div>
                            <?php endif ?>

                            <?php if(settings()->links->biolinks_themes_is_enabled): ?>
                                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#theme_container" aria-expanded="false" aria-controls="theme_container">
                                    <i class="fas fa-fw fa-palette fa-sm mr-1"></i> <?= l('link.settings.theme_header') ?>
                                </button>

                                <div class="collapse" id="theme_container" data-parent="#settings">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('link.settings.biolink_theme_id') ?></label>

                                        <div id="biolinks_themes" class="row">
                                            <?php foreach($data->biolinks_themes as $key => $theme): ?>
                                            <?php $link_style = \Altum\Link::get_processed_link_style($theme->settings->biolink_block) ?>
                                                <label for="settings_biolink_theme_id_<?= $key ?>" class="m-0 col-6 p-3">
                                                    <input type="radio" name="biolink_theme_id" value="<?= $key ?>" id="settings_biolink_theme_id_<?= $key ?>" class="d-none" <?= $data->link->biolink_theme_id == $key ? 'checked="checked"' : null ?> />
                                                    <div class="link-biolink-theme card h-100" style="<?= \Altum\Link::get_processed_background_style($theme->settings->biolink); ?>">
                                                        <div class="card-body flex-column d-flex justify-content-center align-items-center text-truncate">

                                                            <div class="w-100" style="cursor: not-allowed;pointer-events: none;">
                                                                <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->biolink_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                                                    <small><?= $theme->name ?></small>
                                                                </button>

                                                                <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->biolink_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                                                    <small><?= $theme->name ?></small>
                                                                </button>

                                                                <button type="button" class="btn btn-block btn-sm btn-primary link-btn <?= 'link-btn-' . $theme->settings->biolink_block->border_radius ?>" style="<?= $link_style['style'] ?>">
                                                                    <small><?= $theme->name ?></small>
                                                                </button>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </label>
                                            <?php endforeach ?>
                                            <label for="settings_biolink_theme_id_null" class="m-0 col-6 p-3">
                                                <input type="radio" name="biolink_theme_id" value="" id="settings_biolink_theme_id_null" class="d-none" <?= !$data->link->biolink_theme_id ? 'checked="checked"' : null ?> />
                                                <div class="link-biolink-theme link-biolink-theme-custom card h-100">
                                                    <div class="card-body d-flex justify-content-center align-items-center">
                                                        <?= l('link.settings.biolink_theme_id_null') ?>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#customizations_container" aria-expanded="false" aria-controls="customizations_container">
                                <i class="fas fa-fw fa-paint-brush fa-sm mr-1"></i> <?= l('link.settings.customization_header') ?>
                            </button>

                            <div class="collapse" id="customizations_container" data-parent="#settings">
                                <div class="form-group">
                                    <label for="settings_background_type"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type') ?></label>
                                    <select id="settings_background_type" name="background_type" class="custom-select">
                                        <?php foreach($biolink_backgrounds as $key => $value): ?>
                                            <option value="<?= $key ?>" <?= $data->link->settings->background_type == $key ? 'selected="selected"' : null?>><?= l('link.settings.background_type_' . $key) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div id="background_type_preset" class="row" style="margin-right: -7px; margin-left: -7px;">
                                    <?php foreach($biolink_backgrounds['preset'] as $key => $value): ?>
                                        <label for="settings_background_type_preset_<?= $key ?>" class="m-0 col-3 p-2">
                                            <input type="radio" name="background" value="<?= $key ?>" id="settings_background_type_preset_<?= $key ?>" class="d-none" <?= $data->link->settings->background_type == 'preset' && $data->link->settings->background == $key ? 'checked="checked"' : null ?>/>
                                            <div class="link-background-type-preset" style="<?= $value ?>"></div>
                                        </label>
                                    <?php endforeach ?>
                                </div>

                                <div id="background_type_preset_abstract" class="row" style="margin-right: -7px; margin-left: -7px;">
                                    <?php foreach($biolink_backgrounds['preset_abstract'] as $key => $value): ?>
                                        <label for="settings_background_type_preset_abstract_<?= $key ?>" class="m-0 col-3 p-2">
                                            <input type="radio" name="background" value="<?= $key ?>" id="settings_background_type_preset_abstract_<?= $key ?>" class="d-none" <?= $data->link->settings->background_type == 'preset_abstract' && $data->link->settings->background == $key ? 'checked="checked"' : null ?>/>
                                            <div class="link-background-type-preset" style="<?= $value ?>"></div>
                                        </label>
                                    <?php endforeach ?>
                                </div>

                                <div id="background_type_gradient">
                                    <div class="form-group">
                                        <label for="settings_background_type_gradient_color_one"><?= l('link.settings.background_type_gradient_color_one') ?></label>
                                        <input type="hidden" id="settings_background_type_gradient_color_one" name="background_color_one" class="form-control" value="<?= $data->link->settings->background_color_one ?? '#000000' ?>" />
                                        <div id="settings_background_type_gradient_color_one_pickr"></div>
                                    </div>

                                    <div class="form-group">
                                        <label for="settings_background_type_gradient_color_two"><?= l('link.settings.background_type_gradient_color_two') ?></label>
                                        <input type="hidden" id="settings_background_type_gradient_color_two" name="background_color_two" class="form-control" value="<?= $data->link->settings->background_color_two ?? '#000000' ?>" />
                                        <div id="settings_background_type_gradient_color_two_pickr"></div>
                                    </div>
                                </div>

                                <div id="background_type_color">
                                    <div class="form-group">
                                        <label for="settings_background_type_color"><?= l('link.settings.background_type_color') ?></label>
                                        <input type="hidden" id="settings_background_type_color" name="background" class="form-control" value="<?= is_string($data->link->settings->background) ? $data->link->settings->background : '#000000' ?>" />
                                        <div id="settings_background_type_color_pickr"></div>
                                    </div>
                                </div>

                                <div id="background_type_image" data-image-container="background">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col">
                                                <input id="background_type_image_input" type="file" name="background" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('biolink_background') ?>" class="form-control-file altum-file-input" />
                                            </div>

                                            <?php if($data->link->settings->background_type == 'image' && is_string($data->link->settings->background) && !string_ends_with('.mp4', $data->link->settings->background)): ?>
                                                <div class="col-3 d-flex justify-content-center align-items-center">
                                                    <a href="<?= \Altum\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>" target="_blank" data-toggle="tooltip" title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                                        <img id="background_type_image_preview" src="<?= \Altum\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>" data-default-src="<?= \Altum\Uploads::get_full_url('backgrounds') . $data->link->settings->background ?>" class="altum-file-input-preview rounded" loading="lazy" />
                                                    </a>
                                                </div>
                                            <?php endif ?>
                                        </div>
                                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('biolink_background')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->background_size_limit) ?></small>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="background_attachment"><i class="fas fa-fw fa-print fa-sm text-muted mr-1"></i> <?= l('link.settings.background_attachment') ?></label>
                                    <div class="row btn-group-toggle" data-toggle="buttons">
                                        <?php foreach(['scroll', 'fixed'] as $background_attachment): ?>
                                            <div class="col-6">
                                                <label class="btn btn-light btn-block text-truncate <?= $data->link->settings->background_attachment == $background_attachment ? 'active"' : null?>">
                                                    <input type="radio" name="background_attachment" value="<?= $background_attachment ?>" class="custom-control-input" <?= ($data->link->settings->background_attachment ?? null) == $background_attachment ? 'checked="checked"' : null?> />
                                                    <?= l('link.settings.background_attachment.' . $background_attachment) ?>
                                                </label>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>

                                <div class="form-group" data-range-counter data-range-counter-suffix="px">
                                    <label for="background_blur"><i class="fas fa-fw fa-low-vision fa-sm text-muted mr-1"></i> <?= l('link.settings.background_blur') ?></label>
                                    <input id="background_blur" type="range"  min="0" max="30" class="form-control-range" name="background_blur" value="<?= $data->link->settings->background_blur ?? 0 ?>" />
                                </div>

                                <div class="form-group" data-range-counter data-range-counter-suffix="%">
                                    <label for="background_brightness"><i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> <?= l('link.settings.background_brightness') ?></label>
                                    <input id="background_brightness" type="range"  min="0" max="150" class="form-control-range" name="background_brightness" value="<?= $data->link->settings->background_brightness ?? 100 ?>" />
                                </div>

                                <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->favicon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->favicon_size_limit) ?>">
                                    <label for="favicon"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.favicon') ?></label>
                                    <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', ['uploads_file_key' => 'favicons', 'file_key' => 'favicon', 'already_existing_image' => $data->link->settings->favicon, 'image_container' => 'favicon']) ?>
                                    <?= \Altum\Alerts::output_field_error('favicon') ?>
                                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('favicons')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->favicon_size_limit) ?></small>
                                </div>

                                <div <?= $this->user->plan_settings->fonts ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->fonts ? null : 'container-disabled' ?>">
                                        <?php $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php'; ?>
                                        <?php foreach($biolink_fonts as $font_key => $font): ?>
                                            <?php if($font['font_css_url']): ?>
                                                <?php ob_start() ?>
                                                <link href="<?= $font['font_css_url'] ?>" rel="stylesheet">
                                                <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>
                                            <?php endif ?>
                                        <?php endforeach ?>

                                        <div class="form-group">
                                            <label for="settings_font"><i class="fas fa-fw fa-pen-nib fa-sm text-muted mr-1"></i> <?= l('link.settings.font') ?></label>
                                            <div class="row btn-group-toggle" data-toggle="buttons">
                                                <?php foreach($biolink_fonts as $font_key => $font): ?>
                                                    <div class="col-6 col-lg-4 h-100">
                                                        <label class="btn btn-light btn-block text-truncate <?= ($data->link->settings->font ?? 'default') == $font_key ? 'active"' : null?>" style="font-family: <?= $font['font-family'] ?> !important;">
                                                            <input type="radio" name="font" value="<?= $font_key ?>" class="custom-control-input" <?= ($data->link->settings->font ?? 'default') == $font_key ? 'checked="checked"' : null?> required="required" data-font-family="<?= $font['font-family'] ?>" data-font-css-url="<?= $font['font_css_url'] ?>" />
                                                            <?= $font['name'] ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="settings_font_size"><i class="fas fa-fw fa-font fa-sm text-muted mr-1"></i> <?= l('link.settings.font_size') ?></label>
                                            <div class="input-group">
                                                <input id="settings_font_size" type="number" min="14" max="22" name="font_size" class="form-control" value="<?= $data->link->settings->font_size ?>" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">px</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="settings_width"><i class="fas fa-fw fa-arrows-left-right fa-sm text-muted mr-1"></i> <?= l('link.settings.width') ?></label>
                                            <div class="row btn-group-toggle" data-toggle="buttons">
                                                <?php foreach(['6', '8', '10', '12'] as $key): ?>
                                                    <div class="col-12 col-lg-4 h-100">
                                                        <label class="btn btn-light btn-block text-truncate <?= ($data->link->settings->width ?? '8') == $key ? 'active"' : null?>">
                                                            <input type="radio" name="width" value="<?= $key ?>" class="custom-control-input" <?= ($data->link->settings->width ?? '8') == $key ? 'checked="checked"' : null?> required="required" />
                                                            <?= l('link.settings.width.' . $key) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="settings_block_spacing"><i class="fas fa-fw fa-arrows-up-down fa-sm text-muted mr-1"></i> <?= l('link.settings.block_spacing') ?></label>
                                            <div class="row btn-group-toggle" data-toggle="buttons">
                                                <?php foreach(['1', '2', '3',] as $key): ?>
                                                    <div class="col-12 col-lg-4 h-100">
                                                        <label class="btn btn-light btn-block text-truncate <?= ($data->link->settings->block_spacing ?? '2') == $key ? 'active"' : null?>">
                                                            <input type="radio" name="block_spacing" value="<?= $key ?>" class="custom-control-input" <?= ($data->link->settings->block_spacing ?? '2') == $key ? 'checked="checked"' : null?> required="required" />
                                                            <?= l('link.settings.block_spacing.' . $key) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="settings_hover_animation"><i class="fas fa-fw fa-arrow-pointer fa-sm text-muted mr-1"></i> <?= l('link.settings.hover_animation') ?></label>
                                            <div class="row btn-group-toggle" data-toggle="buttons">
                                                <div class="col-12 col-lg-4 h-100">
                                                    <label class="btn btn-light btn-block text-truncate <?= ($data->link->settings->hover_animation ?? 'smooth') == 'false' ? 'active"' : null?>">
                                                        <input type="radio" name="hover_animation" value="false" class="custom-control-input" <?= ($data->link->settings->hover_animation ?? 'smooth') == 'false' ? 'checked="checked"' : null?> required="required" />
                                                        <?= l('global.none') ?>
                                                    </label>
                                                </div>

                                                <?php foreach(['smooth', 'instant',] as $key): ?>
                                                    <div class="col-12 col-lg-4 h-100">
                                                        <label class="btn btn-light btn-block text-truncate <?= ($data->link->settings->hover_animation ?? 'smooth') == $key ? 'active"' : null?>">
                                                            <input type="radio" name="hover_animation" value="<?= $key ?>" class="custom-control-input" <?= ($data->link->settings->hover_animation ?? 'smooth') == $key ? 'checked="checked"' : null?> required="required" />
                                                            <?= l('link.settings.hover_animation.' . $key) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#verified_container" aria-expanded="false" aria-controls="verified_container">
                                <i class="fas fa-fw fa-check-circle fa-sm mr-1"></i> <?= l('link.settings.verified_header') ?>
                            </button>

                            <div class="collapse" id="verified_container" data-parent="#settings">
                                <?php if(!$data->link->is_verified): ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-fw fa-info-circle mr-1"></i> <?= l('link.settings.verified_help') ?>
                                    </div>
                                <?php endif ?>

                                <div <?= $data->link->is_verified ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $data->link->is_verified ? null : 'container-disabled' ?>">

                                        <div class="form-group">
                                            <label for="settings_verified_location"><i class="fas fa-fw fa-check-circle fa-sm text-muted mr-1"></i> <?= l('link.settings.verified_location') ?></label>
                                            <div class="row btn-group-toggle" data-toggle="buttons">
                                                <div class="col-12 col-lg-4 h-100">
                                                    <label class="btn btn-light btn-block text-truncate <?= $data->link->settings->verified_location == '' ? 'active"' : null?>">
                                                        <input type="radio" name="verified_location" value="" class="custom-control-input" <?= $data->link->settings->verified_location == 'false' ? 'checked="checked"' : null?> />
                                                        <?= l('global.none') ?>
                                                    </label>
                                                </div>

                                                <?php foreach(['top', 'bottom',] as $key): ?>
                                                    <div class="col-12 col-lg-4 h-100">
                                                        <label class="btn btn-light btn-block text-truncate <?= $data->link->settings->verified_location == $key ? 'active"' : null?>">
                                                            <input type="radio" name="verified_location" value="<?= $key ?>" class="custom-control-input" <?= $data->link->settings->verified_location == $key ? 'checked="checked"' : null?> />
                                                            <?= l('link.settings.verified_location.' . $key) ?>
                                                        </label>
                                                    </div>
                                                <?php endforeach ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#branding_container" aria-expanded="false" aria-controls="branding_container">
                                <i class="fas fa-fw fa-random fa-sm mr-1"></i> <?= l('link.settings.branding_header') ?>
                            </button>

                            <div class="collapse" id="branding_container" data-parent="#settings">
                                <div <?= $this->user->plan_settings->removable_branding ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->removable_branding ? null : 'container-disabled' ?>">
                                        <div class="form-group custom-control custom-switch">
                                            <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="display_branding"
                                                    name="display_branding"
                                                <?= !$this->user->plan_settings->removable_branding ? 'disabled="disabled"': null ?>
                                                <?= $data->link->settings->display_branding ? 'checked="checked"' : null ?>
                                            >
                                            <label class="custom-control-label" for="display_branding"><?= l('link.settings.display_branding') ?></label>
                                        </div>
                                    </div>
                                </div>

                                <div <?= $this->user->plan_settings->custom_branding ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->custom_branding ? null : 'container-disabled' ?>">
                                        <div class="form-group">
                                            <label for="branding_name"><i class="fas fa-fw fa-random fa-sm text-muted mr-1"></i> <?= l('link.settings.branding.name') ?></label>
                                            <input id="branding_name" type="text" class="form-control" name="branding_name" value="<?= $data->link->settings->branding->name ?? '' ?>" maxlength="128" />
                                            <small class="form-text text-muted"><?= l('link.settings.branding.name_help') ?></small>
                                        </div>

                                        <div id="branding_url_text_color" class="<?= $data->link->settings->branding->name ? null : 'container-disabled' ?>">
                                            <div class="form-group">
                                                <label for="branding_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.settings.branding.url') ?></label>
                                                <input id="branding_url" type="text" class="form-control" name="branding_url" value="<?= $data->link->settings->branding->url ?? '' ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                            </div>

                                            <div class="form-group">
                                                <label for="settings_text_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('link.settings.text_color') ?></label>
                                                <input type="hidden" id="settings_text_color" name="text_color" class="form-control" value="<?= $data->link->settings->text_color ?>" required="required" />
                                                <div id="settings_text_color_pickr"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if(settings()->links->pixels_is_enabled): ?>
                                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#pixels_container" aria-expanded="false" aria-controls="pixels_container">
                                    <i class="fas fa-fw fa-adjust fa-sm mr-1"></i> <?= l('link.settings.pixels_header') ?>
                                </button>

                                <div class="collapse" id="pixels_container" data-parent="#settings">
                                    <div class="form-group">
                                        <div class="d-flex flex-column flex-xl-row justify-content-between">
                                            <label><i class="fas fa-fw fa-sm fa-adjust text-muted mr-1"></i> <?= l('link.settings.pixels_ids') ?></label>
                                            <a href="<?= url('pixels') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('pixels.create') ?></a>
                                        </div>

                                        <div class="row">
                                            <?php $available_pixels = require APP_PATH . 'includes/pixels.php'; ?>
                                            <?php foreach($data->pixels as $pixel): ?>
                                                <div class="col-12 col-lg-6">
                                                    <div class="custom-control custom-checkbox my-2">
                                                        <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->link->pixels_ids) ? 'checked="checked"' : null ?>>
                                                        <label class="custom-control-label d-flex align-items-center" for="pixel_id_<?= $pixel->pixel_id ?>">
                                                            <span><?= $pixel->name ?></span>
                                                            <small class="badge badge-light ml-1" data-toggle="tooltip" title="<?= $available_pixels[$pixel->type]['name'] ?>">
                                                                <i class="<?= $available_pixels[$pixel->type]['icon'] ?> fa-fw fa-sm" style="color: <?= $available_pixels[$pixel->type]['color'] ?>"></i>
                                                            </small>
                                                        </label>
                                                    </div>
                                                </div>
                                            <?php endforeach ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif ?>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#utm_container" aria-expanded="false" aria-controls="utm_container">
                                <i class="fas fa-fw fa-keyboard fa-sm mr-1"></i> <?= l('link.settings.utm_header') ?>
                            </button>

                            <div class="collapse" id="utm_container" data-parent="#settings">
                                <div <?= $this->user->plan_settings->utm ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->utm ? null : 'container-disabled' ?>">
                                        <div class="form-group">
                                            <label for="utm_source"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_source') ?></label>
                                            <input id="utm_source" type="text" class="form-control" name="utm_source" value="<?= $data->link->settings->utm->source ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_source_placeholder') ?>" />
                                        </div>

                                        <div class="form-group">
                                            <label for="utm_medium"><i class="fas fa-fw fa-inbox fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_medium') ?></label>
                                            <input id="utm_medium" type="text" class="form-control" name="utm_medium" value="<?= $data->link->settings->utm->medium ?? '' ?>" maxlength="128" placeholder="<?= l('link.settings.utm_medium_placeholder') ?>" />
                                        </div>

                                        <div class="form-group">
                                            <label for="utm_campaign"><i class="fas fa-fw fa-bullhorn fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_campaign') ?></label>
                                            <input id="utm_campaign" type="text" class="form-control" name="utm_campaign" value="<?= l('link.settings.utm_campaign_placeholder_automatic') ?>" readonly="readonly" />
                                        </div>

                                        <div class="form-group">
                                            <label for="utm_preview"><i class="fas fa-fw fa-eye fa-sm text-muted mr-1"></i> <?= l('link.settings.utm_preview') ?></label>
                                            <input id="utm_preview" type="text" class="form-control-plaintext" name="utm_preview" readonly="readonly" />
                                            <small class="form-text text-muted"><?= l('link.settings.utm_preview_help') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#protection_container" aria-expanded="false" aria-controls="protection_container">
                                <i class="fas fa-fw fa-user-shield fa-sm mr-1"></i> <?= l('link.settings.protection_header') ?>
                            </button>

                            <div class="collapse" id="protection_container" data-parent="#settings">

                                <div <?= $this->user->plan_settings->password ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->password ? null : 'container-disabled' ?>">
                                        <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                                            <label for="qweasdzxc"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('global.password') ?></label>
                                            <input id="qweasdzxc" type="password" class="form-control" name="qweasdzxc" value="<?= $data->link->settings->password ?>" autocomplete="new-password" <?= !$this->user->plan_settings->password ? 'disabled="disabled"': null ?> />
                                            <small class="form-text text-muted"><?= l('link.settings.password_help') ?></small>
                                        </div>
                                    </div>
                                </div>

                                <div <?= $this->user->plan_settings->sensitive_content ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->sensitive_content ? null : 'container-disabled' ?>">
                                        <div class="form-group custom-control custom-switch">
                                            <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="sensitive_content"
                                                    name="sensitive_content"
                                                <?= !$this->user->plan_settings->sensitive_content ? 'disabled="disabled"': null ?>
                                                <?= $data->link->settings->sensitive_content ? 'checked="checked"' : null ?>
                                            >
                                            <label class="custom-control-label" for="sensitive_content"><?= l('link.settings.sensitive_content') ?></label>
                                            <small class="form-text text-muted"><?= l('link.settings.sensitive_content_help') ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#seo_container" aria-expanded="false" aria-controls="seo_container">
                                <i class="fas fa-fw fa-search-plus fa-sm mr-1"></i> <?= l('link.settings.seo_header') ?>
                            </button>

                            <div class="collapse" id="seo_container" data-parent="#settings">
                                <div <?= $this->user->plan_settings->seo ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->seo ? null : 'container-disabled' ?>">
                                        <div class="form-group custom-control custom-switch">
                                            <input id="seo_block" name="seo_block" type="checkbox" class="custom-control-input" <?= $data->link->settings->seo->block ? 'checked="checked"' : null ?>>
                                            <label class="custom-control-label" for="seo_block"><?= l('link.settings.seo_block') ?></label>
                                            <small class="form-text text-muted"><?= l('link.settings.seo_block_help') ?></small>
                                        </div>

                                        <div class="form-group">
                                            <label for="seo_title"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_title') ?></label>
                                            <input id="seo_title" type="text" class="form-control" name="seo_title" value="<?= $data->link->settings->seo->title ?? '' ?>" maxlength="70" />
                                            <small class="form-text text-muted"><?= l('link.settings.seo_title_help') ?></small>
                                        </div>

                                        <div class="form-group">
                                            <label for="seo_meta_description"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_meta_description') ?></label>
                                            <input id="seo_meta_description" type="text" class="form-control" name="seo_meta_description" value="<?= $data->link->settings->seo->meta_description ?? '' ?>" maxlength="160" />
                                            <small class="form-text text-muted"><?= l('link.settings.seo_meta_description_help') ?></small>
                                        </div>

                                        <div class="form-group">
                                            <label for="seo_meta_keywords"><i class="fas fa-fw fa-file-word fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_meta_keywords') ?></label>
                                            <input id="seo_meta_keywords" type="text" class="form-control" name="seo_meta_keywords" value="<?= $data->link->settings->seo->meta_keywords ?? '' ?>" maxlength="160" />
                                        </div>

                                        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->seo_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->seo_image_size_limit) ?>">
                                            <label for="seo_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.seo_image') ?></label>
                                            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', ['uploads_file_key' => 'biolink_seo_image', 'file_key' => 'seo_image', 'already_existing_image' => $data->link->settings->seo->image, 'image_container' => 'seo_image']) ?>
                                            <?= \Altum\Alerts::output_field_error('seo_image') ?>
                                            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('biolink_seo_image')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->seo_image_size_limit) ?></small>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                                <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('link.settings.advanced_header') ?>
                            </button>

                            <div class="collapse" id="advanced_container" data-parent="#settings">
                                <div class="form-group custom-control custom-switch">
                                    <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="share_is_enabled"
                                            name="share_is_enabled"
                                        <?= $data->link->settings->share_is_enabled ? 'checked="checked"' : null ?>
                                    >
                                    <label class="custom-control-label" for="share_is_enabled"><?= l('link.settings.share_is_enabled') ?></label>
                                </div>

                                <div class="form-group custom-control custom-switch">
                                    <input
                                            type="checkbox"
                                            class="custom-control-input"
                                            id="scroll_buttons_is_enabled"
                                            name="scroll_buttons_is_enabled"
                                        <?= $data->link->settings->scroll_buttons_is_enabled ? 'checked="checked"' : null ?>
                                    >
                                    <label class="custom-control-label" for="scroll_buttons_is_enabled"><?= l('link.settings.scroll_buttons_is_enabled') ?></label>
                                    <small class="form-text text-muted"><?= l('link.settings.scroll_buttons_is_enabled_help') ?></small>
                                </div>

                                <div class="form-group">
                                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                                        <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                                        <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                                    </div>
                                    <select id="project_id" name="project_id" class="custom-select">
                                        <option value=""><?= l('global.none') ?></option>
                                        <?php foreach($data->projects as $row): ?>
                                            <option value="<?= $row->project_id ?>" <?= $data->link->project_id == $row->project_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <?php if(settings()->links->splash_page_is_enabled): ?>
                                    <div <?= $this->user->plan_settings->splash_pages_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                        <div class="<?= $this->user->plan_settings->splash_pages_limit ? null : 'container-disabled' ?>">
                                            <div class="form-group">
                                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                                    <label for="splash_page_id"><i class="fas fa-fw fa-sm fa-droplet text-muted mr-1"></i> <?= l('splash_pages.splash_page_id') ?></label>
                                                    <a href="<?= url('splash-pages') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('splash_pages.create') ?></a>
                                                </div>
                                                <select id="splash_page_id" name="splash_page_id" class="custom-select">
                                                    <option value=""><?= l('global.none') ?></option>
                                                    <?php foreach($data->splash_pages as $row): ?>
                                                        <option value="<?= $row->splash_page_id ?>" <?= $data->link->splash_page_id == $row->splash_page_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                                    <?php endforeach ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>

                                <div <?= $this->user->plan_settings->leap_link ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="<?= $this->user->plan_settings->leap_link ? null : 'container-disabled' ?>">
                                        <div class="form-group">
                                            <label for="leap_link"><i class="fas fa-fw fa-forward fa-sm text-muted mr-1"></i> <?= l('link.settings.leap_link') ?></label>
                                            <input id="leap_link" type="url" class="form-control" name="leap_link" value="<?= $data->link->settings->leap_link ?>" maxlength="2048" <?= !$this->user->plan_settings->leap_link ? 'disabled="disabled"': null ?> placeholder="<?= l('global.url_placeholder') ?>" autocomplete="off" />
                                            <small class="form-text text-muted"><?= l('link.settings.leap_link_help') ?></small>
                                        </div>
                                    </div>
                                </div>

                                <div <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="form-group <?= $this->user->plan_settings->custom_css_is_enabled ? null : 'container-disabled' ?>" data-character-counter="textarea">
                                        <label for="custom_css" class="d-flex justify-content-between align-items-center">
                                            <span><i class="fab fa-fw fa-sm fa-css3 text-muted mr-1"></i> <?= l('global.custom_css') ?></span>
                                            <small class="text-muted" data-character-counter-wrapper></small>
                                        </label>
                                        <textarea id="custom_css" class="form-control" name="custom_css" maxlength="8192" placeholder="<?= l('global.custom_css_placeholder') ?>"><?= $data->link->settings->custom_css ?></textarea>
                                        <small class="form-text text-muted"><?= l('global.custom_css_help') ?></small>
                                    </div>
                                </div>

                                <div <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                                    <div class="form-group <?= $this->user->plan_settings->custom_js_is_enabled ? null : 'container-disabled' ?>" data-character-counter="textarea">
                                        <label for="custom_js" class="d-flex justify-content-between align-items-center">
                                            <span><i class="fab fa-fw fa-sm fa-js-square text-muted mr-1"></i> <?= l('global.custom_js') ?></span>
                                            <small class="text-muted" data-character-counter-wrapper></small>
                                        </label>
                                        <textarea id="custom_js" class="form-control" name="custom_js" maxlength="8192" placeholder="<?= l('global.custom_js_placeholder') ?>"><?= $data->link->settings->custom_js ?></textarea>
                                        <small class="form-text text-muted"><?= l('global.custom_js_help') ?></small>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="tab-pane fade <?= $active_tab == 'blocks' ? 'show active' : null ?>" id="biolink_blocks" role="tabpanel" aria-labelledby="blocks-tab">

                <?php if($data->link_links_result->num_rows): ?>
                    <?php while($row = $data->link_links_result->fetch_object()): ?>
                        <?php if(!isset($data->biolink_blocks[$row->type])) continue; ?>

                        <?php $row->settings = (object) json_decode($row->settings) ?>
                        <?php
                        $row->settings->border_shadow_offset_x = $row->settings->border_shadow_offset_x ?? '0';
                        $row->settings->border_shadow_offset_y = $row->settings->border_shadow_offset_y ?? '0';
                        $row->settings->border_shadow_blur = $row->settings->border_shadow_blur ?? '20';
                        $row->settings->border_shadow_spread = $row->settings->border_shadow_spread ?? '0';
                        $row->settings->border_shadow_color = $row->settings->border_shadow_color ?? '#00000010';
                        ?>

                        <div class="biolink_block card <?= $row->is_enabled ? null : 'custom-row-inactive' ?> mb-4" data-biolink-block-id="<?= $row->biolink_block_id ?>">
                            <div class="card-body">
                                <div class="d-flex align-items-center">
                                    <div class="custom-row-side-controller">
                                        <span data-toggle="tooltip" title="<?= l('link.biolink_blocks.link_sort') ?>">
                                            <i class="fas fa-fw fa-bars text-muted custom-row-side-controller-grab drag"></i>
                                        </span>
                                    </div>

                                    <div class="col-1 mr-2 p-0 d-none d-lg-block">
                                        <span class="fa-stack fa-1x" data-toggle="tooltip" title="<?= l('link.biolink.blocks.' . $row->type) ?>">
                                            <i class="fas fa-circle fa-stack-2x" style="color: <?= $data->biolink_blocks[$row->type]['color'] ?>"></i>
                                            <i class="<?= $data->biolink_blocks[$row->type]['icon'] ?> fa-stack-1x fa-inverse"></i>
                                        </span>
                                    </div>

                                    <div class="col-6 col-md-4">
                                        <div class="d-flex flex-column">
                                            <a href="#"
                                               data-toggle="collapse"
                                               data-target="#biolink_block_expanded_content_<?= $row->biolink_block_id ?>"
                                               aria-expanded="false"
                                               aria-controls="biolink_block_expanded_content_<?= $row->biolink_block_id ?>"
                                            >
                                                <strong><?= $data->biolink_blocks[$row->type]['display_dynamic_name'] ? ($row->settings->{$data->biolink_blocks[$row->type]['display_dynamic_name']} ? string_truncate($row->settings->{$data->biolink_blocks[$row->type]['display_dynamic_name']}, 32) : l('link.biolink.blocks.' . $row->type)) : l('link.biolink.blocks.' . $row->type) ?></strong>
                                            </a>

                                            <span class="d-flex align-items-center">
                                            <?php if(!empty($row->location_url)): ?>
                                                <?php if($parsed_host = parse_url($row->location_url, PHP_URL_HOST)): ?>
                                                    <img src="<?= get_favicon_url_from_domain($parsed_host) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />
                                                <?php endif ?>

                                                <span class="d-inline-block text-truncate">
                                                    <a href="<?= $row->location_url ?>" class="text-muted" title="<?= $row->location_url ?>" target="_blank" rel="noreferrer noopener"><?= $row->location_url ?></a>
                                                </span>
                                            <?php elseif(!empty($row->url)): ?>
                                                <img src="<?= get_favicon_url_from_domain(parse_url(url($row->url))['host']) ?>" class="img-fluid icon-favicon mr-1" loading="lazy" />

                                                <span class="d-inline-block text-truncate">
                                                    <a href="<?= url($row->url) ?>" class="text-muted" title="<?= url($row->url) ?>" target="_blank" rel="noreferrer noopener"><?= url($row->url) ?></a>
                                                </span>
                                            <?php endif ?>
                                            </span>

                                        </div>
                                    </div>

                                    <div class="d-none d-md-flex col-md-3 flex-wrap">
                                        <?php if($data->biolink_blocks[$row->type]['has_statistics']): ?>
                                            <a href="<?= url('biolink-block/' . $row->biolink_block_id . '/statistics') ?>">
                                                <span data-toggle="tooltip" title="<?= l('links.clicks') ?>" class="badge badge-light"><i class="fas fa-fw fa-sm fa-chart-bar mr-1"></i> <?= nr($row->clicks) ?></span>
                                            </a>
                                        <?php endif ?>
                                        <?php if($data->biolink_blocks[$row->type]['type'] == 'payment'): ?>
                                            <a href="<?= url('guests-payments?biolink_block_id=' . $row->biolink_block_id) ?>" class="btn btn-link text-secondary" data-toggle="tooltip" title="<?= l('guests_payments.link') ?>">
                                                <i class="fas fa-fw fa-sm fa-coins"></i>
                                            </a>
                                            <a href="<?= url('guests-payments-statistics?biolink_block_id=' . $row->biolink_block_id) ?>" class="btn btn-link text-secondary" data-toggle="tooltip" title="<?= l('guests_payments_statistics.link') ?>">
                                                <i class="fas fa-fw fa-sm fa-chart-pie"></i>
                                            </a>
                                        <?php endif ?>
                                    </div>

                                    <div class="col-5 col-md d-flex align-items-center justify-content-between">
                                        <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= l('link.biolink_blocks.is_enabled_tooltip') ?>">
                                            <input
                                                    type="checkbox"
                                                    class="custom-control-input"
                                                    id="biolink_block_is_enabled_<?= $row->biolink_block_id ?>"
                                                    data-row-id="<?= $row->biolink_block_id ?>"
                                                <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                            >
                                            <label class="custom-control-label" for="biolink_block_is_enabled_<?= $row->biolink_block_id ?>"></label>
                                        </div>

                                        <div class="dropdown">
                                            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                                <i class="fas fa-fw fa-ellipsis-v"></i>
                                            </button>

                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a href="#"
                                                   class="dropdown-item"
                                                   data-toggle="collapse"
                                                   data-target="#biolink_block_expanded_content_<?= $row->biolink_block_id ?>"
                                                   aria-expanded="false"
                                                   aria-controls="biolink_block_expanded_content_<?= $row->biolink_block_id ?>"
                                                >
                                                    <i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?>
                                                </a>

                                                <?php if($data->biolink_blocks[$row->type]['has_statistics']): ?>
                                                    <a href="<?= url('biolink-block/' . $row->biolink_block_id . '/statistics') ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('link.statistics.link') ?></a>
                                                <?php endif ?>

                                                <?php if($data->biolink_blocks[$row->type]['type'] == 'payment'): ?>
                                                    <a href="<?= url('guests-payments?biolink_block_id=' . $row->biolink_block_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('guests_payments.link') ?></a>
                                                    <a href="<?= url('guests-payments-statistics?biolink_block_id=' . $row->biolink_block_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-chart-pie mr-2"></i> <?= l('guests_payments_statistics.link') ?></a>
                                                <?php endif ?>

                                                <?php if(in_array($row->type, ['email_collector', 'phone_collector', 'contact_collector'])): ?>
                                                    <a href="<?= url('data?biolink_block_id=' . $row->biolink_block_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-database mr-2"></i> <?= l('data.link') ?></a>
                                                <?php endif ?>

                                                <a href="<?= $data->link->full_url . '#biolink_block_id_' . $row->biolink_block_id ?>" target="_blank" class="dropdown-item" data-biolink-block-id="<?= $row->biolink_block_id ?>"><i class="fas fa-fw fa-sm fa-external-link-alt mr-2"></i> <?= l('global.view') ?></a>

                                                <?php if($row->type != 'image_slider'): ?>
                                                    <a href="#" data-toggle="modal" data-target="#biolink_block_duplicate_modal" class="dropdown-item" data-biolink-block-id="<?= $row->biolink_block_id ?>"><i class="fas fa-fw fa-sm fa-clone mr-2"></i> <?= l('global.duplicate') ?></a>
                                                <?php endif ?>

                                                <a href="#" data-toggle="modal" data-target="#biolink_block_delete_modal" class="dropdown-item" data-biolink-block-id="<?= $row->biolink_block_id ?>"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="collapse mt-3" id="biolink_block_expanded_content_<?= $row->biolink_block_id ?>" data-link-type="<?= $row->type ?>" data-parent="#biolink_blocks">
                                    <?php require THEME_PATH . 'views/link/settings/biolink_blocks/' . $row->type . '/' . $row->type . '_update_form.php' ?>
                                </div>
                            </div>
                        </div>

                    <?php endwhile ?>
                <?php else: ?>

                    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                        'filters_get' => $data->filters->get ?? [],
                        'name' => 'link.biolink_blocks',
                        'has_secondary_text' => true,
                    ]); ?>

                <?php endif ?>

            </div>
        </div>
    </div>

    <div class="col-12 col-xl-6 mt-5 mt-xl-0 d-flex justify-content-center justify-content-xl-end">
        <div class="biolink-preview-container">
            <div class="biolink-preview sticky">
                <div class="biolink-preview-iframe-container">
                    <div id="biolink_preview_iframe_loading" class="biolink-preview-iframe-loading d-none"><div class="spinner-border bg-primary" role="status"></div></div>
                    <iframe id="biolink_preview_iframe" class="biolink-preview-iframe" src="<?= SITE_URL . 'l/link?link_id=' . $data->link->link_id . '&preview=' . md5($data->link->user_id) ?>"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="template_faq_item">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_faq_modal.title') ?></label>
            <input id="" type="text" name="item_title[]" class="form-control" value="" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('create_biolink_faq_modal.content') ?></label>
            <textarea id="" name="item_content[]" class="form-control" required="required"></textarea>
        </div>

        <button type="button" data-remove="item" class="btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<template id="template_timeline_item">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_timeline_modal.title') ?></label>
            <input id="" type="text" name="item_title[]" class="form-control" value="" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('create_biolink_timeline_modal.description') ?></label>
            <textarea id="" name="item_description[]" class="form-control" required="required"></textarea>
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-calendar fa-sm text-muted mr-1"></i> <?= l('create_biolink_timeline_modal.date') ?></label>
            <input id="" type="text" name="item_date[]" class="form-control" value="" required="required" />
        </div>

        <button type="button" data-remove="item" class="btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php if(\Altum\Plugin::is_active('ultimate-blocks')): ?>
    <template id="template_image_slider_item">
        <div class="mb-4">
            <div class="form-group">
                <label for=""><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                <div class="d-none">
                    <div class="row">
                        <div class="my-2 col-6 col-xl-4">
                            <img src="" class="img-fluid rounded d-none" loading="lazy" />
                        </div>
                    </div>
                </div>
                <input id="" type="file" name="item_image_" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['image_slider']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" required="required" />
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['image_slider']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
            </div>

            <div class="form-group">
                <label for=""><i class="fas fa-fw fa-comment-dots fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.image_alt') ?></label>
                <input id="" type="text" class="form-control" name="item_image_alt[]" maxlength="100" />
                <small class="form-text text-muted"><?= l('create_biolink_link_modal.input.image_alt_help') ?></small>
            </div>

            <div class="form-group">
                <label for=""><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.location_url') ?></label>
                <input id="" type="text" class="form-control" name="item_location_url[]" maxlength="2048" />
            </div>

            <button type="button" data-remove="item" class="btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
        </div>
    </template>
<?php endif ?>

<template id="template_vcard_social">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_label') ?></label>
            <input id="" type="text" name="vcard_social_label[]" class="form-control" maxlength="<?= $data->biolink_blocks['vcard']['fields']['social_label']['max_length'] ?>" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_value') ?></label>
            <input id="" type="url" name="vcard_social_value[]" class="form-control" maxlength="<?= $data->biolink_blocks['vcard']['fields']['social_value']['max_length'] ?>" required="required" />
        </div>

        <button type="button" data-remove="vcard_social" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<template id="template_vcard_phone_numbers">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_label') ?></label>
            <input id="" type="text" name="vcard_phone_number_label[]" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['phone_number_label']['max_length'] ?>" />
            <small class="form-text text-muted"><?= l('create_biolink_vcard_modal.vcard_phone_number_label_help') ?></small>
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_value') ?></label>
            <input id="" type="text" name="vcard_phone_number_value[]" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['phone_number_value']['max_length'] ?>" required="required" />
        </div>

        <button type="button" data-remove="vcard_phone_numbers" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>
<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/pickr.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/fontawesome-iconpicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    /* Settings Tab */
    /* Initiate the color picker */
    let pickr_options = {
        comparison: false,

        components: {
            preview: true,
            opacity: true,
            hue: true,
            comparison: false,
            interaction: {
                hex: true,
                rgba: false,
                hsla: false,
                hsva: false,
                cmyk: false,
                input: true,
                clear: false,
                save: false
            }
        }
    };

    /* UTM */
    let process_utm = () => {

        let utm_source = document.querySelector('input[name="utm_source"]').value;
        let utm_medium = document.querySelector('input[name="utm_medium"]').value;
        let utm_campaign = 'UTM_CAMPAIGN';
        let utm_preview = <?= json_encode(l('global.none')) ?>;

        if(utm_source || utm_medium) {
            let link = new URL(<?= json_encode(SITE_URL) ?>);

            if(utm_source) link.searchParams.set('utm_source', utm_source.trim());
            if(utm_medium) link.searchParams.set('utm_medium', utm_medium.trim());
            if(utm_campaign) link.searchParams.set('utm_campaign', utm_campaign.trim());

            utm_preview = '?' + link.searchParams.toString();
        }

        document.querySelector('input[name="utm_preview"]').value = utm_preview;
    }

    document.querySelectorAll('input[name="utm_source"], input[name="utm_medium"], input[name="utm_campaign"]').forEach(element => {
        ['change', 'paste', 'keyup'].forEach(event_type => {
            element.addEventListener(event_type, process_utm);
        });
    })

    process_utm();

    /* Switching themes & previewing */
    let biolink_theme_preview = () => {
        let biolink_theme_id = document.querySelector('input[name="biolink_theme_id"]:checked').value;

        /* Add loader */
        document.querySelector('#biolink_preview_iframe_loading').classList.remove('d-none');

        /* Refresh iframe */
        let biolink_preview_iframe = document.querySelector('#biolink_preview_iframe');

        setTimeout(() => {
            let biolink_preview_iframe_url = new URL(biolink_preview_iframe.getAttribute('src'));
            biolink_preview_iframe_url.searchParams.set('biolink_theme_id', biolink_theme_id);
            biolink_preview_iframe_url.search = biolink_preview_iframe_url.searchParams.toString()
            biolink_preview_iframe.setAttribute('src', biolink_preview_iframe_url.toString());
        }, 750)

        biolink_preview_iframe.onload = () => {
            document.querySelector('#biolink_preview_iframe').dispatchEvent(new Event('refreshed'));
            document.querySelector('#biolink_preview_iframe_loading').classList.add('d-none');
        }
    }

    document.querySelectorAll('input[name="biolink_theme_id"]').forEach(element => {
        element.addEventListener('change', biolink_theme_preview);
    })

    /* Function to switch theme to custom */
    let set_biolink_theme_id_null = () => {
        if(document.querySelector('input[name="biolink_theme_id"][value=""]')) {
            if (!document.querySelector('input[name="biolink_theme_id"][value=""]').checked) {
                document.querySelector('input[name="biolink_theme_id"][value=""]').checked = true;
                biolink_theme_preview();
            }
        }
    }

    /* Display verified */
    let display_verified = () => {
        let verified_location = document.querySelector('input[name="verified_location"]:checked').value;
        let biolink_preview_iframe = $('#biolink_preview_iframe');

        switch(verified_location) {
            case 'top':
                biolink_preview_iframe.contents().find(`#link-verified-wrapper-top`).show();
                biolink_preview_iframe.contents().find(`#link-verified-wrapper-bottom`).hide();
                break;

            case 'bottom':
                biolink_preview_iframe.contents().find(`#link-verified-wrapper-top`).hide();
                biolink_preview_iframe.contents().find(`#link-verified-wrapper-bottom`).show();
                break;

            case '':
                biolink_preview_iframe.contents().find(`#link-verified-wrapper-top`).hide();
                biolink_preview_iframe.contents().find(`#link-verified-wrapper-bottom`).hide();
                break;
        }
    }

    document.querySelector('input[name="verified_location"]') && document.querySelectorAll('input[name="verified_location"]').forEach(element => element.addEventListener('change', display_verified));

    /* Text Color Handler */
    let settings_text_color_pickr = Pickr.create({
        el: '#settings_text_color_pickr',
        default: $('#settings_text_color').val(),
        ...pickr_options
    });

    settings_text_color_pickr.on('change', hsva => {
        set_biolink_theme_id_null();

        $('#settings_text_color').val(hsva.toHEXA().toString());
        $('#biolink_preview_iframe').contents().find('#branding').css('color', hsva.toHEXA().toString());
        if($('#biolink_preview_iframe').contents().find('#branding a')) {
            $('#biolink_preview_iframe').contents().find('#branding a').css('color', hsva.toHEXA().toString());
        }
    });

    /* Background blur */
    document.querySelector('#background_blur').addEventListener('change', event => {
        let blur = document.querySelector('#background_blur').value;
        let brightness = document.querySelector('#background_brightness').value;
        $('#biolink_preview_iframe').contents().find('.link-body-backdrop').css('backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
        $('#biolink_preview_iframe').contents().find('.link-body-backdrop').css('-webkit-backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
    });

    /* Background brightness */
    document.querySelector('#background_brightness').addEventListener('change', event => {
        let blur = document.querySelector('#background_blur').value;
        let brightness = document.querySelector('#background_brightness').value;
        $('#biolink_preview_iframe').contents().find('.link-body-backdrop').css('backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
        $('#biolink_preview_iframe').contents().find('.link-body-backdrop').css('-webkit-backdrop-filter', `blur(${blur}px) brightness(${brightness}%)`);
    });

    /* Fonts size */
    document.querySelector('#settings_font_size').addEventListener('change', event => {
        let font_size = event.currentTarget.value;
        $('#biolink_preview_iframe').contents().find('body').css('font-size', `${font_size}px`);
        set_biolink_theme_id_null();
    });

    /* Font family */
    document.querySelectorAll('input[name="font"]').forEach(element => element.addEventListener('change', event => {
        let font_key = event.currentTarget.value;
        let font_family = event.currentTarget.getAttribute('data-font-family');
        let font_css_url = event.currentTarget.getAttribute('data-font-css-url');
        if(!font_family) font_family = 'inherit';

        if(font_css_url) {
            let font_css_link = document.querySelector('#biolink_preview_iframe').contentDocument.createElement('link');

            if(!document.querySelector('#biolink_preview_iframe').contentDocument.head.querySelector(`link[id="${font_key}"]`)) {
                font_css_link.rel = 'stylesheet';
                font_css_link.href = font_css_url;
                font_css_link.id = font_key;
                document.querySelector('#biolink_preview_iframe').contentDocument.head.appendChild(font_css_link);
            }
        }

        document.querySelector('#biolink_preview_iframe').contentDocument.querySelector('body').style.setProperty('font-family', `${font_family}`, 'important');

        set_biolink_theme_id_null();
    }));

    /* Background Type Handler */
    let background_type_handler = () => {
        let type = $('#settings_background_type').find(':selected').val();

        /* Show only the active background type */
        $(`div[id="background_type_${type}"]`).show();
        $(`div[id="background_type_${type}"]`).find('[name^="background"]').removeAttr('disabled');

        /* Disable the other possible types so they dont get submitted */
        let background_type_containers = $(`div[id^="background_type_"]:not(div[id$="_${type}"])`);

        background_type_containers.hide();
        background_type_containers.find('[name^="background"]').attr('disabled', 'disabled');
    };

    background_type_handler();

    $('#settings_background_type').on('change', background_type_handler);

    /* Preset background preview */
    $('#background_type_preset input[name="background"]').on('change', event => {
        set_biolink_theme_id_null();

        let preset_style = $(event.currentTarget).parent().find('.link-background-type-preset')[0].getAttribute('style');
        $('#biolink_preview_iframe').contents().find('body').attr('style', preset_style);
        $('#biolink_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Preset background preview */
    $('#background_type_preset_abstract input[name="background"]').on('change', event => {
        set_biolink_theme_id_null();

        let preset_abstract_style = $(event.currentTarget).parent().find('.link-background-type-preset')[0].getAttribute('style');
        $('#biolink_preview_iframe').contents().find('body').attr('style', preset_abstract_style);
        $('#biolink_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Gradient Background */
    let settings_background_type_gradient_color_one_pickr = Pickr.create({
        el: '#settings_background_type_gradient_color_one_pickr',
        default: $('#settings_background_type_gradient_color_one').val(),
        ...pickr_options
    });

    settings_background_type_gradient_color_one_pickr.on('change', hsva => {
        set_biolink_theme_id_null();

        $('#settings_background_type_gradient_color_one').val(hsva.toHEXA().toString());

        let color_one = $('#settings_background_type_gradient_color_one').val();
        let color_two = $('#settings_background_type_gradient_color_two').val();

        $('#biolink_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background-image: linear-gradient(135deg, ${color_one} 10%, ${color_two} 100%);`);
        $('#biolink_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    let settings_background_type_gradient_color_two_pickr = Pickr.create({
        el: '#settings_background_type_gradient_color_two_pickr',
        default: $('#settings_background_type_gradient_color_two').val(),
        ...pickr_options
    });

    settings_background_type_gradient_color_two_pickr.on('change', hsva => {
        set_biolink_theme_id_null();

        $('#settings_background_type_gradient_color_two').val(hsva.toHEXA().toString());

        let color_one = $('#settings_background_type_gradient_color_one').val();
        let color_two = $('#settings_background_type_gradient_color_two').val();

        $('#biolink_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background-image: linear-gradient(135deg, ${color_one} 10%, ${color_two} 100%);`);
        $('#biolink_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Color Background */
    let settings_background_type_color_pickr = Pickr.create({
        el: '#settings_background_type_color_pickr',
        default: $('#settings_background_type_color').val(),
        ...pickr_options
    });

    settings_background_type_color_pickr.on('change', hsva => {
        set_biolink_theme_id_null();

        $('#settings_background_type_color').val(hsva.toHEXA().toString());

        $('#biolink_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background: ${hsva.toHEXA().toString()};`);
        $('#biolink_preview_iframe').contents().find('.link-video-background')[0].classList.add('d-none');
    });

    /* Image Background */
    function generate_background_preview(input) {
        if(input.files && input.files[0]) {
            let reader = new FileReader();

            reader.onload = event => {
                $('#background_type_image_preview').attr('src', event.target.result);
                $('#biolink_preview_iframe').contents().find('body').attr('class', 'link-body').attr('style', `background: url(${event.target.result});`);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#background_type_image_input').on('change', event => {
        set_biolink_theme_id_null();

        generate_background_preview(event.currentTarget);
    });

    /* Display branding switcher */
    $('#display_branding').on('change', event => {
        if($(event.currentTarget).is(':checked')) {
            $('#biolink_preview_iframe').contents().find('#branding').show();
        } else {
            $('#biolink_preview_iframe').contents().find('#branding').hide();
        }
    });

    /* Branding change */
    $('#branding_name').on('change paste keyup', event => {
        let branding_name = event.currentTarget.value.trim();

        if(branding_name != '') {
            $('#biolink_preview_iframe').contents().find('#branding').text(branding_name);
            document.querySelector('#branding_url_text_color').classList.remove('container-disabled');
        } else {
            document.querySelector('#branding_url_text_color').classList.add('container-disabled');
        }
    });

    /* Form handling update */
    $('form[name="update_biolink"],form[name="update_biolink_"]').on('submit', event => {
        let form = $(event.currentTarget)[0];
        let data = new FormData(form);
        let notification_container = event.currentTarget.querySelector('.notification-container');
        notification_container.innerHTML = '';

        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: event.currentTarget.getAttribute('name') == 'update_biolink_' ? `${url}biolink-block-ajax` : `${url}link-ajax`,
            data: data,
            dataType: 'json',
            success: (data) => {
                display_notifications(data.message, data.status, notification_container);

                /* Auto scroll to notification */
                notification_container.scrollIntoView({ behavior: 'smooth', block: 'center' });

                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'))

                /* Update image previews for some link types */
                if(event.currentTarget.getAttribute('name') == 'update_biolink_') {
                    if(data.details.images) {
                        for(const [key, value] of Object.entries(data.details.images)) {
                            event.currentTarget.querySelector(`input[name="${key}"]`).value = null;

                            if(event.currentTarget.querySelector(`[name="${key}_remove"]`) && event.currentTarget.querySelector(`[name="${key}_remove"]`).checked) {
                                event.currentTarget.querySelector(`[name="${key}_remove"]`).click();
                            }

                            if(value) {
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('src', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('data-src', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).classList.remove('d-none');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).setAttribute('href', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).classList.remove('d-none');
                                event.currentTarget.querySelectorAll(`[data-image-container="${key}"]`).forEach(element => element.classList.remove('d-none'));
                                event.currentTarget.querySelector(`[id*="_remove_selected_file_wrapper"]`).classList.add('d-none');
                            } else {
                                if(event.currentTarget.querySelector(`[data-image-container="${key}"] img`)) {
                                    event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('src', '');
                                    event.currentTarget.querySelector(`[data-image-container="${key}"] img`).classList.add('d-none');
                                    event.currentTarget.querySelector(`[data-image-container="${key}"] img`).removeAttribute('data-src');
                                }
                                event.currentTarget.querySelectorAll(`[data-image-container="${key}"]`).forEach(element => element.classList.add('d-none'));
                            }
                        }
                    }
                }

                if(event.currentTarget.getAttribute('name') == 'update_biolink') {
                    if(data.status == 'success') {
                        update_main_url(data.details.url);
                    }

                    if(data.details?.images) {
                        for(const [key, value] of Object.entries(data.details.images)) {
                            event.currentTarget.querySelector(`input[name="${key}"]`).value = null;

                            if(event.currentTarget.querySelector(`[name="${key}_remove"]`) && event.currentTarget.querySelector(`[name="${key}_remove"]`).checked) {
                                event.currentTarget.querySelector(`[name="${key}_remove"]`).click();
                            }

                            if(value) {
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('src', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).classList.remove('d-none');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).setAttribute('href', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).classList.remove('d-none');
                                event.currentTarget.querySelectorAll(`[data-image-container="${key}"]`).forEach(element => element.classList.remove('d-none'));
                            } else {
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('src', '');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).classList.add('d-none');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).setAttribute('href', '');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).classList.add('d-none');
                                event.currentTarget.querySelectorAll(`[data-image-container="${key}"]`).forEach(element => element.classList.add('d-none'));
                            }

                            if(key == 'background') {
                                event.currentTarget.querySelector('#background_type_image_input').value = '';
                            } else {
                                event.currentTarget.querySelector(`#${key}`).value = '';
                            }
                        }
                    }
                }

                /* Refresh iframe */
                refresh_biolink_preview();

            },
            error: () => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            },
        });

        event.preventDefault();
    })

    /* Form handling create */
    $('form[name^="create_biolink_"]').on('submit', event => {
        let form = $(event.currentTarget)[0];
        let data = new FormData(form);
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: `${url}biolink-block-ajax`,
            data: data,
            dataType: 'json',
            success: (data) => {
                let notification_container = event.currentTarget.querySelector('.notification-container');
                notification_container.innerHTML = '';
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(data.status == 'error') {
                    display_notifications(data.message, 'error', notification_container);
                }

                else if(data.status == 'success') {

                    /* Redirect */
                    redirect(data.details.url, true);

                }
            },
        });

        event.preventDefault();
    })

    /* Daterangepicker */
    let locale = <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>;
    $('[data-daterangepicker]').daterangepicker({
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: {...locale, format: 'YYYY-MM-DD HH:mm:ss'},
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
    }, (start, end, label) => {});
</script>

<script src="<?= ASSETS_FULL_URL . 'js/libraries/sortable.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    /* Links tab sortable */
    let sortable = Sortable.create(document.getElementById('biolink_blocks'), {
        animation: 150,
        handle: '.drag',
        onUpdate: (event) => {

            let biolink_blocks = [];
            $('#biolink_blocks > .biolink_block').each((i, elm) => {
                biolink_blocks.push({
                    biolink_block_id: $(elm).data('biolink-block-id'),
                    order: i
                });
            });

            $.ajax({
                type: 'POST',
                url: `${url}biolink-block-ajax`,
                dataType: 'json',
                data: {
                    request_type: 'order',
                    biolink_blocks,
                    global_token
                },
            });

            /* Refresh iframe */
            refresh_biolink_preview();
        }
    });

    /* Status change handler for the links */
    $('[id^="biolink_block_is_enabled_"]').on('change', event => {
        ajax_call_helper(event, 'biolink-block-ajax', 'is_enabled_toggle', () => {

            $(event.currentTarget).closest('.biolink_block').toggleClass('custom-row-inactive');

            /* Refresh iframe */
            refresh_biolink_preview();
        });
    });

    /* When an expanding happens for a link settings */
    $('[id^="biolink_block_expanded_content"]').on('show.bs.collapse', event => {
        let update_form_content = event.currentTarget;
        let link_type = $(update_form_content).data('link-type');
        let biolink_block_id = $(update_form_content.querySelector('input[name="biolink_block_id"]')).val();
        let biolink_link = $('#biolink_preview_iframe').contents().find(`div[data-biolink-block-id="${biolink_block_id}"]`);

        $('#biolink_preview_iframe').off().on('refreshed', event => {
            setTimeout(() => {
                biolink_link = $('#biolink_preview_iframe').contents().find(`div[data-biolink-block-id="${biolink_block_id}"]`);
                block_expanded_content_init();
            }, 900)
        })

        let extra_updating_and_potentially_color_inputs = [];

        let block_expanded_content_init = () => {
            type_handler(`#biolink_block_expanded_content_${biolink_block_id} select[name="animation"]`, 'data-animation', '*=');
            update_form_content.querySelector(`#biolink_block_expanded_content_${biolink_block_id} select[name="animation"]`) && update_form_content.querySelectorAll(`#biolink_block_expanded_content_${biolink_block_id} select[name="animation"]`).forEach(element => element.addEventListener('change', () => { type_handler(`#biolink_block_expanded_content_${biolink_block_id} select[name="animation"]`, 'data-animation', '*='); }));

            switch (link_type) {
                case 'link':
                case 'file':
                case 'cta':
                case 'share':
                case 'pdf_document':
                case 'email_collector':
                case 'phone_collector':
                case 'paypal':
                case 'vcard':
                case 'donation':
                case 'service':
                case 'product':
                case 'rss_feed':
                case 'youtube_feed':
                    extra_updating_and_potentially_color_inputs = ['name'];
                    break;

                case 'alert':
                    extra_updating_and_potentially_color_inputs = ['text'];
                    break;

                case 'review':
                    extra_updating_and_potentially_color_inputs = ['title', 'description', 'author_name', 'author_description', 'stars'];
                    break;

                case 'external_item':
                    extra_updating_and_potentially_color_inputs = ['name', 'description', 'price'];
                    break;

                case 'timeline':
                    extra_updating_and_potentially_color_inputs = ['title', 'description', 'date'];

                    let line_color_pickr = update_form_content.querySelector(`.line_color_pickr`);
                    let line_color_input = update_form_content.querySelector(`input[name="line_color"]`);

                    if(line_color_pickr) {
                        let color_pickr = Pickr.create({
                            el: line_color_pickr,
                            default: line_color_input.value,
                            ...pickr_options
                        });

                        color_pickr.off().on('change', hsva => {
                            line_color_input.value = hsva.toHEXA().toString();

                            biolink_link.find(`[data-line-background-color]`).css('background-color', hsva.toHEXA().toString());
                            biolink_link.find(`[data-line-border-color]`).css('border-color', hsva.toHEXA().toString());
                        });
                    }

                    break;

                case 'heading':
                    extra_updating_and_potentially_color_inputs = ['text'];

                    $(update_form_content.querySelectorAll('input[name="heading_type"]')).off().on('change', event => {
                        biolink_link.find('[data-text]').removeClass('h1 h2 h3 h4 h5 h6').addClass(event.currentTarget.value);
                    });

                    break;

                case 'paragraph':
                case 'markdown':
                    extra_updating_and_potentially_color_inputs = ['text'];
                    break;

                case 'avatar':
                    extra_updating_and_potentially_color_inputs = [];

                    $(update_form_content.querySelectorAll('input[name="border_radius"]')).off().on('change', event => {
                        let border_radius = event.currentTarget.value;

                        switch (border_radius) {
                            case 'straight':
                                biolink_link.find('[data-border-avatar-radius]').removeClass('link-avatar-round link-avatar-rounded');
                                break;

                            case 'round':
                                biolink_link.find('[data-border-avatar-radius]').removeClass('link-avatar-rounded').addClass('link-avatar-round');
                                break;

                            case 'rounded':
                                biolink_link.find('[data-border-avatar-radius]').removeClass('link-avatar-round').addClass('link-avatar-rounded');
                                break;
                        }
                    });

                    $(update_form_content.querySelector('select[name="size"]')).off().on('change paste keyup', event => {
                        let size = event.currentTarget.value;
                        biolink_link.find('[data-avatar]').css('width', size + 'px').css('height', size + 'px');
                    });

                    $(update_form_content.querySelectorAll('input[name="object_fit"]')).off().on('change paste keyup', event => {
                        let object_fit = document.querySelector(`input[name="object_fit"]:checked`).value;
                        biolink_link.find('[data-avatar]').css('object-fit', object_fit);
                    });

                    break;

                case 'big_link':
                    extra_updating_and_potentially_color_inputs = ['name', 'description'];
                    break;

                case 'socials':
                    extra_updating_and_potentially_color_inputs = [];

                    let item_color_pickr = update_form_content.querySelector(`.color_pickr`);
                    let item_color_input = update_form_content.querySelector(`input[name="color"]`);

                    if(item_color_pickr) {
                        let color_pickr = Pickr.create({
                            el: item_color_pickr,
                            default: item_color_input.value,
                            ...pickr_options
                        });

                        color_pickr.off().on('change', hsva => {
                            item_color_input.value = hsva.toHEXA().toString();

                            if(biolink_link.find(`[data-color]`).length) {
                                biolink_link.find(`[data-color]`).css('color', hsva.toHEXA().toString());
                            }
                        });
                    }
                    break;



            }

            /* Extra colored inputs */
            extra_updating_and_potentially_color_inputs.forEach(item => {
                let item_input = update_form_content.querySelector(`[name="${item}"]`);
                let item_color_pickr = update_form_content.querySelector(`.${item}_color_pickr`);
                let item_color_input = update_form_content.querySelector(`input[name="${item}_color"]`);

                if(item_color_pickr) {
                    let color_pickr = Pickr.create({
                        el: item_color_pickr,
                        default: item_color_input.value,
                        ...pickr_options
                    });

                    color_pickr.off().on('change', hsva => {
                        item_color_input.value = hsva.toHEXA().toString();

                        if(biolink_link.find(`[data-${item}-color]`).length) {
                            biolink_link.find(`[data-${item}-color]`).css('color', hsva.toHEXA().toString());
                        }

                        if(biolink_link.find(`[data-${item}-background-color]`).length) {
                            biolink_link.find(`[data-${item}-background-color]`).css('background-color', hsva.toHEXA().toString());
                        }
                    });
                }

                if(item_input) {
                    $(item_input).off().on('change paste keyup', event => {
                        if(biolink_link.find(`[data-${item}]`).length) {
                            biolink_link.find(`[data-${item}]`).text($(event.currentTarget).val());
                        }

                        if(update_form_content.querySelector('input[name="icon"]')) {
                            $(update_form_content.querySelector('input[name="icon"]')).trigger('change');
                        }

                        /* Set the name in the form title */
                        if(item == 'name') {
                            $(`[data-target="#biolink_block_expanded_content${biolink_block_id}"] > strong`).text(name);
                        }
                    });
                }
            });

            /* Iconpicker + icon */
            if(update_form_content.querySelector('input[name="icon"]')) {
                /* Delete previous instances */
                if(update_form_content.querySelector('input[name="icon"]').classList.contains('iconpicker-input')) {
                    $.iconpicker.batch(update_form_content.querySelector('input[name="icon"]'), 'destroy');
                }

                setTimeout(() => {
                    $(update_form_content.querySelector('input[name="icon"]')).iconpicker({
                        animation: false,
                        templates: {
                            popover: '<div class="iconpicker-popover popover"><div class="popover-title"></div><div class="popover-content"></div></div>',
                            search: '<input type="search" class="form-control iconpicker-search" placeholder="<?= l('global.search') ?>" />',
                            iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                            iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
                        }
                    });

                }, 500);

                $(update_form_content.querySelector('input[name="icon"]')).off().on('change paste keyup iconpickerSelected', event => {
                    let icon = $(event.currentTarget).val();

                    if(biolink_link.find('[data-icon]').length) {
                        if(!icon) {
                            biolink_link.find('svg').remove();
                        } else {
                            biolink_link.find('svg,i').remove();
                            biolink_link.find('[data-icon]').html(`<i class="${icon} mr-1"></i>`);
                        }
                    }
                });
            }

            /* Border width */
            if(update_form_content.querySelector('input[name="border_width"]') && biolink_link.find('[data-border-width]').length) {
                $(update_form_content.querySelector('input[name="border_width"]')).off().on('change paste keyup', event => {
                    let border_width = $(event.currentTarget).val();
                    biolink_link.find('[data-border-width]').css('border-width', border_width + 'px');
                });
            }

            /* Generate box shadow values for the preview */
            let generate_box_shadow = () => {
                if(biolink_link.find('[data-border-shadow]').length) {
                    let border_shadow_offset_x = update_form_content.querySelector('input[name="border_shadow_offset_x"]').value;
                    let border_shadow_offset_y = update_form_content.querySelector('input[name="border_shadow_offset_y"]').value;
                    let border_shadow_blur = update_form_content.querySelector('input[name="border_shadow_blur"]').value;
                    let border_shadow_spread = update_form_content.querySelector('input[name="border_shadow_spread"]').value;
                    let border_shadow_color = update_form_content.querySelector('input[name="border_shadow_color"]').value;

                    biolink_link.find('[data-border-shadow]').css('box-shadow', `${border_shadow_offset_x}px ${border_shadow_offset_y}px ${border_shadow_blur}px ${border_shadow_spread}px ${border_shadow_color}`);
                }
            }

            /* Border shadow color */
            let border_shadow_color_pickr_element = update_form_content.querySelector('.border_shadow_color_pickr');

            if(border_shadow_color_pickr_element) {
                let border_shadow_color = update_form_content.querySelector('input[name="border_shadow_color"]');

                /* text color handler */
                let color_pickr = Pickr.create({
                    el: border_shadow_color_pickr_element,
                    default: $(border_shadow_color).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(border_shadow_color).val(hsva.toHEXA().toString());
                    generate_box_shadow()
                });
            }

            $(update_form_content.querySelectorAll('input[name^="border_shadow_"]')).off().on('change', event => {
                generate_box_shadow();
            });

            /* Border color */
            let border_color_pickr_element = update_form_content.querySelector('.border_color_pickr');

            if(border_color_pickr_element) {
                let color_input = update_form_content.querySelector('input[name="border_color"]');

                /* text color handler */
                let color_pickr = Pickr.create({
                    el: border_color_pickr_element,
                    default: $(color_input).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(color_input).val(hsva.toHEXA().toString());

                    if(biolink_link.find('[data-border-color]').length) {
                        biolink_link.find('[data-border-color]').css('border-color', hsva.toHEXA().toString());
                    }
                });
            }

            /* Border radius */
            if(update_form_content.querySelector('input[name="border_radius"]') && biolink_link.find('[data-border-radius]').length) {
                $(update_form_content.querySelectorAll('input[name="border_radius"]')).off().on('change', event => {
                    let border_radius = event.currentTarget.value;

                    switch (border_radius) {
                        case 'straight':
                            biolink_link.find('[data-border-radius]').removeClass('link-btn-round link-btn-rounded');
                            break;

                        case 'round':
                            biolink_link.find('[data-border-radius]').removeClass('link-btn-rounded').addClass('link-btn-round');
                            break;

                        case 'rounded':
                            biolink_link.find('[data-border-radius]').removeClass('link-btn-round').addClass('link-btn-rounded');
                            break;
                    }
                });
            }

            /* Border style */
            if(update_form_content.querySelector('input[name="border_style"]') && biolink_link.find('[data-border-style]').length) {
                $(update_form_content.querySelectorAll('input[name="border_style"]')).off().on('change', event => {
                    biolink_link.find('[data-border-style]').css('border-style', event.currentTarget.value);
                });
            }

            /* Animation */
            if(update_form_content.querySelector('select[name="animation"]')) {
                let current_animation = update_form_content.querySelector('select[name="animation"]').value;

                $(update_form_content.querySelector('select[name="animation"]')).off().on('change', event => {
                    let animation = $(event.currentTarget).find(':selected').val();

                    switch (animation) {
                        case 'false':
                            biolink_link.find('[data-animation]').removeClass(`animated ${current_animation}`);
                            current_animation = false;
                            break;

                        default:
                            biolink_link.find('[data-animation]').removeClass(`animated ${current_animation}`).addClass(`animated ${animation}`);
                            current_animation = animation;
                            break;
                    }
                });
            }

            /* Text alignment */
            if(update_form_content.querySelectorAll('input[name="text_alignment"]').length) {
                $(update_form_content.querySelectorAll('input[name="text_alignment"]')).off().on('change', event => {
                    biolink_link.find('[data-text-alignment]').css('text-align', event.currentTarget.value);
                });
            }

            /* Text color */
            let text_color_pickr_element = update_form_content.querySelector('.text_color_pickr');

            if(text_color_pickr_element) {
                let color_input = update_form_content.querySelector('input[name="text_color"]');

                /* text color handler */
                let color_pickr = Pickr.create({
                    el: text_color_pickr_element,
                    default: $(color_input).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(color_input).val(hsva.toHEXA().toString());
                    biolink_link.find('[data-text-color]').css('color', hsva.toHEXA().toString());
                });
            }

            /* Background color */
            let background_color_pickr_element = update_form_content.querySelector('.background_color_pickr');

            if(background_color_pickr_element) {
                let color_input = update_form_content.querySelector('input[name="background_color"]');

                /* background color handler */
                let color_pickr = Pickr.create({
                    el: background_color_pickr_element,
                    default: $(color_input).val(),
                    ...pickr_options
                });

                color_pickr.off().on('change', hsva => {
                    $(color_input).val(hsva.toHEXA().toString());
                    biolink_link.find('[data-background-color]').css('background-color', hsva.toHEXA().toString());
                });
            }

            /* Schedule Handler */
            let schedule_handler = () => {
                if($(update_form_content.querySelector('input[name="schedule"]')).is(':checked')) {
                    $(update_form_content.querySelector('.schedule_container')).show();
                } else {
                    $(update_form_content.querySelector('.schedule_container')).hide();
                }
            };
            $(update_form_content.querySelector('input[name="schedule"]')).off().on('change', schedule_handler);
            schedule_handler();

            /* Custom select implementation */
            $('select:not([multiple="multiple"]):not([class="input-group-text"]):not([class="custom-select custom-select-sm"]):not([class^="ql"]):not([data-is-not-custom-select])').select2({
                dir: <?= json_encode(l('direction')) ?>,
                minimumResultsForSearch: 5,
            });
        }

        block_expanded_content_init();
    })

</script>

<script>
    /* FAQ Script */
    'use strict';

    /* add new */
    let faq_item_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_faq_item`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="faq_items_${biolink_block_id}"] .mb-4`).length;

        if(count >= 100) return;

        clone.querySelector(`input[name="item_title[]"`).setAttribute('name', `item_title[${count}]`);
        clone.querySelector(`textarea[name="item_content[]"`).setAttribute('name', `item_content[${count}]`);

        document.querySelector(`[id="faq_items_${biolink_block_id}"]`).appendChild(clone);

        faq_item_remove_initiator();
    };

    document.querySelectorAll('[data-add="faq_item"]').forEach(element => {
        element.addEventListener('click', faq_item_add);
    })

    /* remove */
    let faq_item_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let faq_item_remove_initiator = () => {
        document.querySelectorAll('[id^="faq_items_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', faq_item_remove);
            element.addEventListener('click', faq_item_remove)
        })
    };

    faq_item_remove_initiator();
</script>

<script>
    /* Timeline Script */
    'use strict';

    /* add new */
    let timeline_item_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_timeline_item`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="timeline_items_${biolink_block_id}"] .mb-4`).length;

        if(count >= 100) return;

        clone.querySelector(`input[name="item_title[]"`).setAttribute('name', `item_title[${count}]`);
        clone.querySelector(`textarea[name="item_description[]"`).setAttribute('name', `item_description[${count}]`);
        clone.querySelector(`input[name="item_date[]"`).setAttribute('name', `item_date[${count}]`);

        document.querySelector(`[id="timeline_items_${biolink_block_id}"]`).appendChild(clone);

        timeline_item_remove_initiator();
    };

    document.querySelectorAll('[data-add="timeline_item"]').forEach(element => {
        element.addEventListener('click', timeline_item_add);
    })

    /* remove */
    let timeline_item_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let timeline_item_remove_initiator = () => {
        document.querySelectorAll('[id^="timeline_items_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', timeline_item_remove);
            element.addEventListener('click', timeline_item_remove)
        })
    };

    timeline_item_remove_initiator();
</script>

<script>
    /* Image slider Script */
    'use strict';

    /* add new */
    let image_slider_item_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_image_slider_item`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="image_slider_items_${biolink_block_id}"] .mb-4`).length;

        if(count >= 25) return;

        clone.querySelector(`input[name="item_image_"`).setAttribute('name', `item_image_${count}`);
        clone.querySelector(`input[name="item_image_alt[]"`).setAttribute('name', `item_image_alt[${count}]`);
        clone.querySelector(`input[name="item_location_url[]"`).setAttribute('name', `item_location_url[${count}]`);

        document.querySelector(`[id="image_slider_items_${biolink_block_id}"]`).appendChild(clone);

        image_slider_item_remove_initiator();
    };

    document.querySelectorAll('[data-add="image_slider_item"]').forEach(element => {
        element.addEventListener('click', image_slider_item_add);
    })

    /* remove */
    let image_slider_item_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let image_slider_item_remove_initiator = () => {
        document.querySelectorAll('[id^="image_slider_items_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', image_slider_item_remove);
            element.addEventListener('click', image_slider_item_remove)
        })
    };

    image_slider_item_remove_initiator();
</script>

<script>
    /* Vcard Social Script */
    'use strict';

    /* add new */
    let vcard_social_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_vcard_social`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="vcard_socials_${biolink_block_id}"] .mb-4`).length;

        if(count >= 20) return;

        clone.querySelector(`input[name="vcard_social_label[]"`).setAttribute('name', `vcard_social_label[${count}]`);
        clone.querySelector(`input[name="vcard_social_value[]"`).setAttribute('name', `vcard_social_value[${count}]`);

        document.querySelector(`[id="vcard_socials_${biolink_block_id}"]`).appendChild(clone);

        vcard_social_remove_initiator();
    };

    document.querySelectorAll('[data-add="vcard_social"]').forEach(element => {
        element.addEventListener('click', vcard_social_add);
    })

    /* remove */
    let vcard_social_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let vcard_social_remove_initiator = () => {
        document.querySelectorAll('[id^="vcard_socials_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', vcard_social_remove);
            element.addEventListener('click', vcard_social_remove)
        })
    };

    vcard_social_remove_initiator();
</script>

<script>
    /* Vcard Phone Numbers */
    'use strict';

    /* add new */
    let vcard_phone_number_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_vcard_phone_numbers`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="vcard_phone_numbers_${biolink_block_id}"] .mb-4`).length;

        if(count >= 20) return;

        clone.querySelector(`input[name="vcard_phone_number_label[]"`).setAttribute('name', `vcard_phone_number_label[${count}]`);
        clone.querySelector(`input[name="vcard_phone_number_value[]"`).setAttribute('name', `vcard_phone_number_value[${count}]`);

        document.querySelector(`[id="vcard_phone_numbers_${biolink_block_id}"]`).appendChild(clone);

        vcard_phone_number_remove_initiator();
    };

    document.querySelectorAll('[data-add="vcard_phone_numbers"]').forEach(element => {
        element.addEventListener('click', vcard_phone_number_add);
    })

    /* remove */
    let vcard_phone_number_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let vcard_phone_number_remove_initiator = () => {
        document.querySelectorAll('[id^="vcard_phone_numbers_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', vcard_phone_number_remove);
            element.addEventListener('click', vcard_phone_number_remove)
        })
    };

    vcard_phone_number_remove_initiator();
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>

