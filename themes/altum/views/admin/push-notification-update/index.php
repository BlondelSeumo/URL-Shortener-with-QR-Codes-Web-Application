<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/push-notifications') ?>"><?= l('admin_push_notifications.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_push_notification_update.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-bolt-lightning text-primary-900 mr-2"></i> <?= l('admin_push_notification_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/push-notifications/admin_push_notification_dropdown_button.php', ['id' => $data->push_notification->push_notification_id, 'resource_name' => $data->push_notification->title]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form id="form" action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="title"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_push_notifications.main.title') ?></label>
                <input type="text" id="title" name="title" value="<?= $data->push_notification->title ?>" class="form-control <?= \Altum\Alerts::has_field_errors('title') ? 'is-invalid' : null ?>" maxlength="64" required="required" <?= $data->push_notification->status == 'sent' ? 'readonly="readonly"' : null ?> />
                <?= \Altum\Alerts::output_field_error('title') ?>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{SUBSCRIBER:CONTINENT_NAME}}', '{{SUBSCRIBER:COUNTRY_NAME}}', '{{SUBSCRIBER:CITY_NAME}}', '{{SUBSCRIBER:DEVICE_TYPE}}', '{{SUBSCRIBER:OS_NAME}}', '{{SUBSCRIBER:BROWSER_NAME}}', '{{SUBSCRIBER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('global.description') ?></label>
                <input type="text" id="description" name="description" value="<?= $data->push_notification->description ?>" class="form-control <?= \Altum\Alerts::has_field_errors('description') ? 'is-invalid' : null ?>" maxlength="64" required="required" <?= $data->push_notification->status == 'sent' ? 'readonly="readonly"' : null ?> />
                <?= \Altum\Alerts::output_field_error('description') ?>
                <small class="form-text text-muted"><?= sprintf(l('global.variables'), '<code>' . implode('</code> , <code>',  ['{{WEBSITE_TITLE}}', '{{SUBSCRIBER:CONTINENT_NAME}}', '{{SUBSCRIBER:COUNTRY_NAME}}', '{{SUBSCRIBER:CITY_NAME}}', '{{SUBSCRIBER:DEVICE_TYPE}}', '{{SUBSCRIBER:OS_NAME}}', '{{SUBSCRIBER:BROWSER_NAME}}', '{{SUBSCRIBER:BROWSER_LANGUAGE}}']) . '</code>') ?></small>
                <small class="form-text text-muted"><?= l('global.spintax_help') ?></small>
            </div>

            <div class="form-group">
                <label for="url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('global.url') ?></label>
                <input type="url" id="url" name="url" value="<?= $data->push_notification->url ?>" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" maxlength="512" placeholder="<?= l('global.url_placeholder') ?>" <?= $data->push_notification->status == 'sent' ? 'readonly="readonly"' : null ?> />
                <?= \Altum\Alerts::output_field_error('url') ?>
            </div>

            <div class="form-group">
                <label for="segment"><i class="fas fa-fw fa-sm fa-layer-group text-muted mr-1"></i> <?= l('admin_push_notifications.main.segment') ?> <?= $data->push_notification->status == 'sent' ? '<span>(' . $data->push_notification->total_push_notifications .')</span>' : '<span id="segment_count"></span>' ?></label>
                <select id="segment" name="segment" class="form-control <?= \Altum\Alerts::has_field_errors('segment') ? 'is-invalid' : null ?>" required="required" <?= $data->push_notification->status == 'sent' ? 'disabled="disabled"' : null ?>>
                    <option value="all" <?= $data->push_notification->segment == 'all' ? 'selected="selected"' : null ?>><?= l('admin_push_notifications.main.segment.all') ?></option>
                    <option value="custom" <?= $data->push_notification->segment == 'custom' ? 'selected="selected"' : null ?>><?= l('admin_push_notifications.main.segment.custom') ?></option>
                    <option value="filter" <?= $data->push_notification->segment == 'filter' ? 'selected="selected"' : null ?>><?= l('admin_push_notifications.main.segment.filter') ?></option>
                </select>
                <?= \Altum\Alerts::output_field_error('segment') ?>
            </div>

            <div class="form-group" data-segment="custom">
                <label for="push_subscribers_ids"><i class="fas fa-fw fa-sm fa-users text-muted mr-1"></i> <?= l('admin_push_notifications.main.push_subscribers_ids') ?></label>
                <input type="text" id="push_subscribers_ids" name="push_subscribers_ids" value="<?= $data->push_notification->push_subscribers_ids ?>" class="form-control <?= \Altum\Alerts::has_field_errors('push_subscribers_ids') ? 'is-invalid' : null ?>" placeholder="<?= l('admin_push_notifications.main.push_subscribers_ids_placeholder') ?>" required="required" <?= $data->push_notification->status == 'sent' ? 'readonly="readonly"' : null ?> />
                <?= \Altum\Alerts::output_field_error('push_subscribers_ids') ?>
                <small class="form-text text-muted"><?= l('admin_push_notifications.main.push_subscribers_ids_help') ?></small>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="is_registered"><i class="fas fa-fw fa-sm fa-user text-muted mr-1"></i> <?= l('admin_push_notifications.main.segment.filter.is_registered') ?></label>
                <div class="row">
                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_is_registered###yes' ?>" name="filters_is_registered[yes]" value="1" type="checkbox" class="custom-control-input" <?= isset($data->push_notification->settings->filters_is_registered['1']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_is_registered###yes' ?>"><?= l('global.yes') ?></label>
                        </div>
                    </div>

                    <div class="col-6 mb-3">
                        <div class="custom-control custom-switch">
                            <input id="<?= 'filters_is_registered###no' ?>" name="filters_is_registered[no]" value="0" type="checkbox" class="custom-control-input" <?= isset($data->push_notification->settings->filters_is_registered['0']) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label" for="<?= 'filters_is_registered###no' ?>"><?= l('global.no') ?></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <div class="form-group">
                    <label for="filters_continents"><i class="fas fa-fw fa-sm fa-globe-europe text-muted mr-1"></i> <?= l('global.continents') ?></label>
                    <select id="filters_continents" name="filters_continents[]" class="custom-select" multiple="multiple">
                        <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                            <option value="<?= $continent_code ?>" <?= isset($data->push_notification->settings->filters_continents[$continent_code]) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <div class="form-group">
                    <label for="filters_countries"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('global.countries') ?></label>
                    <select id="filters_countries" name="filters_countries[]" class="custom-select" multiple="multiple">
                        <?php foreach(get_countries_array() as $key => $value): ?>
                            <option value="<?= $key ?>" <?= isset($data->push_notification->settings->filters_countries[$key]) ? 'selected="selected"' : null ?>><?= $value ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>

            <div class="form-group" data-segment="filter">
                <label for="device_type"><i class="fas fa-fw fa-sm fa-laptop text-muted mr-1"></i> <?= l('global.device') ?></label>
                <div class="row">
                    <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="<?= 'filters_device_type###' . $device_type ?>" name="filters_device_type[]" value="<?= $device_type ?>" type="checkbox" class="custom-control-input" <?= isset($data->push_notification->settings->filters_device_type) && in_array($device_type, $data->push_notification->settings->filters_device_type) ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="<?= 'filters_device_type###' . $device_type ?>"><?= l('global.device.' . $device_type) ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>


            <?php if($data->push_notification->status == 'sent'): ?>

            <?php else: ?>
                <button type="submit" name="save" class="btn btn-block btn-outline-primary mt-3"><?= l('admin_push_notification_create.save') ?></button>
                <button type="submit" name="send" class="btn btn-lg btn-block btn-primary mt-3"><?= l('admin_push_notification_create.send') ?></button>
            <?php endif ?>
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

    document.querySelectorAll('[name*="filters_is_registered"],[name="filters_countries[]"],[name="filters_continents[]"],[name="filters_device_type[]"]').forEach(element => element.addEventListener('change', async event => {
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
        let response = await fetch(`${url}admin/push-notifications/get_segment_count?${query.toString()}`, {
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
