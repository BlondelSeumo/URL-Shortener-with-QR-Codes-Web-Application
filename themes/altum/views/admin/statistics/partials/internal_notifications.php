<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-bell fa-xs text-primary-900 mr-2"></i> <?= l('admin_internal_notifications.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['internal_notifications'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.internal_notifications.chart_internal_notifications') ?>">
                    <?= ($data->total['internal_notifications'] > 0 ? '+' : null) . nr($data->total['internal_notifications']) ?>
                </span>
                <span class="badge <?= $data->total['read_notifications'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.internal_notifications.chart_read_notifications') ?>">
                    <?= ($data->total['read_notifications'] > 0 ? '+' : null) . nr($data->total['read_notifications']) ?>
                </span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['internal_notifications'] ? null : 'd-none' ?>">
            <canvas id="internal_notifications"></canvas>
        </div>
        <?= $data->total['internal_notifications'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
    <script>
        'use strict';

        let internal_notifications_color = css.getPropertyValue('--gray-500');
        let read_notifications_color = css.getPropertyValue('--primary');

        /* Display chart */
        let internal_notifications_chart = document.getElementById('internal_notifications').getContext('2d');

        let read_notifications_color_gradient = internal_notifications_chart.createLinearGradient(0, 0, 0, 250);
        read_notifications_color_gradient.addColorStop(0, set_hex_opacity(read_notifications_color, 0.1));
        read_notifications_color_gradient.addColorStop(1, set_hex_opacity(read_notifications_color, 0.025));

        let internal_notifications_color_gradient = internal_notifications_chart.createLinearGradient(0, 0, 0, 250);
        internal_notifications_color_gradient.addColorStop(0, set_hex_opacity(internal_notifications_color, 0.1));
        internal_notifications_color_gradient.addColorStop(1, set_hex_opacity(internal_notifications_color, 0.025));

        new Chart(internal_notifications_chart, {
            type: 'line',
            data: {
                labels: <?= $data->internal_notifications_chart['labels'] ?>,
                datasets: [
                    {
                        label: <?= json_encode(l('admin_statistics.internal_notifications.chart_internal_notifications')) ?>,
                        data: <?= $data->internal_notifications_chart['internal_notifications'] ?? '[]' ?>,
                        backgroundColor: internal_notifications_color_gradient,
                        borderColor: internal_notifications_color,
                        fill: true
                    },
                    {
                        label: <?= json_encode(l('admin_statistics.internal_notifications.chart_read_notifications')) ?>,
                        data: <?= $data->internal_notifications_chart['read_notifications'] ?? '[]' ?>,
                        backgroundColor: read_notifications_color_gradient,
                        borderColor: read_notifications_color,
                        fill: true
                    }
                ]
            },
            options: chart_options
        });
    </script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
