<?php defined('ALTUMCODE') || die(); ?>

<?php if(isset(settings()->support->expiry_datetime)): ?>
    <?php
    $expiry_datetime = (new \DateTime(settings()->support->expiry_datetime ?? null));
    $is_active = (new \DateTime()) <= $expiry_datetime;
    ?>

    <?php if(!$is_active && !isset($_COOKIE['dismiss_inactive_support'])): ?>
        <div class="alert alert-warning mb-4">
            <div class="mb-3">
                <i class="fas fa-fw fa-exclamation-triangle text-warning mr-1"></i>
                <strong>Your future support inquiries will be completely discarded.</strong>
            </div>

            <div class="mb-3">
                <i class="fas fa-fw fa-sync-alt text-info mr-1"></i>
                <span>Renewing support is optional. If you do not need support, then you can ignore this message.</span>
            </div>

            <div>
                <button type="button" class="btn btn-sm btn-light" data-dismiss="alert" data-dismiss-inactive-support>
                    <i class="fas fa-fw fa-sm fa-times mr-1"></i> Dismiss notification
                </button>

                <a href="https://altumco.de/club" target="_blank" class="btn btn-sm btn-primary font-weight-bold ml-3">
                    <i class="fas fa-fw fa-sm fa-check-circle mr-1"></i> Extend your support
                </a>
            </div>

            <?php ob_start() ?>
            <script>
                'use strict';
                document.querySelector('[data-dismiss-inactive-support]').addEventListener('click', event => {
                    set_cookie('dismiss_inactive_support', 1, 5, <?= json_encode(COOKIE_PATH) ?>);
                });
            </script>
            <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
        </div>
    <?php endif ?>
<?php endif ?>
