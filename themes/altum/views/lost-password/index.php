<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<h1 class="h5"><?= l('lost_password.header') ?></h1>
<p class="text-muted"><?= l('lost_password.subheader') ?></p>

<form action="" method="post" class="mt-4" role="form">
    <div class="form-group">
        <label for="email"><?= l('global.email') ?></label>
        <input id="email" type="email" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" required="required" autofocus="autofocus" />
        <?= \Altum\Alerts::output_field_error('email') ?>
    </div>

    <?php if(settings()->captcha->lost_password_is_enabled): ?>
        <div class="form-group">
            <?php $data->captcha->display() ?>
        </div>
    <?php endif ?>

    <div class="form-group mt-4">
        <button type="submit" name="submit" class="btn btn-primary btn-block my-1" <?= isset($_COOKIE['lost_password_lockout']) ? 'disabled="disabled"' : null ?>><?= l('lost_password.submit') ?></button>
    </div>
</form>

<div class="mt-5 text-center">
    <a href="<?= url('login' . $data->redirect_append) ?>" class="text-muted"><?= l('lost_password.return') ?></a>
</div>
