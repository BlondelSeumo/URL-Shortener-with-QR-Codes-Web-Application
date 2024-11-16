<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/clipboard.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    let clipboard = new ClipboardJS('[data-clipboard-text],[data-clipboard-target]');

    /* Copy full url handler */
    $('[data-clipboard-text],[data-clipboard-target]').on('click', event => {
        let copy = event.currentTarget.dataset.copy;
        let copied = event.currentTarget.dataset.copied;

        $(event.currentTarget).attr('data-original-title', copied).tooltip('show');

        setTimeout(() => {
            $(event.currentTarget).attr('data-original-title', copy);
        }, 500);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'clipboard_js') ?>
