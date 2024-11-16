<?php defined('ALTUMCODE') || die() ?>

<?php if(count($data->taxes) || $data->filters->has_applied_filters): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-paperclip text-primary-900 mr-2"></i> <?= l('admin_taxes.header') ?></h1>

        <div class="d-flex position-relative">
            <div>
                <a href="<?= url('admin/tax-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_taxes.create') ?></a>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-gray-300 dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('admin/taxes?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('admin/taxes?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                        </a>
                        <button type="button" onclick="window.print();" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                        </button>
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
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="description" <?= $data->filters->search_by == 'description' ? 'selected="selected"' : null ?>><?= l('global.description') ?></option>
                                    <option value="value" <?= $data->filters->search_by == 'value' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.value') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_value_type" class="small"><?= l('admin_taxes.main.value_type') ?></label>
                                <select name="value_type" id="filters_value_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="percentage" <?= isset($data->filters->filters['value_type']) && $data->filters->filters['value_type'] == 'percentage' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.value_type_percentage') ?></option>
                                    <option value="fixed" <?= isset($data->filters->filters['value_type']) && $data->filters->filters['value_type'] == 'fixed' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.value_type_fixed') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_type" class="small"><?= l('global.type') ?></label>
                                <select name="type" id="filters_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="inclusive" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'inclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.type_inclusive') ?></option>
                                    <option value="exclusive" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == 'exclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.type_exclusive') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_billing_type" class="small"><?= l('admin_taxes.main.billing_type') ?></label>
                                <select name="billing_type" id="filters_billing_type" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="personal" <?= isset($data->filters->filters['billing_type']) && $data->filters->filters['billing_type'] == 'personal' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.billing_type_personal') ?></option>
                                    <option value="business" <?= isset($data->filters->filters['billing_type']) && $data->filters->filters['billing_type'] == 'business' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.billing_type_business') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('global.name') ?></option>
                                    <option value="value" <?= $data->filters->search_by == 'value' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.value') ?></option>
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
                <th><?= l('admin_taxes.table.tax') ?></th>
                <th><?= l('global.details') ?></th>
                <th><?= l('admin_taxes.table.billing_type') ?></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach($data->taxes as $row): ?>
                <tr>
                    <td class="text-nowrap">
                        <div class="d-flex flex-column">
                            <div><a href="<?= url('admin/tax-update/' . $row->tax_id) ?>"><?= $row->name ?></a></div>
                            <small class="text-muted"><?= $row->description ?></small>
                        </div>
                    </td>

                    <td class="text-nowrap">
                        <div class="d-flex flex-column">
                            <span><?= $row->value_type == 'percentage' ? $row->value . '%' : $row->value . ' ' . settings()->payment->default_currency ?></span>
                            <span class="text-muted"><?= $row->type == 'inclusive' ? l('admin_taxes.main.type_inclusive') : l('admin_taxes.main.type_exclusive') ?></span>
                        </div>
                    </td>

                    <td class="text-nowrap">
                        <?= l('admin_taxes.main.billing_type_' . $row->billing_type) ?>
                    </td>

                    <td class="text-nowrap">
                        <div class="d-flex align-items-center">
                            <a href="<?= url('admin/payments?taxes_ids=' . $row->tax_id) ?>" class="mr-2" data-toggle="tooltip" title="<?= l('admin_payments.menu') ?>">
                                <i class="fas fa-fw fa-credit-card text-muted"></i>
                            </a>
                        </div>
                    </td>

                    <td class="text-nowrap">
                        <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                            <i class="fas fa-fw fa-calendar text-muted"></i>
                        </span>
                    </td>

                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/taxes/admin_tax_dropdown_button.php', ['id' => $row->tax_id, 'resource_name' => $row->name]) ?>
                        </div>
                    </td>
                </tr>

            <?php endforeach ?>
            </tbody>
        </table>
    </div>

    <div class="mt-3"><?= $data->pagination ?></div>

<?php else: ?>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center">
                <div class="mb-3 mb-md-0 mr-md-5">
                    <i class="fas fa-fw fa-7x fa-paperclip text-primary-200"></i>
                </div>

                <div class="d-flex flex-column">
                    <h1 class="h3 m-0"><?= l('admin_taxes.header_no_data') ?></h1>
                    <p class="text-muted"><?= l('admin_taxes.subheader_no_data') ?></p>

                    <div>
                        <a href="<?= url('admin/tax-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_taxes.create') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif ?>
