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

class AdminPushSubscribers extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'device_type', 'country_code', 'continent_code'], ['ip', 'city_name', 'os_name', 'browser_name', 'browser_language'], ['datetime']));
        $filters->set_default_order_by('push_subscriber_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `push_subscribers` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/push-subscribers?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $push_subscribers = [];
        $push_subscribers_result = database()->query("
            SELECT
                `push_subscribers`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `push_subscribers`
            LEFT JOIN
                `users` ON `push_subscribers`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('push_subscribers')}
                {$filters->get_sql_order_by('push_subscribers')}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $push_subscribers_result->fetch_object()) {
            $push_subscribers[] = $row;
        }

        /* Export handler */
        process_export_json($push_subscribers, 'include', ['push_subscriber_id', 'user_id', 'subscriber_id', 'endpoint', 'keys', 'ip', 'city_name', 'country_code', 'continent_code', 'os_name', 'browser_name', 'browser_language', 'device_type', 'datetime']);
        process_export_csv($push_subscribers, 'include', ['push_subscriber_id', 'user_id', 'subscriber_id', 'endpoint', 'ip', 'city_name', 'country_code', 'continent_code', 'os_name', 'browser_name', 'browser_language', 'device_type', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'push_subscribers' => $push_subscribers,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/push-subscribers/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/push-subscribers');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/push-subscribers');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/push-subscribers');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $id) {
                        db()->where('push_subscriber_id', $id)->delete('push_subscribers');
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/push-subscribers');
    }

    public function delete() {

        $push_subscriber_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$internal_notification = db()->where('push_subscriber_id', $push_subscriber_id)->getOne('push_subscribers', ['push_subscriber_id'])) {
            redirect('admin/push-subscribers');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('push_subscriber_id', $push_subscriber_id)->delete('push_subscribers');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $internal_notification->title . '</strong>'));

        }

        redirect('admin/push-subscribers');
    }

}
