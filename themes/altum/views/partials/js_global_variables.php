<?php defined('ALTUMCODE') || die() ?>

<input type="hidden" name="global_site_url" value="<?= SITE_URL ?>" />
<input type="hidden" name="global_url" value="<?= url() ?>" />
<input type="hidden" name="global_token" value="<?= \Altum\Csrf::get('global_token') ?>" />
<input type="hidden" name="global_number_decimal_point" value="<?= l('global.number.decimal_point') ?>" />
<input type="hidden" name="global_number_thousands_separator" value="<?= l('global.number.thousands_separator') ?>" />

<script>
    /* Some global variables */
    window.altum = {};
    let global_token = document.querySelector('input[name="global_token"]').value;
    let site_url = document.querySelector('input[name="global_site_url"]').value;
    let url = document.querySelector('input[name="global_url"]').value;
    let decimal_point = document.querySelector('[name="global_number_decimal_point"]').value;
    let thousands_separator = document.querySelector('[name="global_number_thousands_separator"]').value;
</script>
