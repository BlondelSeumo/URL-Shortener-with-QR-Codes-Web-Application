<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" class="link-html" dir="<?= l('direction') ?>">
    <head>
        <title><?= \Altum\Title::get() ?></title>
        <base href="<?= SITE_URL; ?>">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

        <?php if(\Altum\Meta::$description): ?>
            <meta name="description" content="<?= \Altum\Meta::$description ?>" />
        <?php endif ?>
        <?php if(\Altum\Meta::$keywords): ?>
            <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
        <?php endif ?>

        <?php \Altum\Meta::output() ?>

        <?php
        /* Block search engine indexing if the user wants, and if the system viewing links (for preview) are used */
        if($this->link->settings->seo->block ?? null || \Altum\Router::$original_request == 'l/link'):
        ?>
            <meta name="robots" content="noindex">
        <?php endif ?>

        <?php if(!empty($data->splash_page->settings->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('splash_pages') . $data->splash_page->settings->favicon ?>" rel="icon" />
        <?php elseif(!empty($this->link->settings->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicons') . $this->link->settings->favicon ?>" rel="icon" />
        <?php elseif(!empty(settings()->main->favicon)): ?>
            <link href="<?= \Altum\Uploads::get_full_url('favicon') . settings()->main->favicon ?>" rel="icon" />
        <?php endif ?>

        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['custom.css', 'link-custom.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>

        <?= \Altum\Event::get_content('head') ?>

        <?php if(!empty(settings()->custom->head_js_splash_page)): ?>
            <?= get_settings_custom_head_js('head_js_splash_page') ?>
        <?php endif ?>

        <?php if(!empty(settings()->custom->head_css_splash_page)): ?>
            <style><?= settings()->custom->head_css_splash_page ?></style>
        <?php endif ?>

        <?php if($data->splash_page && !empty($data->splash_page->settings->custom_css) && $this->user->plan_settings->custom_css_is_enabled): ?>
            <style><?= $data->splash_page->settings->custom_css ?></style>
        <?php endif ?>

        <?php if($data->splash_page && !empty($data->splash_page->settings->custom_js) && $this->user->plan_settings->custom_js_is_enabled): ?>
            <?= $data->splash_page->settings->custom_js ?>
        <?php endif ?>
    </head>

    <body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">
        <?php if(!empty(settings()->custom->body_content_splash_page)): ?>
            <?= settings()->custom->body_content_splash_page ?>
        <?php endif ?>

        <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>

        <main class="altum-animate altum-animate-fill-none altum-animate-fade-in mt-5 mt-lg-8">
            <?php require THEME_PATH . 'views/l/partials/ads_header_splash.php' ?>

            <?= $this->views['content'] ?>

            <?php require THEME_PATH . 'views/l/partials/ads_footer_splash.php' ?>
        </main>
    </body>

    <?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

    <?php foreach(['libraries/jquery.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js',] as $file): ?>
        <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
    <?php endforeach ?>

    <?= \Altum\Event::get_content('javascript') ?>
</html>
