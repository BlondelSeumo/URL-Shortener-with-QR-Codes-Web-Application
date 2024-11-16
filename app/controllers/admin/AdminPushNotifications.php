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
use Altum\Response;

class AdminPushNotifications extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['status'], ['title', 'description'], ['title', 'datetime', 'last_datetime', 'total_push_notifications', 'sent_push_notifications']));
        $filters->set_default_order_by('push_notification_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `push_notifications` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/push-notifications?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $push_notifications = [];
        $push_notifications_result = database()->query("
            SELECT
                `push_notifications`.*
            FROM
                `push_notifications`
            WHERE
                1 = 1
                {$filters->get_sql_where('push_notifications')}
                {$filters->get_sql_order_by('push_notifications')}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $push_notifications_result->fetch_object()) {
            $push_notifications[] = $row;
        }

        /* Export handler */
        process_export_json($push_notifications, 'include', ['push_notification_id', 'title', 'description', 'url', 'status', 'push_subscribers_ids', 'sent_push_subscribers_ids', 'sent_push_notifications', 'total_push_notifications', 'last_sent_datetime', 'datetime', 'last_datetime',]);
        process_export_csv($push_notifications, 'include', ['push_notification_id', 'title', 'description', 'url', 'status', 'push_subscribers_ids', 'sent_push_subscribers_ids', 'sent_push_notifications', 'total_push_notifications', 'last_sent_datetime', 'datetime', 'last_datetime',]);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'push_notifications' => $push_notifications,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/push-notifications/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function get_segment_count() {

        if(!empty($_POST)) {
            redirect();
        }

        \Altum\Authentication::guard();

        $segment = isset($_GET['segment']) ? input_clean($_GET['segment']) : 'all';

        switch($segment) {
            case 'all':

                $count = db()->getValue('push_subscribers', 'COUNT(*)');

                break;

            case 'filter':

                $query = db();

                $has_filters = false;

                /* Is registered */
                if(isset($_GET['filters_is_registered'])) {
                    $has_filters = true;

                    if(isset($_GET['filters_is_registered']['yes']) && !isset($_GET['filters_is_registered']['no'])) {
                        $query->where('user_id', NULL, 'IS NOT');
                    }

                    if(isset($_GET['filters_is_registered']['no']) && !isset($_GET['filters_is_registered']['yes'])) {
                        $query->where('user_id', NULL, 'IS');
                    }

                    if(isset($_GET['filters_is_registered']['no']) && isset($_GET['filters_is_registered']['yes'])) {
                        /* :) */
                    }
                }

                /* Countries */
                if(isset($_GET['filters_countries'])) {
                    $has_filters = true;
                    $query->where('country_code', $_GET['filters_countries'], 'IN');
                }

                /* Continents */
                if(isset($_GET['filters_continents'])) {
                    $has_filters = true;
                    $query->where('continent_code', $_GET['filters_continents'], 'IN');
                }

                /* Device type */
                if(isset($_GET['filters_device_type'])) {
                    $has_filters = true;
                    $query->where('device_type', $_GET['filters_device_type'], 'IN');
                }

                $count = $has_filters ? $query->getValue('push_subscribers', 'COUNT(*)') : 0;

                break;

            default:
                $count = null;
                break;
        }

        Response::json('', 'success', ['count' => $count]);
    }

    public function duplicate() {

        if(empty($_POST)) {
            redirect('admin/push-notifications');
        }

        $push_notification_id = (int) $_POST['push_notification_id'];

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$push_notification = db()->where('push_notification_id', $push_notification_id)->getOne('push_notifications')) {
            redirect('admin/push-notifications');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Insert to database */
            $push_notification_id = db()->insert('push_notifications', [
                'title' => string_truncate($push_notification->title . ' - ' . l('global.duplicated'), 64, null),
                'description' => $push_notification->description,
                'url' => $push_notification->url,
                'segment' => $push_notification->segment,
                'settings' => $push_notification->settings,
                'push_subscribers_ids' => $push_notification->push_subscribers_ids,
                'total_push_notifications' => $push_notification->total_push_notifications,
                'status' => 'draft',
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($push_notification->name) . '</strong>'));

            /* Redirect */
            redirect('admin/push-notification-update/' . $push_notification_id);

        }

        redirect('admin/push-notifications');
    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/push-notifications');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/push-notifications');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/push-notifications');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $id) {
                        db()->where('push_notification_id', $id)->delete('push_notifications');
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/push-notifications');
    }

    public function delete() {

        $push_notification_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$push_notification = db()->where('push_notification_id', $push_notification_id)->getOne('push_notifications', ['push_notification_id'])) {
            redirect('admin/push-notifications');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('push_notification_id', $push_notification_id)->delete('push_notifications');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $push_notification->title . '</strong>'));

        }

        redirect('admin/push-notifications');
    }

}
