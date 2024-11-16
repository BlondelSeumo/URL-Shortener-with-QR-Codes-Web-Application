<?php defined('ALTUMCODE') || die() ?>

<footer class="d-flex flex-column flex-lg-row justify-content-between">
    <div class="mb-3 mb-lg-0">
        <div class="mb-2"><?= sprintf(l('global.footer.copyright'), date('Y'), settings()->main->title) ?></div>

        <div>Powered by <img src="<?= ASSETS_FULL_URL . 'images/altumcode.png' ?>" class="icon-favicon" alt="AltumCode logo" /> <a href="https://altumcode.com/" target="_blank">AltumCode</a>.</div>
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

        <?php if(count((array) settings()->payment->currencies ?? []) > 1): ?>
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
            <div class="ml-lg-3">
                <button type="button" id="switch_theme_style" class="btn btn-link text-decoration-none p-0" data-toggle="tooltip" title="<?= sprintf(l('global.theme_style'), (\Altum\ThemeStyle::get() == 'light' ? l('global.theme_style_dark') : l('global.theme_style_light'))) ?>" data-title-theme-style-light="<?= sprintf(l('global.theme_style'), l('global.theme_style_light')) ?>" data-title-theme-style-dark="<?= sprintf(l('global.theme_style'), l('global.theme_style_dark')) ?>">
                    <span data-theme-style="light" class="<?= \Altum\ThemeStyle::get() == 'light' ? null : 'd-none' ?>"><i class="fas fa-fw fa-sm fa-sun text-warning"></i></span>
                    <span data-theme-style="dark" class="<?= \Altum\ThemeStyle::get() == 'dark' ? null : 'd-none' ?>"><i class="fas fa-fw fa-sm fa-moon"></i></span>
                </button>
            </div>

            <?php include_view(THEME_PATH . 'views/partials/theme_style_js.php') ?>
        <?php endif ?>
    </div>
</footer>
