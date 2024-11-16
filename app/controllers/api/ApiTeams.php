<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiTeams extends Controller {
    use Apiable;

    public function index() {

        if(!\Altum\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

            break;

            case 'POST':

                /* Detect what method to use */
                if(isset($this->params[0])) {
                    $this->patch();
                } else {
                    $this->post();
                }

            break;

            case 'DELETE':
                $this->delete();
            break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by('team_id', $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/teams?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `teams`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Get all the team members */
            $team_members = [];
            $team_members_result = database()->query("SELECT `team_member_id`, `user_email`, `access`, `status`, `datetime`, `last_datetime` FROM `teams_members` WHERE `team_id` = {$row->team_id}");
            while($team_member = $team_members_result->fetch_object()) {
                $team_member->access = json_decode($team_member->access);
                $team_member->team_member_id = (int) $team_member->team_member_id;
                $team_member->status = (int) $team_member->status;
                $team_members[] = $team_member;
            }

            /* Prepare the data */
            $row = [
                'id' => (int) $row->team_id,
                'user_id' => (int) $row->user_id,
                'name' => $row->name,
                'team_members' => $team_members,
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime,
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team = db()->where('team_id', $team_id)->where('user_id', $this->api_user->user_id)->getOne('teams');

        /* We haven't found the resource */
        if(!$team) {
            $this->return_404();
        }

        /* Get all the team members */
        $team_members = [];
        $team_members_result = database()->query("SELECT `team_member_id`, `user_email`, `access`, `status`, `datetime`, `last_datetime` FROM `teams_members` WHERE `team_id` = {$team->team_id}");
        while($team_member = $team_members_result->fetch_object()) {
            $team_member->access = json_decode($team_member->access);
            $team_member->team_member_id = (int) $team_member->team_member_id;
            $team_member->status = (int) $team_member->status;
            $team_members[] = $team_member;
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $team->team_id,
            'user_id' => (int) $team->user_id,
            'name' => $team->name,
            'team_members' => $team_members,
            'last_datetime' => $team->last_datetime,
            'datetime' => $team->datetime,
        ];

        Response::jsonapi_success($data);

    }

    private function post() {

        /* Check for the plan limit */
        $total_rows = db()->where('user_id', $this->api_user->user_id)->getValue('teams', 'count(`team_id`)');

        if($this->api_user->plan_settings->teams_limit != -1 && $total_rows >= $this->api_user->plan_settings->teams_limit) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        /* Check for any errors */
        $required_fields = ['name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        $_POST['name'] = trim(input_clean($_POST['name']));

        /* Database query */
        $team_id = db()->insert('teams', [
            'user_id' => $this->api_user->user_id,
            'name' => $_POST['name'],
            'datetime' => get_date(),
        ]);

        /* Prepare the data */
        $data = [
            'id' => $team_id
        ];

        Response::jsonapi_success($data, null, 201);

    }

    private function patch() {

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team = db()->where('team_id', $team_id)->where('user_id', $this->api_user->user_id)->getOne('teams');

        /* We haven't found the resource */
        if(!$team) {
            $this->return_404();
        }

        $_POST['name'] = trim(input_clean($_POST['name'] ?? $team->name));

        /* Database query */
        db()->where('team_id', $team->team_id)->update('teams', [
            'name' => $_POST['name'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('team?team_id=' . $team->team_id);

        /* Prepare the data */
        $data = [
            'id' => $team->team_id
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team = db()->where('team_id', $team_id)->where('user_id', $this->api_user->user_id)->getOne('teams');

        /* We haven't found the resource */
        if(!$team) {
            $this->return_404();
        }

        /* Delete the resource */
        db()->where('team_id', $team_id)->delete('teams');

        /* Clear the cache */
        cache()->deleteItemsByTag('team_id=' . $team->team_id);
        cache()->deleteItem('team?team_id=' . $team->team_id);

        http_response_code(200);
        die();

    }

}
