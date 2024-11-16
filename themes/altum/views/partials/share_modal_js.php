<?php defined('ALTUMCODE') || die() ?>

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

                <div data-qr></div>

                <div class="d-flex align-items-center justify-content-between flex-wrap my-3">
                    <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => '%s', 'class' => 'btn btn-gray-100 mb-2', 'print_is_enabled' => false]) ?>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <input id="share_modal_value" type="text" class="form-control" value="%s" onclick="this.select();" readonly="readonly" />

                        <div class="input-group-append">
                            <button
                                    id="share_modal_value_copy"
                                    type="button"
                                    class="btn btn-light"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text="%s"
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

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/jquery-qrcode.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    /* On modal show load new data */
    $('#share_modal').on('show.bs.modal', event => {
        let url = $(event.relatedTarget).data('url');
        let qr = event.currentTarget.querySelector('[data-qr]');

        let generate_qr = (url) => {
            let default_options = {
                render: 'image',
                minVersion: 1,
                maxVersion: 40,
                ecLevel: 'L',
                left: 0,
                top: 0,
                size: 1000,
                text: url,
                quiet: 0,
                mode: 0,
                mSize: 0.1,
                mPosX: 0.5,
                mPosY: 0.5,
            };

            /* Delete already existing image generated */
            qr.querySelector('img') && qr.querySelector('img').remove();
            $(qr).qrcode(default_options);

            /* Set class to QR */
            qr.querySelector('img').classList.add('w-100');
            qr.querySelector('img').classList.add('rounded');

        }

        generate_qr(url);

        /* Update url */
        event.currentTarget.querySelectorAll('a').forEach(element => {
            let new_href = element.getAttribute('href').replace('%s', url);
            element.setAttribute('href', new_href);
        });

        event.currentTarget.querySelector('#share_modal_value').value = url;
        event.currentTarget.querySelector('#share_modal_value_copy').setAttribute('data-clipboard-text', url);

        /* Refresh clipboard */
        let modal_clipboard = new ClipboardJS('[data-clipboard-text]', {
            container: document.getElementById('share_modal')
        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
