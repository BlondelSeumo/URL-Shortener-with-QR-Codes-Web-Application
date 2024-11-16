<?php defined('ALTUMCODE') || die() ?>

<div class="form-group">
    <label for="cron"><?= l('admin_settings.cron.cron') ?></label>
    <div class="input-group">
        <input id="cron" name="cron" type="text" class="form-control" value="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron?key=' . settings()->cron->key ?>" readonly="readonly" />
        <div class="input-group-append">
            <button
                    type="button"
                    class="btn btn-light"
                    data-toggle="tooltip"
                    title="<?= l('global.clipboard_copy') ?>"
                    aria-label="<?= l('global.clipboard_copy') ?>"
                    data-copy="<?= l('global.clipboard_copy') ?>"
                    data-copied="<?= l('global.clipboard_copied') ?>"
                    data-clipboard-text="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron?key=' . settings()->cron->key ?>"
            >
                <i class="fas fa-fw fa-sm fa-copy"></i>
            </button>
        </div>
        <div class="input-group-append">
            <a
                    href="<?= SITE_URL . 'cron?key=' . settings()->cron->key ?>"
                    target="_blank"
                    class="btn btn-light"
                    data-toggle="tooltip"
                    title="<?= l('admin_settings.cron.run_manually') ?>"
            >
                <i class="fas fa-fw fa-sm fa-external-link-alt"></i>
            </a>
        </div>
    </div>
    <small class="form-text text-muted"><?= sprintf(l('admin_settings.cron.last_execution'), isset(settings()->cron->cron_datetime) ? \Altum\Date::get_timeago(settings()->cron->cron_datetime) : '-') ?></small>
</div>

<div class="form-group">
    <label for="cron_broadcasts"><?= l('admin_settings.cron.broadcasts') ?></label>
    <div class="input-group">
        <input id="cron_broadcasts" name="cron_broadcasts" type="text" class="form-control" value="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron/broadcasts?key=' . settings()->cron->key ?>" readonly="readonly" />
        <div class="input-group-append">
            <button
                    type="button"
                    class="btn btn-light"
                    data-toggle="tooltip"
                    title="<?= l('global.clipboard_copy') ?>"
                    aria-label="<?= l('global.clipboard_copy') ?>"
                    data-copy="<?= l('global.clipboard_copy') ?>"
                    data-copied="<?= l('global.clipboard_copied') ?>"
                    data-clipboard-text="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron/broadcasts?key=' . settings()->cron->key ?>"
            >
                <i class="fas fa-fw fa-sm fa-copy"></i>
            </button>
        </div>
        <div class="input-group-append">
            <a
                    href="<?= SITE_URL . 'cron/broadcasts?key=' . settings()->cron->key ?>"
                    target="_blank"
                    class="btn btn-light"
                    data-toggle="tooltip"
                    title="<?= l('admin_settings.cron.run_manually') ?>"
            >
                <i class="fas fa-fw fa-sm fa-external-link-alt"></i>
            </a>
        </div>
    </div>
    <small class="form-text text-muted"><?= sprintf(l('admin_settings.cron.last_execution'), isset(settings()->cron->broadcasts_datetime) ? \Altum\Date::get_timeago(settings()->cron->broadcasts_datetime) : '-') ?></small>
</div>

<div <?= !\Altum\Plugin::is_active('push-notifications') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('push-notifications')->name ?? 'push-notifications') . '"' : null ?>>
    <div class="<?= !\Altum\Plugin::is_active('push-notifications') ? 'container-disabled' : null ?>">
        <div class="form-group">
            <label for="cron_push_notifications"><?= l('admin_settings.cron.push_notifications') ?></label>
            <div class="input-group">
                <input id="cron_push_notifications" name="cron_push_notifications" type="text" class="form-control" value="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron/push_notifications?key=' . settings()->cron->key ?>" readonly="readonly" />
                <div class="input-group-append">
                    <button
                            type="button"
                            class="btn btn-light"
                            data-toggle="tooltip"
                            title="<?= l('global.clipboard_copy') ?>"
                            aria-label="<?= l('global.clipboard_copy') ?>"
                            data-copy="<?= l('global.clipboard_copy') ?>"
                            data-copied="<?= l('global.clipboard_copied') ?>"
                            data-clipboard-text="<?= '* * * * * wget --quiet -O /dev/null ' . SITE_URL . 'cron/push_notifications?key=' . settings()->cron->key ?>"
                    >
                        <i class="fas fa-fw fa-sm fa-copy"></i>
                    </button>
                </div>
                <div class="input-group-append">
                    <a
                            href="<?= SITE_URL . 'cron/push_notifications?key=' . settings()->cron->key ?>"
                            target="_blank"
                            class="btn btn-light"
                            data-toggle="tooltip"
                            title="<?= l('admin_settings.cron.run_manually') ?>"
                    >
                        <i class="fas fa-fw fa-sm fa-external-link-alt"></i>
                    </a>
                </div>
            </div>
            <small class="form-text text-muted"><?= sprintf(l('admin_settings.cron.last_execution'), isset(settings()->cron->push_notifications_datetime) ? \Altum\Date::get_timeago(settings()->cron->push_notifications_datetime) : '-') ?></small>
        </div>
    </div>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
