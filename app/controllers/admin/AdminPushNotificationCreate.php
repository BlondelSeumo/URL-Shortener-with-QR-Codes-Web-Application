<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Alerts;

class AdminPushNotificationCreate extends Controller {

    public function index() {

        /* Clear $_GET */
        foreach($_GET as $key => $value) {
            $_GET[$key] = input_clean($value);
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['title'] = input_clean($_POST['title'], 64);
            $_POST['description'] = input_clean($_POST['description'], 128);
            $_POST['url'] = get_url($_POST['url'], 512);
            $_POST['segment'] = in_array($_POST['segment'], ['all', 'custom', 'filter']) ? input_clean($_POST['segment']) : 'all';

            $_POST['push_subscribers_ids'] = trim($_POST['push_subscribers_ids'] ?? '');
            if($_POST['push_subscribers_ids']) {
                $_POST['push_subscribers_ids'] = explode(',', $_POST['push_subscribers_ids'] ?? '');
                if(count($_POST['push_subscribers_ids'])) {
                    $_POST['push_subscribers_ids'] = array_map(function ($user_id) {
                        return (int) $user_id;
                    }, $_POST['push_subscribers_ids']);
                    $_POST['push_subscribers_ids'] = array_unique($_POST['push_subscribers_ids']);
                }
            }

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $required_fields = ['title', 'description'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = [];

                /* Get all the users needed */
                switch($_POST['segment']) {
                    case 'all':
                        $push_subscribers = db()->get('push_subscribers', null, ['push_subscriber_id', 'user_id']);
                        break;

                    case 'custom':
                        $push_subscribers = db()->where('push_subscriber_id', $_POST['push_subscribers_ids'], 'IN')->get('push_subscribers', null, ['push_subscriber_id']);
                        break;

                    case 'filter':

                        $query = db();

                        $has_filters = false;

                        /* Is registered */
                        if(isset($_POST['filters_is_registered'])) {
                            $has_filters = true;

                            if(isset($_POST['filters_is_registered']['yes']) && !isset($_POST['filters_is_registered']['no'])) {
                                $query->where('user_id', NULL, 'IS NOT');
                            }

                            if(isset($_POST['filters_is_registered']['no']) && !isset($_POST['filters_is_registered']['yes'])) {
                                $query->where('user_id', NULL, 'IS');
                            }

                            if(isset($_POST['filters_is_registered']['no']) && isset($_POST['filters_is_registered']['yes'])) {
                                $query->where('user_id', NULL, 'IS NOT');
                                $query->orWhere('user_id', NULL, 'IS NOT');
                            }
                        }

                        /* Countries */
                        if(isset($_POST['filters_countries'])) {
                            $has_filters = true;
                            $query->where('country', $_POST['filters_countries'], 'IN');
                        }

                        /* Continents */
                        if(isset($_POST['filters_continents'])) {
                            $has_filters = true;
                            $query->where('continent_code', $_POST['filters_continents'], 'IN');
                        }

                        /* Device type */
                        if(isset($_POST['filters_device_type'])) {
                            $has_filters = true;
                            $query->where('device_type', $_POST['filters_device_type'], 'IN');
                        }

                        $push_subscribers = $has_filters ? $query->get('push_subscribers', null, ['push_subscriber_id']) : [];

                        break;
                }

                $push_subscribers_ids = [];
                foreach($push_subscribers as $push_subscriber) {
                    $push_subscribers_ids[] = $push_subscriber->push_subscriber_id;
                }

                /* Database query */
                $push_notification_id = db()->insert('push_notifications', [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'url' => $_POST['url'],
                    'segment' => $_POST['segment'],
                    'settings' => json_encode($settings),
                    'push_subscribers_ids' => json_encode($push_subscribers_ids),
                    'sent_push_subscribers_ids' => '[]',
                    'total_push_notifications' => count($push_subscribers_ids),
                    'status' => isset($_POST['save']) ? 'draft' : 'processing',
                    'datetime' => get_date(),
                ]);

                if(isset($_POST['save'])) {
                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('admin_push_notification_create.success_message.save'), '<strong>' . $_POST['title'] . '</strong>'));
                } else {
                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('admin_push_notification_create.success_message.send'), '<strong>' . $_POST['title'] . '</strong>'));
                }

                redirect('admin/push-notifications');
            }
        }

        $values = [
            'title' => $_GET['title'] ?? $_POST['title'] ?? null,
            'description' => $_GET['description'] ?? $_POST['description'] ?? null,
            'url' => $_GET['url'] ?? $_POST['url'] ?? null,
            'segment' => $_GET['segment'] ?? $_POST['segment'] ?? 'all',
            'push_subscribers_ids' => $_POST['push_subscribers_ids'] ?? null,
            'filters_is_registered' => $_POST['filters_is_registered'] ?? [],
            'filters_continents' => $_POST['filters_continents'] ?? [],
            'filters_countries' => $_POST['filters_countries'] ?? [],
        ];

        /* Main View */
        $data = [
            'values' => $values,
        ];

        $view = new \Altum\View('admin/push-notification-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
