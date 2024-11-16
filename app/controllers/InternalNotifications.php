<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;


use Altum\Response;

class InternalNotifications extends Controller {

    public function index() {

        if(!settings()->internal_notifications->users_is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], ['datetime']));
        $filters->set_default_order_by('internal_notification_id', 'DESC');
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `internal_notifications` WHERE (`user_id` = {$this->user->user_id} OR `user_id` IS NULL) AND `for_who` = 'user' {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('internal-notifications?' . $filters->get_get() . '&page=%d')));

        /* Get the internal_notifications list for the user */
        $internal_notifications = [];
        $internal_notifications_result = database()->query("SELECT * FROM `internal_notifications` WHERE (`user_id` = {$this->user->user_id} OR `user_id` IS NULL) AND `for_who` = 'user' {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $internal_notifications_result->fetch_object()) $internal_notifications[] = $row;

        /* Export handler */
        process_export_json($internal_notifications, 'include', ['internal_notification_id', 'user_id', 'for_who', 'from_who', 'icon', 'title', 'description', 'url', 'is_read', 'datetime', 'read_datetime',]);
        process_export_csv($internal_notifications, 'include', ['internal_notification_id', 'user_id', 'for_who', 'from_who', 'icon', 'title', 'description', 'url', 'is_read', 'datetime', 'read_datetime',]);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'internal_notifications' => $internal_notifications,
            'total_internal_notifications' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('internal-notifications/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function get_ajax() {

        if(!empty($_POST)) {
            redirect();
        }

        \Altum\Authentication::guard();

        $for_who = isset($_GET['for_who']) && in_array($_GET['for_who'], ['user', 'admin']) ? $_GET['for_who'] : 'user';

        switch($for_who) {
            case 'user':

                if(!settings()->internal_notifications->users_is_enabled) {
                    redirect('not-found');
                }

                $internal_notifications = db()->where('for_who', 'user')->where('(`user_id` = ? OR `user_id` IS NULL)', [$this->user->user_id])->orderBy('internal_notification_id', 'DESC')->get('internal_notifications', 3);

                $should_set_all_read = false;
                foreach($internal_notifications as $notification) {
                    if(!$notification->is_read) $should_set_all_read = true;
                    $notification->datetime_timeago = \Altum\Date::get_timeago($notification->datetime);
                }

                if($should_set_all_read) {
                    db()->where('for_who', 'user')->where('(`user_id` = ? OR `user_id` IS NULL)', [$this->user->user_id])->update('internal_notifications', [
                        'is_read' => 1,
                        'read_datetime' => get_date(),
                    ]);

                    db()->where('user_id', $this->user->user_id)->update('users', [
                        'has_pending_internal_notifications' => 0
                    ]);

                    /* Clear the cache */
                    cache()->deleteItemsByTag('user_id=' . $this->user->user_id);
                }

                Response::json('', 'success', ['internal_notifications' => $internal_notifications]);

                break;

            case 'admin':

                if(!settings()->internal_notifications->admins_is_enabled) {
                    redirect('not-found');
                }

                /* :) */

                break;
        }

    }

}
