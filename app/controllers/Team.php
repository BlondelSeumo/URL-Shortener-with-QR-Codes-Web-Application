<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Title;

class Team extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$team = db()->where('team_id', $team_id)->where('user_id', $this->user->user_id)->getOne('teams')) {
            redirect('teams');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id'], ['user_email'], ['datetime']));
        $filters->set_default_order_by('team_member_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams_members` WHERE `team_id` = {$team->team_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('team/' . $team->team_id . '?' . $filters->get_get() . 'page=%d')));

        /* Get the teams list for the user */
        $team_members = [];
        $team_members_result = database()->query("
            SELECT `teams_members`.*, `users`.`name`, `users`.`email`
            FROM `teams_members` 
            LEFT JOIN `users` ON `users`.`user_id` = `teams_members`.`user_id` 
            WHERE `teams_members`.`team_id` = {$team->team_id} {$filters->get_sql_where('teams_members')} 
            {$filters->get_sql_order_by('teams_members')} 
            {$paginator->get_sql_limit()}
        ");
        while($row = $team_members_result->fetch_object()) {
            $row->access = json_decode($row->access);
            $team_members[] = $row;
        }

        /* Export handler */
        process_export_json($team_members, 'include', ['team_member_id', 'team_id', 'user_id', 'name', 'email', 'access', 'datetime', 'last_datetime']);
        process_export_csv($team_members, 'include', ['team_member_id', 'team_id', 'user_id', 'name', 'email', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Set a custom title */
        Title::set(sprintf(l('team.title'), $team->name));

        /* Prepare the view */
        $data = [
            'team' => $team,
            'team_members' => $team_members,
            'total_team_members'=> $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'teams_access' => require APP_PATH . 'includes/teams_access.php',
        ];

        $view = new \Altum\View('team/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
