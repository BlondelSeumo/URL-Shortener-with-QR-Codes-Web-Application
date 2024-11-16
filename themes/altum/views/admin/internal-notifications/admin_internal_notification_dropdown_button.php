<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <a href="<?= url('admin/internal-notification-create?title=' . $data->internal_notification->title . '&description=' . $data->internal_notification->description . '&url=' . $data->internal_notification->url . '&icon=' . $data->internal_notification->icon) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-clone mr-2"></i> <?= l('global.duplicate') ?></a>
        <a href="#" data-toggle="modal" data-target="#internal_notification_delete_modal" data-internal-notification-id="<?= $data->id ?>" data-resource-name="<?= $data->resource_name ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'internal_notification',
    'resource_id' => 'internal_notification_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/internal-notifications/delete/'
]), 'modals', 'internal_notification_delete_modal'); ?>

