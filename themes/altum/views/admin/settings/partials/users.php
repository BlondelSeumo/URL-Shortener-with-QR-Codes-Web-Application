<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="register_is_enabled" name="register_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->users->register_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="register_is_enabled"><i class="fas fa-fw fa-sm fa-user-plus text-muted mr-1"></i> <?= l('admin_settings.users.register_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.users.register_is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="register_only_social_logins" name="register_only_social_logins" type="checkbox" class="custom-control-input" <?= settings()->users->register_only_social_logins ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="register_only_social_logins"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.users.register_only_social_logins') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.users.register_only_social_logins_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="register_social_login_require_password" name="register_social_login_require_password" type="checkbox" class="custom-control-input" <?= settings()->users->register_social_login_require_password ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="register_social_login_require_password"><i class="fas fa-fw fa-sm fa-unlock-alt text-muted mr-1"></i> <?= l('admin_settings.users.register_social_login_require_password') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.users.register_social_login_require_password_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="register_display_newsletter_checkbox" name="register_display_newsletter_checkbox" type="checkbox" class="custom-control-input" <?= settings()->users->register_display_newsletter_checkbox ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="register_display_newsletter_checkbox"><i class="fas fa-fw fa-sm fa-newspaper text-muted mr-1"></i> <?= l('admin_settings.users.register_display_newsletter_checkbox') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="login_rememberme_checkbox_is_checked" name="login_rememberme_checkbox_is_checked" type="checkbox" class="custom-control-input" <?= settings()->users->login_rememberme_checkbox_is_checked ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="login_rememberme_checkbox_is_checked"><i class="fas fa-fw fa-sm fa-bookmark text-muted mr-1"></i> <?= l('admin_settings.users.login_rememberme_checkbox_is_checked') ?></label>
    </div>

    <div class="form-group">
        <label for="login_rememberme_cookie_days"><i class="fas fa-fw fa-sm fa-cookie text-muted mr-1"></i> <?= l('admin_settings.users.login_rememberme_cookie_days') ?></label>
        <div class="input-group">
            <input id="login_rememberme_cookie_days" type="number" min="1" name="login_rememberme_cookie_days" class="form-control" value="<?= settings()->users->login_rememberme_cookie_days ?? 30 ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><?= l('global.date.days') ?></span>
            </div>
        </div>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="email_confirmation" name="email_confirmation" type="checkbox" class="custom-control-input" <?= settings()->users->email_confirmation ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="email_confirmation"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.users.email_confirmation') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.users.email_confirmation_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="welcome_email_is_enabled" name="welcome_email_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->users->welcome_email_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="welcome_email_is_enabled"><i class="fas fa-fw fa-sm fa-paper-plane text-muted mr-1"></i> <?= l('admin_settings.users.welcome_email_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.users.welcome_email_is_enabled_help') ?></small>
    </div>

    <div class="form-group">
        <label for="auto_delete_unconfirmed_users"><i class="fas fa-fw fa-sm fa-user-minus text-muted mr-1"></i> <?= l('admin_settings.users.auto_delete_unconfirmed_users') ?></label>
        <div class="input-group">
            <input id="auto_delete_unconfirmed_users" type="number" min="0" name="auto_delete_unconfirmed_users" class="form-control" value="<?= settings()->users->auto_delete_unconfirmed_users ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><?= l('global.date.days') ?></span>
            </div>
        </div>
        <small class="form-text text-muted"><?= l('admin_settings.users.auto_delete_unconfirmed_users_help') ?></small>
    </div>

    <div class="form-group">
        <label for="auto_delete_inactive_users"><i class="fas fa-fw fa-sm fa-users-slash text-muted mr-1"></i> <?= l('admin_settings.users.auto_delete_inactive_users') ?></label>
        <div class="input-group">
            <input id="auto_delete_inactive_users" type="number" min="0" name="auto_delete_inactive_users" class="form-control" value="<?= settings()->users->auto_delete_inactive_users ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><?= l('global.date.days') ?></span>
            </div>
        </div>
        <small class="form-text text-muted"><?= l('admin_settings.users.auto_delete_inactive_users_help') ?></small>
    </div>

    <div class="form-group">
        <label for="user_deletion_reminder"><i class="fas fa-fw fa-sm fa-calendar-minus text-muted mr-1"></i> <?= l('admin_settings.users.user_deletion_reminder') ?></label>
        <div class="input-group">
            <input id="user_deletion_reminder" type="text" max="<?= settings()->users->auto_delete_inactive_users - 1 ?>" name="user_deletion_reminder" class="form-control" value="<?= settings()->users->user_deletion_reminder ?>" />
            <div class="input-group-append">
                <span class="input-group-text"><?= l('global.date.days') ?></span>
            </div>
        </div>
        <small class="form-text text-muted"><?= l('admin_settings.users.user_deletion_reminder_help') ?></small>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#security_container" aria-expanded="false" aria-controls="security_container">
        <i class="fas fa-fw fa-shield-alt fa-sm mr-1"></i> <?= l('admin_settings.users.lockouts') ?>
    </button>

    <div class="collapse" id="security_container">
        <div class="form-group">
            <label for="blacklisted_domains"><i class="fas fa-fw fa-sm fa-ban text-muted mr-1"></i> <?= l('admin_settings.users.blacklisted_domains') ?></label>
            <textarea id="blacklisted_domains" name="blacklisted_domains" class="form-control"><?= implode(',', settings()->users->blacklisted_domains) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.users.blacklisted_domains_help') ?></small>
        </div>

        <div class="form-group">
            <label for="blacklisted_countries"><i class="fas fa-fw fa-sm fa-user-slash text-muted mr-1"></i> <?= l('admin_settings.users.blacklisted_countries') ?></label>
            <select id="blacklisted_countries" name="blacklisted_countries[]" class="custom-select" multiple="multiple">
                <?php foreach(get_countries_array() as $key => $value): ?>
                    <option value="<?= $key ?>" <?= in_array($key, settings()->users->blacklisted_countries ?? []) ? 'selected="selected"' : null ?>><?= $value ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.users.blacklisted_countries_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="login_lockout_is_enabled" name="login_lockout_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->users->login_lockout_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="login_lockout_is_enabled"><i class="fas fa-fw fa-sm fa-shield-alt text-muted mr-1"></i> <?= l('admin_settings.users.login_lockout_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="login_lockout_max_retries"><i class="fas fa-fw fa-sm fa-retweet text-muted mr-1"></i> <?= l('admin_settings.users.login_lockout_max_retries') ?></label>
            <input id="login_lockout_max_retries" type="number" min="2" name="login_lockout_max_retries" class="form-control" value="<?= settings()->users->login_lockout_max_retries ?? 3 ?>" />
        </div>

        <div class="form-group">
            <label for="login_lockout_time"><i class="fas fa-fw fa-sm fa-stopwatch text-muted mr-1"></i> <?= l('admin_settings.users.login_lockout_time') ?></label>
            <div class="input-group">
                <input id="login_lockout_time" type="number" min="1" name="login_lockout_time" class="form-control" value="<?= settings()->users->login_lockout_time ?? 60 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <?= l('global.date.minutes') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="lost_password_lockout_is_enabled" name="lost_password_lockout_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->users->lost_password_lockout_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="lost_password_lockout_is_enabled"><i class="fas fa-fw fa-sm fa-shield-alt text-muted mr-1"></i> <?= l('admin_settings.users.lost_password_lockout_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="lost_password_lockout_max_retries"><i class="fas fa-fw fa-sm fa-retweet text-muted mr-1"></i> <?= l('admin_settings.users.lost_password_lockout_max_retries') ?></label>
            <input id="lost_password_lockout_max_retries" type="number" min="2" name="lost_password_lockout_max_retries" class="form-control" value="<?= settings()->users->lost_password_lockout_max_retries ?? 3 ?>" />
        </div>

        <div class="form-group">
            <label for="lost_password_lockout_time"><i class="fas fa-fw fa-sm fa-stopwatch text-muted mr-1"></i> <?= l('admin_settings.users.lost_password_lockout_time') ?></label>
            <div class="input-group">
                <input id="lost_password_lockout_time" type="number" min="1" name="lost_password_lockout_time" class="form-control" value="<?= settings()->users->lost_password_lockout_time ?? 60 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <?= l('global.date.minutes') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="resend_activation_lockout_is_enabled" name="resend_activation_lockout_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->users->resend_activation_lockout_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="resend_activation_lockout_is_enabled"><i class="fas fa-fw fa-sm fa-shield-alt text-muted mr-1"></i> <?= l('admin_settings.users.resend_activation_lockout_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="resend_activation_lockout_max_retries"><i class="fas fa-fw fa-sm fa-retweet text-muted mr-1"></i> <?= l('admin_settings.users.resend_activation_lockout_max_retries') ?></label>
            <input id="resend_activation_lockout_max_retries" type="number" min="2" name="resend_activation_lockout_max_retries" class="form-control" value="<?= settings()->users->resend_activation_lockout_max_retries ?? 3 ?>" />
        </div>

        <div class="form-group">
            <label for="resend_activation_lockout_time"><i class="fas fa-fw fa-sm fa-stopwatch text-muted mr-1"></i> <?= l('admin_settings.users.resend_activation_lockout_time') ?></label>
            <div class="input-group">
                <input id="resend_activation_lockout_time" type="number" min="1" name="resend_activation_lockout_time" class="form-control" value="<?= settings()->users->resend_activation_lockout_time ?? 60 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <?= l('global.date.minutes') ?>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="register_lockout_is_enabled" name="register_lockout_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->users->register_lockout_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="register_lockout_is_enabled"><i class="fas fa-fw fa-sm fa-shield-alt text-muted mr-1"></i> <?= l('admin_settings.users.register_lockout_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="register_lockout_max_registrations"><i class="fas fa-fw fa-sm fa-retweet text-muted mr-1"></i> <?= l('admin_settings.users.register_lockout_max_registrations') ?></label>
            <input id="register_lockout_max_registrations" type="number" min="1" name="register_lockout_max_registrations" class="form-control" value="<?= settings()->users->register_lockout_max_registrations ?? 1 ?>" />
        </div>

        <div class="form-group">
            <label for="register_lockout_time"><i class="fas fa-fw fa-sm fa-stopwatch text-muted mr-1"></i> <?= l('admin_settings.users.register_lockout_time') ?></label>
            <div class="input-group">
                <input id="register_lockout_time" type="number" min="1" max="30" name="register_lockout_time" class="form-control" value="<?= settings()->users->register_lockout_time ?? 30 ?>" />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <?= l('global.date.days') ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
