<?php defined('ALTUMCODE') || die(); ?>

<?php if(settings()->ads->ad_blocker_detector_is_enabled && (!\Altum\Authentication::check() || (\Altum\Authentication::check() && !$this->user->plan_settings->no_ads))): ?>

    <div class="modal fade" id="ad_blocker_detector_modal" <?= settings()->ads->ad_blocker_detector_lock_is_enabled ? 'data-backdrop="static" data-keyboard="false"' : null ?> tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="modal-title">
                            <i class="fas fa-fw fa-sm fa-eye text-dark mr-2"></i>
                            <?= l('ad_blocker_detector_modal.header') ?>
                        </h5>

                        <?php if(!settings()->ads->ad_blocker_detector_lock_is_enabled): ?>
                            <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        <?php endif ?>
                    </div>

                    <p class="text-muted"><?= l('ad_blocker_detector_modal.subheader') ?></p>

                    <div class="mt-4">
                        <a href="#" class="btn btn-block btn-primary" onClick="event.preventDefault();window.location.reload();"><?= l('ad_blocker_detector_modal.button') ?></a>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        let ads_url = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js';

        let request_ads_to_display = callback => {
            let xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function () {
                if (xhr.readyState == XMLHttpRequest.DONE) {
                    callback(xhr.status === 0 || xhr.responseURL !== ads_url);
                }
            };
            xhr.open('HEAD', ads_url, true);
            xhr.send(null);
        }

        request_ads_to_display(is_blocked => {
            if(is_blocked) {
                setTimeout(() => {
                    $('#ad_blocker_detector_modal').modal('show');
                }, <?= (int) (settings()->ads->ad_blocker_detector_delay ?? 2) ?> * 1000);
            }
            
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
