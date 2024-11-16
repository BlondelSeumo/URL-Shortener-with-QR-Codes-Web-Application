<?php defined('ALTUMCODE') || die() ?>

<?php if(!\Altum\Event::exists_content_type_key('javascript', 'select2')): ?>
    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/select2.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/select2.js?v=' . PRODUCT_CODE ?>"></script>

    <script>
        'use strict';

        /* Custom select implementation */
        $('select[class^="input-group-text"]:not([multiple="multiple"])').select2({
            dir: <?= json_encode(l('direction')) ?>,
            minimumResultsForSearch: 5,
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
