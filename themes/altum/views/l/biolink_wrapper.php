<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" class="link-html" dir="<?= l('direction') ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
            <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>
            <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . \Altum\Uploads::get_path('pwa') . 'manifest.json' ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>

        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <?php \Altum\Meta::output() ?>

        <?php if(\Altum\Meta::$canonical): ?>
            <link rel="canonical" href="<?= \Altum\Meta::$canonical ?>" />
        <?php endif ?>

        <?php
        /* Block search engine indexing if the user wants, and if the system viewing links (for preview) are used */
        if($this->link->settings->seo->block ?? null || \Altum\Router::$original_request == 'l/link'):
        ?>
            <meta name="robots" content="noindex">
        <?php endif ?>

        <?php if(!empty($this->link->settings->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicons') . $this->link->settings->favicon ?>" rel="icon" />
        <?php elseif(!empty(settings()->main->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicon') . settings()->main->favicon ?>" rel="icon" />
        <?php endif ?>

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['custom.css', 'link-custom.css', 'animate.min.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>

        <?php if($this->link->settings->font ?? null): ?>
            <?php $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php' ?>
            <?php if($biolink_fonts[$this->link->settings->font]['font_css_url']): ?>
                <link href="<?= $biolink_fonts[$this->link->settings->font]['font_css_url'] ?>" rel="stylesheet">
            <?php endif ?>

            <?php if($biolink_fonts[$this->link->settings->font]['font-family']): ?>
                <style>html, body {font-family: <?= $biolink_fonts[$this->link->settings->font]['font-family'] ?>, "Helvetica Neue", Arial, sans-serif !important;}</style>
            <?php endif ?>
        <?php endif ?>
        <style>
            html {
                font-size: <?= (int) ($this->link->settings->font_size ?? 16) . 'px' ?> !important;
                <?php if(isset($_GET['preview_template'])) echo 'zoom: 75%'; ?>
            }
        </style>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js_biolink)): ?>
            <?= get_settings_custom_head_js('head_js_biolink') ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css_biolink)): ?>
            <style><?= settings()->custom->head_css_biolink ?></style>
        <?php endif ?>

        <?php if(!empty($this->link->settings->custom_css) && $this->user->plan_settings->custom_css_is_enabled): ?>
            <style><?= $this->link->settings->custom_css ?></style>
        <?php endif ?>

        <?php if(!empty($this->link->settings->custom_js) && $this->user->plan_settings->custom_js_is_enabled): ?>
            <?= $this->link->settings->custom_js ?>
        <?php endif ?>
    </head>

    <?php if(!isset($_GET['preview_template'], $_GET['preview'])): ?>
        <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>
    <?php endif ?>

    <?php if(!$this->is_preview): ?>
        <?php if(!$this->user->plan_settings->no_ads): ?>
            <?php require THEME_PATH . 'views/partials/ad_blocker_detector.php' ?>
        <?php endif ?>
    <?php endif ?>

    <?= $this->views['content'] ?>

    <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

    <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js',] as $file): ?>
        <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>

    <?= \Altum\Event::get_content('javascript') ?>
</html>
