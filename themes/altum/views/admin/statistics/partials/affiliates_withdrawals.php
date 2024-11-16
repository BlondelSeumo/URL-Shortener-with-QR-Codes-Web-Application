<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-wallet fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.affiliates_withdrawals.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['total_affiliates_withdrawals'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.affiliates_withdrawals.chart_total_affiliates_withdrawals') ?>">
                    <?= ($data->total['total_affiliates_withdrawals'] > 0 ? '+' : null) . nr($data->total['total_affiliates_withdrawals']) ?>
                </span>
                <span class="badge <?= $data->total['amount'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.affiliates_commissions.chart_amount') ?>">
                    <?= ($data->total['amount'] > 0 ? '+' : null) . nr($data->total['amount'], 2) . ' ' . settings()->payment->default_currency ?>
                </span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['total_affiliates_withdrawals'] ? null : 'd-none' ?>">
            <canvas id="affiliates_withdrawals"></canvas>
        </div>
        <?= $data->total['total_affiliates_withdrawals'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let total_affiliates_withdrawals_color = css.getPropertyValue('--gray-500');
    let amount_color = css.getPropertyValue('--primary');

    /* Display chart */
    let affiliates_withdrawals_chart = document.getElementById('affiliates_withdrawals').getContext('2d');

    let amount_color_gradient = affiliates_withdrawals_chart.createLinearGradient(0, 0, 0, 250);
    amount_color_gradient.addColorStop(0, set_hex_opacity(amount_color, 0.1));
    amount_color_gradient.addColorStop(1, set_hex_opacity(amount_color, 0.025));

    let total_affiliates_withdrawals_color_gradient = affiliates_withdrawals_chart.createLinearGradient(0, 0, 0, 250);
    total_affiliates_withdrawals_color_gradient.addColorStop(0, set_hex_opacity(total_affiliates_withdrawals_color, 0.1));
    total_affiliates_withdrawals_color_gradient.addColorStop(1, set_hex_opacity(total_affiliates_withdrawals_color, 0.025));

    new Chart(affiliates_withdrawals_chart, {
        type: 'line',
        data: {
            labels: <?= $data->affiliates_withdrawals_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('admin_statistics.affiliates_withdrawals.chart_total_affiliates_withdrawals')) ?>,
                    data: <?= $data->affiliates_withdrawals_chart['total_affiliates_withdrawals'] ?? '[]' ?>,
                    backgroundColor: total_affiliates_withdrawals_color_gradient,
                    borderColor: total_affiliates_withdrawals_color,
                    fill: true
                },
                {
                    label: <?= json_encode(l('admin_statistics.affiliates_withdrawals.chart_amount')) ?>,
                    data: <?= $data->affiliates_withdrawals_chart['amount'] ?? '[]' ?>,
                    backgroundColor: amount_color_gradient,
                    borderColor: amount_color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
