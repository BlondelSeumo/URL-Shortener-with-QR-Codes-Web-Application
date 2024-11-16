<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="users_is_enabled" name="users_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->users_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="users_is_enabled"><i class="fas fa-fw fa-sm fa-users text-muted mr-1"></i> <?= l('admin_settings.internal_notifications.users_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="admins_is_enabled" name="admins_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->admins_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="admins_is_enabled"><i class="fas fa-fw fa-sm fa-user-tie text-muted mr-1"></i> <?= l('admin_settings.internal_notifications.admins_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="new_user" name="new_user" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->new_user ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="new_user"><?= l('admin_settings.internal_notifications.new_user') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="delete_user" name="delete_user" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->delete_user ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="delete_user"><?= l('admin_settings.internal_notifications.delete_user') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="new_newsletter_subscriber" name="new_newsletter_subscriber" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->new_newsletter_subscriber ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="new_newsletter_subscriber"><?= l('admin_settings.internal_notifications.new_newsletter_subscriber') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="new_payment" name="new_payment" type="checkbox" class="custom-control-input" <?= settings()->internal_notifications->new_payment ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="new_payment"><?= l('admin_settings.internal_notifications.new_payment') ?></label>
    </div>

    <div <?= \Altum\Plugin::is_active('affiliate') ? null : 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('affiliate')->name ?? 'affiliate') . '"' ?>>
        <div class="form-group custom-control custom-switch <?= \Altum\Plugin::is_active('affiliate') ? null : 'container-disabled' ?>">
            <input id="new_affiliate_withdrawal" name="new_affiliate_withdrawal" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('affiliate') && settings()->internal_notifications->new_affiliate_withdrawal ? 'checked="checked"' : null ?> <?= \Altum\Plugin::is_active('affiliate') ? null : 'disabled="disabled"' ?>>
            <label class="custom-control-label" for="new_affiliate_withdrawal"><?= l('admin_settings.internal_notifications.new_affiliate_withdrawal') ?></label>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
