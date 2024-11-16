<?php defined('ALTUMCODE') || die() ?>

<div class="p-3 mt-3 p-lg-0 mt-lg-0">
    <nav class="navbar navbar-expand-lg navbar-light rounded admin-navbar-top">
        <div
            class="navbar-brand text-truncate"
            data-logo
            data-light-value="<?= settings()->main->logo_light != '' ? \Altum\Uploads::get_full_url('logo_light') . settings()->main->logo_light : settings()->main->title ?>"
            data-light-class="<?= settings()->main->logo_light != '' ? 'img-fluid admin-navbar-logo-top' : '' ?>"
            data-light-tag="<?= settings()->main->logo_light != '' ? 'img' : 'span' ?>"
            data-dark-value="<?= settings()->main->logo_dark != '' ? \Altum\Uploads::get_full_url('logo_dark') . settings()->main->logo_dark : settings()->main->title ?>"
            data-dark-class="<?= settings()->main->logo_dark != '' ? 'img-fluid admin-navbar-logo-top' : '' ?>"
            data-dark-tag="<?= settings()->main->logo_dark != '' ? 'img' : 'span' ?>"
            
            id="sidebar_title"
            tabindex="0"
            data-toggle="tooltip"
            data-placement="right"
            data-html="true"
            data-trigger="hover"
            data-delay='{ "hide": 5500 }'
            title="
            <div class='d-flex text-left flex-column'>
                <div class='mb-2'><a href='<?= url() ?>' class='text-gray-50 text-decoration-none'>🌐 &nbsp; <?= l('index.menu') ?></a></div>
                <div><a href='<?= url('dashboard') ?>' class='text-gray-50 text-decoration-none'>🖥️ &nbsp; <?= l('dashboard.menu') ?></a></div>
            </div>
            "
        >
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= \Altum\Uploads::get_full_url('logo_' . \Altum\ThemeStyle::get()) . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="img-fluid admin-navbar-logo-top" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <span><?= settings()->main->title ?></span>
            <?php endif ?>
        </div>

        <ul class="navbar-nav ml-auto">
            <button class="btn navbar-custom-toggler" type="button" id="admin_menu_toggler" aria-controls="main_navbar" aria-expanded="false" aria-label="<?= l('global.accessibility.toggle_navigation') ?>">
                <i class="fas fa-fw fa-bars"></i>
            </button>
        </ul>
    </nav>
</div>
