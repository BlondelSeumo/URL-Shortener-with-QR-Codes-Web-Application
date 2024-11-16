<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="mb-5">
        <div class="row m-n3 justify-content-between">
            <?php if(settings()->links->biolinks_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 mr-3 position-static" style="background: #eff6ff;">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <a href="<?= url('links?type=biolink') ?>" class="stretched-link" style="color: #3b82f6;">
                                            <i class="fas fa-fw fa-hashtag fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-title h4 m-0"><?= nr($data->biolink_links_total) ?></div>
                                <span class="text-muted"><?= l('dashboard.biolinks') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->shortener_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 mr-3 position-static" style="background: #f0fdfa;">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <a href="<?= url('links?type=link') ?>" class="stretched-link" style="color: #14b8a6;">
                                            <i class="fas fa-fw fa-link fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-title h4 m-0"><?= nr($data->link_links_total) ?></div>
                                <span class="text-muted"><?= l('dashboard.links') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->files_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 mr-3 position-static" style="background: #ecfdf5;">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <a href="<?= url('links?type=file') ?>" class="stretched-link" style="color: #10b981;">
                                            <i class="fas fa-fw fa-file fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-title h4 m-0"><?= nr($data->file_links_total) ?></div>
                                <span class="text-muted"><?= l('dashboard.file_links') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->vcards_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 mr-3 position-static" style="background: #ecfeff;">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <a href="<?= url('links?type=vcard') ?>" class="stretched-link" style="color: #06b6d4;">
                                            <i class="fas fa-fw fa-id-card fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-title h4 m-0"><?= nr($data->vcard_links_total) ?></div>
                                <span class="text-muted"><?= l('dashboard.vcard_links') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->events_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 mr-3 position-static" style="background: #eef2ff;">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <a href="<?= url('links?type=event') ?>" class="stretched-link" style="color: #6366f1;">
                                            <i class="fas fa-fw fa-calendar fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-title h4 m-0"><?= nr($data->event_links_total) ?></div>
                                <span class="text-muted"><?= l('dashboard.event_links') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <?php if(settings()->links->static_is_enabled): ?>
                <div class="col-12 col-sm-6 col-xl-4 p-3">
                    <div class="card h-100 position-relative">
                        <div class="card-body d-flex">
                            <div>
                                <div class="card border-0 mr-3 position-static" style="background: #fdf4ff;">
                                    <div class="p-3 d-flex align-items-center justify-content-between">
                                        <a href="<?= url('links?type=static') ?>" class="stretched-link" style="color: #c026d3;">
                                            <i class="fas fa-fw fa-code fa-lg"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="card-title h4 m-0"><?= nr($data->static_links_total) ?></div>
                                <span class="text-muted"><?= l('dashboard.static_links') ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
        </div>

        <?php if($data->links_chart): ?>
            <div class="card mt-5">
                <div class="card-body">
                    <div class="chart-container <?= !$data->links_chart['is_empty'] ? null : 'd-none' ?>">
                        <canvas id="pageviews_chart"></canvas>
                    </div>
                    <?= !$data->links_chart['is_empty'] ? null : include_view(THEME_PATH . 'views/partials/no_chart_data.php', ['has_wrapper' => false]); ?>

                    <?php if(!$data->links_chart['is_empty'] && settings()->main->chart_cache ?? 12): ?>
                        <small class="text-muted"><i class="fas fa-fw fa-sm fa-info-circle mr-1"></i> <?= sprintf(l('global.chart_help'), settings()->main->chart_cache ?? 12, settings()->main->chart_days ?? 30) ?></small>
                    <?php endif ?>
                </div>
            </div>

            <?php ob_start() ?>
            <script src="<?= ASSETS_FULL_URL . 'js/libraries/Chart.bundle.min.js?v=' . PRODUCT_CODE ?>"></script>
            <script src="<?= ASSETS_FULL_URL . 'js/chartjs_defaults.js?v=' . PRODUCT_CODE ?>"></script>

            <script>
                if(document.getElementById('pageviews_chart')) {
                    let css = window.getComputedStyle(document.body);
                    let pageviews_color = css.getPropertyValue('--primary');
                    let visitors_color = css.getPropertyValue('--gray-300');
                    let pageviews_color_gradient = null;
                    let visitors_color_gradient = null;

                    /* Chart */
                    let pageviews_chart = document.getElementById('pageviews_chart').getContext('2d');

                    /* Colors */
                    pageviews_color_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
                    pageviews_color_gradient.addColorStop(0, set_hex_opacity(pageviews_color, 0.6));
                    pageviews_color_gradient.addColorStop(1, set_hex_opacity(pageviews_color, 0.1));

                    visitors_color_gradient = pageviews_chart.createLinearGradient(0, 0, 0, 250);
                    visitors_color_gradient.addColorStop(0, set_hex_opacity(visitors_color, 0.6));
                    visitors_color_gradient.addColorStop(1, set_hex_opacity(visitors_color, 0.1));

                    new Chart(pageviews_chart, {
                        type: 'line',
                        data: {
                            labels: <?= $data->links_chart['labels'] ?? '[]' ?>,
                            datasets: [
                                {
                                    label: <?= json_encode(l('link.statistics.pageviews')) ?>,
                                    data: <?= $data->links_chart['pageviews'] ?? '[]' ?>,
                                    backgroundColor: pageviews_color_gradient,
                                    borderColor: pageviews_color,
                                    fill: true
                                },
                                {
                                    label: <?= json_encode(l('link.statistics.visitors')) ?>,
                                    data: <?= $data->links_chart['visitors'] ?? '[]' ?>,
                                    backgroundColor: visitors_color_gradient,
                                    borderColor: visitors_color,
                                    fill: true
                                }
                            ]
                        },
                        options: chart_options
                    });
                }
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        <?php endif ?>
    </div>

    <?= $this->views['links_content'] ?>
</div>


