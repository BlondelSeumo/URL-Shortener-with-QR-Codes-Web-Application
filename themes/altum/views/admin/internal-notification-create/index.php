<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/internal-notifications') ?>"><?= l('admin_internal_notifications.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_internal_notification_create.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-bell text-primary-900 mr-2"></i> <?= l('admin_internal_notification_create.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form id="form" action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="title"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.title') ?></label>
                <input type="text" id="title" name="title" value="<?= $data->values['title'] ?>" class="form-control <?= \Altum\Alerts::has_field_errors('title') ? 'is-invalid' : null ?>" maxlength="128" required="required" />
                <?= \Altum\Alerts::output_field_error('title') ?>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="form-group" data-character-counter="textarea">
                <label for="description" class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('global.description') ?></span>
                    <small class="text-muted" data-character-counter-wrapper></small>
                </label>
                <textarea id="description" name="description" class="form-control" maxlength="1024" required="required"><?= $data->values['description'] ?></textarea>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="form-group">
                <label for="url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('global.url') ?></label>
                <input type="url" id="url" name="url" value="<?= $data->values['url'] ?>" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" maxlength="512" placeholder="<?= l('global.url_placeholder') ?>" />
                <?= \Altum\Alerts::output_field_error('url') ?>
            </div>

            <div class="form-group">
                <label for="icon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                <input type="text" id="icon" name="icon" value="<?= $data->values['icon'] ?>" class="form-control" maxlength="64" placeholder="<?= l('global.icon_placeholder') ?>" required="required" />
                <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
            </div>

            <div class="form-group">
                <label for="segment"><i class="fas fa-fw fa-sm fa-layer-group text-muted mr-1"></i> <?= l('admin_broadcasts.main.segment') ?> <span id="segment_count"></span></label>
                <select id="segment" name="segment" class="form-control <?= \Altum\Alerts::has_field_errors('segment') ? 'is-invalid' : null ?>" required="required">
                    <option value="all" <?= $data->values['segment'] == 'all' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.all') ?></option>
                    <option value="custom" <?= $data->values['segment'] == 'custom' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.custom') ?></option>
                    <option value="filter" <?= $data->values['segment'] == 'filter' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.filter') ?></option>
                </select>
                <?= \Altum\Alerts::output_field_error('segment') ?>
            </div>

            <div class="form-group" data-segment="custom">
                <label for="users_ids"><i class="fas fa-fw fa-sm fa-users text-muted mr-1"></i> <?= l('admin_broadcasts.main.users_ids') ?></label>
                <input type="text" id="users_ids" name="users_ids" value="<?= $data->values['users_ids'] ?>" class="form-control <?= \Altum\Alerts::has_field_errors('users_ids') ? 'is-invalid' : null ?>" placeholder="<?= l('admin_broadcasts.main.users_ids_placeholder') ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('users_ids') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.users_ids_help') ?></small>
            </div>

            <div class="form-group custom-control custom-switch" data-segment="filter">
                <input id="<?= 'filters_is_newsletter_subscribed' ?>" name="filters_is_newsletter_subscribed" type="checkbox" class="custom-control-input" <?= $data->values['filters_is_newsletter_subscribed'] ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="<?= 'filters_is_newsletter_subscribed' ?>"><?= l('admin_broadcasts.main.segment.filter.is_newsletter_subscribed') ?></label>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="plans"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i> <?= l('admin_broadcasts.main.segment.filter.plans') ?></label>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_plans###free' ?>" name="filters_plans[]" value="free" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_plans']['free']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_plans###free' ?>"><?= settings()->plan_free->name ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_plans###custom' ?>" name="filters_plans[]" value="custom" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_plans']['custom']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_plans###custom' ?>"><?= settings()->plan_custom->name ?></label>
                        </div>
                    </div>

                    <?php foreach($data->plans as $plan): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="<?= 'filters_plans###' . $plan->plan_id ?>" name="filters_plans[]" value="<?= $plan->plan_id ?>" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_plans'][$plan->plan_id]) ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="<?= 'filters_plans###' . $plan->plan_id ?>"><?= $plan->name ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="status"><i class="fas fa-fw fa-sm fa-circle-dot text-muted mr-1"></i> <?= l('global.status') ?></label>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_status###active' ?>" name="filters_status[]" value="1" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_status']['1']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_status###active' ?>"><?= l('admin_users.main.status_active') ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_status###unconfirmed' ?>" name="filters_status[]" value="0" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_status']['0']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_status###unconfirmed' ?>"><?= l('admin_users.main.status_unconfirmed') ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_status###disabled' ?>" name="filters_status[]" value="2" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_status']['2']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_status###disabled' ?>"><?= l('admin_users.main.status_disabled') ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="source"><i class="fas fa-fw fa-sm fa-right-to-bracket text-muted mr-1"></i> <?= l('admin_users.main.source') ?></label>
                <div class="row">
                    <?php foreach(['direct', 'admin_create', 'admin_api_create', 'facebook', 'twitter', 'discord', 'google', 'linkedin', 'microsoft'] as $source): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="<?= 'filters_source###' . $source ?>" name="filters_source[]" value="<?= $source ?>" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_source'][$source]) ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="<?= 'filters_source###' . $source ?>"><?= l('admin_users.main.source.' . $source) ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="device_type"><i class="fas fa-fw fa-sm fa-laptop text-muted mr-1"></i> <?= l('global.device') ?></label>
                <div class="row">
                    <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="<?= 'filters_device_type###' . $device_type ?>" name="filters_device_type[]" value="<?= $device_type ?>" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_device_type'][$device_type]) ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="<?= 'filters_device_type###' . $device_type ?>"><?= l('global.device.' . $device_type) ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="languages"><i class="fas fa-fw fa-sm fa-language text-muted mr-1"></i> <?= l('admin_broadcasts.main.languages') ?></label>
                <div class="row">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="<?= 'filters_languages###' . $language_code ?>" name="filters_languages[]" value="<?= $language_name ?>" type="checkbox" class="custom-control-input" <?= isset($data->values['filters_languages'][$language_name]) ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="<?= 'filters_languages###' . $language_code ?>"><?= $language_name ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <div class="form-group">
                    <label for="filters_continents"><i class="fas fa-fw fa-sm fa-globe-europe text-muted mr-1"></i> <?= l('global.continents') ?></label>
                    <select id="filters_continents" name="filters_continents[]" class="custom-select" multiple="multiple">
                        <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                            <option value="<?= $continent_code ?>" <?= isset($data->values['filters_continents'][$continent_code]) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <div class="form-group">
                    <label for="filters_countries"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('global.countries') ?></label>
                    <select id="filters_countries" name="filters_countries[]" class="custom-select" multiple="multiple">
                        <?php foreach(get_countries_array() as $key => $value): ?>
                            <option value="<?= $key ?>" <?= isset($data->values['filters_countries'][$key]) ? 'selected="selected"' : null ?>><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_cities"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.cities') ?></label>
                <input type="text" id="filters_cities" name="filters_cities" value="<?= $data->values['filters_cities'] ?>" class="form-control" placeholder="<?= l('admin_broadcasts.main.cities_placeholder') ?>" />
                <?= \Altum\Alerts::output_field_error('filters_cities') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.cities_help') ?></small>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_operating_systems"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('admin_broadcasts.main.operating_systems') ?></label>
                <select id="filters_operating_systems" name="filters_operating_systems[]" class="custom-select" multiple="multiple">
                    <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                        <option value="<?= $os_name ?>" <?= in_array($os_name, $data->values['filters_operating_systems'] ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_browsers"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('admin_broadcasts.main.browsers') ?></label>
                <select id="filters_browsers" name="filters_browsers[]" class="custom-select" multiple="multiple">
                    <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                        <option value="<?= $browser_name ?>" <?= in_array($browser_name, $data->values['filters_browsers'] ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_browser_languages"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('admin_broadcasts.main.browser_languages') ?></label>
                <select id="filters_browser_languages" name="filters_browser_languages[]" class="custom-select" multiple="multiple">
                    <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                        <option value="<?= $locale ?>" <?= in_array($locale, $data->values['filters_browser_languages'] ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <button type="submit" class="btn btn-lg btn-block btn-primary mt-3"><?= l('admin_internal_notification_create.send') ?></button>
        </form>

    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    type_handler('[name="segment"]', 'data-segment');
    document.querySelector('[name="segment"]') && document.querySelectorAll('[name="segment"]').forEach(element => element.addEventListener('change', () => { type_handler('[name="segment"]', 'data-segment'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php ob_start() ?>
<script>
    'use strict';

    document.querySelector('#segment').addEventListener('change', async event => {
        await get_segment_count();
    });

    document.querySelectorAll('#filters_is_newsletter_subscribed,[name^="filters_"]').forEach(element => element.addEventListener('change', async event => {
        await get_segment_count();
    }));

    let get_segment_count = async () => {
        let segment = document.querySelector('#segment').value;

        if(segment == 'custom') {
            document.querySelector('#segment_count').innerHTML = ``;
            return;
        }

        /* Display a loader */
        document.querySelector('#segment_count').innerHTML = `<div class="spinner-border spinner-border-sm" role="status"></div>`;

        /* Prepare query string */
        let query = new URLSearchParams();
        query.set('segment', segment);

        /* Filter preparing on query string */
        if(segment == 'filter') {
            query = new URLSearchParams(new FormData(document.querySelector('#form')));
        }

        /* Send request to server */
        let response = await fetch(`${url}admin/internal-notifications/get_segment_count?${query.toString()}`, {
            method: 'get',
        });

        let data = null;
        try {
            data = await response.json();
        } catch (error) {
            /* :)  */
        }

        if(!response.ok) {
            /* :)  */
        }

        if(data.status == 'error') {
            /* :)  */
        } else if(data.status == 'success') {
            document.querySelector('#segment_count').innerHTML = `(${data.details.count})`;
        }
    }

    get_segment_count();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
