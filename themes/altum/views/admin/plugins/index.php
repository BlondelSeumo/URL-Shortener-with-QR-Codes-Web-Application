<?php defined('ALTUMCODE') || die() ?>

<?php if(count(\Altum\Plugin::$plugins)): ?>

    <?php
    $plugins_info = \Altum\Cache::cache_function_result('admin_plugins_info', null, function() {
        try {
            $response = \Unirest\Request::get('https://dev.altumcode.com/plugins-versions');

            if($response->code == 200) {
                return $response->body;
            } else {
                return null;
            }
        } catch (\Exception $exception) {
            return null;
        }
    }, 86400 * 1);
    ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-puzzle-piece text-primary-900 mr-2"></i> <?= l('admin_plugins.header') ?></h1>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row">
        <?php foreach(\Altum\Plugin::$plugins as $plugin): ?>
            <div class="col-lg-6 col-xl-4 p-3">
                <div class="card h-100">
                    <div class="card-body d-flex justify-content-between flex-column">
                        <div>
                            <div class="d-flex justify-content-center mb-3">
                                <div class="plugin-avatar rounded-circle d-flex justify-content-center align-items-center" style="<?= $plugin->avatar_style ?? null ?>">
                                    <?= $plugin->icon ?? null ?>
                                </div>
                            </div>

                            <div class="text-center h6 mb-3">
                                <?= $plugin->name ?>
                                <a href="<?= $plugin->url ?>" target="_blank" rel="nofollow noreferrer"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                            </div>

                            <p class="text-muted small m-0 text-center">
                                <?= $plugin->description ?>
                            </p>
                        </div>

                        <div class="d-flex align-items-center justify-content-center mt-4">
                            <?php if($plugin->status !== -2 && $plugin->status != 'inexistent'): ?>
                                <?php if($plugins_info && isset($plugins_info->{$plugin->plugin_id}) && $plugins_info->{$plugin->plugin_id}->version && $plugins_info->{$plugin->plugin_id}->version != $plugin->version): ?>
                                    <a href="https://altumcode.com/downloads" class="badge badge-warning mr-3" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('admin_plugins.outdated'), 'v' . $plugins_info->{$plugin->plugin_id}->version) ?>">
                                        <i class="fas fa-fw fa-sm fa-sync mr-1"></i> <?= 'v' . $plugin->version ?>
                                    </a>
                                <?php else: ?>
                                    <span class="badge badge-light mr-3">
                                    <i class="fas fa-fw fa-sm fa-code-branch mr-1"></i> <?= 'v' . $plugin->version ?>
                                </span>
                                <?php endif ?>
                            <?php endif ?>

                            <div class="mr-3">
                                <?php if($plugin->status === -2 || $plugin->status == 'inexistent'): ?>
                                    <a href="<?= $plugin->url ?>" target="_blank" rel="nofollow noreferrer" class="badge badge-success">
                                        <i class="fas fa-fw fa-sm fa-download mr-1"></i>
                                        <?= l('admin_plugins.status_inexistent') ?>
                                    </a>
                                <?php elseif($plugin->status === -1 || $plugin->status == 'uninstalled'): ?>
                                    <span class="badge badge-light">
                                        <i class="fas fa-fw fa-sm fa-times mr-1"></i> <?= l('admin_plugins.status_uninstalled') ?>
                                    </span>
                                <?php elseif($plugin->status === 0 || $plugin->status == 'installed'): ?>
                                    <span class="badge badge-secondary">
                                        <i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i>
                                        <?= l('admin_plugins.status_disabled') ?>
                                    </span>
                                <?php elseif($plugin->status === 1 || $plugin->status == 'active'): ?>
                                    <span class="badge badge-success">
                                    <i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('admin_plugins.status_active') ?>
                                </span>
                                <?php endif ?>
                            </div>

                            <?php if($plugin->actions && ($plugin->status !== -2 && $plugin->status != 'inexistent')): ?>
                                <?= include_view(THEME_PATH . 'views/admin/plugins/admin_plugin_dropdown_button.php', ['id' => $plugin->plugin_id, 'status' => $plugin->status, 'settings_url' => $plugin->settings_url ?? null]) ?>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>

<?php else: ?>

    <div class="d-flex flex-column flex-md-row align-items-md-center">
        <div class="mb-3 mb-md-0 mr-md-5">
            <i class="fas fa-fw fa-7x fa-puzzle-piece text-primary-200"></i>
        </div>

        <div class="d-flex flex-column">
            <h1 class="h3 m-0"><?= l('admin_plugins.header_no_data') ?></h1>
            <p class="text-muted"><?= l('admin_plugins.subheader_no_data') ?></p>

        </div>
    </div>

<?php endif ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/plugins/plugin_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/plugins/plugin_uninstall_modal.php'), 'modals'); ?>
