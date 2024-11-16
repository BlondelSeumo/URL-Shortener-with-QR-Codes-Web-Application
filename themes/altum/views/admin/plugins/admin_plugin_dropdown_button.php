<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-sm btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <?php if($data->status === -1 || $data->status == 'uninstalled'): ?>
            <a href="<?= url('admin/plugins/install/' . $data->id . '&global_token=' . \Altum\Csrf::get('global_token')) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-plug mr-2"></i> <?= l('admin_plugins.install') ?></a>
            <a href="#" data-toggle="modal" data-target="#plugin_delete_modal" data-plugin-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
        <?php elseif($data->status === 0 || $data->status == 'installed'): ?>
            <a href="<?= url('admin/plugins/activate/' . $data->id . '&global_token=' . \Altum\Csrf::get('global_token')) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-check mr-2"></i> <?= l('admin_plugins.activate') ?></a>
            <a href="#" data-toggle="modal" data-target="#plugin_uninstall_modal" data-plugin-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-times mr-2"></i> <?= l('admin_plugins.uninstall') ?></a>
        <?php elseif($data->status === 1 || $data->status == 'active'): ?>
            <a href="<?= url('admin/plugins/disable/' . $data->id . '&global_token=' . \Altum\Csrf::get('global_token')) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-eye-slash mr-2"></i> <?= l('admin_plugins.disable') ?></a>
        <?php endif ?>

        <?php if($data->settings_url): ?>
            <a href="<?= $data->settings_url ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-wrench mr-2"></i> <?= l('admin_plugins.settings') ?></a>
        <?php endif ?>
    </div>
</div>
