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

class AdminInternalNotifications extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'from_who', 'for_who', 'is_read'], ['title', 'description'], ['datetime', 'read_datetime']));
        $filters->set_default_order_by('internal_notification_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `internal_notifications` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/internal-notifications?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $internal_notifications = [];
        $internal_notifications_result = database()->query("
            SELECT
                `internal_notifications`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `internal_notifications`
            LEFT JOIN
                `users` ON `internal_notifications`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('internal_notifications')}
                {$filters->get_sql_order_by('internal_notifications')}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $internal_notifications_result->fetch_object()) {
            $internal_notifications[] = $row;
        }

        /* Export handler */
        process_export_json($internal_notifications, 'include', ['internal_notification_id', 'user_id', 'for_who', 'from_who', 'icon', 'title', 'description', 'url', 'is_read', 'datetime', 'read_datetime',]);
        process_export_csv($internal_notifications, 'include', ['internal_notification_id', 'user_id', 'for_who', 'from_who', 'icon', 'title', 'description', 'url', 'is_read', 'datetime', 'read_datetime',]);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'internal_notifications' => $internal_notifications,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/internal-notifications/index', (array) $this);

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

                $count = db()->getValue('users', 'COUNT(*)');

                break;

            case 'subscribers':

                $count = db()->where('is_newsletter_subscribed', 1)->getValue('users', 'COUNT(*)');

                break;

            case 'filter':

                $query = db();

                $has_filters = false;

                /* Is subscribed */
                $_GET['filters_is_newsletter_subscribed'] = isset($_GET['filters_is_newsletter_subscribed']) ? (bool) $_GET['filters_is_newsletter_subscribed'] : 0;

                if($_GET['filters_is_newsletter_subscribed']) {
                    $has_filters = true;
                    $query->where('is_newsletter_subscribed', 1);
                }

                /* Plans */
                if(isset($_GET['filters_plans'])) {
                    $has_filters = true;
                    $query->where('plan_id', $_GET['filters_plans'], 'IN');
                }

                /* Status */
                if(isset($_GET['filters_status'])) {
                    $has_filters = true;
                    $query->where('status', $_GET['filters_status'], 'IN');
                }

                /* Countries */
                if(isset($_GET['filters_countries'])) {
                    $has_filters = true;
                    $query->where('country', $_GET['filters_countries'], 'IN');
                }

                /* Continents */
                if(isset($_GET['filters_continents'])) {
                    $has_filters = true;
                    $query->where('continent_code', $_GET['filters_continents'], 'IN');
                }

                /* Source */
                if(isset($_GET['filters_source'])) {
                    $has_filters = true;
                    $query->where('source', $_GET['filters_source'], 'IN');
                }

                /* Device type */
                if(isset($_GET['filters_device_type'])) {
                    $has_filters = true;
                    $query->where('device_type', $_GET['filters_device_type'], 'IN');
                }

                /* Languages */
                if(isset($_GET['filters_languages'])) {
                    $has_filters = true;
                    $query->where('language', $_GET['filters_languages'], 'IN');
                }

                /* Cities */
                if(!empty($_GET['filters_cities'])) {
                    $_GET['filters_cities'] = is_array($_GET['filters_cities']) ? $_GET['filters_cities'] : explode(',', $_GET['filters_cities']);

                    if(count($_GET['filters_cities'])) {
                        $_GET['filters_cities'] = array_map(function($city) {
                            return query_clean($city);
                        }, $_GET['filters_cities']);
                        $_GET['filters_cities'] = array_unique($_GET['filters_cities']);

                        $has_filters = true;
                        $query->where('city_name', $_GET['filters_cities'], 'IN');
                    }
                }

                /* Languages */
                if(isset($_GET['filters_browser_languages'])) {
                    $_GET['filters_browser_languages'] = array_filter($_GET['filters_browser_languages'], function($locale) {
                        return array_key_exists($locale, get_locale_languages_array());
                    });

                    $has_filters = true;
                    $query->where('browser_language', $_GET['filters_browser_languages'], 'IN');
                }

                /* Filters operating systems */
                if (isset($_GET['filters_operating_systems'])) {
                    $_GET['filters_operating_systems'] = array_filter($_GET['filters_operating_systems'], function($os_name) {
                        return in_array($os_name, ['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS']);
                    });

                    $has_filters = true;
                    $query->where('os_name', $_GET['filters_operating_systems'], 'IN');
                }

                /* Filters browsers */
                if (isset($_GET['filters_browsers'])) {
                    $_GET['filters_browsers'] = array_filter($_GET['filters_browsers'], function($browser_name) {
                        return in_array($browser_name, ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet']);
                    });

                    $has_filters = true;
                    $query->where('browser_name', $_GET['filters_browsers'], 'IN');
                }

                $count = $has_filters ? $query->getValue('users', 'COUNT(*)') : 0;

                break;

            default:
                $count = null;
                break;
        }

        Response::json('', 'success', ['count' => $count]);
    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/internal-notifications');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/internal-notifications');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/internal-notifications');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $id) {
                        db()->where('internal_notification_id', $id)->delete('internal_notifications');
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/internal-notifications');
    }

    public function delete() {

        $internal_notification_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$internal_notification = db()->where('internal_notification_id', $internal_notification_id)->getOne('internal_notifications', ['internal_notification_id'])) {
            redirect('admin/internal-notifications');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('internal_notification_id', $internal_notification_id)->delete('internal_notifications');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $internal_notification->title . '</strong>'));

        }

        redirect('admin/internal-notifications');
    }

}
