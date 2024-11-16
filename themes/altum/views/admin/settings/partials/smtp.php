<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="from_name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_settings.smtp.from_name') ?></label>
            <input id="from_name" type="text" name="from_name" class="form-control" value="<?= settings()->smtp->from_name ?>" autocomplete="off" />
            <small class="form-text text-muted"><?= l('admin_settings.smtp.from_name_help') ?></small>
        </div>

        <div class="form-group col-lg-6">
            <label for="from"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.smtp.from') ?></label>
            <input id="from" type="text" name="from" class="form-control" value="<?= settings()->smtp->from ?>" autocomplete="off" />
            <small class="form-text text-muted"><?= l('admin_settings.smtp.from_help') ?></small>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-lg-6">
            <label for="reply_to_name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_settings.smtp.reply_to_name') ?></label>
            <input id="reply_to_name" type="text" name="reply_to_name" class="form-control" value="<?= settings()->smtp->reply_to_name ?>" autocomplete="off" />
            <small class="form-text text-muted"><?= l('admin_settings.smtp.reply_to_name_help') ?></small>
        </div>

        <div class="form-group col-lg-6">
            <label for="reply_to"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('admin_settings.smtp.reply_to') ?></label>
            <input id="reply_to" type="text" name="reply_to" class="form-control" value="<?= settings()->smtp->reply_to ?>" autocomplete="off" />
            <small class="form-text text-muted"><?= l('admin_settings.smtp.reply_to_help') ?></small>
        </div>
    </div>

    <div class="form-group">
        <label for="cc"><i class="fas fa-fw fa-sm fa-mail-bulk text-muted mr-1"></i> <?= l('admin_settings.smtp.cc') ?></label>
        <input id="cc" type="text" name="cc" class="form-control" value="<?= settings()->smtp->cc ?>" autocomplete="off" />
        <small class="form-text text-muted"><?= l('admin_settings.smtp.cc_help') ?></small>
    </div>

    <div class="form-group">
        <label for="bcc"><i class="fas fa-fw fa-sm fa-reply-all text-muted mr-1"></i> <?= l('admin_settings.smtp.bcc') ?></label>
        <input id="bcc" type="text" name="bcc" class="form-control" value="<?= settings()->smtp->bcc ?>" autocomplete="off" />
        <small class="form-text text-muted"><?= l('admin_settings.smtp.bcc_help') ?></small>
    </div>

    <div class="form-group">
        <label for="host"><i class="fas fa-fw fa-sm fa-server text-muted mr-1"></i> <?= l('admin_settings.smtp.host') ?></label>
        <input id="host" type="text" name="host" class="form-control" value="<?= settings()->smtp->host ?>" autocomplete="off" />
        <small class="form-text text-muted"><?= l('admin_settings.smtp.host_help') ?></small>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="encryption"><i class="fas fa-fw fa-sm fa-user-shield text-muted mr-1"></i> <?= l('admin_settings.smtp.encryption') ?></label>
                <select id="encryption" name="encryption" class="custom-select" autocomplete="off">
                    <option value="0" <?= settings()->smtp->encryption == '0' ? 'selected="selected"' : null ?>>None</option>
                    <option value="ssl" <?= settings()->smtp->encryption == 'ssl' ? 'selected="selected"' : null ?>>SSL</option>
                    <option value="tls" <?= settings()->smtp->encryption == 'tls' ? 'selected="selected"' : null ?>>TLS</option>
                </select>
            </div>
        </div>

        <div class="col-md-9">
            <div class="form-group">
                <label for="port"><i class="fas fa-fw fa-sm fa-passport text-muted mr-1"></i> <?= l('admin_settings.smtp.port') ?></label>
                <input id="port" type="text" name="port" class="form-control" value="<?= settings()->smtp->port ?>" autocomplete="off" />
            </div>
        </div>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="auth" name="auth" type="checkbox" class="custom-control-input" <?= settings()->smtp->auth ? 'checked="checked"' : null ?> autocomplete="off">
        <label class="custom-control-label" for="auth"><i class="fas fa-fw fa-sm fa-lock text-muted mr-1"></i> <?= l('admin_settings.smtp.auth') ?></label>
    </div>

    <div class="form-group">
        <label for="username"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_settings.smtp.username') ?></label>
        <input id="username" type="text" name="username" class="form-control" value="<?= settings()->smtp->username ?>" autocomplete="off" />
    </div>

    <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
        <label for="password"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_settings.smtp.password') ?></label>
        <input id="password" type="password" name="password" class="form-control" value="<?= settings()->smtp->password ?>" autocomplete="off" />
    </div>

    <div class="my-3">
        <button type="button" class="btn btn-block btn-outline-info" data-toggle="modal" data-target="#settings_send_test_email_modal"><?= l('admin_settings_send_test_email_modal.header') ?></button>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="display_socials" name="display_socials" type="checkbox" class="custom-control-input" <?= settings()->smtp->display_socials ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="display_socials"><i class="fab fa-fw fa-sm fa-instagram text-muted mr-1"></i> <?= l('admin_settings.smtp.display_socials') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.smtp.display_socials_help') ?></small>
    </div>

    <div class="form-group">
        <label for="company_details"><i class="fas fa-fw fa-sm fa-briefcase text-muted mr-1"></i> <?= l('admin_settings.smtp.company_details') ?></label>
        <textarea id="company_details" name="company_details" class="form-control" autocomplete="off"><?= settings()->smtp->company_details ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.smtp.company_details_help') ?></small>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php ob_start() ?>
<script>
    'use strict';

    /* SMTP */
    let auth_handler = () => {
        if(document.querySelector('input[name="auth"]').checked) {
            document.querySelector('input[name="username"]').removeAttribute('readonly');
            document.querySelector('input[name="password"]').removeAttribute('readonly');
        } else {
            document.querySelector('input[name="username"]').setAttribute('readonly', 'readonly');
            document.querySelector('input[name="password"]').setAttribute('readonly', 'readonly');
        }
    }

    auth_handler();
    document.querySelector('input[name="auth"]').addEventListener('change', auth_handler);

    /* Disable send test email if the smtp fields change & are not saved */
    let fields = {};

    document.querySelectorAll('input').forEach(element => {
        fields[element.name] = element.value;

        element.addEventListener('change', event => {
            if(fields[element.name] !== element.value) {
                document.querySelector('[data-target="#settings_send_test_email_modal"]').classList.add('disabled');
                document.querySelector('[data-target="#settings_send_test_email_modal"]').setAttribute('disabled', 'disabled');
            }
        }) ;
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/settings/settings_send_test_email_modal.php', ['email' => $this->user->email]), 'modals'); ?>
