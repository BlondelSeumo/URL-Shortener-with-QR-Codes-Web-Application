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
use Altum\Captcha;
use Altum\Logger;

class ResendActivation extends Controller {

    public function index() {

        \Altum\Authentication::guard('guest');

        if(!settings()->users->email_confirmation) {
            redirect('not-found');
        }

        $redirect = process_and_get_redirect_params() ?? 'dashboard';
        $redirect_append = $redirect ? '?redirect=' . $redirect : null;

        /* Default values */
        $values = [
            'email' => ''
        ];

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST)) {
            /* Clean the posted variable */
            $_POST['email'] = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $values['email'] = $_POST['email'];

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for any errors */
            if(settings()->captcha->resend_activation_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }

            /* Make sure to check against the limiter */
            if(settings()->users->resend_activation_lockout_is_enabled) {
                $minutes_ago_datetime = (new \DateTime())->modify('-' . settings()->users->resend_activation_lockout_time . ' minutes')->format('Y-m-d H:i:s');

                $recent_fails = db()->where('ip', get_ip())->where('type', 'resend_activation.request_sent')->where('datetime', $minutes_ago_datetime, '>=')->getValue('users_logs', 'COUNT(*)');

                if($recent_fails >= settings()->users->resend_activation_lockout_max_retries) {
                    Alerts::add_error(sprintf(l('global.error_message.limit_try_again'), settings()->users->resend_activation_lockout_time, l('global.date.minutes')));
                    setcookie('resend_activation_lockout', 'true', time()+60*settings()->users->resend_activation_lockout_time, COOKIE_PATH);
                    $_COOKIE['resend_activation_lockout'] = 'true';
                }
            }

            /* If there are no errors, resend the activation link */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $user = db()->where('email', $_POST['email'])->getOne('users', ['user_id', 'status', 'name', 'email', 'language']);

                if($user && !$user->status) {
                    /* Generate new email code */
                    $email_code = md5($_POST['email'] . microtime());

                    /* Update the current activation email */
                    db()->where('user_id', $user->user_id)->update('users', ['email_activation_code' => $email_code]);

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $user->name,
                        ],
                        l('global.emails.user_activation.subject', $user->language),
                        [
                            '{{ACTIVATION_LINK}}' => url('activate-user?email=' . md5($_POST['email']) . '&email_activation_code=' . $email_code . '&type=user_activation' . '&redirect=' . $redirect),
                            '{{NAME}}' => $user->name,
                        ],
                        l('global.emails.user_activation.body', $user->language)
                    );

                    /* Send the email */
                    send_mail($_POST['email'], $email_template->subject, $email_template->body);

                    Logger::users($user->user_id, 'resend_activation.request_sent');
                }

                /* Set a nice success message */
                Alerts::add_success(l('resend_activation.success_message'));
            }
        }

        /* Prepare the view */
        $data = [
            'values'    => $values,
            'captcha'   => $captcha,
            'redirect_append' => $redirect_append,
        ];

        $view = new \Altum\View('resend-activation/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
