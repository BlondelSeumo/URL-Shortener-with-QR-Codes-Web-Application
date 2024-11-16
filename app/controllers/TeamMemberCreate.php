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
use Altum\Title;

class TeamMemberCreate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('teams')) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        $team_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$team = db()->where('team_id', $team_id)->where('user_id', $this->user->user_id)->getOne('teams')) {
            redirect('teams');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `teams_members` WHERE `team_id` = {$team->team_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->team_members_limit != -1 && $total_rows >= $this->user->plan_settings->team_members_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('team/' . $team->team_id);
        }

        $teams_access = require APP_PATH . 'includes/teams_access.php';

        if(!empty($_POST)) {
            $_POST['user_email'] = trim(filter_var($_POST['user_email'], FILTER_SANITIZE_EMAIL));

            /* Generate the access variable for the database */
            $access = [];
            foreach($teams_access as $key => $value) {
                foreach($value as $access_key => $access_translation) {
                    $access[$access_key] = in_array($access_key, $_POST['access'] ?? []);
                }
            }

            /* Force read access */
            $access['read.all'] = true;

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['user_email'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!filter_var($_POST['user_email'], FILTER_VALIDATE_EMAIL)) {
                Alerts::add_field_error('user_email', l('global.error_message.invalid_email'));
            }

            if($_POST['user_email'] == $this->user->email) {
                Alerts::add_field_error('user_email', '');
            }

            if(db()->where('user_email', $_POST['user_email'])->where('team_id', $team->team_id)->has('teams_members')) {
                Alerts::add_field_error('user_email', l('team_members.error_message.email_exists'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

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
                        '{{USER:NAME}}' => str_replace('.', '. ', $this->user->name),
                        '{{USER:EMAIL}}' => $this->user->email,
                        '{{LOGIN_LINK}}' => url('login?redirect=teams-member&email=' . $_POST['user_email']),
                        '{{REGISTER_LINK}}' => url('register?redirect=teams-member&email=' . $_POST['user_email']) . '&unique_registration_identifier=' . md5($_POST['user_email'] . $_POST['user_email']),
                    ],
                    $user_exists ? l('global.emails.team_member_create.body_login') : l('global.emails.team_member_create.body_register'));

                send_mail($_POST['user_email'], $email_template->subject, $email_template->body);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('team_member_create.success_message'), '<strong>' . $_POST['user_email'] . '</strong>'));

                redirect('team/' . $team_id);
            }
        }

        /* Set default values */
        $values = [
            'user_email' => $_POST['user_email'] ?? '',
            'access' => $_POST['access'] ?? ['read.all'],
        ];

        /* Set a custom title */
        Title::set(sprintf(l('team_member_create.title'), $team->name));

        /* Prepare the view */
        $data = [
            'values' => $values,
            'team' => $team,
            'teams_access' => $teams_access,
        ];

        $view = new \Altum\View('team-member-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
