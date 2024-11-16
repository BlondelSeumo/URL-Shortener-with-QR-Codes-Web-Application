<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-credit-card text-primary-900 mr-2"></i> <?= l('admin_payments.header') ?></h1>

    <div class="d-flex position-relative d-print-none">
        <div>
            <div class="dropdown">
                <button type="button" class="btn btn-gray-300 dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-download"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right d-print-none">
                    <a href="<?= url('admin/payments?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                    </a>
                    <a href="<?= url('admin/payments?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" onclick="window.print();return false;" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                </div>
            </div>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-secondary' : 'btn-gray-300' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                    <i class="fas fa-fw fa-sm fa-filter"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                    <div class="dropdown-header d-flex justify-content-between">
                        <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                        <?php if($data->filters->has_applied_filters): ?>
                            <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                        <?php endif ?>
                    </div>

                    <div class="dropdown-divider"></div>

                    <form action="" method="get" role="form">
                        <div class="form-group px-4">
                            <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                            <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="custom-select custom-select-sm">
                                <option value="payment_id" <?= $data->filters->search_by == 'payment_id' ? 'selected="selected"' : null ?>><?= l('admin_payments.filters.search_by_payment_id') ?></option>
                                <option value="code" <?= $data->filters->search_by == 'code' ? 'selected="selected"' : null ?>><?= l('admin_codes.main.code') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_status" class="small"><?= l('global.status') ?></label>
                            <select name="status" id="filters_status" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="1" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == '1' ? 'selected="selected"' : null ?>><?= l('admin_payments.filters.status_paid') ?></option>
                                <option value="0" <?= isset($data->filters->filters['status']) && $data->filters->filters['status'] == '0' ? 'selected="selected"' : null ?>><?= l('admin_payments.filters.status_pending') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_plan_id" class="small"><?= l('admin_payments.filters.plan_id') ?></label>
                            <select name="plan_id" id="filters_plan_id" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <?php foreach($data->plans as $plan): ?>
                                    <option value="<?= $plan->plan_id ?>" <?= isset($data->filters->filters['plan_id']) && $data->filters->filters['plan_id'] == $plan->plan_id ? 'selected="selected"' : null ?>><?= $plan->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_type" class="small"><?= l('pay.custom_plan.payment_type') ?></label>
                            <select name="type" id="filters_type" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="recurring" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'recurring' ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.recurring_type') ?></option>
                                <option value="one_time" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'one_time' ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.one_time_type') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_processor" class="small"><?= l('pay.custom_plan.payment_processor') ?></label>
                            <select name="processor" id="filters_processor" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <?php foreach($data->payment_processors as $key => $value): ?>
                                    <option value="<?= $key ?>" <?= isset($data->filters->filters['processor']) && $data->filters->filters['processor'] == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_frequency" class="small"><?= l('pay.custom_plan.payment_frequency') ?></label>
                            <select name="frequency" id="filters_frequency" class="custom-select custom-select-sm">
                                <option value=""><?= l('global.all') ?></option>
                                <option value="monthly" <?= isset($data->filters->filters['frequency']) && $data->filters->filters['frequency'] == 'monthly' ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.monthly') ?></option>
                                <option value="annual" <?= isset($data->filters->filters['frequency']) && $data->filters->filters['frequency'] == 'annual' ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.annual') ?></option>
                                <option value="lifetime" <?= isset($data->filters->filters['frequency']) && $data->filters->filters['frequency'] == 'lifetime' ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.lifetime') ?></option>
                            </select>
                        </div>


                        <div class="form-group px-4">
                            <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="total_amount" <?= $data->filters->order_by == 'total_amount' ? 'selected="selected"' : null ?>><?= l('admin_payments.filters.order_by_total_amount') ?></option>
                                <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                <option value="email" <?= $data->filters->order_by == 'email' ? 'selected="selected"' : null ?>><?= l('global.email') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                            <select name="order_type" id="filters_order_type" class="custom-select custom-select-sm">
                                <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                            <select name="results_per_page" id="filters_results_per_page" class="custom-select custom-select-sm">
                                <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                    <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4 mt-4">
                            <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="table-responsive table-custom-container">
    <table class="table table-custom">
        <thead>
        <tr>
            <th><?= l('global.user') ?></th>
            <th><?= l('admin_payments.table.plan') ?></th>
            <th><?= l('admin_payments.table.total_amount') ?></th>
            <th><?= l('global.type') ?></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach($data->payments as $row): ?>
            <?php //ALTUMCODE:DEMO if(DEMO) {$row->email = $row->user_email = 'hidden@demo.com'; $row->name = $row->user_name = 'hidden on demo';} ?>
            <?php $row->taxes_ids = json_decode($row->taxes_ids ?? ''); ?>
            <tr>
                <td class="text-nowrap">
                    <div class="d-flex align-items-center">
                        <?php if($row->user_name || $row->user_email): ?>
                            <a href="<?= url('admin/user-view/' . $row->user_id) ?>">
                                <img src="<?= get_gravatar($row->user_email) ?>" class="user-avatar rounded-circle mr-3" alt="" />
                            </a>

                            <div class="d-flex flex-column">
                                <div>
                                    <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                                </div>

                                <span class="text-muted"><?= $row->user_email ?></span>
                            </div>
                        <?php else: ?>
                            <img src="<?= get_gravatar($row->user_email) ?>" class="user-avatar rounded-circle mr-3" alt="" />

                            <div class="text-muted">
                                <?= l('global.unknown') ?>
                            </div>
                        <?php endif ?>
                    </div>
                </td>

                <td class="text-nowrap">
                    <?php if(isset($data->plans[$row->plan_id])): ?>
                        <a href="<?= url('admin/plan-update/' . $row->plan_id) ?>" class="badge badge-light">
                            <?= $data->plans[$row->plan_id]->name ?>
                        </a>
                    <?php else: ?>
                        <span class="badge badge-light"><?= $row->plan->name ?? l('global.unknown') ?></span>
                    <?php endif ?>
                </td>

                <td class="text-nowrap">
                    <span class="badge badge-success"><?= nr($row->total_amount, 2) . ' ' . $row->currency ?></span>
                </td>

                <td class="text-nowrap">
                    <div class="d-flex flex-column">
                        <span><?= l('pay.custom_plan.' . $row->type . '_type') ?></span>
                        <div>
                            <span class="text-muted"><?= l('pay.custom_plan.' . $row->frequency) ?></span> - <span class="text-muted"><?= l('pay.custom_plan.' . $row->processor) ?></span>
                        </div>
                    </div>
                </td>

                <td class="text-nowrap">
                    <span class="mr-2 <?= $row->code ? null : 'opacity-0' ?>" data-toggle="tooltip" title="<?= $row->code ? $row->code . ' (-' . nr($row->discount_amount, 2) . ' ' . $row->currency . ')' : null ?>">
                        <i class="fas fa-fw fa-sm fa-tag text-muted"></i>
                    </span>

                    <?php
                    $taxes_html = null;
                    if(count($row->taxes_ids ?? [])) {
                        $taxes_html = l('admin_taxes.menu') . ' - ';
                        foreach($row->taxes_ids as $tax_id) {
                            $taxes_html .= '<a href=\'' . url('admin/tax-update/' . $tax_id) . '\' target=\'_blank\' class=\'mr-1\'>' . $tax_id . '</a>';
                        }
                    }
                    ?>
                    <a href="#" onclick="return false;" class="mr-2 text-decoration-none <?= $taxes_html ? null : 'opacity-0' ?>" data-toggle="popover" data-placement="top" data-container="body" data-html="true" data-content="<?= $taxes_html ?>">
                        <i class="fas fa-fw fa-sm fa-paperclip text-muted"></i>
                    </a>

                    <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                        <i class="fas fa-fw fa-calendar text-muted"></i>
                    </span>
                </td>
                <td>
                    <div class="d-flex justify-content-end">
                        <?= include_view(THEME_PATH . 'views/admin/payments/admin_payment_dropdown_button.php', [
                            'id' => $row->id,
                            'payment_proof' => $row->payment_proof,
                            'processor' => $row->processor,
                            'status' => $row->status
                        ]) ?>
                    </div>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>

<div class="mt-3"><?= $data->pagination ?></div>

<?php ob_start() ?>
<script>
    'use strict';

    $('[data-toggle="popover"]').popover();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
