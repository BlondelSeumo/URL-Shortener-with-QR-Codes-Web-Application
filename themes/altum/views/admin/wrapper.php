<?php defined('ALTUMCODE') || die() ?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$code ?>" dir="<?= l('direction') ?>" class="w-100 h-100">
<head>
    <title><?= \Altum\Title::get() ?></title>
    <base href="<?= SITE_URL; ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
        <meta name="theme-color" content="<?= settings()->pwa->theme_color ?>"/>
        <link rel="manifest" href="<?= SITE_URL . UPLOADS_URL_PATH . \Altum\Uploads::get_path('pwa') . 'manifest.json' ?>" />
    <?php endif ?>

    <link rel="alternate" href="<?= SITE_URL . \Altum\Router::$original_request ?>" hreflang="x-default" />
    <?php if(count(\Altum\Language::$active_languages) > 1): ?>
        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
            <?php if(settings()->main->default_language != $language_name): ?>
                <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>

    <?php if(!empty(settings()->main->favicon)): ?>
        <link href="<?= \Altum\Uploads::get_full_url('favicon') . settings()->main->favicon ?>" rel="icon" />
    <?php endif ?>

    <link href="<?= ASSETS_FULL_URL . 'css/admin-' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
    <?php foreach(['admin-custom.css', 'libraries/select2.css'] as $file): ?>
        <link href="<?= ASSETS_FULL_URL ?>css/<?= $file ?>?v=<?= PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php endforeach ?>

    <?= \Altum\Event::get_content('head') ?>
</head>

<body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?>" data-theme-style="<?= \Altum\ThemeStyle::get() ?>">
<div id="admin_overlay" class="admin-overlay" style="display: none"></div>

<div class="admin-container">
    <?= $this->views['admin_sidebar'] ?>

    <section class="admin-content altum-animate altum-animate-fill-none altum-animate-fade-in">
        <?= $this->views['admin_menu'] ?>

        <div class="p-3 p-lg-5 position-relative">
            <?= include_view(THEME_PATH . 'views/admin/partials/admin_version_updates_bar.php') ?>
            <?= include_view(THEME_PATH . 'views/admin/partials/admin_support_bar.php') ?>

            <?= $this->views['content'] ?>

            <div class="card mt-4">
                <div class="card-body">
                    <?= $this->views['footer'] ?>
                </div>
            </div>
        </div>
    </section>
</div>

<?= \Altum\Event::get_content('modals') ?>

<?php require THEME_PATH . 'views/partials/js_global_variables.php' ?>

<?php foreach(['libraries/jquery.slim.min.js', 'libraries/popper.min.js', 'libraries/bootstrap.min.js', 'custom.js', 'libraries/fontawesome.min.js', 'libraries/fontawesome-solid.min.js', 'libraries/fontawesome-brands.min.js', 'libraries/select2.js'] as $file): ?>
    <script src="<?= ASSETS_FULL_URL ?>js/<?= $file ?>?v=<?= PRODUCT_CODE ?>"></script>
<?php endforeach ?>

<?= \Altum\Event::get_content('javascript') ?>

<script>
    let toggle_admin_sidebar = () => {
        /* Open sidebar menu */
        let body = document.querySelector('body');
        body.classList.toggle('admin-sidebar-opened');

        /* Toggle overlay */
        let admin_overlay = document.querySelector('#admin_overlay');
        admin_overlay.style.display == 'none' ? admin_overlay.style.display = 'block' : admin_overlay.style.display = 'none';

        /* Change toggle button content */
        let button = document.querySelector('#admin_menu_toggler');

        if(body.classList.contains('admin-sidebar-opened')) {
            button.innerHTML = `<i class="fas fa-fw fa-times"></i>`;
        } else {
            button.innerHTML = `<i class="fas fa-fw fa-bars"></i>`;
        }
    };

    /* Toggler for the sidebar */
    document.querySelector('#admin_menu_toggler').addEventListener('click', event => {
        event.preventDefault();

        toggle_admin_sidebar();

        let admin_sidebar_is_opened = document.querySelector('body').classList.contains('admin-sidebar-opened');

        if(admin_sidebar_is_opened) {
            document.querySelector('#admin_overlay').removeEventListener('click', toggle_admin_sidebar);
            document.querySelector('#admin_overlay').addEventListener('click', toggle_admin_sidebar);
        } else {
            document.querySelector('#admin_overlay').removeEventListener('click', toggle_admin_sidebar);
        }
    });

    /* Custom select implementation */
    $('select:not([multiple="multiple"]):not([class="input-group-text"]):not([class="custom-select custom-select-sm"]):not([class^="ql"]):not([data-is-not-custom-select])').select2({
        dir: <?= json_encode(l('direction')) ?>,
        minimumResultsForSearch: 5,
    });
</script>
</body>
</html>
