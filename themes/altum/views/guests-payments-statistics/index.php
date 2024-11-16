<?php defined('ALTUMCODE') || die() ?>

<?php $payment_processors = require APP_PATH . 'includes/payment_processors.php'; ?>

<section class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('guests-payments') ?>"><?= l('guests_payments.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('guests_payments_statistics.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 text-truncate m-0"><?= sprintf(l('guests_payments_statistics.header'), $data->biolink_block->settings->name) ?></h1>

        <div class="d-flex align-items-center col-auto p-0">
            <button
                    id="daterangepicker"
                    type="button"
                    class="btn btn-sm btn-light"
                    data-min-date="<?= \Altum\Date::get($data->biolink_block->datetime, 4) ?>"
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

    <?php if(count($data->guests_payments)): ?>
        <div class="card">
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="guests_payments_chart"></canvas>
                </div>
            </div>
        </div>
    <?php else: ?>

        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'guests_payments_statistics',
            'has_secondary_text' => false,
        ]); ?>

    <?php endif ?>
</section>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js?v=' . PRODUCT_CODE ?>"></script>

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
        redirect(`<?= url('guests-payments-statistics?biolink_block_id=' . $data->biolink_block->biolink_block_id) ?>&start_date=${start.format('YYYY-MM-DD')}&end_date=${end.format('YYYY-MM-DD')}`, true);

    });

    <?php if(count($data->guests_payments)): ?>

    let css = window.getComputedStyle(document.body)

    /* Colors */
    let total_amount_color = css.getPropertyValue('--primary');
    let total_payments_color = css.getPropertyValue('--gray-400');
    let payments_gradient = null;
    let total_amount_gradient = null;

    /* Chart */
    let guests_payments_chart = document.getElementById('guests_payments_chart').getContext('2d');

    /* Colors */
    total_amount_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
    total_amount_gradient.addColorStop(0, set_hex_opacity(total_amount_color, 0.6));
    total_amount_gradient.addColorStop(1, set_hex_opacity(total_amount_color, 0.1));

    payments_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
    payments_gradient.addColorStop(0, set_hex_opacity(total_payments_color, 0.6));
    payments_gradient.addColorStop(1, set_hex_opacity(total_payments_color, 0.1));

    /* Display chart */
    new Chart(guests_payments_chart, {
        type: 'line',
        data: {
            labels: <?= $data->guests_payments_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('guests_payments_statistics.payments_label')) ?>,
                    data: <?= $data->guests_payments_chart['payments'] ?? '[]' ?>,
                    backgroundColor: payments_gradient,
                    borderColor: payments_color,
                    fill: true
                },
                {
                    label: <?= json_encode(l('guests_payments_statistics.total_amount_label')) ?>,
                    data: <?= $data->guests_payments_chart['total_amount'] ?? '[]' ?>,
                    backgroundColor: total_amount_gradient,
                    borderColor: total_amount_color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
    <?php endif ?>
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
