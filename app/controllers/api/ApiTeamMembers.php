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

class ApiTeamMembers extends Controller {
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

    private function get() {

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $team = db()->where('team_id', $team_id)->where('user_id', $this->api_user->user_id)->getOne('teams');

        /* We haven't found the resource */
        if(!$team) {
            $this->return_404();
        }

        /* Get all the team members */
        $data = [];
        $team_members_result = database()->query("SELECT * FROM `teams_members` WHERE `team_id` = {$team->team_id}");
        while($team_member = $team_members_result->fetch_object()) {

            /* Prepare the data */
            $team_member = [
                'id' => (int) $team_member->team_member_id,
                'team_id' => (int) $team_member->team_id,
                'user_id' => (int) $team_member->user_id,
                'user_email' => $team_member->user_email,
                'access' => json_decode($team_member->access),
                'status' => (int) $team_member->status,
                'last_datetime' => $team_member->last_datetime,
                'datetime' => $team_member->datetime,
            ];

            $data[] = $team_member;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => 1,
            'results_per_page' => -1,
            'total_results' => (int) count($data),
        ];

        Response::jsonapi_success($data, $meta, 200);

    }

    private function post() {

        /* Check for the plan limit */
        $total_rows = db()->where('user_id', $this->api_user->user_id)->getValue('teams_members', 'count(`team_member_id`)');

        if($this->api_user->plan_settings->team_members_limit != -1 && $total_rows >= $this->api_user->plan_settings->team_members_limit) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        /* Check for any errors */
        $required_fields = ['team_id', 'user_email'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        if(!$team = db()->where('team_id', $_POST['team_id'])->where('user_id', $this->api_user->user_id)->getOne('teams')) {
            $this->return_404();
        }

        if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
            $this->response_error(l('global.error_message.invalid_email'), 401);
        }

        if($_POST['user_email'] == $this->api_user->email) {
            $this->response_error('', 401);
        }

        if(db()->where('user_email', $_POST['user_email'])->where('team_id', $team->team_id)->has('teams_members')) {
            $this->response_error(l('team_members.error_message.email_exists'), 401);
        }

        $teams_access = require APP_PATH . 'includes/teams_access.php';

        $_POST['team_id'] = (int) $_POST['team_id'];
        $_POST['user_email'] = trim(filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));

        /* Generate the access variable for the database */
        $access = [];
        foreach($teams_access as $key => $value) {
            foreach($value as $access_key => $access_translation) {
                $access[$access_key] = in_array($access_key, $_POST['access']);
            }
        }

        /* Force read access */
        $access['read.all'] = true;

        /* Database query */
        $team_member_id = db()->insert('teams_members', [
            'team_id' => $team->team_id,
            'user_email' => $_POST['user_email'],
            'access' => json_encode($access),
            'datetime' => get_date(),
        ]);

        /* Is the invited user already registered on the platform? */
        $user_exists = db()->where('email', $_POST['user_email'])->has('users');

        /* Prepare the email */
        $email_template = get_email_template(
            [
                '{{TEAM:NAME}}' => $team->name,
            ],
            l('global.emails.team_member_create.subject'),
            [
                '{{TEAM:NAME}}' => $team->name,
                '{{USER:NAME}}' => str_replace('.', '. ', $this->api_user->name),
                '{{USER:EMAIL}}' => $this->api_user->email,
                '{{LOGIN_LINK}}' => url('login?redirect=teams-system&email=' . $_POST['user_email']),
                '{{REGISTER_LINK}}' => url('register?redirect=teams-system&email=' . $_POST['user_email']) . '&unique_registration_identifier=' . md5($_POST['user_email'] . $_POST['user_email']),
            ],
            $user_exists ? l('global.emails.team_member_create.body_login') : l('global.emails.team_member_create.body_register'));

        send_mail($_POST['user_email'], $email_template->subject, $email_template->body);

        /* Prepare the data */
        $data = [
            'id' => $team_member_id
        ];

        Response::jsonapi_success($data, null, 201);

    }

    private function patch() {

        $team_member_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        if(!$team_member = db()->where('team_member_id', $team_member_id)->getOne('teams_members')) {
            $this->return_404();
        }

        if(!$team = db()->where('team_id', $team_member->team_id)->where('user_id', $this->api_user->user_id)->getOne('teams')) {
            $this->return_404();
        }

        $teams_access = require APP_PATH . 'includes/teams_access.php';

        /* Generate the access variable for the database */
        $access = [];
        foreach($teams_access as $key => $value) {
            foreach($value as $access_key => $access_translation) {
                $access[$access_key] = in_array($access_key, $_POST['access'] ?? []);
            }
        }

        /* Force read access */
        $access['read.all'] = true;

        /* Database query */
        db()->where('team_id', $team->team_id)->update('teams_members', [
            'access' => json_encode($access),
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
        if(!$team_member = db()->where('team_member_id', $team_member_id)->getOne('teams_members')) {
            $this->return_404();
        }

        if(!$team = db()->where('team_id', $team_member->team_id)->where('user_id', $this->api_user->user_id)->getOne('teams')) {
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
