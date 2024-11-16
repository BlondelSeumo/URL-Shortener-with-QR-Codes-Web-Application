<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-sign-in-alt fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.sources') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.users.source') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->sources)): ?>
                    <?php foreach ($data->sources as $source => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= l('admin_users.main.source.' . $source) ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['sources'] * 100, 2) . '%'; ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
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
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-box-open fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.plans') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('admin_statistics.users.source') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->plans)): ?>
                    <?php foreach ($data->plans as $plan => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <?= (new \Altum\Models\Plan())->get_plan_by_id($plan)->name ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['plans'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
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
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-laptop fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.devices') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.device') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->devices)): ?>
                    <?php foreach ($data->devices as $device => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <i class="fas fa-fw fa-sm fa-<?= $device ?> text-muted mr-1"></i> <?= l('global.device.' . $device) ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['devices'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
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
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-server fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.operating_systems') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.os_name') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->operating_systems)): ?>
                    <?php foreach ($data->operating_systems as $os_name => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <img src="<?= ASSETS_FULL_URL . 'images/os/' . os_name_to_os_key($os_name) . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                                <span class=""><?= $os_name ?:  l('global.unknown') ?></span>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['operating_systems'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
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
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-window-restore fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.users.browsers') ?></h2>

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('global.browser_name') ?></th>
                    <th><?= l('admin_statistics.percentage') ?></th>
                    <th><?= l('admin_statistics.users') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php if (count($data->browsers)): ?>
                    <?php foreach ($data->browsers as $browser_name => $total): ?>
                        <tr>
                            <td class="text-nowrap">
                                <img src="<?= ASSETS_FULL_URL . 'images/browsers/' . browser_name_to_browser_key($browser_name) . '.svg' ?>" class="img-fluid icon-favicon mr-1" />
                                <span class=""><?= $browser_name ?: l('global.unknown') ?></span>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total / $data->total['browsers'] * 100, 2) . '%' ?>
                            </td>
                            <td class="text-nowrap">
                                <?= nr($total) ?>
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
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
