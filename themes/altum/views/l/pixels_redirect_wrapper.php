<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $data->cloaking && $data->cloaking->cloaking_is_enabled ? $data->cloaking->cloaking_title : null ?></title>

        <base href="<?= SITE_URL; ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(!empty($data->cloaking->cloaking_favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicons') . $data->cloaking->cloaking_favicon ?>" rel="icon" />
        <?php elseif(!empty(settings()->main->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicon') . settings()->main->favicon ?>" rel="icon" />
        <?php endif ?>

        <?php if(!empty($data->cloaking->cloaking_opengraph)): ?>
            <?php \Altum\Meta::output() ?>
        <?php endif ?>

        <?= \Altum\Event::get_content('head') ?>
    </head>

    <body>
        <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>

        <?php if($data->cloaking): ?>
            <iframe id="iframe" src="<?= $data->location_url ?>" style="position:fixed; top:0; left:0; bottom:0; right:0; width:100%; height:100%; border:none; margin:0; padding:0; overflow:hidden; z-index: 1;"></iframe>
        <?php endif ?>

        <?= count($data->pixels) ? $this->views['pixels'] : null ?>

        <?php if(!$data->cloaking): ?>
            <style>
                body, html {
                    height: 100%;
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }

                .spinner-border {
                    display: inline-block;
                    width: 2rem;
                    height: 2rem;
                    vertical-align: -.125em;
                    border: .25em solid currentcolor;
                    border-right-color: transparent;
                    border-radius: 50%;
                    -webkit-animation: .75s linear infinite spinner-border;
                    animation: .75s linear infinite spinner-border;
                }

                @keyframes spinner-border {
                    100% {
                        transform: rotate(360deg);
                    }
                }
            </style>

            <div class="spinner-border"></div>

            <?php ob_start() ?>
            <script>
                'use strict';

                let app_linking_location_url = <?=  json_encode($data->app_linking_location_url ?: null) ?>;
                let location_url = <?= json_encode($data->location_url) ?>;

                <?php if(settings()->cookie_consent->is_enabled): ?>
                    window.addEventListener('cc:onFirstConsent', (detail) => {
                        if(app_linking_location_url) window.location = app_linking_location_url;

                        setTimeout(() => {
                            window.location = location_url;
                        }, 650);
                    });

                    if(get_cookie('cc_cookie')) {
                        if(app_linking_location_url) window.location = app_linking_location_url;

                        setTimeout(() => {
                            window.location = location_url;
                        }, 650);
                    }
                <?php else: ?>
                    if(app_linking_location_url) window.location = app_linking_location_url;

                    setTimeout(() => {
                        window.location = location_url;
                    }, 650);
                <?php endif ?>
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        <?php endif ?>

        <?php if(settings()->cookie_consent->is_enabled): ?>
            <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

            <?php foreach(['custom.js'] as $file): ?>
                <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
            <?php endforeach ?>
        <?php endif ?>

        <?= \Altum\Event::get_content('javascript') ?>
    </body>
</html>
