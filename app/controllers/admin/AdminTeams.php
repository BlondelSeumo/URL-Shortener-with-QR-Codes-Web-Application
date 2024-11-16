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

class AdminTeams extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('team_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/teams?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $teams = [];
        $teams_result = database()->query("
            SELECT
                `teams`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `teams`
            LEFT JOIN
                `users` ON `teams`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('teams')}
                {$filters->get_sql_order_by('teams')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $teams_result->fetch_object()) {
            $teams[] = $row;
        }

        /* Export handler */
        process_export_json($teams, 'include', ['team_id', 'user_id', 'name', 'datetime', 'last_datetime'], sprintf(l('teams.title')));
        process_export_csv($teams, 'include', ['team_id', 'user_id', 'name', 'datetime', 'last_datetime'], sprintf(l('teams.title')));


        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'teams' => $teams,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/teams/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/teams');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/teams');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/teams');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $team_id) {

                        /* Delete the team */
                        db()->where('team_id', $team_id)->delete('teams');

                        /* Clear the cache */
                        cache()->deleteItemsByTag('team_id=' . $team_id);
                        cache()->deleteItem('team?team_id=' . $team_id);

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/teams');
    }

    public function delete() {

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$team = db()->where('team_id', $team_id)->getOne('teams', ['team_id', 'name'])) {
            redirect('admin/teams');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the team */
            db()->where('team_id', $team_id)->delete('teams');

            /* Clear the cache */
            cache()->deleteItemsByTag('team_id=' . $team_id);
            cache()->deleteItem('team?team_id=' . $team_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $team->name . '</strong>'));

        }

        redirect('admin/teams');
    }

}
