<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 py-6">
            <?= \Altum\Alerts::output_alerts() ?>

            <div class="text-center">
                <?php if($data->splash_page->settings->logo): ?>
                <div class="d-flex flex-column align-items-center mb-4">
                    <img src="<?= $data->splash_page->settings->logo ? \Altum\Uploads::get_full_url('splash_pages') . $data->splash_page->settings->logo : null ?>" class="link-image link-avatar-round" />
                </div>
                <?php endif ?>

                <h1 class="h3"><?= $data->splash_page->title ?? l('link.splash.header') ?></h1>
                <span class="text-muted">
                    <?= $data->splash_page->description ?? l('link.splash.subheader') ?>
                </span>

                <form>
                    <div class="row mt-4">
                        <div class="col-6">
                            <a href="<?= $data->splash_page->settings->secondary_button_url ?? url() ?>" class="btn btn-block btn-gray-300">
                                <?php if(!$data->splash_page->settings->secondary_button_name): ?>
                                <i class="fas fa-fw fa-sm fa-home mr-1"></i>
                                <?php endif ?>

                                <?= $data->splash_page->settings->secondary_button_name ?? l('link.splash.home') ?>
                            </a>
                        </div>

                        <div class="col-6">
                            <a href="#" id="link_continue" class="btn btn-block btn-primary disabled">
                                <i class="fas fa-fw fa-sm fa-link mr-1"></i> <?= l('link.splash.continue') ?>
                            </a>
                        </div>
                    </div>
                </form>

                <div class="text-muted mt-3" id="link_unlock_seconds">
                    <?= sprintf(l('link.splash.link_unlock_seconds'), $data->splash_page->link_unlock_seconds ?? settings()->links->splash_page_link_unlock_seconds) ?>
                </div>
            </div>
        </div>
    </div>
</div>


<?php ob_start() ?>
<script>
    'use strict';

    let link_unlock_seconds = <?= $data->splash_page->link_unlock_seconds ?? settings()->links->splash_page_link_unlock_seconds ?>;
    let splash_page_auto_redirect = <?= json_encode((bool) ($data->splash_page->auto_redirect ?? settings()->links->splash_page_auto_redirect)) ?>;

    let link_unlock_seconds_remaining = link_unlock_seconds;

    let countdown = setInterval(() => {
        document.querySelector('#link_unlock_seconds').innerHTML = <?= json_encode(l('link.splash.link_unlock_seconds')) ?>.replace('%s', link_unlock_seconds_remaining);

        link_unlock_seconds_remaining -= 1;

        if(link_unlock_seconds_remaining < 0) {
            clearInterval(countdown);
            document.querySelector('#link_unlock_seconds').classList.add('d-none');
            document.querySelector('#link_continue').classList.remove('disabled');
            document.querySelector('#link_continue').href = <?= json_encode($this->link->full_url) ?>;

            if(splash_page_auto_redirect) {
                window.location.replace(<?= json_encode($this->link->full_url) ?>);
            }

            set_cookie(<?= json_encode('link_unlocked_' . $this->link->link_id) ?>, <?= json_encode(md5($this->link->link_id . $this->link->link_id)) ?>, 1, <?= json_encode(COOKIE_PATH) ?>);
        }
    }, 1000);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

