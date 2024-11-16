<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-mail-bulk fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.broadcasts.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['broadcasts'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.broadcasts.chart_broadcasts') ?>">
                    <?= ($data->total['broadcasts'] > 0 ? '+' : null) . nr($data->total['broadcasts']) ?>
                </span>
                <span class="badge <?= $data->total['sent_emails'] > 0 ? 'badge-success' : 'badge-secondary' ?>" data-toggle="tooltip" title="<?= l('admin_statistics.broadcasts.chart_sent_emails') ?>">
                    <?= ($data->total['sent_emails'] > 0 ? '+' : null) . nr($data->total['sent_emails']) ?>
                </span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['broadcasts'] + $data->total['sent_emails'] ? null : 'd-none' ?>">
            <canvas id="broadcasts"></canvas>
        </div>
        <?= $data->total['broadcasts'] + $data->total['sent_emails'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
    <script>
        'use strict';

        let broadcasts_color = css.getPropertyValue('--gray-500');
        let sent_emails_color = css.getPropertyValue('--primary');

        /* Display chart */
        let broadcasts_chart = document.getElementById('broadcasts').getContext('2d');

        let sent_emails_color_gradient = broadcasts_chart.createLinearGradient(0, 0, 0, 250);
        sent_emails_color_gradient.addColorStop(0, set_hex_opacity(sent_emails_color, 0.1));
        sent_emails_color_gradient.addColorStop(1, set_hex_opacity(sent_emails_color, 0.025));

        let broadcasts_color_gradient = broadcasts_chart.createLinearGradient(0, 0, 0, 250);
        broadcasts_color_gradient.addColorStop(0, set_hex_opacity(broadcasts_color, 0.1));
        broadcasts_color_gradient.addColorStop(1, set_hex_opacity(broadcasts_color, 0.025));

        new Chart(broadcasts_chart, {
            type: 'line',
            data: {
                labels: <?= $data->broadcasts_chart['labels'] ?>,
                datasets: [
                    {
                        label: <?= json_encode(l('admin_statistics.broadcasts.chart_broadcasts')) ?>,
                        data: <?= $data->broadcasts_chart['broadcasts'] ?? '[]' ?>,
                        backgroundColor: broadcasts_color_gradient,
                        borderColor: broadcasts_color,
                        fill: true
                    },
                    {
                        label: <?= json_encode(l('admin_statistics.broadcasts.chart_sent_emails')) ?>,
                        data: <?= $data->broadcasts_chart['sent_emails'] ?? '[]' ?>,
                        backgroundColor: sent_emails_color_gradient,
                        borderColor: sent_emails_color,
                        fill: true
                    }
                ]
            },
            options: chart_options
        });
    </script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
