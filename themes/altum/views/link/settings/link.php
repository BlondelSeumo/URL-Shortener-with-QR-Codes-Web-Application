<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>

<div class="card">
    <div class="card-body">

        <form name="update_link" action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="request_type" value="update" />
            <input type="hidden" name="type" value="link" />
            <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

            <div class="notification-container"></div>

            <div class="form-group">
                <label for="location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('link.settings.location_url') ?></label>
                <input id="location_url" type="text" class="form-control" name="location_url" value="<?= $data->link->location_url ?>" maxlength="2048" required="required" placeholder="<?= l('global.url_placeholder') ?>" />
            </div>

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
                            maxlength="<?= $this->user->plan_settings->url_maximum_characters ?? 64 ?>"
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

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#app_linking_container" aria-expanded="false" aria-controls="app_linking_container">
                <i class="fas fa-fw fa-mobile-button fa-sm mr-1"></i> <?= l('link.settings.app_linking_header') ?>
            </button>

            <div class="collapse" id="app_linking_container">
                <div <?= $this->user->plan_settings->app_linking_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->app_linking_is_enabled ? null : 'container-disabled' ?>">
                        <div class="form-group custom-control custom-switch">
                            <input
                                    id="app_linking_is_enabled"
                                    name="app_linking_is_enabled"
                                    type="checkbox"
                                    class="custom-control-input"
                                <?= $data->link->settings->app_linking_is_enabled ? 'checked="checked"' : null ?>
                                <?= $this->user->plan_settings->app_linking_is_enabled ? null : 'disabled="disabled"' ?>
                            >
                            <label class="custom-control-label" for="app_linking_is_enabled"><i class="fas fa-fw fa-mobile-screen-button fa-sm text-muted mr-1"></i> <?= l('link.settings.app_linking_is_enabled') ?></label>
                            <small class="form-text text-muted"><?= l('link.settings.app_linking_is_enabled_help') ?></small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="h6"><?= l('link.settings.app_linking_supported_os') ?></div>
                    <div class="row">
                        <div class="col-12 col-lg-6 mb-2 mb-lg-0">
                            <small class="badge badge-light mr-1">
                                <i class="fab fa-apple fa-fw fa-sm"></i>
                            </small>

                            Apple
                        </div>

                        <div class="col-12 col-lg-6 mb-2 mb-lg-0">
                            <small class="badge badge-light mr-1">
                                <i class="fab fa-android fa-fw fa-sm"></i>
                            </small>

                            Android
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="h6"><?= l('link.settings.app_linking_supported_apps') ?></div>
                    <small class="form-text text-muted mb-3"><?= l('link.settings.app_linking_supported_apps_help') ?></small>

                    <div id="app_linking_supported_apps" class="row">
                        <?php
                        $supported_apps = require APP_PATH . 'includes/app_linking.php';
                        ?>
                        <?php foreach($supported_apps as $app_key => $app): ?>
                            <?php
                            $tooltip_title = '<div class=\'p-3 text-left\'><p class=\'my-1\'>' . implode('</p> <p class=\'my-1\'>', array_map(function($key) {
                                    return $key;
                                }, $app['display_formats'])) . '</p></div>';
                            ?>

                            <div id="<?= $app_key ?>" class="col-12 col-lg-6 mb-2">
                                <small class="badge badge-light mr-1" data-toggle="tooltip" data-html="true" title="<?= $tooltip_title ?>">
                                    <i class="<?= $app['icon'] ?> fa-fw fa-sm" style="color: <?= $app['color'] ?>"></i>
                                </small>

                                <?= $app['name'] ?>

                                <small class="badge badge-success ml-1 <?= $data->link->settings->app_linking->app == $app_key ? null : 'd-none' ?>" data-app-linking-matched="<?= $app_key ?>">
                                    <i class="fas fa-check fa-fw fa-sm"></i> <?= l('link.settings.app_linking_supported_apps.matched') ?>
                                </small>
                            </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <div id="app_linking_supported_apps_no_match" class="alert alert-info <?= $data->link->settings->app_linking->app ? 'd-none' : null ?>"><?= l('link.settings.app_linking_supported_apps.no_match') ?></div>
            </div>

            <?php if(settings()->links->pixels_is_enabled): ?>
                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#pixels_container" aria-expanded="false" aria-controls="pixels_container">
                    <i class="fas fa-fw fa-adjust fa-sm mr-1"></i> <?= l('link.settings.pixels_header') ?>
                </button>

                <div class="collapse" id="pixels_container">
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

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#temporary_url_container" aria-expanded="false" aria-controls="temporary_url_container">
                <i class="fas fa-fw fa-clock fa-sm mr-1"></i> <?= l('link.settings.temporary_url_header') ?>
            </button>

            <div class="collapse" id="temporary_url_container">
                <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                        <div class="form-group custom-control custom-switch">
                            <input
                                    id="schedule"
                                    name="schedule"
                                    type="checkbox"
                                    class="custom-control-input"
                                <?= $data->link->settings->schedule && !empty($data->link->start_date) && !empty($data->link->end_date) ? 'checked="checked"' : null ?>
                                <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                            >
                            <label class="custom-control-label" for="schedule"><?= l('link.settings.schedule') ?></label>
                            <small class="form-text text-muted"><?= l('link.settings.schedule_help') ?></small>
                        </div>
                    </div>
                </div>

                <div id="schedule_container" style="display: none;">
                    <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                name="start_date"
                                                value="<?= \Altum\Date::get($data->link->start_date, 1) ?>"
                                                placeholder="<?= l('link.settings.start_date') ?>"
                                                autocomplete="off"
                                                data-daterangepicker
                                        >
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                name="end_date"
                                                value="<?= \Altum\Date::get($data->link->end_date, 1) ?>"
                                                placeholder="<?= l('link.settings.end_date') ?>"
                                                autocomplete="off"
                                                data-daterangepicker
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                        <label for="clicks_limit"><i class="fas fa-fw fa-mouse fa-sm text-muted mr-1"></i> <?= l('link.settings.clicks_limit') ?></label>
                        <input id="clicks_limit" type="number" class="form-control" name="clicks_limit" value="<?= $data->link->settings->clicks_limit ?>" />
                        <small class="form-text text-muted"><?= l('link.settings.clicks_limit_help') ?></small>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                        <label for="expiration_url"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.expiration_url') ?></label>
                        <input id="expiration_url" type="url" class="form-control" name="expiration_url" value="<?= $data->link->settings->expiration_url ?>" maxlength="2048" />
                        <small class="form-text text-muted"><?= l('link.settings.expiration_url_help') ?></small>
                    </div>
                </div>
            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#utm_container" aria-expanded="false" aria-controls="utm_container">
                <i class="fas fa-fw fa-keyboard fa-sm mr-1"></i> <?= l('link.settings.utm_header') ?>
            </button>

            <div class="collapse" id="utm_container">
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
                            <input id="utm_campaign" type="text" class="form-control" name="utm_campaign" value="<?= $data->link->settings->utm->campaign ?? '' ?>" placeholder="<?= l('link.settings.utm_campaign_placeholder') ?>" />
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

            <div class="collapse" id="protection_container">
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

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#targeting_container" aria-expanded="false" aria-controls="targeting_container">
                <i class="fas fa-fw fa-bullseye fa-sm mr-1"></i> <?= l('link.settings.targeting_header') ?>
            </button>

            <div class="collapse" id="targeting_container">
                <div class="form-group">
                    <label for="targeting_type"><i class="fas fa-fw fa-bullseye fa-sm text-muted mr-1"></i> <?= l('link.settings.targeting_type') ?></label>
                    <select id="targeting_type" name="targeting_type" class="custom-select">
                        <option value="false" <?= $data->link->settings->targeting_type == 'false' ? 'selected="selected"' : null?>>😊 <?= l('global.none') ?></option>
                        <option value="continent_code" <?= $data->link->settings->targeting_type == 'continent_code' ? 'selected="selected"' : null?>>🌍 <?= l('global.continent') ?></option>
                        <option value="country_code" <?= $data->link->settings->targeting_type == 'country_code' ? 'selected="selected"' : null?>>🇨🇺 <?= l('global.country') ?></option>
                        <option value="city_name" <?= $data->link->settings->targeting_type == 'city_name' ? 'selected="selected"' : null?>>🏙️ <?= l('global.city') ?></option>
                        <option value="device_type" <?= $data->link->settings->targeting_type == 'device_type' ? 'selected="selected"' : null?>>📱 <?= l('link.settings.targeting_type_device_type') ?></option>
                        <option value="os_name" <?= $data->link->settings->targeting_type == 'os_name' ? 'selected="selected"' : null?>>💻 <?= l('link.settings.targeting_type_os_name') ?></option>
                        <option value="browser_name" <?= $data->link->settings->targeting_type == 'browser_name' ? 'selected="selected"' : null?>>🌐 <?= l('link.settings.targeting_type_browser_name') ?></option>
                        <option value="browser_language" <?= $data->link->settings->targeting_type == 'browser_language' ? 'selected="selected"' : null?>>🗣️ <?= l('link.settings.targeting_type_browser_language') ?></option>
                        <option value="rotation" <?= $data->link->settings->targeting_type == 'rotation' ? 'selected="selected"' : null?>>🔄 <?= l('link.settings.targeting_type_rotation') ?></option>
                    </select>
                </div>

                <div data-targeting-type="false" class="d-none"></div>

                <div data-targeting-type="continent_code" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_continent_code_help') ?></p>

                    <div data-targeting-list="continent_code">
                        <?php if(isset($data->link->settings->targeting_continent_code) && !empty($data->link->settings->targeting_continent_code)): ?>
                            <?php foreach($data->link->settings->targeting_continent_code as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <select name="targeting_continent_code_key[<?= $key ?>]" class="custom-select">
                                            <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                                                <option value="<?= $continent_code ?>" <?= $targeting->key == $continent_code ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_continent_code_value[<?= $key ?>]" class="form-control <?= \Altum\Alerts::has_field_errors('targeting_continent_code_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                        <?= \Altum\Alerts::output_field_error('targeting_continent_code_value[' . $key . ']') ?>
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="continent_code" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="country_code" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_country_code_help') ?></p>

                    <div data-targeting-list="country_code">
                        <?php if(isset($data->link->settings->targeting_country_code) && !empty($data->link->settings->targeting_country_code)): ?>
                            <?php foreach($data->link->settings->targeting_country_code as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <select name="targeting_country_code_key[<?= $key ?>]" class="custom-select">
                                            <?php foreach(get_countries_array() as $country => $country_name): ?>
                                                <option value="<?= $country ?>" <?= $targeting->key == $country ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_country_code_value[<?= $key ?>]" class="form-control" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="country_code" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="city_name" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_city_name_help') ?></p>

                    <div data-targeting-list="city_name">
                        <?php if(isset($data->link->settings->targeting_city_name) && !empty($data->link->settings->targeting_city_name)): ?>
                            <?php foreach($data->link->settings->targeting_city_name as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <input type="text" name="targeting_city_name_key[<?= $key ?>]" class="form-control" value="<?= $targeting->key ?>" placeholder="<?= l('link.settings.targeting_type_city_name_placeholder') ?>" maxlength="128" />
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_city_name_value[<?= $key ?>]" class="form-control <?= \Altum\Alerts::has_field_errors('targeting_city_name_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                        <?= \Altum\Alerts::output_field_error('targeting_city_name_value[' . $key . ']') ?>
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="city_name" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="device_type" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_device_type_help') ?></p>

                    <div data-targeting-list="device_type">
                        <?php if(isset($data->link->settings->targeting_device_type) && !empty($data->link->settings->targeting_device_type)): ?>
                            <?php foreach($data->link->settings->targeting_device_type as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <select name="targeting_device_type_key[<?= $key ?>]" class="custom-select">
                                            <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                                                <option value="<?= $device_type ?>" <?= $targeting->key == $device_type ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_device_type_value[<?= $key ?>]" class="form-control" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="device_type" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="os_name" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_os_name_help') ?></p>

                    <div data-targeting-list="os_name">
                        <?php if(isset($data->link->settings->targeting_os_name) && !empty($data->link->settings->targeting_os_name)): ?>
                            <?php foreach($data->link->settings->targeting_os_name as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <select name="targeting_os_name_key[<?= $key ?>]" class="custom-select">
                                            <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                                                <option value="<?= $os_name ?>" <?= $targeting->key == $os_name ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_os_name_value[<?= $key ?>]" class="form-control" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="os_name" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="browser_name" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_browser_name_help') ?></p>

                    <div data-targeting-list="browser_name">
                        <?php if(isset($data->link->settings->targeting_browser_name) && !empty($data->link->settings->targeting_browser_name)): ?>
                            <?php foreach($data->link->settings->targeting_browser_name as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <select name="targeting_browser_name_key[<?= $key ?>]" class="custom-select">
                                            <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                                                <option value="<?= $browser_name ?>" <?= $targeting->key == $browser_name ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_browser_name_value[<?= $key ?>]" class="form-control <?= \Altum\Alerts::has_field_errors('targeting_browser_name_value[' . $key . ']') ? 'is-invalid' : null ?>" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                        <?= \Altum\Alerts::output_field_error('targeting_browser_name_value[' . $key . ']') ?>
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="browser_name" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="browser_language" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_browser_language_help') ?></p>

                    <div data-targeting-list="browser_language">
                        <?php if(isset($data->link->settings->targeting_browser_language) && !empty($data->link->settings->targeting_browser_language)): ?>
                            <?php foreach($data->link->settings->targeting_browser_language as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <select name="targeting_browser_language_key[<?= $key ?>]" class="custom-select">
                                            <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                                                <option value="<?= $locale ?>" <?= $targeting->key == $locale ? 'selected="selected"' : null ?>><?= $language ?></option>
                                            <?php endforeach ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_browser_language_value[<?= $key ?>]" class="form-control" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="browser_language" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>

                <div data-targeting-type="rotation" class="d-none">
                    <p class="small text-muted"><?= l('link.settings.targeting_type_rotation_help') ?></p>

                    <div data-targeting-list="rotation">
                        <?php if(isset($data->link->settings->targeting_rotation) && !empty($data->link->settings->targeting_rotation)): ?>
                            <?php foreach($data->link->settings->targeting_rotation as $key => $targeting): ?>
                                <div class="form-row">
                                    <div class="form-group col-lg-5">
                                        <input type="number" min="0" max="100" name="targeting_rotation_key[<?= $key ?>]" class="form-control" value="<?= $targeting->key ?>" placeholder="<?= l('link.settings.targeting_type_percentage') ?>" />
                                    </div>

                                    <div class="form-group col-lg-5">
                                        <input type="url" name="targeting_rotation_value[<?= $key ?>]" class="form-control" value="<?= $targeting->value ?>" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                                    </div>

                                    <div class="form-group col-lg-2 text-center">
                                        <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>

                    <div class="mb-3">
                        <button data-targeting-add="rotation" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                    </div>
                </div>
            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#cloaking_container" aria-expanded="false" aria-controls="cloaking_container">
                <i class="fas fa-fw fa-eye fa-sm mr-1"></i> <?= l('link.settings.cloaking_header') ?>
            </button>

            <div class="collapse" id="cloaking_container">
                <div <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->cloaking_is_enabled ? null : 'container-disabled' ?>">
                        <div class="form-group custom-control custom-switch">
                            <input
                                    id="cloaking_is_enabled"
                                    name="cloaking_is_enabled"
                                    type="checkbox"
                                    class="custom-control-input"
                                <?= $data->link->settings->cloaking_is_enabled ? 'checked="checked"' : null ?>
                                <?= $this->user->plan_settings->cloaking_is_enabled ? null : 'disabled="disabled"' ?>
                            >
                            <label class="custom-control-label" for="cloaking_is_enabled"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_is_enabled') ?></label>
                            <small class="form-text text-muted"><?= l('link.settings.cloaking_is_enabled_help') ?></small>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="cloaking_title"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_title') ?></label>
                    <input id="cloaking_title" type="text" class="form-control" name="cloaking_title" value="<?= $data->link->settings->cloaking_title ?>" maxlength="70" />
                </div>

                <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->favicon_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->favicon_size_limit) ?>">
                    <label for="cloaking_favicon"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_favicon') ?></label>
                    <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'favicons', 'file_key' => 'cloaking_favicon', 'already_existing_image' => $data->link->settings->cloaking_favicon]) ?>
                    <?= \Altum\Alerts::output_field_error('cloaking_favicon') ?>
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('favicons')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->favicon_size_limit) ?></small>
                </div>

                <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->seo_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->seo_image_size_limit) ?>">
                    <label for="cloaking_opengraph"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('link.settings.cloaking_opengraph') ?></label>
                    <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'biolink_seo_image', 'file_key' => 'cloaking_opengraph', 'already_existing_image' => $data->link->settings->cloaking_opengraph]) ?>
                    <?= \Altum\Alerts::output_field_error('cloaking_opengraph') ?>
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('biolink_seo_image')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->seo_image_size_limit) ?></small>
                </div>
            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#http_container" aria-expanded="false" aria-controls="http_container">
                <i class="fas fa-fw fa-laptop-code fa-sm mr-1"></i> <?= l('link.settings.http_header') ?>
            </button>

            <div class="collapse" id="http_container">
                <div class="alert alert-info"><?= l('link.settings.http_header_help') ?></div>

                <div class="form-group custom-control custom-radio">
                    <input type="radio" id="http_status_code_301" name="http_status_code" value="301" class="custom-control-input" <?= $data->link->settings->http_status_code == '301' ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="http_status_code_301"><?= l('link.settings.http_status_code.301') ?></label>
                </div>

                <div class="form-group custom-control custom-radio">
                    <input type="radio" id="http_status_code_302" name="http_status_code" value="302" class="custom-control-input" <?= $data->link->settings->http_status_code == '302' ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="http_status_code_302"><?= l('link.settings.http_status_code.302') ?></label>
                </div>

                <div class="form-group custom-control custom-radio">
                    <input type="radio" id="http_status_code_307" name="http_status_code" value="307" class="custom-control-input" <?= $data->link->settings->http_status_code == '307' ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="http_status_code_307"><?= l('link.settings.http_status_code.307') ?></label>
                </div>

                <div class="form-group custom-control custom-radio">
                    <input type="radio" id="http_status_code_308" name="http_status_code" value="308" class="custom-control-input" <?= $data->link->settings->http_status_code == '308' ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="http_status_code_308"><?= l('link.settings.http_status_code.308') ?></label>
                </div>
            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('link.settings.advanced_header') ?>
            </button>

            <div class="collapse" id="advanced_container">
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

                <div class="form-group custom-control custom-switch">
                    <input
                            id="forward_query_parameters_is_enabled"
                            name="forward_query_parameters_is_enabled"
                            type="checkbox"
                            class="custom-control-input"
                        <?= $data->link->settings->forward_query_parameters_is_enabled ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="forward_query_parameters_is_enabled"><i class="fas fa-fw fa-forward fa-sm text-muted mr-1"></i> <?= l('link.settings.forward_query_parameters_is_enabled') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.forward_query_parameters_is_enabled_help') ?></small>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
            </div>
        </form>

    </div>
</div>

<template id="template_targeting_continent_code">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_continent_code_key[]" class="custom-select">
                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                    <option value="<?= $continent_code ?>"><?= $continent_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_continent_code_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_country_code">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_country_code_key[]" class="custom-select">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>"><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_country_code_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_city_name">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <input type="text" name="targeting_city_name_key[]" class="form-control" value="" placeholder="<?= l('link.settings.targeting_type_city_name_placeholder') ?>" maxlength="128" />
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_city_name_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_device_type">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_device_type_key[]" class="custom-select">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>"><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_device_type_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_os_name">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_os_name_key[]" class="custom-select">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>"><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_os_name_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_browser_name">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_browser_name_key[]" class="custom-select">
                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                    <option value="<?= $browser_name ?>"><?= $browser_name ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_browser_name_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_browser_language">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <select name="targeting_browser_language_key[]" class="custom-select">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>"><?= $language ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_browser_language_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<template id="template_targeting_rotation">
    <div class="form-row">
        <div class="form-group col-lg-5">
            <input type="number" min="0" max="100" name="targeting_rotation_key[]" class="form-control" value="" placeholder="<?= l('link.settings.targeting_type_percentage') ?>" />
        </div>

        <div class="form-group col-lg-5">
            <input type="url" name="targeting_rotation_value[]" class="form-control" value="" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
        </div>

        <div class="form-group col-lg-2 text-center">
            <button type="button" data-targeting-remove="" class="btn btn-block btn-outline-danger" title="<?= l('global.delete') ?>"><i class="fas fa-fw fa-times"></i></button>
        </div>
    </div>
</template>

<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* UTM */
    let process_utm = () => {

        let utm_source = document.querySelector('input[name="utm_source"]').value;
        let utm_medium = document.querySelector('input[name="utm_medium"]').value;
        let utm_campaign = document.querySelector('input[name="utm_campaign"]').value;
        let utm_preview = <?= json_encode(l('global.none')) ?>;

        if(utm_source || utm_medium || utm_campaign) {
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

    /* Targeting */
    let targeting_type_handler = () => {
        let targeting_type = document.querySelector('#targeting_type').value;

        document.querySelectorAll('[data-targeting-type]').forEach(element => {
            let element_targeting_type = element.getAttribute('data-targeting-type');

            if(element_targeting_type == targeting_type) {
                document.querySelector(`[data-targeting-type="${element_targeting_type}"]`).classList.remove('d-none');
            } else {
                document.querySelector(`[data-targeting-type="${element_targeting_type}"]`).classList.add('d-none');
            }
        })
    }

    targeting_type_handler();
    document.querySelector('#targeting_type').addEventListener('change', targeting_type_handler);

    /* add new request header */
    let targeting_add = event => {
        let type = event.currentTarget.getAttribute('data-targeting-add');

        let clone = document.querySelector(`#template_targeting_${type}`).content.cloneNode(true);

        let request_headers_count = document.querySelectorAll(`[data-targeting-list="${type}"] .form-row`).length;

        clone.querySelector(`[name="targeting_${type}_key[]"`).setAttribute('name', `targeting_${type}_key[${request_headers_count}]`);
        clone.querySelector(`[name="targeting_${type}_value[]"`).setAttribute('name', `targeting_${type}_value[${request_headers_count}]`);

        document.querySelector(`[data-targeting-list="${type}"]`).appendChild(clone);

        targeting_remove_initiator();
    };

    document.querySelectorAll('[data-targeting-add]').forEach(element => {
        element.addEventListener('click', targeting_add);
    })

    /* remove request header */
    let targeting_remove = event => {
        event.currentTarget.closest('.form-row').remove();
    };

    let targeting_remove_initiator = () => {
        document.querySelectorAll('[data-targeting-remove]').forEach(element => {
            element.removeEventListener('click', targeting_remove);
            element.addEventListener('click', targeting_remove)
        })
    };

    targeting_remove_initiator();


    /* Settings Tab */
    let schedule_handler = () => {
        if($('#schedule').is(':checked')) {
            $('#schedule_container').show();
        } else {
            $('#schedule_container').hide();
        }
    };

    $('#schedule').on('change', schedule_handler);

    schedule_handler();

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
    }, (start, end, label) => {
    });

    /* Form handling */
    $('form[name="update_link"]').on('submit', event => {
        let form = $(event.currentTarget)[0];
        let data = new FormData(form);
        let notification_container = event.currentTarget.querySelector('.notification-container');
        notification_container.innerHTML = '';
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            processData: false,
            contentType: false,
            cache: false,
            url: `${url}link-ajax`,
            data: data,
            dataType: 'json',
            success: (data) => {
                display_notifications(data.message, data.status, notification_container);
                notification_container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(data.status == 'success') {
                    update_main_url(data.details.url);

                    /* App linking */
                    document.querySelectorAll('[data-app-linking-matched]').forEach(element => element.classList.add('d-none'));

                    if(data.details.app_linking.app) {
                        document.querySelector(`[data-app-linking-matched="${data.details.app_linking.app}"]`).classList.remove('d-none');
                        document.querySelector('#app_linking_supported_apps_no_match').classList.add('d-none');
                    } else {
                        document.querySelector('#app_linking_supported_apps_no_match').classList.remove('d-none');
                    }
                }
            },
            error: () => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            },
        });

        event.preventDefault();
    })
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
