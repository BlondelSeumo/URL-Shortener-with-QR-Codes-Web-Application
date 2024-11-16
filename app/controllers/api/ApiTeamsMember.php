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

class ApiTeamsMember extends Controller {
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
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams_members` WHERE (`user_id` = {$this->api_user->user_id} OR `user_email` = '{$this->api_user->email}')")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/teams-member?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                `teams`.`name`, `teams_members`.*
            FROM
                `teams_members`
            LEFT JOIN `teams` ON `teams`.`team_id` = `teams_members`.`team_id` 
            WHERE 
                  (`teams_members`.`user_id` = {$this->api_user->user_id} 
                  OR `teams_members`.`user_email` = '{$this->api_user->email}')
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->team_member_id,
                'access' => json_decode($row->access),
                'status' => (int) $row->status,
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime,
                'team_id' => (int) $row->team_id,
                'user_id' => (int) $row->user_id,
                'name' => $row->name,
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

        $team_member_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team_member = db()->where('team_member_id', $team_member_id)->where('user_id', $this->api_user->user_id)->getOne('teams_members');

        /* We haven't found the resource */
        if(!$team_member) {
            $this->return_404();
        }

        /* Get the main resource too */
        $team = db()->where('team_id', $team_member->team_id)->getOne('teams');

        /* Prepare the data */
        $data = [
            'id' => (int) $team_member->team_member_id,
            'access' => json_decode($team_member->access),
            'status' => (int) $team_member->status,
            'last_datetime' => $team_member->last_datetime,
            'datetime' => $team_member->datetime,
            'team_id' => (int) $team->team_id,
            'user_id' => (int) $team->user_id,
            'name' => $team->name,
        ];

        Response::jsonapi_success($data);

    }

    private function patch() {

        $team_member_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team_member = db()->where('team_member_id', $team_member_id)->getOne('teams_members');

        /* We haven't found the resource */
        if(!$team_member) {
            $this->return_404();
        }

        if($team_member && $team_member->user_id != $this->api_user->user_id && $team_member->user_email != $this->api_user->email) {
            $this->return_404();
        }

        /* Check for any errors */
        $required_fields = ['status'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        $_POST['status'] = (int) (bool) $_POST['status'];

        /* Database query */
        db()->where('team_member_id', $team_member->team_member_id)->update('teams_members', [
            'user_id' => $this->api_user->user_id,
            'status' => $_POST['status'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('team_member?team_id=' . $team_member->team_id . '&user_id=' . $team_member->user_id);

        /* Prepare the data */
        $data = [
            'id' => $team_member->team_member_id
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $team_member_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team_member = db()->where('team_member_id', $team_member_id)->getOne('teams_members');

        /* We haven't found the resource */
        if(!$team_member) {
            $this->return_404();
        }

        if($team_member && $team_member->user_id != $this->api_user->user_id && $team_member->user_email != $this->api_user->email) {
            $this->return_404();
        }

        /* Delete the resource */
        db()->where('team_member_id', $team_member->team_member_id)->delete('teams_members');

        /* Clear the cache */
        cache()->deleteItem('team_member?team_id=' . $team_member->team_id . '&user_id=' . $team_member->user_id);

        http_response_code(200);
        die();

    }

}
