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
use Altum\Models\User;
use MaxMind\Db\Reader;

class Register extends Controller {

    public function index() {

        \Altum\Authentication::guard('guest');

        /* Check for a special registration identifier */
        $unique_registration_identifier = isset($_GET['unique_registration_identifier'], $_GET['email']) && $_GET['unique_registration_identifier'] == md5($_GET['email'] . $_GET['email']) ? query_clean($_GET['unique_registration_identifier']) : null;

        /* Check if Registration is enabled first */
        if(!settings()->users->register_is_enabled && (!\Altum\Plugin::is_active('teams') || (\Altum\Plugin::is_active('teams') && !$unique_registration_identifier))) {
            redirect('not-found');
        }

        $redirect = process_and_get_redirect_params() ?? 'dashboard';
        $redirect_append = $redirect ? '?redirect=' . $redirect : null;

        /* Default variables */
        $values = [
            'name' => isset($_GET['name']) ? query_clean($_GET['name']) : '',
            'email' => isset($_GET['email']) ? query_clean($_GET['email']) : '',
            'password' => ''
        ];

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST) && !settings()->users->register_only_social_logins) {

            /* Clean some posted variables */
            $_POST['name'] = mb_substr(trim(input_clean($_POST['name'])), 0, 64);
            $_POST['email'] = mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);
            $_POST['is_newsletter_subscribed'] = settings()->users->register_display_newsletter_checkbox ? (bool) $_POST['is_newsletter_subscribed'] : false;

            /* Default variables */
            $values['name'] = $_POST['name'];
            $values['email'] = $_POST['email'];
            $values['password'] = $_POST['password'];

            /* Check for any errors */
            $required_fields = ['name', 'email' ,'password'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(settings()->captcha->register_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }
            if(mb_strlen($_POST['name']) < 1 || mb_strlen($_POST['name']) > 64) {
                Alerts::add_field_error('name', l('register.error_message.name_length'));
            }
            if(db()->where('email', $_POST['email'])->has('users')) {
                Alerts::add_field_error('email', l('register.error_message.email_exists'));
            }
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
                Alerts::add_field_error('email', l('global.error_message.invalid_email'));
            }
            if(mb_strlen($_POST['password']) < 6 || mb_strlen($_POST['password']) > 64) {
                Alerts::add_field_error('password', l('global.error_message.password_length'));
            }

            /* Make sure the domain is not blacklisted */
            $email_domain = get_domain_from_email($_POST['email']);
            if(settings()->users->blacklisted_domains && in_array($email_domain, settings()->users->blacklisted_domains)) {
                Alerts::add_field_error('email', l('register.error_message.blacklisted_domain'));
            }

            /* Detect the location */
            try {
                $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-Country.mmdb'))->get(get_ip());
            } catch(\Exception $exception) { /* :) */ }
            $country = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;

            /* Make sure the country is not blacklisted */
            if($country && in_array($country, settings()->users->blacklisted_countries ?? [])) {
                Alerts::add_error(l('register.error_message.blacklisted_country'));
            }

            /* Make sure to check against the limiter */
            if(settings()->users->register_lockout_is_enabled) {
                $days_ago_datetime = (new \DateTime())->modify('-' . settings()->users->register_lockout_time . ' days')->format('Y-m-d H:i:s');

                $recent_registrations = db()->where('ip', get_ip())->where('type', 'register.success')->where('datetime', $days_ago_datetime, '>=')->getValue('users_logs', 'COUNT(*)');

                if($recent_registrations >= settings()->users->register_lockout_max_registrations) {
                    Alerts::add_error(sprintf(l('global.error_message.limit_try_again'), settings()->users->register_lockout_time, l('global.date.days')));
                    setcookie('register_lockout', 'true', time()+60*60*24*settings()->users->register_lockout_time, COOKIE_PATH);
                    $_COOKIE['register_lockout'] = 'true';
                }
            }

            /* If there are no errors continue the registering process */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $values = [
                    'name' => '',
                    'email' => '',
                    'password' => '',
                ];

                /* Define some needed variables */
                $active 	                = (int) !settings()->users->email_confirmation;
                $email_code                 = md5($_POST['email'] . microtime());

                /* Determine what plan is set by default */
                $plan_id                    = 'free';
                $plan_settings              = json_encode(settings()->plan_free->settings ?? '');
                $plan_expiration_date       = get_date();

                $registered_user = (new User())->create(
                    $_POST['email'],
                    $_POST['password'],
                    $_POST['name'],
                    (int) !settings()->users->email_confirmation,
                    'direct',
                    $email_code,
                    null,
                    $_POST['is_newsletter_subscribed'],
                    $plan_id,
                    $plan_settings,
                    $plan_expiration_date,
                    settings()->main->default_timezone
                );

                /* Log the action */
                Logger::users($registered_user['user_id'], 'register.success');

                /* If active = 1 then login the user, else send the user an activation email */
                if($active == '1') {

                    /* Send a welcome email if needed */
                    if(settings()->users->welcome_email_is_enabled) {

                        $email_template = get_email_template(
                            [],
                            l('global.emails.user_welcome.subject'),
                            [
                                '{{NAME}}' => $_POST['name'],
                                '{{URL}}' => url(),
                            ],
                            l('global.emails.user_welcome.body')
                        );

                        send_mail($_POST['email'], $email_template->subject, $email_template->body);

                    }

                    /* Send notification to admin if needed */
                    if(settings()->email_notifications->new_user && !empty(settings()->email_notifications->emails)) {
                        /* Prepare the email */
                        $email_template = get_email_template(
                            [],
                            l('global.emails.admin_new_user_notification.subject'),
                            [
                                '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                                '{{EMAIL}}' => $_POST['email'],
                            ],
                            l('global.emails.admin_new_user_notification.body')
                        );

                        send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);
                    }

                    /* Send webhook notification if needed */
                    if(settings()->webhooks->user_new) {
                        \Unirest\Request::post(settings()->webhooks->user_new, [], [
                            'user_id' => $registered_user['user_id'],
                            'email' => $_POST['email'],
                            'name' => $_POST['name'],
                            'source' => 'direct',
                            'is_newsletter_subscribed' => $_POST['is_newsletter_subscribed'],
                        ]);
                    }

                    /* Send internal notification if needed */
                    if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_user) {
                        db()->insert('internal_notifications', [
                            'for_who' => 'admin',
                            'from_who' => 'system',
                            'icon' => 'fas fa-user',
                            'title' => l('global.notifications.new_user.title'),
                            'description' => sprintf(l('global.notifications.new_user.description'), $_POST['name'], $_POST['email']),
                            'url' => 'admin/user-view/' . $registered_user['user_id'],
                            'datetime' => get_date(),
                        ]);
                    }

                    /* Send internal notification if needed */
                    if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_newsletter_subscriber && $_POST['is_newsletter_subscribed']) {
                        db()->insert('internal_notifications', [
                            'for_who' => 'admin',
                            'from_who' => 'system',
                            'icon' => 'fas fa-newspaper',
                            'title' => l('global.notifications.new_newsletter_subscriber.title'),
                            'description' => sprintf(l('global.notifications.new_newsletter_subscriber.description'), $_POST['name'], $_POST['email']),
                            'url' => 'admin/user-view/' . $registered_user['user_id'],
                            'datetime' => get_date(),
                        ]);
                    }

                    /* Set a nice success message */
                    Alerts::add_success(l('register.success_message.login'));

                    $_SESSION['user_id'] = $registered_user['user_id'];
                    $_SESSION['user_password_hash'] = md5($registered_user['password']);

                    Logger::users($registered_user['user_id'], 'login.success');

                    redirect($redirect . '&welcome=' . $registered_user['user_id']);
                } else {

                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                        ],
                        l('global.emails.user_activation.subject'),
                        [
                            '{{ACTIVATION_LINK}}' => url('activate-user?email=' . md5($_POST['email']) . '&email_activation_code=' . $email_code . '&type=user_activation' . '&redirect=' . $redirect),
                            '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                        ],
                        l('global.emails.user_activation.body')
                    );

                    send_mail($_POST['email'], $email_template->subject, $email_template->body);

                    /* Set a nice success message */
                    Alerts::add_success(l('register.success_message.registration'));
                }

            }
        }

        /* Main View */
        $data = [
            'values' => $values,
            'captcha' => $captcha,
            'redirect_append' => $redirect_append,
        ];

        $view = new \Altum\View('register/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
