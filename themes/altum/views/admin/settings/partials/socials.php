<?php defined('ALTUMCODE') || die() ?>

<div>
    <p class="text-muted"><?= l('admin_settings.socials.help') ?></p>

    <?php foreach(require APP_PATH . 'includes/admin_socials.php' AS $key => $value): ?>
        <div class="form-group">
            <label for="<?= $key ?>"><i class="<?= $value['icon'] ?> fa-fw fa-sm mr-1 text-muted"></i> <?= $value['name'] ?></label>
            <div class="input-group">
                <?php if($value['input_display_format']): ?>
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= remove_url_protocol_from_url(str_replace('%s', '', $value['format'])) ?></span>
                    </div>
                <?php endif ?>
                <input id="<?= $key ?>" type="text" name="<?= $key ?>" class="form-control" value="<?= settings()->socials->{$key} ?>" placeholder="<?= $value['placeholder'] ?>" />
            </div>
        </div>
    <?php endforeach ?>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
