<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->signatures->is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('admin_settings.signatures.is_enabled') ?></label>
    </div>

    <div class="form-group">
        <label for="branding"><?= l('admin_settings.signatures.branding') ?></label>
        <textarea id="branding" name="branding" class="form-control"><?= settings()->signatures->branding ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.signatures.branding_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
