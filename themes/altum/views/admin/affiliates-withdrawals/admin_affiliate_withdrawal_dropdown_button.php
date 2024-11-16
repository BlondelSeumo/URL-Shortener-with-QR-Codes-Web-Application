<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <?php if(!$data->is_paid): ?>
            <a href="#" data-toggle="modal" data-target="#affiliate_withdrawal_approve_modal" data-affiliate-withdrawal-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-check mr-2"></i> <?= l('admin_affiliates_withdrawals.table.action_pay_affiliate_withdrawal') ?></a>
        <?php endif ?>

        <a href="#" data-toggle="modal" data-target="#affiliate_withdrawal_delete_modal" data-affiliate-withdrawal-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>
