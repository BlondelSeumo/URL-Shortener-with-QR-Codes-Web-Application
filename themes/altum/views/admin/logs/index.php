<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-md-row justify-content-between mb-4">
    <h1 class="h3 mb-3 mb-md-0"><i class="fas fa-fw fa-xs fa-clipboard-list text-primary-900 mr-2"></i> <?= l('admin_logs.header') ?></h1>

    <div class="d-flex position-relative d-print-none">
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

<form id="table" action="<?= SITE_URL . 'admin/logs/bulk' ?>" method="post" role="form">
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
                <th>
                    #
                </th>
                <th><?= l('global.name') ?></th>
                <th><?= l('admin_logs.main.size') ?></th>
                <th><?= l('admin_logs.main.last_modified') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php $i = 1; ?>
            <?php $total_size = 0; ?>
            <?php foreach($data->logs as $row): ?>
                <tr>
                    <td data-bulk-table class="d-none">
                        <div class="custom-control custom-checkbox">
                            <input id="selected_id_<?= $row->name ?>" type="checkbox" class="custom-control-input" name="selected[]" value="<?= $row->name ?>" />
                            <label class="custom-control-label" for="selected_id_<?= $row->name ?>"></label>
                        </div>
                    </td>
                    <td class="text-nowrap">
                        <span class="text-muted"><?= $i++ ?></span>
                    </td>
                    <td class="text-nowrap">
                        <a href="<?= url('admin/log/' . $row->name) ?>"><?= $row->name ?></a>
                        <div><span class="text-muted"><?= UPLOADS_URL_PATH . 'logs/' . $row->full_name ?></span></div>
                    </td>
                    <td class="text-nowrap">
                        <span class="badge badge-light">
                            <i class="fas fa-fw fa-sm fa-hdd mr-1"></i>
                            <?= get_formatted_bytes($row->size) ?>
                            <?php $total_size += $row->size ?>
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->last_modified) ?>">
                            <?= \Altum\Date::get_timeago($row->last_modified) ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <?= include_view(THEME_PATH . 'views/admin/logs/admin_log_dropdown_button.php', ['id' => $row->name, 'resource_name' => $row->name]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="2" class="text-muted"><?= sprintf(l('admin_logs.total'), nr($i-1)) ?></td>
                <td colspan="3">
                    <span class="badge badge-light">
                        <i class="fas fa-fw fa-sm fa-hdd mr-1"></i>
                        <?= get_formatted_bytes($total_size) ?>
                    </span>
                </td>
            </tr>
            </tfoot>
        </table>
    </div>
</form>

<?php require THEME_PATH . 'views/partials/js_bulk.php' ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/bulk_delete_modal.php'), 'modals'); ?>
