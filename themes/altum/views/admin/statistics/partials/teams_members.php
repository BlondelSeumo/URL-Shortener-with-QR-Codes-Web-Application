<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <div class="d-flex justify-content-between mb-4">
            <h2 class="h4 text-truncate mb-0"><i class="fas fa-fw fa-user-tag fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.teams_members.header') ?></h2>

            <div>
                <span class="badge <?= $data->total['teams_members'] > 0 ? 'badge-success' : 'badge-secondary' ?>"><?= ($data->total['teams_members'] > 0 ? '+' : null) . nr($data->total['teams_members']) ?></span>
            </div>
        </div>

        <div class="chart-container <?= $data->total['teams_members'] ? null : 'd-none' ?>">
            <canvas id="teams_members"></canvas>
        </div>
        <?= $data->total['teams_members'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let color = css.getPropertyValue('--primary');
    let color_gradient = null;

    /* Prepare chart */
    let teams_members_chart = document.getElementById('teams_members').getContext('2d');
    color_gradient = teams_members_chart.createLinearGradient(0, 0, 0, 250);
    color_gradient.addColorStop(0, set_hex_opacity(color, 0.1));
    color_gradient.addColorStop(1, set_hex_opacity(color, 0.025));

    /* Display chart */
    new Chart(teams_members_chart, {
        type: 'line',
        data: {
            labels: <?= $data->teams_members_chart['labels'] ?>,
            datasets: [{
                label: <?= json_encode(l('admin_statistics.teams_members.chart')) ?>,
                data: <?= $data->teams_members_chart['teams_members'] ?? '[]' ?>,
                backgroundColor: color_gradient,
                borderColor: color,
                fill: true
            }]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
