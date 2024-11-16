<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class AccountLogs extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'device_type', 'country_code', 'continent_code'], ['ip', 'city_name', 'os_name', 'browser_name', 'browser_name'], ['datetime']));
        $filters->set_default_order_by('id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `users_logs` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('account-logs?' . $filters->get_get() . 'page=%d')));

        /* Get the logs list for the user */
        $logs = [];
        $logs_result = database()->query("SELECT * FROM `users_logs` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $logs_result->fetch_object()) $logs[] = $row;

        /* Export handler */
        process_export_json($logs, 'include', ['id', 'user_id', 'type', 'ip', 'continent_code', 'country_code', 'city_name', 'device_type', 'os_name', 'browser_name', 'browser_name', 'datetime']);
        process_export_csv($logs, 'include', ['id', 'user_id', 'type', 'ip', 'continent_code', 'country_code', 'city_name', 'device_type', 'os_name', 'browser_name', 'browser_name', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get the account header menu */
        $menu = new \Altum\View('partials/account_header_menu', (array) $this);
        $this->add_view_content('account_header_menu', $menu->run());

        /* Prepare the view */
        $data = [
            'logs' => $logs,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('account-logs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
