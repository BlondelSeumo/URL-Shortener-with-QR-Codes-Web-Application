<?php defined('ALTUMCODE') || die() ?>

<?php

/* Get some variables */
$biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';

/* Get the proper settings depending on the type of link */
$settings = require THEME_PATH . 'views/link/settings/' . mb_strtolower($data->link->type) . '.php';

?>

<?= $settings->html ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    let update_main_url = (new_url) => {
        $('#link_url').text(new_url);

        let new_full_url = null;
        let new_full_url_no_protocol = null;
        if($('select[name="domain_id"]').length) {
            let selected_domain_id_element = $('select[name="domain_id"]').find(':selected');
            new_full_url = `${selected_domain_id_element.data('full-url')}${new_url}`;
            new_full_url_no_protocol = `${selected_domain_id_element.text()}${new_url}`;
        } else {
            new_full_url_no_protocol = new_full_url = `${$('input[name="link_base"]').val()}${new_url}`;
        }

        $('#link_full_url').text(new_full_url_no_protocol).attr('href', new_full_url);
        $('#link_full_url_copy').attr('data-clipboard-text', new_full_url);

        /* Refresh iframe */
        refresh_biolink_preview();
    };

    let refresh_biolink_preview = () => {
        if(!document.querySelector('#biolink_preview_iframe')) {
            return;
        }

        /* Add loader */
        document.querySelector('#biolink_preview_iframe_loading').classList.remove('d-none');

        /* Refresh iframe */
        let biolink_preview_iframe = document.querySelector('#biolink_preview_iframe');

        setTimeout(() => {
            biolink_preview_iframe.setAttribute('src', biolink_preview_iframe.getAttribute('src'));
        }, 750)

        biolink_preview_iframe.onload = () => {
            document.querySelector('#biolink_preview_iframe').dispatchEvent(new Event('refreshed'));
            document.querySelector('#biolink_preview_iframe_loading').classList.add('d-none');
        }
    }

    <?php if(count($data->domains)): ?>
    /* Is main link handler */
    let is_main_link_handler = () => {
        if(document.querySelector('#is_main_link').checked) {
            document.querySelector('#url').setAttribute('disabled', 'disabled');
        } else {
            document.querySelector('#url').removeAttribute('disabled');
        }
    }

    document.querySelector('#is_main_link') && document.querySelector('#is_main_link').addEventListener('change', is_main_link_handler);

    /* Domain Id Handler */
    let domain_id_handler = () => {
        let domain_id = document.querySelector('select[name="domain_id"]').value;

        if(document.querySelector(`select[name="domain_id"] option[value="${domain_id}"]`).getAttribute('data-type') == '0') {
            document.querySelector('#is_main_link_wrapper').classList.remove('d-none');
        } else {
            document.querySelector('#is_main_link_wrapper').classList.add('d-none');
            document.querySelector('#is_main_link').checked = false;
        }

        is_main_link_handler();
    }

    domain_id_handler();

    document.querySelector('select[name="domain_id"]') && document.querySelector('select[name="domain_id"]').addEventListener('change', domain_id_handler);
    <?php endif ?>
</script>

<?= $settings->javascript ?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
