<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex flex-column flex-lg-row justify-content-between mb-3">
    <div class="mb-3 mb-lg-0">
        <a
            class="h5 footer-heading"
            href="<?= url() ?>"
            data-logo
            data-light-value="<?= settings()->main->logo_light != '' ? \Altum\Uploads::get_full_url('logo_light') . settings()->main->logo_light : settings()->main->title ?>"
            data-light-class="<?= settings()->main->logo_light != '' ? 'mb-2 footer-logo' : 'mb-2' ?>"
            data-light-tag="<?= settings()->main->logo_light != '' ? 'img' : 'span' ?>"
            data-dark-value="<?= settings()->main->logo_dark != '' ? \Altum\Uploads::get_full_url('logo_dark') . settings()->main->logo_dark : settings()->main->title ?>"
            data-dark-class="<?= settings()->main->logo_dark != '' ? 'mb-2 footer-logo' : 'mb-2' ?>"
            data-dark-tag="<?= settings()->main->logo_dark != '' ? 'img' : 'span' ?>"
        >
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= \Altum\Uploads::get_full_url('logo_' . \Altum\ThemeStyle::get()) . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="mb-2 footer-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <span class="mb-2"><?= settings()->main->title ?></span>
            <?php endif ?>
        </a>
        <div class="text-muted"><?= sprintf(l('global.footer.copyright'), date('Y'), settings()->main->title) ?></div>
    </div>

    <div class="d-flex flex-column flex-lg-row">
        <?php if(count(\Altum\Language::$active_languages) > 1): ?>
            <div class="dropdown mb-2 ml-lg-3">
                <button type="button" class="btn btn-link text-decoration-none p-0" id="language_switch" data-tooltip data-tooltip-hide-on-click title="<?= l('global.choose_language') ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-sm fa-language mr-1"></i> <?= \Altum\Language::$name ?>
                </button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language_switch">
                    <?php foreach(\Altum\Language::$languages_ordered as $language): ?>
                        <?php if($language['status'] == 'active'): ?>
                            <a href="<?= SITE_URL . $language['code'] . '/' . \Altum\Router::$original_request . (\Altum\Router::$original_request_query ? '?' . \Altum\Router::$original_request_query : null) ?>" class="dropdown-item" data-set-language="<?= $language['name'] ?>">
                                <?php if($language['name'] == \Altum\Language::$name): ?>
                                    <i class="fas fa-fw fa-sm fa-check mr-2 text-success"></i>
                                <?php else: ?>
                                    <?php if($language['language_flag']): ?>
                                        <span class="mr-2"><?= $language['language_flag'] ?></span>
                                    <?php else: ?>
                                        <i class="fas fa-fw fa-sm fa-circle-notch mr-2 text-muted"></i>
                                    <?php endif ?>
                                <?php endif ?>

                                <?= $language['name'] ?>
                            </a>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>

            <?php ob_start() ?>
            <script>
                'use strict';

                document.querySelectorAll('[data-set-language]').forEach(element => element.addEventListener('click', event => {
                    let language = event.currentTarget.getAttribute('data-set-language');
                    set_cookie(`set_language`, language, 90, <?= json_encode(COOKIE_PATH) ?>);
                }));
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        <?php endif ?>

        <?php if(\Altum\Router::$controller_settings['currency_switcher'] && count((array) settings()->payment->currencies ?? []) > 1): ?>
            <div class="dropdown mb-2 ml-lg-3">
                <button type="button" class="btn btn-link text-decoration-none p-0" id="currency_switch" data-tooltip data-tooltip-hide-on-click title="<?= l('global.choose_currency') ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-fw fa-sm fa-coins mr-1"></i> <?= currency() ?>
                </button>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="currency_switch">
                    <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                        <a href="#" class="dropdown-item" data-set-currency="<?= $currency ?>">
                            <?php if($currency == currency()): ?>
                                <i class="fas fa-fw fa-sm fa-check mr-2 text-success"></i>
                            <?php else: ?>
                                <span class="fas fa-fw text-muted mr-2"><?= $currency_data->symbol ?: '&nbsp;' ?></span>
                            <?php endif ?>

                            <?= $currency ?>
                        </a>
                    <?php endforeach ?>
                </div>
            </div>

            <?php ob_start() ?>
            <script>
                'use strict';

                document.querySelectorAll('[data-set-currency]').forEach(element => element.addEventListener('click', event => {
                    let currency = event.currentTarget.getAttribute('data-set-currency');
                    set_cookie(`set_currency`, currency, 90, <?= json_encode(COOKIE_PATH) ?>);
                    window.location.reload();
                    event.preventDefault();
                }));
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        <?php endif ?>

        <?php if(settings()->main->theme_style_change_is_enabled): ?>
            <div class="mb-2 ml-lg-3">
                <button type="button" id="switch_theme_style" class="btn btn-link text-decoration-none p-0" data-toggle="tooltip" title="<?= sprintf(l('global.theme_style'), (\Altum\ThemeStyle::get() == 'light' ? l('global.theme_style_dark') : l('global.theme_style_light'))) ?>" data-title-theme-style-light="<?= sprintf(l('global.theme_style'), l('global.theme_style_light')) ?>" data-title-theme-style-dark="<?= sprintf(l('global.theme_style'), l('global.theme_style_dark')) ?>">
                    <span data-theme-style="light" class="<?= \Altum\ThemeStyle::get() == 'light' ? null : 'd-none' ?>"><i class="fas fa-fw fa-sm fa-sun text-warning"></i></span>
                    <span data-theme-style="dark" class="<?= \Altum\ThemeStyle::get() == 'dark' ? null : 'd-none' ?>"><i class="fas fa-fw fa-sm fa-moon"></i></span>
                </button>
            </div>

            <?php include_view(THEME_PATH . 'views/partials/theme_style_js.php') ?>
        <?php endif ?>
    </div>
</div>

<div class="row">
    <div class="col-12 col-lg mb-3 mb-lg-0">
        <ul class="list-style-none d-flex flex-column flex-lg-row flex-wrap m-0">
            <?php if(settings()->content->blog_is_enabled): ?>
                <li class="mb-2 mr-lg-3"><a href="<?= url('blog') ?>"><?= l('blog.menu') ?></a></li>
            <?php endif ?>

            <?php if(settings()->payment->is_enabled): ?>
                <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                    <li class="mb-2 mr-lg-3"><a href="<?= url('affiliate') ?>"><?= l('affiliate.menu') ?></a></li>
                <?php endif ?>
            <?php endif ?>

            <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
                <li class="mb-2 mr-lg-3"><a href="<?= url('contact') ?>"><?= l('contact.menu') ?></a></li>
            <?php endif ?>

            <?php if(settings()->cookie_consent->is_enabled): ?>
                <li class="mb-2 mr-lg-3"><a href="#" data-cc="show-preferencesModal"><?= l('global.cookie_consent.menu') ?></a></li>
            <?php endif ?>

            <?php if(\Altum\Plugin::is_active('push-notifications') && settings()->push_notifications->is_enabled && (\Altum\Authentication::check() || (!\Altum\Authentication::check() && settings()->push_notifications->guests_is_enabled))): ?>
                <li class="mb-2 mr-lg-3"><a href="#" data-toggle="modal" data-target="#push_notifications_modal"><?= l('push_notifications_modal.menu') ?></a></li>
            <?php endif ?>

            <?php if(count($data->pages)): ?>
                <?php foreach($data->pages as $row): ?>
                    <li class="mb-2 mr-lg-3">
                        <a href="<?= $row->url ?>" target="<?= $row->target ?>">
                            <?php if($row->icon): ?>
                                <i class="<?= $row->icon ?> fa-fw fa-sm mr-1"></i>
                            <?php endif ?>

                            <?= $row->title ?>
                        </a>
                    </li>
                <?php endforeach ?>
            <?php endif ?>
        </ul>
    </div>


    <div class="col-12 col-lg-auto">
        <div class="d-flex flex-wrap">
            <?php foreach(require APP_PATH . 'includes/admin_socials.php' as $key => $value): ?>
                <?php if(isset(settings()->socials->{$key}) && !empty(settings()->socials->{$key})): ?>
                    <a href="<?= sprintf($value['format'], settings()->socials->{$key}) ?>" class="mr-2 mr-lg-0 ml-lg-2 mb-2" target="_blank" data-toggle="tooltip" title="<?= $value['name'] ?>"><i class="<?= $value['icon'] ?> fa-fw fa-lg"></i></a>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
</div>
