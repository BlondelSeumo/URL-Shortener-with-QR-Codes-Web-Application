<?php defined('ALTUMCODE') || die() ?>

<?php if($this->link->settings->share_is_enabled): ?>
    <div data-toggle="modal" data-target="#share_modal" class="d-flex justify-content-center align-items-center position-absolute share-button-wrapper">
        <button type="button" class="btn share-button zoom-animation-subtle" data-toggle="tooltip" title="<?= l('global.share') ?>" data-tooltip-hide-on-click>
            <i class="fas fa-fw fa-share"></i>
        </button>
    </div>

    <?php ob_start() ?>
    <div class="modal fade" id="share_modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="d-flex justify-content-between mb-3">
                        <h5 class="modal-title">
                            <i class="fas fa-fw fa-sm fa-share-alt text-dark mr-2"></i>
                            <?= l('global.share') ?>
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="w-100" data-qr="<?= $data->link->full_url ?>"></div>

                    <div class="d-flex align-items-center justify-content-between flex-wrap my-3">
                        <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => $data->link->full_url, 'class' => 'btn btn-gray-100 mb-2', 'print_is_enabled' => false]) ?>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <input id="share" type="text" class="form-control" value="<?= $data->link->full_url ?>" onclick="this.select();" readonly="readonly" />

                            <div class="input-group-append">
                                <button
                                        type="button"
                                        class="btn btn-light"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-target="#share"
                                >
                                    <i class="fas fa-fw fa-sm fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <?php \Altum\Event::add_content(ob_get_clean(), 'modals') ?>

    <?php if(!\Altum\Event::exists_content_type_key('javascript', 'share')): ?>
        <?php ob_start() ?>
        <script src="<?= ASSETS_FULL_URL . 'js/libraries/jquery-qrcode.min.js?v=' . PRODUCT_CODE ?>"></script>

        <script>
            'use strict';

            let generate_qr = (element, data) => {
                let default_options = {
                    render: 'image',
                    minVersion: 1,
                    maxVersion: 40,
                    ecLevel: 'L',
                    left: 0,
                    top: 0,
                    size: 1000,
                    text: data,
                    quiet: 0,
                    mode: 0,
                    mSize: 0.1,
                    mPosX: 0.5,
                    mPosY: 0.5,
                };

                /* Delete already existing image generated */
                element.querySelector('img') && element.querySelector('img').remove();
                $(element).qrcode(default_options);

                /* Set class to QR */
                element.querySelector('img').classList.add('w-100');
                element.querySelector('img').classList.add('rounded');
            }

            let qr_codes = document.querySelectorAll('[data-qr]');

            qr_codes.forEach(element => {
                generate_qr(element, element.getAttribute('data-qr'));
            })
        </script>

        <?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'share') ?>
    <?php endif ?>
<?php endif ?>
