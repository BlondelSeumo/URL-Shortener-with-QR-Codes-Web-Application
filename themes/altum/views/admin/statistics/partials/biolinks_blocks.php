<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<?php if(count($data->total)): ?>
<?php foreach($data->total as $key => $value): ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="<?= $data->biolink_blocks[$key]['icon'] ?> fa-xs text-primary-900 mr-2"></i> <?= l('link.biolink.blocks.' . $key) ?></h2>

            <div>
                <span class="badge <?= $data->total[$key] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total[$key] > 0 ? '+' : null) . nr($data->total[$key]) ?></span>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="<?= $key ?>"></canvas>
        </div>
    </div>
</div>
<?php endforeach ?>
<?php else: ?>
    <?= include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => true]); ?>
<?php endif ?>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    'use strict';

    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    <?php foreach($data->total as $key => $value): ?>
    let <?= $key ?>_chart = document.getElementById('<?= $key ?>').getContext('2d');

    color_gradient = <?= $key ?>_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.1));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.025));

    new Chart(<?= $key ?>_chart, {
        type: 'line',
        data: {
            labels: <?= $data->biolinks_blocks_chart[$key]['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(l('link.biolink.blocks.' . $key)) ?>,
                    data: <?= $data->biolinks_blocks_chart[$key]['total'] ?? '[]' ?>,
                    backgroundColor: color_gradient,
                    borderColor: color,
                    fill: true
                }
            ]
        },
        options: chart_options
    });
    <?php endforeach ?>
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
