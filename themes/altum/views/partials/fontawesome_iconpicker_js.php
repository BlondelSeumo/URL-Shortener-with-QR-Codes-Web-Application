<?php defined('ALTUMCODE') || die() ?>

<?php if(!\Altum\Event::exists_content_type_key('javascript', 'fontawesome_iconpicker')): ?>
    <?php ob_start() ?>
    <link href="<?= ASSETS_FULL_URL . 'css/libraries/fontawesome-iconpicker.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php ob_start() ?>
    <script src="<?= ASSETS_FULL_URL . 'js/libraries/fontawesome-iconpicker.min.js?v=' . PRODUCT_CODE ?>"></script>

    <script>
        'use strict';

        $('input[name="icon"]').iconpicker({
            animation: false,
            templates: {
                popover: '<div class="iconpicker-popover popover"><div class="popover-title"></div><div class="popover-content"></div></div>',
                search: '<input type="search" class="form-control iconpicker-search" placeholder="<?= l('global.search') ?>" />',
                iconpicker: '<div class="iconpicker"><div class="iconpicker-items"></div></div>',
                iconpickerItem: '<a role="button" href="javascript:;" class="iconpicker-item"><i></i></a>'
            }
        });
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
