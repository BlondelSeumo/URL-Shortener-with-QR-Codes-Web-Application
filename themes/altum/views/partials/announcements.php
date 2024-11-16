<?php $has_announcements = false ?>
<?php foreach(['guests', 'users'] as $type): ?>
    <?php if(
        settings()->announcements->{$type . '_is_enabled'}
        && settings()->announcements->{$type . '_content'}
        && (!isset($_COOKIE['announcement_' . $type . '_id']) || (isset($_COOKIE['announcement_' . $type . '_id']) && $_COOKIE['announcement_' . $type . '_id'] != settings()->announcements->{$type . '_id'}))
        && (
            ($type == 'guests' && !\Altum\Authentication::check())
            || ($type == 'users' && \Altum\Authentication::check())
        )
    ): ?>
        <?php
        $has_announcements = true;

        $announcement_content = settings()->announcements->translations->{\Altum\Language::$name}->{$type . '_content'} ?: settings()->announcements->{$type . '_content'};

        /* Dynamic variables processing */
        $replacers = [
            '{{WEBSITE_TITLE}}' => settings()->main->title,
            '{{USER:NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->name : '',
            '{{USER:EMAIL}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->email : '',
            '{{USER:CONTINENT_NAME}}' => \Altum\Authentication::check() ? get_continent_from_continent_code(\Altum\Authentication::$user->continent_code) : '',
            '{{USER:COUNTRY_NAME}}' => \Altum\Authentication::check() ? get_country_from_country_code(\Altum\Authentication::$user->country) : '',
            '{{USER:CITY_NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->city_name : '',
            '{{USER:DEVICE_TYPE}}' => \Altum\Authentication::check() ? l('global.device.' . \Altum\Authentication::$user->device_type) : '',
            '{{USER:OS_NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->os_name : '',
            '{{USER:BROWSER_NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->browser_name : '',
            '{{USER:BROWSER_LANGUAGE}}' => \Altum\Authentication::check() ? get_language_from_locale(\Altum\Authentication::$user->browser_language) : '',
        ];

        $announcement_content = str_replace(
            array_keys($replacers),
            array_values($replacers),
            $announcement_content
        );

        $announcement_content = process_spintax($announcement_content);

        ?>

        <div data-announcement="<?= $type ?>" class="announcement-wrapper py-3" style="background-color: <?= settings()->announcements->{$type . '_background_color'} ?>;">
            <div class="container d-flex justify-content-center position-relative">
                <div class="row w-100">
                    <div class="col">
                        <div class="text-center" style="color: <?= settings()->announcements->{$type . '_text_color'} ?>;"><?= $announcement_content ?></div>
                    </div>
                    <div class="col-auto px-0">
                        <div>
                            <button data-announcement-close="<?= $type ?>" data-announcement-id="<?= settings()->announcements->{$type . '_id'} ?>" type="button" class="close ml-2" data-dismiss="alert" data-toggle="tooltip" title="<?= l('global.close') ?>" data-tooltip-hide-on-click>
                                <i class="fas fa-sm fa-times" style="color: <?= settings()->announcements->{$type . '_text_color'} ?>; opacity: .5;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
<?php endforeach ?>

<?php if($has_announcements): ?>
    <?php ob_start() ?>
    <script>
        document.querySelector('[data-announcement-close]').addEventListener('click', event => {
            let type = event.currentTarget.getAttribute('data-announcement-close');
            let id = event.currentTarget.getAttribute('data-announcement-id');
            document.querySelector(`[data-announcement="${type}"]`).style.display = 'none';
            set_cookie(`announcement_${type}_id`, id, 15, <?= json_encode(COOKIE_PATH) ?>);
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
