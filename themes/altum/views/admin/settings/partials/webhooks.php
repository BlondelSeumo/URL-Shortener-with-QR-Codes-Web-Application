<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group">
        <label for="user_new"><?= l('admin_settings.webhooks.user_new') ?></label>
        <input id="user_new" type="url" name="user_new" class="form-control" value="<?= settings()->webhooks->user_new ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'source', 'is_newsletter_subscribed']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="user_update"><?= l('admin_settings.webhooks.user_update') ?></label>
        <input id="user_update" type="url" name="user_update" class="form-control" value="<?= settings()->webhooks->user_update ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'source']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="user_delete"><?= l('admin_settings.webhooks.user_delete') ?></label>
        <input id="user_delete" type="url" name="user_delete" class="form-control" value="<?= settings()->webhooks->user_delete ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="payment_new"><?= l('admin_settings.webhooks.payment_new') ?></label>
        <input id="payment_new" type="url" name="payment_new" class="form-control" value="<?= settings()->webhooks->payment_new ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'plan_id', 'plan_expiration_date', 'payment_id', 'payment_processor', 'payment_type', 'payment_frequency', 'payment_total_amount', 'payment_currency', 'payment_code']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="code_redeemed"><?= l('admin_settings.webhooks.code_redeemed') ?></label>
        <input id="code_redeemed" type="url" name="code_redeemed" class="form-control" value="<?= settings()->webhooks->code_redeemed ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'email', 'name', 'plan_id', 'plan_expiration_date', 'code_id', 'code', 'code_name', 'redeemed_days']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="contact"><?= l('admin_settings.webhooks.contact') ?></label>
        <input id="contact" type="url" name="contact" class="form-control" value="<?= settings()->webhooks->contact ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['name', 'email', 'subject', 'message']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="cron_start"><?= l('admin_settings.webhooks.cron_start') ?></label>
        <input id="cron_start" type="url" name="cron_start" class="form-control" value="<?= settings()->webhooks->cron_start ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['type']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="cron_end"><?= l('admin_settings.webhooks.cron_end') ?></label>
        <input id="cron_end" type="url" name="cron_end" class="form-control" value="<?= settings()->webhooks->cron_end ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['type']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="domain_new"><?= l('admin_settings.webhooks.domain_new') ?></label>
        <input id="domain_new" type="url" name="domain_new" class="form-control" value="<?= settings()->webhooks->domain_new ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'domain_id', 'host']) . '</code>') ?></small>
    </div>

    <div class="form-group">
        <label for="domain_update"><?= l('admin_settings.webhooks.domain_update') ?></label>
        <input id="domain_update" type="url" name="domain_update" class="form-control" value="<?= settings()->webhooks->domain_update ?>" />
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.webhooks.help'), '<code>' . implode('</code>, <code>', ['user_id', 'domain_id', 'old_host', 'new_host']) . '</code>') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
