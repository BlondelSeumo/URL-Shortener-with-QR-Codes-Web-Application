<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h4 text-truncate"><?= l('link.statistics.header') ?></h2>

    <div class="d-flex align-items-center col-auto p-0">
        <div data-toggle="tooltip" title="<?= l('statistics_reset_modal.header') ?>">
            <button
                    type="button"
                    class="btn btn-link text-secondary"
                    data-toggle="modal"
                    data-target="#link_statistics_reset_modal"
                    aria-label="<?= l('statistics_reset_modal.header') ?>"
                    data-link-id="<?= $data->link->link_id ?>"
                    data-start-date="<?= $data->datetime['start_date'] ?>"
                    data-end-date="<?= $data->datetime['end_date'] ?>"
            >
                <i class="fas fa-fw fa-sm fa-eraser"></i>
            </button>
        </div>

        <button
                id="daterangepicker"
                type="button"
                class="btn btn-sm btn-light"
                data-min-date="<?= \Altum\Date::get($data->link->datetime, 4) ?>"
                data-max-date="<?= \Altum\Date::get('', 4) ?>"
        >
            <i class="fas fa-fw fa-calendar mr-lg-1"></i>
            <span class="d-none d-lg-inline-block">
                <?php if($data->datetime['start_date'] == $data->datetime['end_date']): ?>
                    <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) ?>
                <?php else: ?>
                    <?= \Altum\Date::get($data->datetime['start_date'], 2, \Altum\Date::$default_timezone) . ' - ' . \Altum\Date::get($data->datetime['end_date'], 2, \Altum\Date::$default_timezone) ?>
                <?php endif ?>
            </span>
            <i class="fas fa-fw fa-caret-down d-none d-lg-inline-block ml-lg-1"></i>
        </button>
    </div>
</div>

<div class="row">
    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'overview' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=overview&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-list mr-1"></i>
            <?= l('link.statistics.overview') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'entries' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=entries&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-chart-bar mr-1"></i>
            <?= l('link.statistics.entries') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'continent_code' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=continent_code&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-globe-europe mr-1"></i>
            <?= l('global.continent') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'country' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=country&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-globe mr-1"></i>
            <?= l('global.countries') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'city_name' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=city_name&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-city mr-1"></i>
            <?= l('global.cities') ?>
        </a>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= in_array($data->type, ['referrer_host', 'referrer_path']) ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=referrer_host&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-random mr-1"></i>
            <?= l('link.statistics.referrer_host') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'device' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=device&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-laptop mr-1"></i>
            <?= l('link.statistics.device') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'os' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=os&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-server mr-1"></i>
            <?= l('link.statistics.os') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'browser' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=browser&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-window-restore mr-1"></i>
            <?= l('link.statistics.browser') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= $data->type == 'language' ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=language&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-language mr-1"></i>
            <?= l('link.statistics.language') ?>
        </a>
    </div>

    <div class="col-lg p-1 p-lg-2 text-truncate">
        <a class="btn btn-block btn-custom text-truncate <?= in_array($data->type, ['utm_source', 'utm_medium', 'utm_campaign']) ? 'active' : null ?>" href="<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=utm_source&start_date=' . $data->datetime['start_date'] . '&end_date=' . $data->datetime['end_date']) ?>">
            <i class="fas fa-fw fa-sm fa-link mr-1"></i>
            <?= l('link.statistics.utms') ?>
        </a>
    </div>
</div>

<?php if(!$data->has_data): ?>

    <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
        'filters_get' => $data->filters->get ?? [],
        'name' => 'link.statistics',
        'has_secondary_text' => true,
    ]); ?>

<?php else: ?>

    <?= $this->views['statistics'] ?>

<?php endif ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    /* Daterangepicker */
    $('#daterangepicker').daterangepicker({
        startDate: <?= json_encode($data->datetime['start_date']) ?>,
        endDate: <?= json_encode($data->datetime['end_date']) ?>,
        minDate: $('#daterangepicker').data('min-date'),
        maxDate: $('#daterangepicker').data('max-date'),
        ranges: {
            <?= json_encode(l('global.date.today')) ?>: [moment(), moment()],
            <?= json_encode(l('global.date.yesterday')) ?>: [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            <?= json_encode(l('global.date.last_7_days')) ?>: [moment().subtract(6, 'days'), moment()],
            <?= json_encode(l('global.date.last_30_days')) ?>: [moment().subtract(29, 'days'), moment()],
            <?= json_encode(l('global.date.this_month')) ?>: [moment().startOf('month'), moment().endOf('month')],
            <?= json_encode(l('global.date.last_month')) ?>: [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            <?= json_encode(l('global.date.all_time')) ?>: [moment($('#daterangepicker').data('min-date')), moment()]
        },
        alwaysShowCalendars: true,
        linkedCalendars: false,
        singleCalendar: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {

        /* Redirect */
        redirect(`<?= url((isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/' . $data->method . '?type=' . $data->type) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/statistics_reset_modal.php', ['modal_id' => 'link_statistics_reset_modal', 'resource_id' => 'link_id', 'path' => (isset($data->link->biolink_block_id) ? 'biolink-block/' . $data->link->biolink_block_id : 'link/' . $data->link->link_id) . '/statistics/reset']), 'modals'); ?>
