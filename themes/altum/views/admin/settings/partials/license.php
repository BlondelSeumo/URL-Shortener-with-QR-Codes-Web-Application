<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="license"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_settings.license.license') ?></label>
        <input id="license" name="license" type="text" class="form-control disabled" value="<?= settings()->license->license ?>" readonly="readonly" />
        <small class="form-text text-muted"><?= l('admin_settings.license.license_help') ?></small>
    </div>

    <div class="form-group">
        <label for="type"><i class="fas fa-fw fa-sm <?= in_array(settings()->license->type, ['Extended License', 'extended']) ? 'fa-user-tie text-primary' : 'fa-user text-muted' ?> mr-1"></i> <?= l('admin_settings.license.type') ?></label>
        <input id="type" name="type" type="text" class="form-control disabled" value="<?= settings()->license->type ?>" readonly="readonly" />
    </div>

    <div class="form-group">
        <label for="new_license"><i class="fas fa-fw fa-sm fa-ticket-alt text-muted mr-1"></i> <?= l('admin_settings.license.new_license') ?></label>
        <input id="new_license" name="new_license" type="text" class="form-control" required="required" />
        <small class="form-text text-muted"><?= l('admin_settings.license.new_license_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
