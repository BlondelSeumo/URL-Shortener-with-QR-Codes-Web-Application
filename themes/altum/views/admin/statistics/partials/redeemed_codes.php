<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-tags fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.redeemed_codes.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['discount_codes'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.redeemed_codes.chart_discount_codes') ?>">
                    <?= ($data->total['discount_codes'] > 0 ? '+' : null) . nr($data->total['discount_codes']) ?>
                </span>
                <span class="badge <?= $data->total['redeemable_codes'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.redeemed_codes.chart_redeemable_codes') ?>">
                    <?= ($data->total['redeemable_codes'] > 0 ? '+' : null) . nr($data->total['redeemable_codes']) ?>
                </span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['discount_codes'] + $data->total['redeemable_codes'] ? null : 'd-none' ?>">
            <canvas id="redeemed_codes"></canvas>
        </div>
        <?= $data->total['discount_codes'] + $data->total['redeemable_codes'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
    <script>
        'use strict';

        let discount_codes_color = css.getPropertyValue('--gray-500');
        let redeemable_codes_color = css.getPropertyValue('--primary');

        /* Display chart */
        let chart = document.getElementById('redeemed_codes').getContext('2d');

        let redeemable_codes_color_gradient = chart.createLinearGradient(0, 0, 0, 250);
        redeemable_codes_color_gradient.addColorStop(0, set_hex_opacity(redeemable_codes_color, 0.1));
        redeemable_codes_color_gradient.addColorStop(1, set_hex_opacity(redeemable_codes_color, 0.025));

        let discount_codes_color_gradient = chart.createLinearGradient(0, 0, 0, 250);
        discount_codes_color_gradient.addColorStop(0, set_hex_opacity(discount_codes_color, 0.1));
        discount_codes_color_gradient.addColorStop(1, set_hex_opacity(discount_codes_color, 0.025));

        new Chart(chart, {
            type: 'line',
            data: {
                labels: <?= $data->chart['labels'] ?>,
                datasets: [
                    {
                        label: <?= json_encode(l('admin_statistics.redeemed_codes.chart_discount_codes')) ?>,
                        data: <?= $data->chart['discount'] ?? '[]' ?>,
                        backgroundColor: discount_codes_color_gradient,
                        borderColor: discount_codes_color,
                        fill: true
                    },
                    {
                        label: <?= json_encode(l('admin_statistics.redeemed_codes.chart_redeemable_codes')) ?>,
                        data: <?= $data->chart['redeemable'] ?? '[]' ?>,
                        backgroundColor: redeemable_codes_color_gradient,
                        borderColor: redeemable_codes_color,
                        fill: true
                    }
                ]
            },
            options: chart_options
        });
    </script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
