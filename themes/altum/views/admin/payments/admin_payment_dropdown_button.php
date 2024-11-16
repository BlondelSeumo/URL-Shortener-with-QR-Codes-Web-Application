<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v <?= $data->processor == 'offline_payment' && !$data->status ? 'text-danger' : null ?>"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <?php if($data->processor == 'offline_payment'): ?>
            <a href="<?= \Altum\Uploads::get_full_url('offline_payment_proofs') . $data->payment_proof ?>" target="_blank" class="dropdown-item"><i class="fas fa-fw fa-sm fa-download mr-2"></i> <?= l('admin_payments.table.action_view_proof') ?></a>

            <?php if(!$data->status): ?>
                <a href="#" data-toggle="modal" data-target="#payment_approve_modal" data-payment-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-check mr-2"></i> <?= l('admin_payments.table.action_approve_proof') ?></a>
            <?php endif ?>
        <?php endif ?>

        <?php if($data->status): ?>
            <a href="<?= url('invoice/' . $data->id) ?>" target="_blank" class="dropdown-item"><i class="fas fa-fw fa-sm fa-file-invoice mr-2"></i> <?= l('admin_payments.table.invoice') ?></a>
        <?php endif ?>

        <a href="#" data-toggle="modal" data-target="#payment_delete_modal" data-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'payment',
    'resource_id' => 'id',
    'has_dynamic_resource_name' => false,
    'path' => 'admin/payments/delete/'
]), 'modals', 'payment_delete_modal'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/payments/payment_approve_modal.php'), 'modals', 'payment_approve_modal'); ?>
