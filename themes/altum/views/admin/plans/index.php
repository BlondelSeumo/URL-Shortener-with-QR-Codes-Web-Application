<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= l('admin_plans.header') ?></h1>

    <div class="col-auto p-0">
        <a href="<?= url('admin/plan-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_plans.create') ?></a>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= l('admin_plans.table.name') ?></th>
            <th><?= l('admin_plans.table.price') ?></th>
            <th><?= l('admin_plans.table.users') ?></th>
            <th><?= l('global.status') ?></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset(settings()->plan_guest)): ?>
            <tr>
                <td class="text-nowrap">
                    <a href="<?= url('admin/plan-update/guest') ?>"><?= settings()->plan_guest->name ?></a>
                    <a href="<?= url('pay/guest') ?>" target="_blank" rel="noreferrer noopener"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                </td>
                <td class="text-nowrap">-</td>
                <td class="text-nowrap">-</td>
                <td class="text-nowrap">
                    <?php if(settings()->plan_guest->status == 0): ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                    <?php elseif(settings()->plan_guest->status == 1): ?>
                        <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                    <?php else: ?>
                        <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-nowrap">
                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />-' ?>">
                        <i class="fas fa-fw fa-sort text-muted"></i>
                    </span>

                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), (null ? \Altum\Date::get(null, 2) . ' - <small>' . \Altum\Date::get(null, 3) . '</small>' : '<br />-')) ?>">
                        <i class="fas fa-fw fa-calendar text-muted"></i>
                    </span>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex justify-content-end">
                        <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => 'guest']) ?>
                    </div>
                </td>
            </tr>
        <?php endif ?>

        <tr>
            <td class="text-nowrap">
                <a href="<?= url('admin/plan-update/free') ?>"><?= settings()->plan_free->name ?></a>
                <a href="<?= url('pay/free') ?>" target="_blank" rel="noreferrer noopener"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
            </td>
            <td class="text-nowrap">-</td>
            <td class="text-nowrap">
                <a href="<?= url('admin/users?plan_id=free') ?>" class="badge badge-light" data-toggle="tooltip" title="<?= l('admin_users.menu') ?>">
                    <i class="fas fa-fw fa-sm fa-users mr-1"></i>
                    <?= nr($data->users_plans['free'] ?? 0) ?>
                    &#x2022;
                    <?= nr(get_percentage_between_two_numbers($data->users_plans['free'] ?? 0, $data->total_users)) . '%' ?>
                </a>
            </td>
            <td class="text-nowrap">
                <?php if(settings()->plan_free->status == 0): ?>
                    <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                <?php elseif(settings()->plan_free->status == 1): ?>
                    <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                <?php else: ?>
                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                <?php endif ?>
            </td>

            <td class="text-nowrap">
                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />-' ?>">
                    <i class="fas fa-fw fa-sort text-muted"></i>
                </span>

                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), (null ? \Altum\Date::get(null, 2) . ' - <small>' . \Altum\Date::get(null, 3) . '</small>' : '<br />-')) ?>">
                    <i class="fas fa-fw fa-calendar text-muted"></i>
                </span>
            </td>

            <td class="text-nowrap">
                <div class="d-flex justify-content-end">
                    <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => 'free']) ?>
                </div>
            </td>
        </tr>

        <tr>
            <td class="text-nowrap">
                <a href="<?= url('admin/plan-update/custom') ?>"><?= settings()->plan_custom->name ?></a>
                <span data-toggle="tooltip" title="<?= l('admin_plans.table.custom_help') ?>"><i class="fas fa-fw fa-sm fa-info-circle text-muted ml-1"></i></span>
            </td>
            <td class="text-nowrap">-</td>
            <td class="text-nowrap">
                <a href="<?= url('admin/users?plan_id=custom') ?>" class="badge badge-light" data-toggle="tooltip" title="<?= l('admin_users.menu') ?>">
                    <i class="fas fa-fw fa-sm fa-users mr-1"></i>
                    <?= nr($data->users_plans['custom'] ?? 0) ?>
                    &#x2022;
                    <?= nr(get_percentage_between_two_numbers($data->users_plans['custom'] ?? 0, $data->total_users)) . '%' ?>
                </a>
            </td>
            <td class="text-nowrap">
                <?php if(settings()->plan_custom->status == 0): ?>
                    <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                <?php elseif(settings()->plan_custom->status == 1): ?>
                    <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                <?php else: ?>
                    <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                <?php endif ?>
            </td>

            <td class="text-nowrap">
                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />-' ?>">
                    <i class="fas fa-fw fa-sort text-muted"></i>
                </span>

                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), (null ? \Altum\Date::get(null, 2) . ' - <small>' . \Altum\Date::get(null, 3) . '</small>' : '<br />-')) ?>">
                    <i class="fas fa-fw fa-calendar text-muted"></i>
                </span>
            </td>

            <td class="text-nowrap">
                <div class="d-flex justify-content-end">
                    <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => 'custom']) ?>
                </div>
            </td>
        </tr>

        <?php foreach($data->plans as $row): ?>
            <?php
            $row->prices = json_decode($row->prices);

            $tooltips = [
                'monthly' => '',
                'annual' => '',
                'lifetime' => '',
            ];

            foreach((array) settings()->payment->currencies as $currency => $currency_data) {
                $tooltips['monthly'] .= $row->prices->monthly->{$currency} . ' ' . $currency . '<br />';
                $tooltips['annual'] .= $row->prices->annual->{$currency} . ' ' . $currency . '<br />';
                $tooltips['lifetime'] .= $row->prices->lifetime->{$currency} . ' ' . $currency . '<br />';
            }
            ?>

            <tr>
                <td class="text-nowrap">
                    <a href="<?= url('admin/plan-update/' . $row->plan_id) ?>"><?= $row->name ?></a>
                    <?php if($row->status != 0): ?>
                        <a href="<?= url('pay/' . $row->plan_id) ?>" target="_blank" rel="noreferrer noopener"><i class="fas fa-fw fa-xs fa-external-link-alt ml-1"></i></a>
                    <?php endif ?>
                </td>
                <td class="text-nowrap">
                    <div class="d-flex flex-column text-muted small">
                        <div><span data-toggle="tooltip" data-html="true" title="<?= $tooltips['monthly'] ?>"><?= l('admin_plans.table.monthly') ?></span></div>
                        <div><span data-toggle="tooltip" data-html="true" title="<?= $tooltips['annual'] ?>"><?= l('admin_plans.table.annual') ?></span></div>
                        <div><span data-toggle="tooltip" data-html="true" title="<?= $tooltips['lifetime'] ?>"><?= l('admin_plans.table.lifetime') ?></span></div>
                    </div>
                </td>
                <td class="text-nowrap">
                    <a href="<?= url('admin/users?plan_id=' . $row->plan_id) ?>" class="badge badge-light" data-toggle="tooltip" title="<?= l('admin_users.menu') ?>">
                        <i class="fas fa-fw fa-sm fa-users mr-1"></i>
                        <?= nr($data->users_plans[$row->plan_id] ?? 0) ?>
                        &#x2022;
                        <?= nr(get_percentage_between_two_numbers($data->users_plans[$row->plan_id] ?? 0, $data->total_users)) . '%' ?>
                    </a>
                </td>
                <td class="text-nowrap">
                    <?php if($row->status == 0): ?>
                        <span class="badge badge-warning"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.disabled') ?></span>
                    <?php elseif($row->status == 1): ?>
                        <span class="badge badge-success"><i class="fas fa-fw fa-sm fa-check mr-1"></i> <?= l('global.active') ?></span>
                    <?php else: ?>
                        <span class="badge badge-info"><i class="fas fa-fw fa-sm fa-eye-slash mr-1"></i> <?= l('global.hidden') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-muted">
                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= l('global.order') . '<br />' . $row->order ?>">
                        <i class="fas fa-fw fa-sort text-muted"></i>
                    </span>

                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                        <i class="fas fa-fw fa-calendar text-muted"></i>
                    </span>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex justify-content-end">
                        <?= include_view(THEME_PATH . 'views/admin/plans/admin_plan_dropdown_button.php', ['id' => $row->plan_id]) ?>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
