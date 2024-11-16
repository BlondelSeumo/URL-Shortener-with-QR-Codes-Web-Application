<?php defined('ALTUMCODE') || die() ?>

<div class="dropdown">
    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
        <i class="fas fa-fw fa-ellipsis-v"></i>
    </button>

    <div class="dropdown-menu dropdown-menu-right">
        <?php if($data->status): ?>
        <a href="#" data-toggle="modal" data-target="#teams_member_login_modal" data-team-member-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-sign-in-alt mr-2"></i> <?= l('teams_member_login_modal.menu') ?></a>
        <?php else: ?>
        <a href="#" data-toggle="modal" data-target="#teams_member_join_modal" data-team-member-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-check mr-2"></i> <?= l('teams_member_join_modal.menu') ?></a>
        <?php endif ?>
        <a href="#" data-toggle="modal" data-target="#teams_member_delete_modal" data-team-member-id="<?= $data->id ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
    </div>
</div>
