<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script>
    'use strict';
    /* PWA */
    <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
    if('setAppBadge' in navigator) {
        navigator.setAppBadge(<?= (int) $data->has_pending_internal_notifications ?>);
    }
    <?php endif ?>

    $('#internal_notifications').on('hidden.bs.dropdown', async event => {
        document.querySelector('#internal_notifications_content').innerHTML = `
            <div class="d-flex justify-content-center align-items-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>`;
    })

    $('#internal_notifications_link').on('click', event => {
        if(window.innerWidth <= 991) {
            redirect('internal-notifications');

            event.stopPropagation();
            event.preventDefault();
        }
    });

    $('#internal_notifications').on('show.bs.dropdown', async event => {

        document.querySelector('#internal_notifications_content').innerHTML = `
            <div class="d-flex justify-content-center align-items-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
            </div>`;

        /* Get type of notification to retrieve */
        let for_who = document.querySelector('#internal_notifications_link').getAttribute('data-internal-notifications');

        /* Send request to server */
        let response = await fetch(`${url}internal-notifications/get_ajax?for_who=${for_who}`, {
            method: 'get',
        });

        let data = null;
        try {
            data = await response.json();
        } catch (error) {
            /* :)  */
        }

        if(!response.ok) {
            /* :)  */
        }

        if(data.status == 'error') {
            /* :)  */
        } else if(data.status == 'success') {

            let notifications_html = '';

            if(data.details.internal_notifications.length) {
                data.details.internal_notifications.forEach(notification => {
                    notifications_html += `
                    <div class="bg-gray-100 p-3 my-3 rounded ${notification.is_read ? null : 'border border-info'} icon-zoom-animation">
                        <div class="d-flex align-items-center justify-content-between position-relative ">
                            <div class="d-flex align-items-center">
                                <div class="p-3 bg-gray-50 mr-3 rounded">
                                    <i class="${notification.icon} fa-fw text-primary-900"></i>
                                </div>

                                <div class="d-flex flex-column">
                                    <small class="font-weight-bold text-body mb-1">
                                        ${notification.url ? `<a href="${notification.url}" class="stretched-link text-decoration-none text-body">${notification.title}</a>` : notification.title}
                                    </small>

                                    <small class="text-muted">${notification.description}</small>

                                    <div>
                                        <small class="text-muted">${notification.datetime_timeago}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                });

                notifications_html += `
                    <div class="mb-2">
                        <a href="${url}internal-notifications" class="btn btn-light btn-sm btn-block"><?= l('global.view_more') ?></a>
                    </div>
                `;
            } else {
                notifications_html = `
                <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                    'filters_get' => $data->filters->get ?? [],
                    'name' => 'internal_notifications',
                    'has_secondary_text' => false,
                    'has_wrapper' => false,
                ]); ?>
                `;
            }

            document.querySelector('#internal_notifications_content').innerHTML = notifications_html;

            /* Change the icon from unread to read */
            if(data.details.internal_notifications.length) {
                document.querySelector('#internal_notifications_icon_wrapper') && document.querySelector('#internal_notifications_icon_wrapper').remove();
                document.querySelector('#internal_notifications_link').innerHTML = '<i class="fas fa-fw fa-bell"></i>';
            }

            /* PWA */
            <?php if(\Altum\Plugin::is_active('pwa') && settings()->pwa->is_enabled): ?>
            if('setAppBadge' in navigator) {
                navigator.setAppBadge(0);
            }
            <?php endif ?>
        }
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
