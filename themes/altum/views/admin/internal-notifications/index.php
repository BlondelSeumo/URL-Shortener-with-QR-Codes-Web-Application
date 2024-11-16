<?php defined('ALTUMCODE') || die() ?>

<?php if(count($data->internal_notifications) || $data->filters->has_applied_filters): ?>

    <div class="d-flex flex-column flex-md-row justify-content-between mb-4">
        <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-bell text-primary-900 mr-2"></i> <?= l('admin_internal_notifications.header') ?></h1>

        <div class="d-flex position-relative">
            <div>
                <a href="<?= url('admin/internal-notification-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_internal_notifications.create') ?></a>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-gray-300 dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('admin/internal-notifications?' . $data->filters->get_get() . '&export=csv') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('admin/internal-notifications?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
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
                                    <option value="title" <?= $data->filters->search_by == 'title' ? 'selected="selected"' : null ?>><?= l('global.title') ?></option>
                                    <option value="description" <?= $data->filters->search_by == 'description' ? 'selected="selected"' : null ?>><?= l('global.description') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_for_who" class="small"><?= l('admin_internal_notifications.main.for_who') ?></label>
                                <select name="for_who" id="filters_for_who" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="user" <?= isset($data->filters->filters['for_who']) && $data->filters->filters['for_who'] == 'user' ? 'selected="selected"' : null ?>><?= l('admin_internal_notifications.main.for_who.user') ?></option>
                                    <option value="admin" <?= isset($data->filters->filters['for_who']) && $data->filters->filters['for_who'] == 'admin' ? 'selected="selected"' : null ?>><?= l('admin_internal_notifications.main.for_who.admin') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_from_who" class="small"><?= l('admin_internal_notifications.main.from_who') ?></label>
                                <select name="from_who" id="filters_from_who" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="system" <?= isset($data->filters->filters['from_who']) && $data->filters->filters['from_who'] == 'system' ? 'selected="selected"' : null ?>><?= l('admin_internal_notifications.main.from_who.system') ?></option>
                                    <option value="admin" <?= isset($data->filters->filters['from_who']) && $data->filters->filters['from_who'] == 'admin' ? 'selected="selected"' : null ?>><?= l('admin_internal_notifications.main.from_who.admin') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_is_read" class="small"><?= l('admin_internal_notifications.main.is_read') ?></label>
                                <select name="is_read" id="filters_is_read" class="custom-select custom-select-sm">
                                    <option value=""><?= l('global.all') ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_read']) && $data->filters->filters['is_read'] == '1' ? 'selected="selected"' : null ?>><?= l('global.yes') ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_read']) && $data->filters->filters['is_read'] == '0' ? 'selected="selected"' : null ?>><?= l('global.no') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="filters_order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="read_datetime" <?= $data->filters->search_by == 'read_datetime' ? 'selected="selected"' : null ?>><?= l('admin_internal_notifications.main.filters.read_datetime') ?></option>
                                    <option value="title" <?= $data->filters->search_by == 'title' ? 'selected="selected"' : null ?>><?= l('global.title') ?></option>
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

            <div class="ml-3">
                <button id="bulk_enable" type="button" class="btn btn-gray-300" data-toggle="tooltip" title="<?= l('global.bulk_actions') ?>"><i class="fas fa-fw fa-sm fa-list"></i></button>

                <div id="bulk_group" class="btn-group d-none" role="group">
                    <div class="btn-group dropdown" role="group">
                        <button id="bulk_actions" type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                            <?= l('global.bulk_actions') ?> <span id="bulk_counter" class="d-none"></span>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="bulk_actions">
                            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#bulk_delete_modal"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                        </div>
                    </div>

                    <button id="bulk_disable" type="button" class="btn btn-secondary" data-toggle="tooltip" title="<?= l('global.close') ?>"><i class="fas fa-fw fa-times"></i></button>
                </div>
            </div>
        </div>
    </div>

    <?= \Altum\Alerts::output_alerts() ?>

    <form id="table" action="<?= SITE_URL . 'admin/internal-notifications/bulk' ?>" method="post" role="form">
        <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
        <input type="hidden" name="type" value="" data-bulk-type />
    <input type="hidden" name="original_request" value="<?= base64_encode(\Altum\Router::$original_request) ?>" />
    <input type="hidden" name="original_request_query" value="<?= base64_encode(\Altum\Router::$original_request_query) ?>" />

        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th data-bulk-table class="d-none">
                        <div class="custom-control custom-checkbox">
                            <input id="bulk_select_all" type="checkbox" class="custom-control-input" />
                            <label class="custom-control-label" for="bulk_select_all"></label>
                        </div>
                    </th>
                    <th><?= l('admin_internal_notifications.table.internal_notification') ?></th>
                    <th><?= l('admin_internal_notifications.main.for_who') ?></th>
                    <th><?= l('admin_internal_notifications.main.from_who') ?></th>
                    <th><?= l('admin_internal_notifications.main.is_read') ?></th>
                    <th></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->internal_notifications as $row): ?>
                    <?php //ALTUMCODE:DEMO if(DEMO) {$row->user_email = 'hidden@demo.com'; $row->user_name = $row->ip = 'hidden on demo';} ?>

                    <tr>
                        <td data-bulk-table class="d-none">
                            <div class="custom-control custom-checkbox">
                                <input id="selected_id_<?= $row->internal_notification_id ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->internal_notification_id ?>" />
                                <label class="custom-control-label" for="selected_id_<?= $row->internal_notification_id ?>"></label>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <div class="p-3 bg-gray-50 mr-3 rounded">
                                    <i class="<?= $row->icon ?> fa-fw text-primary-900"></i>
                                </div>

                                <div class="d-flex flex-column">
                                    <div><?= $row->title ?></div>
                                    <small class="text-muted"><?= $row->description ?></small>
                                </div>
                            </div>

                        </td>

                        <td class="text-nowrap">
                            <?php if($row->for_who == 'user'): ?>
                                <div class="d-flex">
                                    <a href="<?= url('admin/user-view/' . $row->user_id) ?>">
                                        <img src="<?= get_gravatar($row->user_email) ?>" class="user-avatar rounded-circle mr-3" alt="" />
                                    </a>

                                    <div class="d-flex flex-column">
                                        <div>
                                            <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                                        </div>

                                        <span class="text-muted"><?= $row->user_email ?></span>
                                    </div>
                                </div>
                            <?php else: ?>
                                <span class="badge badge-light"><?= l('admin_internal_notifications.main.for_who.' . $row->for_who) ?></span>
                            <?php endif ?>
                        </td>

                        <td class="text-nowrap">
                            <span class="badge badge-info"><?= l('admin_internal_notifications.main.from_who.' . $row->from_who) ?></span>
                        </td>

                        <td class="text-nowrap">
                            <?php if($row->is_read): ?>
                                <span class="badge badge-success"><i class="fas fa-fw fa-eye fa-sm mr-1"></i> <?= l('global.yes') ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning"><i class="fas fa-fw fa-eye-slash fa-sm mr-1"></i> <?= l('global.no') ?></span>
                            <?php endif ?>
                        </td>

                        <td class="text-nowrap">
                            <div class="d-flex align-items-center">
                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('admin_internal_notifications.main.read_datetime'), ($row->read_datetime ? '<br />' . \Altum\Date::get($row->read_datetime, 2) . '<br /><small>' . \Altum\Date::get($row->read_datetime, 3) . '</small>' : '<br />-')) ?>">
                                    <i class="fas fa-fw fa-eye text-muted"></i>
                                </span>

                                <span class="mr-2" data-toggle="tooltip" data-html="true" title="<?= sprintf(l('global.datetime_tooltip'), '<br />' . \Altum\Date::get($row->datetime, 2) . '<br /><small>' . \Altum\Date::get($row->datetime, 3) . '</small>' . '<br /><small>(' . \Altum\Date::get_timeago($row->datetime) . ')</small>') ?>">
                                    <i class="fas fa-fw fa-calendar text-muted"></i>
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex justify-content-end">
                                <?= include_view(THEME_PATH . 'views/admin/internal-notifications/admin_internal_notification_dropdown_button.php', ['id' => $row->internal_notification_id, 'resource_name' => $row->title, 'internal_notification' => $row]) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </form>

    <div class="mt-3"><?= $data->pagination ?></div>

<?php else: ?>

    <?= \Altum\Alerts::output_alerts() ?>

    <div class="card">
        <div class="card-body">
            <div class="d-flex flex-column flex-md-row align-items-md-center">
                <div class="mb-3 mb-md-0 mr-md-5">
                    <i class="fas fa-fw fa-7x fa-bell text-primary-200"></i>
                </div>

                <div class="d-flex flex-column">
                    <h1 class="h3 m-0"><?= l('admin_internal_notifications.header_no_data') ?></h1>
                    <p class="text-muted"><?= l('admin_internal_notifications.subheader_no_data') ?></p>

                    <div>
                        <a href="<?= url('admin/internal-notification-create') ?>" class="btn btn-primary"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('admin_internal_notifications.create') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif ?>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
