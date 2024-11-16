<?php defined('ALTUMCODE') || die(); ?>

<?php if(settings()->cookie_consent->is_enabled): ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL ?>js/libraries/cookieconsent.js?v=<?= PRODUCT_CODE ?>"></script>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/cookieconsent.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
    <style>
        :root {
            --cc-font-family: inherit;
            --cc-bg: var(--white);
            --cc-separator-border-color: var(--white);

            --cc-modal-border-radius: var(--border-radius);
            --cc-btn-border-radius: var(--border-radius);

            --cc-primary-color:var(--gray-700);
            --cc-secondary-color:var(--gray-600);

            --cc-btn-primary-color: var(--white);
            --cc-btn-primary-bg: var(--primary);
            --cc-btn-primary-hover-bg: var(--primary-600);
            --cc-btn-primary-color-bg: var(--white);
            --cc-btn-primary-hover-color: var(--white);

            --cc-btn-secondary-bg:var(--gray-300);
            --cc-btn-secondary-hover-bg:var(--gray-400);

            --cc-btn-secondary-hover-color: var(--black);
            --cc-btn-secondary-hover-border-color: var(--cc-btn-secondary-hover-bg);
        }

        .cc--darkmode {
            --cc-bg: var(--white);
        }
    </style>

    <script>
        window.addEventListener('load', () => {
            let language_code = document.documentElement.getAttribute('lang');
            let language_direction = document.documentElement.getAttribute('dir');
            let translations = {};

            translations[language_code] = {
                consentModal: {
                    title: <?= json_encode(l('global.cookie_consent.header')) ?>,
                    description: <?= json_encode(l('global.cookie_consent.subheader')) ?>,
                    acceptAllBtn: <?= json_encode(l('global.cookie_consent.accept_all')) ?>,
                    acceptNecessaryBtn: <?= json_encode(l('global.cookie_consent.reject_all')) ?>,
                    showPreferencesBtn: <?= json_encode(l('global.cookie_consent.customize')) ?>,
                },

                preferencesModal: {
                    title: <?= json_encode(l('global.cookie_consent.modal.preferences.header')) ?>,
                    acceptAllBtn: <?= json_encode(l('global.cookie_consent.accept_all')) ?>,
                    acceptNecessaryBtn: <?= json_encode(l('global.cookie_consent.reject_all')) ?>,
                    savePreferencesBtn: <?= json_encode(l('global.cookie_consent.save')) ?>,
                    closeIconLabel: <?= json_encode(l('global.close')) ?>,
                    sections: [
                        {
                            title: <?= json_encode(l('global.cookie_consent.modal.header')) ?>,
                            description: <?= json_encode(sprintf(l('global.cookie_consent.modal.subheader'), settings()->main->privacy_policy_url)) ?>
                        },

                        {
                            title: <?= json_encode(l('global.cookie_consent.modal.necessary.header')) ?>,
                            description: <?= json_encode(l('global.cookie_consent.modal.necessary.subheader')) ?>,
                            linkedCategory: 'necessary'
                        },

                        <?php if(settings()->cookie_consent->analytics_is_enabled): ?>
                        {
                            title: <?= json_encode(l('global.cookie_consent.modal.analytics.header')) ?>,
                            description: <?= json_encode(l('global.cookie_consent.modal.analytics.subheader')) ?>,
                            linkedCategory: 'analytics'
                        },
                        <?php endif ?>

                        <?php if(settings()->cookie_consent->targeting_is_enabled): ?>
                        {
                            title: <?= json_encode(l('global.cookie_consent.modal.targeting.header')) ?>,
                            description: <?= json_encode(l('global.cookie_consent.modal.targeting.subheader')) ?>,
                            linkedCategory: 'targeting'
                        },
                        <?php endif ?>
                    ]
                }
            };

            CookieConsent.run({
                categories: {
                    necessary: {
                        enabled: true,
                        readOnly: true,
                    },
                    analytics: {},
                    targeting: {},
                },

                language: {
                    rtl: language_direction == 'rtl' ? language_code : null,
                    default: language_code,
                    autoDetect: 'document',
                    translations
                },

                <?php if(settings()->cookie_consent->logging_is_enabled): ?>
                onFirstConsent: () => {
                    const preferences = CookieConsent.getUserPreferences();

                    if(!get_cookie('cookie_consent_logged')) {
                        navigator.sendBeacon(`${url}cookie-consent`, JSON.stringify({global_token, level: preferences.acceptedCategories}));
                        set_cookie('cookie_consent_logged', '1', 182, <?= json_encode(COOKIE_PATH) ?>);
                    }
                },
                onChange: () => {
                    const preferences = CookieConsent.getUserPreferences();

                    navigator.sendBeacon(`${url}cookie-consent`, JSON.stringify({global_token, level: preferences.acceptedCategories}));
                    set_cookie('cookie_consent_logged', '1', 182, <?= json_encode(COOKIE_PATH) ?>);
                },
                <?php endif ?>

                guiOptions: {
                    consentModal: {
                        layout: <?= json_encode(settings()->cookie_consent->layout) ?>,
                        position: <?= json_encode(settings()->cookie_consent->position_y . ' ' . settings()->cookie_consent->position_x) ?>,
                        flipButtons: false
                    },
                    preferencesModal: {
                        layout: 'box',
                    }
                },
            });
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php endif ?>
