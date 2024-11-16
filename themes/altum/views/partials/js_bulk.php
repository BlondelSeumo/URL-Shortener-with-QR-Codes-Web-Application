<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script>
    'use strict';

    /* Select all handler */
    document.querySelector('#bulk_select_all') && document.querySelector('#bulk_select_all').addEventListener('click', event => {
        if(event.currentTarget.checked) {
            document.querySelectorAll('td[data-bulk-table]').forEach(element => element.querySelector('input').checked = true);
        } else {
            document.querySelectorAll('td[data-bulk-table]').forEach(element => element.querySelector('input').checked = false);
        }
    });

    /* Counter handler */
    let bulk_counter_handler = () => {
        let available_count = document.querySelectorAll('td[data-bulk-table] input').length;
        let selected_count = document.querySelectorAll('td[data-bulk-table] input:checked').length;

        if(selected_count) {
            document.querySelector('#bulk_counter').textContent = `(${nr(selected_count)})`;
            document.querySelector('#bulk_counter').classList.remove('d-none');
            document.querySelector('#bulk_actions').classList.remove('disabled');
        } else {
            document.querySelector('#bulk_counter').classList.add('d-none');
            document.querySelector('#bulk_actions').classList.add('disabled');
        }

        document.querySelector('#bulk_select_all').checked = selected_count == available_count;
    }

    document.querySelectorAll('[data-bulk-table] input').forEach(element => element.addEventListener('click', bulk_counter_handler));

    /* Handler to toggle the bulk actions on */
    document.querySelector('#bulk_enable').addEventListener('click', event => {
        if(document.querySelectorAll('[data-bulk-table]').length) {
            document.querySelector('#bulk_enable').classList.add('d-none');
            document.querySelector('#bulk_group').classList.remove('d-none');
            document.querySelectorAll('[data-bulk-table]').forEach(element => element.classList.remove('d-none'));
            bulk_counter_handler();
        }
    });

    /* Handler to toggle the bulk actions off */
    document.querySelector('#bulk_disable').addEventListener('click', event => {
        document.querySelector('#bulk_group').classList.add('d-none');
        document.querySelector('#bulk_enable').classList.remove('d-none');
        document.querySelectorAll('[data-bulk-table]').forEach(element => element.classList.add('d-none'));
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
