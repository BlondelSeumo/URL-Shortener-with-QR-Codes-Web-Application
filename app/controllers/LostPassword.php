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

class LostPassword extends Controller {

    public function index() {

        \Altum\Authentication::guard('guest');

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
            if(settings()->captcha->lost_password_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }

            /* Make sure to check against the limiter */
            if(settings()->users->lost_password_lockout_is_enabled) {
                $minutes_ago_datetime = (new \DateTime())->modify('-' . settings()->users->lost_password_lockout_time . ' minutes')->format('Y-m-d H:i:s');

                $recent_fails = db()->where('ip', get_ip())->where('type', 'lost_password.request_sent')->where('datetime', $minutes_ago_datetime, '>=')->getValue('users_logs', 'COUNT(*)');

                if($recent_fails >= settings()->users->lost_password_lockout_max_retries) {
                    Alerts::add_error(sprintf(l('global.error_message.limit_try_again'), settings()->users->lost_password_lockout_time, l('global.date.minutes')));
                    setcookie('lost_password_lockout', 'true', time()+60*settings()->users->lost_password_lockout_time, COOKIE_PATH);
                    $_COOKIE['lost_password_lockout'] = 'true';
                }
            }

            /* If there are no errors, resend the activation link */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $user = db()->where('email', $_POST['email'])->getOne('users', ['user_id', 'email', 'name', 'status', 'language', 'anti_phishing_code']);

                if($user && $user->status != 2) {
                    /* Define some variables */
                    $lost_password_code = md5($_POST['email'] . microtime());

                    /* Update the current activation email */
                    db()->where('user_id', $user->user_id)->update('users', ['lost_password_code' => $lost_password_code]);

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => $user->name,
                        ],
                        l('global.emails.user_lost_password.subject', $user->language),
                        [
                            '{{LOST_PASSWORD_LINK}}' => url('reset-password/' . md5($_POST['email']) . '/' . $lost_password_code . '?redirect=' . $redirect),
                            '{{NAME}}' => $user->name,
                        ],
                        l('global.emails.user_lost_password.body', $user->language),
                    );

                    /* Send the email */
                    send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

                    Logger::users($user->user_id, 'lost_password.request_sent');
                }

                /* Set a nice success message */
                Alerts::add_success(l('lost_password.success_message'));
            }
        }

        /* Prepare the view */
        $data = [
            'values'    => $values,
            'captcha'   => $captcha,
            'redirect_append' => $redirect_append,
        ];

        $view = new \Altum\View('lost-password/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
