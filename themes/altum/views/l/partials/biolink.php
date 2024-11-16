<?php defined('ALTUMCODE') || die() ?>

<body class="<?= l('direction') == 'rtl' ? 'rtl' : null ?> link-body <?= $data->link->design->background_class ?>" style="<?= $data->link->design->background_style ?>">
<?php if(!empty(settings()->custom->body_content_biolink)): ?>
    <?= settings()->custom->body_content_biolink ?>
<?php endif ?>

<?php if((is_string($data->link->settings->background) && string_ends_with('.mp4', $data->link->settings->background)) || isset($_GET['preview'])): ?>
<video autoplay muted loop playsinline class="link-video-background <?= is_string($data->link->settings->background) && string_ends_with('.mp4', $data->link->settings->background) ? '' : 'd-none' ?>">
    <source src="<?= is_string($data->link->settings->background) && string_ends_with('.mp4', $data->link->settings->background) ? \Altum\Uploads::get_full_url('backgrounds') . $data->link->settings->background : null; ?>" type="video/mp4">
</video>
<?php endif ?>

<div id="backdrop" class="link-body-backdrop" style="<?= $data->link->design->backdrop_style ?>"></div>

<div class="container animate__animated animate__fadeIn">
    <?php require THEME_PATH . 'views/l/partials/biolink_scroll_buttons.php' ?>
    <?php require THEME_PATH . 'views/l/partials/biolink_share.php' ?>

    <div class="row d-flex justify-content-center text-center">
        <div class="col-md-<?= $data->link->settings->width ?? '8' ?> link-content <?= isset($_GET['preview']) ? 'container-disabled-simple' : null ?>">

            <?php require THEME_PATH . 'views/l/partials/ads_header_biolink.php' ?>

            <main id="links" class="my-<?= $data->link->settings->block_spacing ?? '2' ?>">
                <div class="row">
                    <?php if($data->link->is_verified): ?>
                    <div id="link-verified-wrapper-top" class="col-12 my-<?= $data->link->settings->block_spacing ?? '2' ?> text-center" style="<?= $data->link->settings->verified_location == 'top' ? null : 'display: none;' ?>">
                        <div>
                            <small class="link-verified" data-toggle="tooltip" title="<?= sprintf(l('link.biolink.verified_help'), settings()->main->title) ?>"><i class="fas fa-fw fa-check-circle fa-1x"></i> <?= l('link.biolink.verified') ?></small>
                        </div>
                    </div>
                    <?php endif ?>

                    <?php if($data->biolink_blocks): ?>
                        <?php
                        /* Detect the location */
                        try {
                            $maxmind = (new \MaxMind\Db\Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get(get_ip());
                        } catch(\Exception $exception) {
                            /* :) */
                        }
                        /* Detect extra details about the user */
                        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
                        $os_name = $whichbrowser->os->name ?? null;
                        $browser_name = $whichbrowser->browser->name ?? null;
                        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
                        $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
                        $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;
                        $continent_code = isset($maxmind) && isset($maxmind['continent']) ? $maxmind['continent']['code'] : null;
                        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
                        ?>

                        <?php foreach($data->biolink_blocks as $row): ?>

                            <?php
                            $row->settings = json_decode($row->settings ?? '');

                            /* Check if its a scheduled link and we should show it or not */
                            if(
                                !empty($row->start_date) &&
                                !empty($row->end_date) &&
                                (
                                    \Altum\Date::get('', null) < \Altum\Date::get($row->start_date, null, \Altum\Date::$default_timezone) ||
                                    \Altum\Date::get('', null) > \Altum\Date::get($row->end_date, null, \Altum\Date::$default_timezone)
                                )
                            ) {
                                continue;
                            }

                            /* Check if the user has permissions to use the link */
                            if(!$data->user->plan_settings->enabled_biolink_blocks->{$row->type}) {
                                continue;
                            }

                            /* Check if there are any extra display rules */
                            if($continent_code && count($row->settings->display_continents ?? []) && !in_array($continent_code, $row->settings->display_continents ?? [])) {
                                continue;
                            }
                            if($country_code && count($row->settings->display_countries ?? []) && !in_array($country_code, $row->settings->display_countries ?? [])) {
                                continue;
                            }
                            if($city_name && count($row->settings->display_cities ?? []) && !in_array($city_name, $row->settings->display_cities ?? [])) {
                                continue;
                            }
                            if($device_type && count($row->settings->display_devices ?? []) && !in_array($device_type, $row->settings->display_devices ?? [])) {
                                continue;
                            }
                            if($browser_language && count($row->settings->display_languages ?? []) && !in_array($browser_language, $row->settings->display_languages ?? [])) {
                                continue;
                            }
                            if($os_name && count($row->settings->display_operating_systems ?? []) && !in_array($os_name, $row->settings->display_operating_systems ?? [])) {
                                continue;
                            }
                            if($browser_name && count($row->settings->display_browsers ?? []) && !in_array($browser_name, $row->settings->display_browsers ?? [])) {
                                continue;
                            }

                            $row->utm = $data->link->settings->utm;
                            ?>

                            <?= \Altum\Link::get_biolink_link($row, $data->user, $this->biolink_theme ?? null, $data->link) ?? null ?>

                        <?php endforeach ?>
                    <?php endif ?>
                </div>
            </main>

            <?php require THEME_PATH . 'views/l/partials/ads_footer_biolink.php' ?>

            <footer id="footer" class="link-footer">
                <?php if($data->link->is_verified): ?>
                <div id="link-verified-wrapper-bottom" class="my-<?= $data->link->settings->block_spacing ?? '2' ?>" style="<?= $data->link->settings->verified_location == 'bottom' ? null : 'display: none;' ?>">
                    <small class="link-verified" data-toggle="tooltip" title="<?= sprintf(l('link.biolink.verified_help'), settings()->main->title) ?>"><i class="fas fa-fw fa-check-circle fa-1x"></i> <?= l('link.biolink.verified') ?></small>
                </div>
                <?php endif ?>

                <div id="branding" class="link-footer-branding">
                    <?php if($data->link->settings->display_branding): ?>
                        <?php if(isset($data->link->settings->branding, $data->link->settings->branding->name, $data->link->settings->branding->url) && !empty($data->link->settings->branding->name)): ?>
                            <a href="<?= !empty($data->link->settings->branding->url) ? $data->link->settings->branding->url : '#' ?>" style="<?= $data->link->design->text_style ?>"><?= $data->link->settings->branding->name ?></a>
                        <?php else: ?>

                            <?php
                            $replacers = [
                                '{{URL}}' => url(),
                                '{{WEBSITE_TITLE}}' => settings()->main->title,
                                '{{AFFILIATE_URL_TAG}}' => \Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled ? '?ref=' . $data->user->referral_key : null,
                            ];

                            settings()->links->branding = str_replace(
                                array_keys($replacers),
                                array_values($replacers),
                                settings()->links->branding
                            );
                            ?>

                            <?= settings()->links->branding ?>
                        <?php endif ?>
                    <?php endif ?>
                </div>
            </footer>

        </div>
    </div>
</div>

<?= \Altum\Event::get_content('modals') ?>
</body>

<?php ob_start() ?>
<script>
    /* Background backdrop fix on modal */
    let backdrop_filter = null;
    $('.modal').on('show.bs.modal', function () {
        backdrop_filter = document.querySelector('body').style.backdropFilter;
        document.querySelector('body').style.backdropFilter = '';
    });

    $('.modal').on('hide.bs.modal', function () {
        document.querySelector('body').style.backdropFilter = backdrop_filter;
    });

    /* Internal tracking for biolink page blocks */
    document.querySelectorAll('a[data-track-biolink-block-id]').forEach(element => {
        element.addEventListener('click', event => {
            let biolink_block_id = event.currentTarget.getAttribute('data-track-biolink-block-id');
            navigator.sendBeacon(`${site_url}l/link?biolink_block_id=${biolink_block_id}&no_redirect`);
        });
    });

    /* Fix CSS when using scroll for background attachment on long content */
    if(document.body.offsetHeight > window.innerHeight) {
        let background_attachment = document.querySelector('body').style.backgroundAttachment;
        if(background_attachment == 'scroll') {
            document.documentElement.style.height = 'auto';
        }
    }
</script>

<?= $this->views['pixels'] ?? null ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

