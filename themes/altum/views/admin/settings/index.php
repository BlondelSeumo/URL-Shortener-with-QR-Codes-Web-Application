<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-wrench text-primary-900 mr-2"></i> <?= sprintf(l('admin_settings.header'), l('admin_settings.' . $data->method . '.tab')) ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="row">
    <div class="mb-3 mb-xl-5 mb-xl-0 col-12 col-xl-4">
        <div class="d-xl-none">
            <select name="settings_menu" class="custom-select">
                <option value="<?= url('admin/settings/main') ?>" class="nav-link" <?= $data->method == 'main' ? 'selected="selected"' : null ?>><?= l('admin_settings.main.tab') ?></option>
                <option value="<?= url('admin/settings/users') ?>" class="nav-link" <?= $data->method == 'users' ? 'selected="selected"' : null ?>><?= l('admin_settings.users.tab') ?></option>
                <option value="<?= url('admin/settings/content') ?>" class="nav-link" <?= $data->method == 'content' ? 'selected="selected"' : null ?>><?= l('admin_settings.content.tab') ?></option>
                <option value="<?= url('admin/settings/links') ?>" class="nav-link" <?= $data->method == 'links' ? 'selected="selected"' : null ?>><?= l('admin_settings.links.tab') ?></option>
                <option value="<?= url('admin/settings/tools') ?>" class="nav-link" <?= $data->method == 'tools' ? 'selected="selected"' : null ?>><?= l('admin_settings.tools.tab') ?></option>
                <option value="<?= url('admin/settings/codes') ?>" class="nav-link" <?= $data->method == 'codes' ? 'selected="selected"' : null ?>><?= l('admin_settings.codes.tab') ?></option>
                <?php if(\Altum\Plugin::is_active('email-signatures')): ?>
                <option value="<?= url('admin/settings/signatures') ?>" class="nav-link" <?= $data->method == 'signatures' ? 'selected="selected"' : null ?>><?= l('admin_settings.signatures.tab') ?></option>
                <?php endif ?>
                <?php if(\Altum\Plugin::is_active('aix')): ?>
                    <option value="<?= url('admin/settings/aix') ?>" class="nav-link" <?= $data->method == 'aix' ? 'selected="selected"' : null ?>><?= l('admin_settings.aix.tab') ?></option>
                <?php endif ?>
                <option value="<?= url('admin/settings/payment') ?>" class="nav-link" <?= $data->method == 'payment' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.tab') ?></option>
                <option value="<?= url('admin/settings/business') ?>" class="nav-link" <?= $data->method == 'business' ? 'selected="selected"' : null ?>><?= l('admin_settings.business.tab') ?></option>
                <?php foreach($data->payment_processors as $key => $value): ?>
                    <option value="<?= url('admin/settings/' . $key) ?>" class="nav-link" <?= $data->method == $key ? 'selected="selected"' : null ?>><?= l('admin_settings.' . $key . '.tab') ?></option>
                <?php endforeach ?>
                <option value="<?= url('admin/settings/affiliate') ?>" class="nav-link" <?= $data->method == 'affiliate' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.tab') ?></option>
                <option value="<?= url('admin/settings/captcha') ?>" class="nav-link" <?= $data->method == 'captcha' ? 'selected="selected"' : null ?>><?= l('admin_settings.captcha.tab') ?></option>
                <option value="<?= url('admin/settings/facebook') ?>" class="nav-link" <?= $data->method == 'facebook' ? 'selected="selected"' : null ?>><?= l('admin_settings.facebook.tab') ?></option>
                <option value="<?= url('admin/settings/google') ?>" class="nav-link" <?= $data->method == 'google' ? 'selected="selected"' : null ?>><?= l('admin_settings.google.tab') ?></option>
                <option value="<?= url('admin/settings/twitter') ?>" class="nav-link" <?= $data->method == 'twitter' ? 'selected="selected"' : null ?>><?= l('admin_settings.twitter.tab') ?></option>
                <option value="<?= url('admin/settings/discord') ?>" class="nav-link" <?= $data->method == 'discord' ? 'selected="selected"' : null ?>><?= l('admin_settings.discord.tab') ?></option>
                <option value="<?= url('admin/settings/linkedin') ?>" class="nav-link" <?= $data->method == 'linkedin' ? 'selected="selected"' : null ?>><?= l('admin_settings.linkedin.tab') ?></option>
                <option value="<?= url('admin/settings/microsoft') ?>" class="nav-link" <?= $data->method == 'microsoft' ? 'selected="selected"' : null ?>><?= l('admin_settings.microsoft.tab') ?></option>
                <option value="<?= url('admin/settings/ads') ?>" class="nav-link" <?= $data->method == 'ads' ? 'selected="selected"' : null ?>><?= l('admin_settings.ads.tab') ?></option>
                <option value="<?= url('admin/settings/cookie_consent') ?>" class="nav-link" <?= $data->method == 'cookie_consent' ? 'selected="selected"' : null ?>><?= l('admin_settings.cookie_consent.tab') ?></option>
                <option value="<?= url('admin/settings/socials') ?>" class="nav-link" <?= $data->method == 'socials' ? 'selected="selected"' : null ?>><?= l('admin_settings.socials.tab') ?></option>
                <option value="<?= url('admin/settings/smtp') ?>" class="nav-link" <?= $data->method == 'smtp' ? 'selected="selected"' : null ?>><?= l('admin_settings.smtp.tab') ?></option>
                <option value="<?= url('admin/settings/theme') ?>" class="nav-link" <?= $data->method == 'theme' ? 'selected="selected"' : null ?>><?= l('admin_settings.theme.tab') ?></option>
                <option value="<?= url('admin/settings/custom') ?>" class="nav-link" <?= $data->method == 'custom' ? 'selected="selected"' : null ?>><?= l('admin_settings.custom.tab') ?></option>
                <option value="<?= url('admin/settings/announcements') ?>" class="nav-link" <?= $data->method == 'announcements' ? 'selected="selected"' : null ?>><?= l('admin_settings.announcements.tab') ?></option>
                <option value="<?= url('admin/settings/internal_notifications') ?>" class="nav-link" <?= $data->method == 'internal_notifications' ? 'selected="selected"' : null ?>><?= l('admin_settings.internal_notifications.tab') ?></option>
                <option value="<?= url('admin/settings/email_notifications') ?>" class="nav-link" <?= $data->method == 'email_notifications' ? 'selected="selected"' : null ?>><?= l('admin_settings.email_notifications.tab') ?></option>
                <option value="<?= url('admin/settings/push_notifications') ?>" class="nav-link" <?= $data->method == 'push_notifications' ? 'selected="selected"' : null ?>><?= l('admin_settings.push_notifications.tab') ?></option>
                <option value="<?= url('admin/settings/webhooks') ?>" class="nav-link" <?= $data->method == 'webhooks' ? 'selected="selected"' : null ?>><?= l('admin_settings.webhooks.tab') ?></option>
                <option value="<?= url('admin/settings/offload') ?>" class="nav-link" <?= $data->method == 'offload' ? 'selected="selected"' : null ?>><?= l('admin_settings.offload.tab') ?></option>
                <option value="<?= url('admin/settings/pwa') ?>" class="nav-link" <?= $data->method == 'pwa' ? 'selected="selected"' : null ?>><?= l('admin_settings.pwa.tab') ?></option>
                <option value="<?= url('admin/settings/sso') ?>" class="nav-link" <?= $data->method == 'sso' ? 'selected="selected"' : null ?>><?= l('admin_settings.sso.tab') ?></option>
                <option value="<?= url('admin/settings/cron') ?>" class="nav-link" <?= $data->method == 'cron' ? 'selected="selected"' : null ?>><?= l('admin_settings.cron.tab') ?></option>
                <option value="<?= url('admin/settings/health') ?>" class="nav-link" <?= $data->method == 'health' ? 'selected="selected"' : null ?>><?= l('admin_settings.health.tab') ?></option>
                <option value="<?= url('admin/settings/cache') ?>" class="nav-link" <?= $data->method == 'cache' ? 'selected="selected"' : null ?>><?= l('admin_settings.cache.tab') ?></option>
                <option value="<?= url('admin/settings/license') ?>" class="nav-link" <?= $data->method == 'license' ? 'selected="selected"' : null ?>><?= l('admin_settings.license.tab') ?></option>
                <option value="<?= url('admin/settings/support') ?>" class="nav-link" <?= $data->method == 'support' ? 'selected="selected"' : null ?>><?= l('admin_settings.support.tab') ?></option>
            </select>
        </div>

        <?php ob_start() ?>
        <script>
            document.querySelector('select[name="settings_menu"]').addEventListener('change', event => {
                document.querySelector(`a[href="${event.currentTarget.value}"]`).click();
            })
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

        <div class="card d-none d-xl-flex">
            <div class="card-body">
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    <a class="nav-link <?= $data->method == 'main' ? 'active' : null ?>" href="<?= url('admin/settings/main') ?>"><i class="fas fa-fw fa-sm fa-home mr-2"></i> <?= l('admin_settings.main.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'users' ? 'active' : null ?>" href="<?= url('admin/settings/users') ?>"><i class="fas fa-fw fa-sm fa-users mr-2"></i> <?= l('admin_settings.users.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'content' ? 'active' : null ?>" href="<?= url('admin/settings/content') ?>"><i class="fas fa-fw fa-sm fa-blog mr-2"></i> <?= l('admin_settings.content.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'links' ? 'active' : null ?>" href="<?= url('admin/settings/links') ?>"><i class="fas fa-fw fa-sm fa-link mr-2"></i> <?= l('admin_settings.links.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'tools' ? 'active' : null ?>" href="<?= url('admin/settings/tools') ?>"><i class="fas fa-fw fa-sm fa-tools mr-2"></i> <?= l('admin_settings.tools.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'codes' ? 'active' : null ?>" href="<?= url('admin/settings/codes') ?>"><i class="fas fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('admin_settings.codes.tab') ?></a>
                    <?php if(\Altum\Plugin::is_active('email-signatures')): ?>
                        <a class="nav-link <?= $data->method == 'signatures' ? 'active' : null ?>" href="<?= url('admin/settings/signatures') ?>"><i class="fas fa-fw fa-sm fa-file-signature mr-2"></i> <?= l('admin_settings.signatures.tab') ?></a>
                    <?php endif ?>
                    <?php if(\Altum\Plugin::is_active('aix')): ?>
                        <a class="nav-link <?= $data->method == 'aix' ? 'active' : null ?>" href="<?= url('admin/settings/aix') ?>"><i class="fas fa-fw fa-sm fa-robot mr-2"></i> <?= l('admin_settings.aix.tab') ?></a>
                    <?php endif ?>
                    <a class="nav-link <?= $data->method == 'payment' ? 'active' : null ?>" href="<?= url('admin/settings/payment') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('admin_settings.payment.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'business' ? 'active' : null ?>" href="<?= url('admin/settings/business') ?>"><i class="fas fa-fw fa-sm fa-briefcase mr-2"></i> <?= l('admin_settings.business.tab') ?></a>

                    <a class="nav-link <?= array_key_exists($data->method, $data->payment_processors) ? 'active' : null ?>" data-toggle="collapse" href="#payment_processors_collapse">
                        <i class="fas fa-fw fa-sm fa-piggy-bank mr-2"></i> <?= l('admin_settings.payment_processors') ?> <i class="fas fa-fw fa-sm fa-caret-down"></i>
                    </a>
                    <div class="mt-1 bg-gray-200 rounded collapse <?= array_key_exists($data->method, $data->payment_processors) ? 'show' : null ?>" id="payment_processors_collapse">
                        <div>
                            <?php foreach($data->payment_processors as $key => $value): ?>
                                <a class="nav-link <?= $data->method == $key ? 'active' : null ?>" href="<?= url('admin/settings/' . $key) ?>"><i class="<?= $value['icon'] ?> fa-fw fa-sm mr-2"></i> <?= l('admin_settings.' . $key . '.tab') ?></a>
                            <?php endforeach ?>
                        </div>
                    </div>

                    <a class="nav-link <?= $data->method == 'affiliate' ? 'active' : null ?>" href="<?= url('admin/settings/affiliate') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('admin_settings.affiliate.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'captcha' ? 'active' : null ?>" href="<?= url('admin/settings/captcha') ?>"><i class="fas fa-fw fa-sm fa-low-vision mr-2"></i> <?= l('admin_settings.captcha.tab') ?></a>

                    <a class="nav-link <?= in_array($data->method, ['facebook', 'google', 'twitter', 'discord', 'linkedin', 'microsoft']) ? 'active' : null ?>" data-toggle="collapse" href="#social_logins_collapse">
                        <i class="fas fa-fw fa-sm fa-share-alt mr-2"></i> <?= l('admin_settings.social_logins') ?> <i class="fas fa-fw fa-sm fa-caret-down"></i>
                    </a>
                    <div class="mt-1 bg-gray-200 rounded collapse <?= in_array($data->method, ['facebook', 'google', 'twitter', 'discord', 'linkedin', 'microsoft']) ? 'show' : null ?>" id="social_logins_collapse">
                        <div>
                            <a class="nav-link <?= $data->method == 'facebook' ? 'active' : null ?>" href="<?= url('admin/settings/facebook') ?>"><i class="fab fa-fw fa-sm fa-facebook mr-2"></i> <?= l('admin_settings.facebook.tab') ?></a>
                            <a class="nav-link <?= $data->method == 'google' ? 'active' : null ?>" href="<?= url('admin/settings/google') ?>"><i class="fab fa-fw fa-sm fa-google mr-2"></i> <?= l('admin_settings.google.tab') ?></a>
                            <a class="nav-link <?= $data->method == 'twitter' ? 'active' : null ?>" href="<?= url('admin/settings/twitter') ?>"><i class="fab fa-fw fa-sm fa-twitter mr-2"></i> <?= l('admin_settings.twitter.tab') ?></a>
                            <a class="nav-link <?= $data->method == 'discord' ? 'active' : null ?>" href="<?= url('admin/settings/discord') ?>"><i class="fab fa-fw fa-sm fa-discord mr-2"></i> <?= l('admin_settings.discord.tab') ?></a>
                            <a class="nav-link <?= $data->method == 'linkedin' ? 'active' : null ?>" href="<?= url('admin/settings/linkedin') ?>"><i class="fab fa-fw fa-sm fa-linkedin mr-2"></i> <?= l('admin_settings.linkedin.tab') ?></a>
                            <a class="nav-link <?= $data->method == 'microsoft' ? 'active' : null ?>" href="<?= url('admin/settings/microsoft') ?>"><i class="fab fa-fw fa-sm fa-microsoft mr-2"></i> <?= l('admin_settings.microsoft.tab') ?></a>
                        </div>
                    </div>

                    <a class="nav-link <?= $data->method == 'ads' ? 'active' : null ?>" href="<?= url('admin/settings/ads') ?>"><i class="fas fa-fw fa-sm fa-ad mr-2"></i> <?= l('admin_settings.ads.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'cookie_consent' ? 'active' : null ?>" href="<?= url('admin/settings/cookie_consent') ?>"><i class="fas fa-fw fa-sm fa-cookie mr-2"></i> <?= l('admin_settings.cookie_consent.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'socials' ? 'active' : null ?>" href="<?= url('admin/settings/socials') ?>"><i class="fab fa-fw fa-sm fa-instagram mr-2"></i> <?= l('admin_settings.socials.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'smtp' ? 'active' : null ?>" href="<?= url('admin/settings/smtp') ?>"><i class="fas fa-fw fa-sm fa-mail-bulk mr-2"></i> <?= l('admin_settings.smtp.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'theme' ? 'active' : null ?>" href="<?= url('admin/settings/theme') ?>"><i class="fas fa-fw fa-sm fa-palette mr-2"></i> <?= l('admin_settings.theme.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'custom' ? 'active' : null ?>" href="<?= url('admin/settings/custom') ?>"><i class="fas fa-fw fa-sm fa-paint-brush mr-2"></i> <?= l('admin_settings.custom.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'announcements' ? 'active' : null ?>" href="<?= url('admin/settings/announcements') ?>"><i class="fas fa-fw fa-sm fa-bullhorn mr-2"></i> <?= l('admin_settings.announcements.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'internal_notifications' ? 'active' : null ?>" href="<?= url('admin/settings/internal_notifications') ?>"><i class="fas fa-fw fa-sm fa-bell mr-2"></i> <?= l('admin_settings.internal_notifications.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'email_notifications' ? 'active' : null ?>" href="<?= url('admin/settings/email_notifications') ?>"><i class="fas fa-fw fa-sm fa-envelope mr-2"></i> <?= l('admin_settings.email_notifications.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'push_notifications' ? 'active' : null ?>" href="<?= url('admin/settings/push_notifications') ?>"><i class="fas fa-fw fa-sm fa-bolt-lightning mr-2"></i> <?= l('admin_settings.push_notifications.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'webhooks' ? 'active' : null ?>" href="<?= url('admin/settings/webhooks') ?>"><i class="fas fa-fw fa-sm fa-code-branch mr-2"></i> <?= l('admin_settings.webhooks.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'offload' ? 'active' : null ?>" href="<?= url('admin/settings/offload') ?>"><i class="fas fa-fw fa-sm fa-cloud mr-2"></i> <?= l('admin_settings.offload.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'pwa' ? 'active' : null ?>" href="<?= url('admin/settings/pwa') ?>"><i class="fas fa-fw fa-sm fa-rocket mr-2"></i> <?= l('admin_settings.pwa.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'sso' ? 'active' : null ?>" href="<?= url('admin/settings/sso') ?>"><i class="fas fa-fw fa-sm fa-random mr-2"></i> <?= l('admin_settings.sso.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'cron' ? 'active' : null ?>" href="<?= url('admin/settings/cron') ?>"><i class="fas fa-fw fa-sm fa-sync mr-2"></i> <?= l('admin_settings.cron.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'health' ? 'active' : null ?>" href="<?= url('admin/settings/health') ?>"><i class="fas fa-fw fa-sm fa-heartbeat mr-2"></i> <?= l('admin_settings.health.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'cache' ? 'active' : null ?>" href="<?= url('admin/settings/cache') ?>"><i class="fas fa-fw fa-sm fa-database mr-2"></i> <?= l('admin_settings.cache.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'license' ? 'active' : null ?>" href="<?= url('admin/settings/license') ?>"><i class="fas fa-fw fa-sm fa-key mr-2"></i> <?= l('admin_settings.license.tab') ?></a>
                    <a class="nav-link <?= $data->method == 'support' ? 'active' : null ?>" href="<?= url('admin/settings/support') ?>"><i class="fas fa-fw fa-sm fa-life-ring mr-2"></i> <?= l('admin_settings.support.tab') ?></a>
                </div>
            </div>
        </div>
    </div>

    <div class="col">
        <div class="card">
            <div class="card-body">

                <form action="<?= url('admin/settings/' . $data->method) ?>" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                    <?= $this->views['method'] ?>
                </form>

            </div>
        </div>

        <div class="alert alert-info my-3"><?= l('admin_settings.documentation') ?></div>
    </div>
</div>
