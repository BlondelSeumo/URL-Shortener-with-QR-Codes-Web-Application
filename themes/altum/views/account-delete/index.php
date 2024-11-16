<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="d-flex align-items-center mb-3">
        <h1 class="h4 m-0"><?= l('account_delete.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('account_delete.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                    <label for="current_password"><i class="fas fa-fw fa-sm fa-unlock text-muted mr-1"></i> <?= l('account_delete.current_password') ?></label>
                    <input type="password" id="current_password" name="current_password" class="form-control <?= \Altum\Alerts::has_field_errors('current_password') ? 'is-invalid' : null ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('current_password') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-danger"><?= l('global.delete') ?></button>
            </form>

        </div>
    </div>
</div>
