<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-credit-card fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.payments.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['total_payments'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.payments.chart_total_payments') ?>">
                    <?= ($data->total['total_payments'] > 0 ? '+' : null) . nr($data->total['total_payments']) ?>
                </span>
                <span class="badge <?= $data->total['total_amount'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.payments.chart_total_amount') ?>">
                    <?= ($data->total['total_amount'] > 0 ? '+' : null) . nr($data->total['total_amount'], 2) . ' ' . settings()->payment->default_currency ?>
                </span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['total_payments'] ? null : 'd-none' ?>">
            <canvas id="total_payments"></canvas>
        </div>
        <?= $data->total['total_payments'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-piggy-bank fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.payments.processors') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.name') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.payments') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->processors)): ?>
                    <?php foreach ($data->processors as $processor): ?>
                        <tr>
                            <td class="text-nowrap">
                                <i class="<?= $data->payment_processors[$processor['processor']]['icon'] ?> fa-fw fa-sm mr-1" style="color: <?= $data->payment_processors[$processor['processor']]['color'] ?>"></i> <?= l('pay.custom_plan.' . $processor['processor']) ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($processor['total'] / $data->total['total_payments'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($processor['total']) ?>
                                &#x2022;
                                <?= nr($processor['total_amount'], 2) . ' ' . settings()->payment->default_currency ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-box-open fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.payments.plans') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.name') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.payments') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->payments_plans)): ?>
                    <?php foreach ($data->payments_plans as $payment_plan): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= $data->plans[$payment_plan['plan_id']]->name ?? l('global.unknown') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($payment_plan['total'] / $data->total['total_payments'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($payment_plan['total']) ?>
                                &#x2022;
                                <?= nr($payment_plan['total_amount'], 2) . ' ' . settings()->payment->default_currency ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-dollar-sign fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.payments.types') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.name') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.payments') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->types)): ?>
                    <?php foreach ($data->types as $type): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('pay.custom_plan.' . $type['type'] . '_type') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($type['total'] / $data->total['total_payments'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($type['total']) ?>
                                &#x2022;
                                <?= nr($type['total_amount'], 2) . ' ' . settings()->payment->default_currency ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-bag-shopping fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.payments.frequencies') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.name') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.payments') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->frequencies)): ?>
                    <?php foreach ($data->frequencies as $frequency): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('pay.custom_plan.' . $frequency['frequency']) ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($frequency['total'] / $data->total['total_payments'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($frequency['total']) ?>
                                &#x2022;
                                <?= nr($frequency['total_amount'], 2) . ' ' . settings()->payment->default_currency ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="text-nowrap text-muted" colspan="3">
                            <?= l('global.no_data') ?>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let total_payments_color = css.getPropertyValue('--gray-500');
    let total_amount_color = css.getPropertyValue('--primary');

    /* Display chart */
    let payments_chart = document.getElementById('payments').getContext('2d');

    let total_amount_color_gradient = payments_chart.createLinearGradient(0, 0, 0, 250);
    total_amount_color_gradient.addColorStop(0, set_hex_opacity(total_amount_color, 0.1));
    total_amount_color_gradient.addColorStop(1, set_hex_opacity(total_amount_color, 0.025));

    let total_payments_color_gradient = payments_chart.createLinearGradient(0, 0, 0, 250);
    total_payments_color_gradient.addColorStop(0, set_hex_opacity(total_payments_color, 0.1));
    total_payments_color_gradient.addColorStop(1, set_hex_opacity(total_payments_color, 0.025));

    new Chart(payments_chart, {
        type: 'line',
        data: {
            labels: <?= $data->payments_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.payments.chart_total_payments')) ?>,
                    data: <?= $data->payments_chart['total_payments'] ?? '[]' ?>,
                    backgroundColor: total_payments_color_gradient,
                    borderColor: total_payments_color,
                    fill: true
                },
                {
                    label: <?= json_encode(l('admin_statistics.payments.chart_total_amount')) ?>,
                    data: <?= $data->payments_chart['total_amount'] ?? '[]' ?>,
                    backgroundColor: total_amount_color_gradient,
                    borderColor: total_amount_color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
