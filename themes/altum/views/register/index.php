<?php defined('ALTUMCODE') || die() ?>

<?= \Altum\Alerts::output_alerts() ?>

<h1 class="h5"><?= l('register.header') ?></h1>

<form action="" method="post" class="mt-4" role="form">
    <?php if(!settings()->users->register_only_social_logins): ?>
        <div class="form-group">
            <label for="name"><?= l('global.name') ?></label>
            <input id="name" type="text" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->values['name'] ?>" maxlength="32" required="required" autofocus="autofocus" />
            <?= \Altum\Alerts::output_field_error('name') ?>
        </div>

        <div class="form-group">
            <label for="email"><?= l('global.email') ?></label>
            <input id="email" type="email" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->values['email'] ?>" maxlength="128" required="required" />
            <?= \Altum\Alerts::output_field_error('email') ?>
        </div>

        <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
            <label for="password"><?= l('global.password') ?></label>
            <input id="password" type="password" name="password" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" value="<?= $data->values['password'] ?>" required="required" />
            <?= \Altum\Alerts::output_field_error('password') ?>
        </div>

        <?php if(settings()->captcha->register_is_enabled): ?>
            <div class="form-group">
                <?php $data->captcha->display() ?>
            </div>
        <?php endif ?>

        <div class="custom-control custom-checkbox">
            <input type="checkbox" name="accept" class="custom-control-input" id="accept" required="required">
            <label class="custom-control-label" for="accept">
                <small class="text-muted">
                    <?= sprintf(
                        l('register.form.accept'),
                        '<a href="' . settings()->main->terms_and_conditions_url . '" target="_blank">' . l('global.terms_and_conditions') . '</a>',
                        '<a href="' . settings()->main->privacy_policy_url . '" target="_blank">' . l('global.privacy_policy') . '</a>'
                    ) ?>
                </small>
            </label>
        </div>

        <?php if(settings()->users->register_display_newsletter_checkbox): ?>
            <div class="mt-3 custom-control custom-checkbox">
                <input type="checkbox" name="is_newsletter_subscribed" class="custom-control-input" id="is_newsletter_subscribed">
                <label class="custom-control-label" for="is_newsletter_subscribed">
                    <small class="text-muted">
                        <?= l('register.form.is_newsletter_subscribed') ?>
                    </small>
                </label>
            </div>
        <?php endif ?>

        <div class="form-group mt-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block" <?= isset($_COOKIE['register_lockout']) ? 'disabled="disabled"' : null ?>><?= l('register.form.register') ?></button>
        </div>
    <?php endif ?>

    <?php if(settings()->facebook->is_enabled || settings()->google->is_enabled || settings()->twitter->is_enabled || settings()->discord->is_enabled || settings()->linkedin->is_enabled || settings()->microsoft->is_enabled): ?>
        <hr class="border-gray-100 my-3" />

        <div>
            <?php if(settings()->facebook->is_enabled): ?>
                <div class="mt-2">
                    <a href="<?= url('login/facebook-initiate') ?>" class="btn btn-light btn-block">
                        <img src="<?= ASSETS_FULL_URL . 'images/facebook.svg' ?>" class="mr-1" />
                        <?= l('login.display.facebook') ?>
                    </a>
                </div>
            <?php endif ?>
            <?php if(settings()->google->is_enabled): ?>
                <div class="mt-2">
                    <a href="<?= url('login/google-initiate') ?>" class="btn btn-light btn-block">
                        <img src="<?= ASSETS_FULL_URL . 'images/google.svg' ?>" class="mr-1" />
                        <?= l('login.display.google') ?>
                    </a>
                </div>
            <?php endif ?>
            <?php if(settings()->twitter->is_enabled): ?>
                <div class="mt-2">
                    <a href="<?= url('login/twitter-initiate') ?>" class="btn btn-light btn-block">
                        <img src="<?= ASSETS_FULL_URL . 'images/x.svg' ?>" class="mr-1" />
                        <?= l('login.display.twitter') ?>
                    </a>
                </div>
            <?php endif ?>
            <?php if(settings()->discord->is_enabled): ?>
                <div class="mt-2">
                    <a href="<?= url('login/discord-initiate') ?>" class="btn btn-light btn-block">
                        <img src="<?= ASSETS_FULL_URL . 'images/discord.svg' ?>" class="mr-1" />
                        <?= l('login.display.discord') ?>
                    </a>
                </div>
            <?php endif ?>
            <?php if(settings()->linkedin->is_enabled): ?>
                <div class="mt-2">
                    <a href="<?= url('login/linkedin-initiate') ?>" class="btn btn-light btn-block">
                        <img src="<?= ASSETS_FULL_URL . 'images/linkedin.svg' ?>" class="mr-1" />
                        <?= l('login.display.linkedin') ?>
                    </a>
                </div>
            <?php endif ?>
            <?php if(settings()->microsoft->is_enabled): ?>
                <div class="mt-2">
                    <a href="<?= url('login/microsoft-initiate') ?>" class="btn btn-light btn-block">
                        <img src="<?= ASSETS_FULL_URL . 'images/microsoft.svg' ?>" class="mr-1" />
                        <?= l('login.display.microsoft') ?>
                    </a>
                </div>
            <?php endif ?>
        </div>
    <?php endif ?>
</form>


<div class="mt-5 text-center text-muted">
    <?= sprintf(l('register.display.login'), '<a href="' . url('login' . $data->redirect_append) . '" class="font-weight-bold">' . l('register.display.login_help') . '</a>') ?></a>
</div>
