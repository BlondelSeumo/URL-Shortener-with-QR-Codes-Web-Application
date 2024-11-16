<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/broadcasts') ?>"><?= l('admin_broadcasts.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_broadcast_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-mail-bulk text-primary-900 mr-2"></i> <?= l('admin_broadcast_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/broadcasts/admin_broadcast_dropdown_button.php', ['id' => $data->broadcast->broadcast_id, 'resource_name' => $data->broadcast->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form id="form" action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input type="text" id="name" name="name" value="<?= $data->broadcast->name ?>" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" maxlength="64" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.name_help') ?></small>
            </div>

            <div class="form-group">
                <label for="subject"><i class="fas fa-fw fa-sm fa-heading text-muted mr-1"></i> <?= l('admin_broadcasts.main.subject') ?></label>
                <input type="text" id="subject" name="subject" value="<?= $data->broadcast->subject ?>" class="form-control <?= \Altum\Alerts::has_field_errors('subject') ? 'is-invalid' : null ?>" maxlength="128" required="required" <?= $data->broadcast->status == 'sent' ? 'readonly="readonly"' : null ?> />
                <?= \Altum\Alerts::output_field_error('subject') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.subject_help') ?></small>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
            </div>

            <div class="form-group custom-control custom-switch" data-type="external">
                <input id="is_system_email" name="is_system_email" type="checkbox" class="custom-control-input" <?= $data->broadcast->settings->is_system_email ? 'checked="checked"' : null ?> <?= $data->broadcast->status == 'sent' ? 'disabled="disabled"' : null ?>>
                <label class="custom-control-label" for="is_system_email"><i class="fas fa-fw fa-sm fa-at text-muted mr-1"></i> <?= l('admin_broadcasts.main.is_system_email') ?></label>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.is_system_email_help') ?></small>
            </div>

            <div class="form-group">
                <label for="segment"><i class="fas fa-fw fa-sm fa-layer-group text-muted mr-1"></i> <?= l('admin_broadcasts.main.segment') ?> <?= $data->broadcast->status == 'sent' ? '<span>(' . $data->broadcast->total_emails .')</span>' : '<span id="segment_count"></span>' ?></label>
                <select id="segment" name="segment" class="form-control <?= \Altum\Alerts::has_field_errors('segment') ? 'is-invalid' : null ?>" required="required" <?= $data->broadcast->status == 'sent' ? 'disabled="disabled"' : null ?>>
                    <option value="all" <?= $data->broadcast->segment == 'all' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.all') ?></option>
                    <option value="subscribers" <?= $data->broadcast->segment == 'subscribers' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.subscribers') ?></option>
                    <option value="custom" <?= $data->broadcast->segment == 'custom' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.custom') ?></option>
                    <option value="filter" <?= $data->broadcast->segment == 'filter' ? 'selected="selected"' : null ?>><?= l('admin_broadcasts.main.segment.filter') ?></option>
                </select>
                <?= \Altum\Alerts::output_field_error('segment') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.segment_help') ?></small>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.segment_help2') ?></small>
            </div>

            <div class="form-group" data-segment="custom">
                <label for="users_ids"><i class="fas fa-fw fa-sm fa-users text-muted mr-1"></i> <?= l('admin_broadcasts.main.users_ids') ?></label>
                <input type="text" id="users_ids" name="users_ids" value="<?= $data->broadcast->users_ids ?>" class="form-control <?= \Altum\Alerts::has_field_errors('users_ids') ? 'is-invalid' : null ?>" placeholder="<?= l('admin_broadcasts.main.users_ids_placeholder') ?>" required="required" <?= $data->broadcast->status == 'sent' ? 'readonly="readonly"' : null ?> />
                <?= \Altum\Alerts::output_field_error('users_ids') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.users_ids_help') ?></small>
            </div>

            <div class="form-group custom-control custom-switch" data-segment="filter">
                <input id="<?= 'filters_is_newsletter_subscribed' ?>" name="filters_is_newsletter_subscribed" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_is_newsletter_subscribed) ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="<?= 'filters_is_newsletter_subscribed' ?>"><?= l('admin_broadcasts.main.segment.filter.is_newsletter_subscribed') ?></label>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="plans"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i> <?= l('admin_broadcasts.main.segment.filter.plans') ?></label>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_plans###free' ?>" name="filters_plans[]" value="free" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_plans) && in_array('free', $data->broadcast->settings->filters_plans) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_plans###free' ?>"><?= settings()->plan_free->name ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_plans###custom' ?>" name="filters_plans[]" value="custom" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_plans) && in_array('custom', $data->broadcast->settings->filters_plans) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_plans###custom' ?>"><?= settings()->plan_custom->name ?></label>
                        </div>
                    </div>

                    <?php foreach($data->plans as $plan): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="<?= 'filters_plans###' . $plan->plan_id ?>" name="filters_plans[]" value="<?= $plan->plan_id ?>" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_plans) && in_array($plan->plan_id, $data->broadcast->settings->filters_plans) ? 'checked="checked"' : null ?>>
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
                            <input id="<?= 'filters_status###active' ?>" name="filters_status[]" value="1" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_status['1']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_status###active' ?>"><?= l('admin_users.main.status_active') ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_status###unconfirmed' ?>" name="filters_status[]" value="0" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_status['0']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_status###unconfirmed' ?>"><?= l('admin_users.main.status_unconfirmed') ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_status###disabled' ?>" name="filters_status[]" value="2" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_status['2']) ? 'checked="checked"' : null ?>>
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
                                <input id="<?= 'filters_source###' . $source ?>" name="filters_source[]" value="<?= $source ?>" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_source) && in_array($source, $data->broadcast->settings->filters_source) ? 'checked="checked"' : null ?>>
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
                                <input id="<?= 'filters_device_type###' . $device_type ?>" name="filters_device_type[]" value="<?= $device_type ?>" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_device_type) && in_array($device_type, $data->broadcast->settings->filters_device_type) ? 'checked="checked"' : null ?>>
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
                                <input id="<?= 'filters_languages###' . $language_code ?>" name="filters_languages[]" value="<?= $language_name ?>" type="checkbox" class="custom-control-input" <?= isset($data->broadcast->settings->filters_languages) && in_array($language_name, $data->broadcast->settings->filters_languages) ? 'checked="checked"' : null ?>>
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
                            <option value="<?= $continent_code ?>" <?= isset($data->broadcast->settings->filters_continents) && in_array($continent_code, $data->broadcast->settings->filters_continents) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <div class="form-group">
                    <label for="filters_countries"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('global.countries') ?></label>
                    <select id="filters_countries" name="filters_countries[]" class="custom-select" multiple="multiple">
                        <?php foreach(get_countries_array() as $key => $value): ?>
                            <option value="<?= $key ?>" <?= isset($data->broadcast->settings->filters_countries) && in_array($key, $data->broadcast->settings->filters_countries) ? 'selected="selected"' : null ?>><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_cities"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.cities') ?></label>
                <input type="text" id="filters_cities" name="filters_cities" value="<?= implode(',', $data->broadcast->settings->filters_cities ?? []) ?>" class="form-control" placeholder="<?= l('admin_broadcasts.main.cities_placeholder') ?>" />
                <?= \Altum\Alerts::output_field_error('filters_cities') ?>
                <small class="form-text text-muted"><?= l('admin_broadcasts.main.cities_help') ?></small>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_operating_systems"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('admin_broadcasts.main.operating_systems') ?></label>
                <select id="filters_operating_systems" name="filters_operating_systems[]" class="custom-select" multiple="multiple">
                    <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                        <option value="<?= $os_name ?>" <?= in_array($os_name, $data->broadcast->settings->filters_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_browsers"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('admin_broadcasts.main.browsers') ?></label>
                <select id="filters_browsers" name="filters_browsers[]" class="custom-select" multiple="multiple">
                    <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                        <option value="<?= $browser_name ?>" <?= in_array($browser_name, $data->broadcast->settings->filters_browsers ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="filters_browser_languages"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('admin_broadcasts.main.browser_languages') ?></label>
                <select id="filters_browser_languages" name="filters_browser_languages[]" class="custom-select" multiple="multiple">
                    <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                        <option value="<?= $locale ?>" <?= in_array($locale, $data->broadcast->settings->filters_browser_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="content"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('admin_broadcasts.main.content') ?></label>
                <div class="bg-gray-100 rounded p-3 <?= $data->broadcast->status == 'sent' ? 'container-disabled' : null ?>" id="editorjs">
                    <?php if(!json_decode($data->broadcast->content)): ?>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <?= $data->broadcast->content ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
                <textarea name="content" id="content" class="form-control d-none <?= \Altum\Alerts::has_field_errors('content') ? 'is-invalid' : null ?>"><?= e($data->broadcast->content) ?></textarea>
                <?= \Altum\Alerts::output_field_error('content') ?>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{USER:NAME}}', '{{USER:EMAIL}}', '{{USER:CONTINENT_NAME}}', '{{USER:COUNTRY_NAME}}', '{{USER:CITY_NAME}}', '{{USER:DEVICE_TYPE}}', '{{USER:OS_NAME}}', '{{USER:BROWSER_NAME}}', '{{USER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="alert alert-info" role="alert"><?= l('admin_broadcast_create.info1') ?></div>
            <div class="alert alert-info" role="alert"><?= l('admin_broadcast_create.info2') ?></div>
            <div class="alert alert-info" role="alert"><?= l('admin_broadcast_create.info3') ?></div>

            <div class="form-group">
                <div class="input-group">
                    <input type="email" id="preview_email" name="preview_email" value="<?= $this->user->email ?>" class="form-control <?= \Altum\Alerts::has_field_errors('preview_email') ? 'is-invalid' : null ?>" placeholder="<?= l('global.email_placeholder') ?>" />
                    <div class="input-group-append">
                        <button type="submit" name="preview" class="btn btn-light"><?= l('admin_broadcast_create.send_preview') ?></button>
                    </div>
                </div>
                <?= \Altum\Alerts::output_field_error('preview_email') ?>
            </div>

            <?php if($data->broadcast->status == 'sent'): ?>
                <button type="submit" name="save" class="btn btn-block btn-outline-primary mt-3"><?= l('global.update') ?></button>
            <?php else: ?>
                <button type="submit" name="save" class="btn btn-block btn-outline-primary mt-3"><?= l('admin_broadcast_create.save_draft') ?></button>
                <button type="submit" name="send" class="btn btn-lg btn-block btn-primary mt-3"><?= l('admin_broadcast_create.send_broadcast') ?></button>
            <?php endif ?>
        </form>

    </div>
</div>

<?php ob_start() ?>
<style>
    .codex-editor__redactor {
        padding-bottom: 0 !important;
    }
</style>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php if(json_decode($data->broadcast->content)): ?>
    <?php ob_start() ?>
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script><!-- Header -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script><!-- Image -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script><!-- List -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script><!-- Link -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script><!-- Code -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script><!-- Raw HTML -->

    <!-- Load Editor.js's Core -->
    <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

    <script>
        'use strict';

        const is_valid_json = (str) => {
            try {
                JSON.parse(str);
                return true;
            } catch {
                return false;
            }
        };

        /* EditorJS initiatilization */
        let editorjs = new EditorJS({
            readOnly: false,
            holder: 'editorjs',

            /* Data */
            data: is_valid_json(document.querySelector('#content').innerText) ? JSON.parse(document.querySelector('#content').innerText) : {},

            /* Tolls */
            tools: {
                header: {
                    class: Header,
                    inlineToolbar: true,
                },

                list: {
                    class: List,
                    inlineToolbar: true,
                },

                image: SimpleImage,

                code: CodeTool,

                raw: RawTool,
            },
        });

        (async () => {
            try {
                await editorjs.isReady;
            } catch (reason) {
                console.log(`Editor.js initialization failed because of ${reason}`)
            }
        })();

        /* Handle form submission with the editor */
        document.querySelector('form').addEventListener('submit', async event => {
            let data = await editorjs.save();
            document.querySelector('textarea[name="content"]').innerHTML = JSON.stringify(data);
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>

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
        let response = await fetch(`${url}admin/broadcasts/get_segment_count?${query.toString()}`, {
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
