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

class Teams extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['datetime', 'last_datetime']));
        $filters->set_default_order_by('team_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('teams?' . $filters->get_get() . 'page=%d')));

        /* Get the teams list for the user */
        $teams = [];
        $teams_result = database()->query("
            SELECT `teams`.*, COUNT(`teams_members`.`team_member_id`) AS `members` 
            FROM `teams` 
            LEFT JOIN `teams_members` ON `teams`.`team_id` = `teams_members`.`team_id` 
            WHERE `teams`.`user_id` = {$this->user->user_id} {$filters->get_sql_where('teams')} 
            GROUP BY `teams`.`team_id` 
            {$filters->get_sql_order_by('teams')} 
            {$paginator->get_sql_limit()}
        ");
        while($row = $teams_result->fetch_object()) $teams[] = $row;

        /* Export handler */
        process_export_json($teams, 'include', ['team_id', 'user_id', 'name', 'members', 'datetime', 'last_datetime']);
        process_export_csv($teams, 'include', ['team_id', 'user_id', 'name', 'members', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'teams' => $teams,
            'total_teams' => $total_rows,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('teams/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {
        \Altum\Authentication::guard();

        if(empty($_POST)) {
            redirect('teams');
        }

        $team_id = (int) query_clean($_POST['team_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('teams');
        }

        /* Make sure the team id is created by the logged in user */
        if(!$team = db()->where('team_id', $team_id)->where('user_id', $this->user->user_id)->getOne('teams')) {
            redirect('teams');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the domain */
            db()->where('team_id', $team->team_id)->delete('teams');

            /* Clear the cache */
            cache()->deleteItemsByTag('team_id=' . $team->team_id);
            cache()->deleteItem('team?team_id=' . $team->team_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $team->name . '</strong>'));

            redirect('teams');

        }

        redirect('teams');
    }
}
