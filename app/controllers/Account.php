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

class Account extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the TwoFA codes just in case we need them */
        $twofa = new \RobThree\Auth\TwoFactorAuth(settings()->main->title, 6, 30);
        $twofa_secret = $twofa->createSecret();
        $twofa_image = $twofa->getQRCodeImageAsDataUri($this->user->email . ' - ' . $this->user->name, $twofa_secret, 400);

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $_POST['email'] = mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['timezone'] = in_array($_POST['timezone'], \DateTimeZone::listIdentifiers()) ? query_clean($_POST['timezone']) : settings()->main->default_timezone;
            $_POST['anti_phishing_code'] = input_clean($_POST['anti_phishing_code'], 8);
            $_POST['twofa_is_enabled'] = (bool) $_POST['twofa_is_enabled'];
            $_POST['twofa_token'] = input_clean(str_replace(' ', '', $_POST['twofa_token'] ?? ''));
            $_POST['is_newsletter_subscribed'] = (int) isset($_POST['is_newsletter_subscribed']);
            $twofa_secret = $_POST['twofa_is_enabled'] ? $this->user->twofa_secret : null;

            if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled) {
                $_POST['referral_key'] = input_clean($_POST['referral_key'], 32);
            } else {
                $_POST['referral_key'] = $this->user->referral_key;
            }

            /* Billing */
            if(empty($this->user->payment_subscription_id)) {
                $_POST['billing_type'] = in_array($_POST['billing_type'], ['personal', 'business']) ? query_clean($_POST['billing_type']) : 'personal';
                $_POST['billing_name'] = input_clean($_POST['billing_name'], 128);
                $_POST['billing_address'] = input_clean($_POST['billing_address'], 128);
                $_POST['billing_city'] = input_clean($_POST['billing_city'], 64);
                $_POST['billing_county'] = input_clean($_POST['billing_county'], 64);
                $_POST['billing_zip'] = input_clean($_POST['billing_zip'], 32);
                $_POST['billing_country'] = array_key_exists($_POST['billing_country'], get_countries_array()) ? query_clean($_POST['billing_country']) : 'US';
                $_POST['billing_phone'] = input_clean($_POST['billing_phone'], 32);
                $_POST['billing_tax_id'] = $_POST['billing_type'] == 'business' ? input_clean($_POST['billing_tax_id'], 64) : '';
                $_POST['billing_notes'] = input_clean($_POST['billing_notes'], 512);
                $_POST['billing'] = json_encode([
                    'type' => $_POST['billing_type'],
                    'name' => $_POST['billing_name'],
                    'address' => $_POST['billing_address'],
                    'city' => $_POST['billing_city'],
                    'county' => $_POST['billing_county'],
                    'zip' => $_POST['billing_zip'],
                    'country' => $_POST['billing_country'],
                    'phone' => $_POST['billing_phone'],
                    'tax_id' => $_POST['billing_tax_id'],
                    'notes' => $_POST['billing_notes'],
                ]);
            }

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                Alerts::add_field_error('email', l('global.error_message.invalid_email'));
            }
            if(db()->where('email', $_POST['email'])->has('users') && $_POST['email'] !== $this->user->email) {
                Alerts::add_field_error('email', l('register.error_message.email_exists'));
            }

            if(db()->where('referral_key', $_POST['referral_key'])->has('users') && $_POST['referral_key'] !== $this->user->referral_key) {
                Alerts::add_field_error('referral_key', l('account.error_message.referral_key_exists'));
            }

            if(mb_strlen($_POST['name']) < 1 || mb_strlen($_POST['name']) > 64) {
                Alerts::add_field_error('name', l('register.error_message.name_length'));
            }

            if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                if(!password_verify($_POST['old_password'], $this->user->password)) {
                    Alerts::add_field_error('old_password', l('account.error_message.invalid_current_password'));
                }
                if(mb_strlen($_POST['new_password']) < 6 || mb_strlen($_POST['new_password']) > 64) {
                    Alerts::add_field_error('new_password', l('global.error_message.password_length'));
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    Alerts::add_field_error('repeat_password', l('global.error_message.passwords_not_matching'));
                }
            }

            if($_POST['twofa_is_enabled'] && $_POST['twofa_token']) {
                $twofa_check = $twofa->verifyCode($_SESSION['twofa_potential_secret'], $_POST['twofa_token']);

                if(!$twofa_check) {
                    Alerts::add_field_error('twofa_token', l('account.error_message.twofa_check'));

                    /* Regenerate */
                    $twofa_secret = $twofa->createSecret();
                    $twofa_image = $twofa->getQRCodeImageAsDataUri($this->user->email . ' - ' . $this->user->name, $twofa_secret, 400);

                } else {
                    $twofa_secret = $_SESSION['twofa_potential_secret'];
                }

            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Only update the billing if no active subscriptions are found */
                if(!empty($this->user->payment_subscription_id)) {
                    $_POST['billing'] = json_encode($this->user->billing);
                }

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', [
                    'name' => $_POST['name'],
                    'billing' => $_POST['billing'],
                    'timezone' => $_POST['timezone'],
                    'twofa_secret' => $twofa_secret,
                    'anti_phishing_code' => $_POST['anti_phishing_code'],
                    'is_newsletter_subscribed' => $_POST['is_newsletter_subscribed'],
                    'referral_key' => $_POST['referral_key'],
                ]);

                /* Set a nice success message */
                Alerts::add_success(l('account.success_message.account_updated'));

                /* Update all websites if any */
                if(settings()->sso->is_enabled && count((array) settings()->sso->websites)) {
                    foreach(settings()->sso->websites as $website) {
                        $response = \Unirest\Request::post(
                            $website->url . 'admin-api/sso/update',
                            ['Authorization' => 'Bearer ' . $website->api_key],
                            \Unirest\Request\Body::form([
                                'email' => $this->user->email,
                                'name' => $_POST['name'],
                            ])
                        );
                    }
                }

                /* Check for an email address change */
                if($_POST['email'] != $this->user->email) {

                    if(settings()->users->email_confirmation) {
                        $email_activation_code = md5($_POST['email'] . microtime());

                        /* Prepare the email */
                        $email_template = get_email_template(
                            [],
                            l('global.emails.user_pending_email.subject'),
                            [
                                '{{ACTIVATION_LINK}}' => url('activate-user?email=' . md5($_POST['email']) . '&email_activation_code=' . $email_activation_code . '&type=user_pending_email'),
                                '{{NAME}}' => $this->user->name,
                                '{{CURRENT_EMAIL}}' => $this->user->email,
                                '{{NEW_EMAIL}}' => $_POST['email'],
                            ],
                            l('global.emails.user_pending_email.body')
                        );

                        send_mail($_POST['email'], $email_template->subject, $email_template->body, ['anti_phishing_code' => $this->user->anti_phishing_code, 'language' => $this->user->language]);

                        /* Save the potential new email as pending */
                        db()->where('user_id', $this->user->user_id)->update('users', [
                            'pending_email' => $_POST['email'],
                            'email_activation_code' => $email_activation_code,
                        ]);

                        Alerts::add_info(l('account.info_message.user_pending_email'));

                    } else {

                        /* Save the new email without verification */
                        db()->where('user_id', $this->user->user_id)->update('users', ['email' => $_POST['email']]);

                        /* Update all websites if any */
                        if(settings()->sso->is_enabled && count((array) settings()->sso->websites)) {
                            foreach(settings()->sso->websites as $website) {
                                $response = \Unirest\Request::post(
                                    $website->url . 'admin-api/sso/update',
                                    ['Authorization' => 'Bearer ' . $website->api_key],
                                    \Unirest\Request\Body::form([
                                        'email' => $this->user->email,
                                        'new_email' => $_POST['email'],
                                    ])
                                );
                            }
                        }

                    }

                }

                if(!empty($_POST['old_password']) && !empty($_POST['new_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    db()->where('user_id', $this->user->user_id)->update('users', ['password' => $new_password]);

                    /* Logout of the user */
                    \Altum\Authentication::logout(false);

                    /* Start a new session to set a success message */
                    session_start();

                    /* Clear the cache */
                    cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                    /* Set a nice success message */
                    Alerts::add_success(l('account.success_message.password_updated'));

                    redirect('login');
                }

                /* Send internal notification if needed */
                if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_newsletter_subscriber && $_POST['is_newsletter_subscribed'] && !$this->user->is_newsletter_subscribed) {
                    db()->insert('internal_notifications', [
                        'for_who' => 'admin',
                        'from_who' => 'system',
                        'icon' => 'fas fa-newspaper',
                        'title' => l('global.notifications.new_newsletter_subscriber.title'),
                        'description' => sprintf(l('global.notifications.new_newsletter_subscriber.description'), $_POST['name'], $_POST['email']),
                        'url' => 'admin/user-view/' . $this->user->user_id,
                        'datetime' => get_date(),
                    ]);
                }

                /* Send webhook notification if needed */
                if(settings()->webhooks->user_update) {
                    \Unirest\Request::post(settings()->webhooks->user_update, [], [
                        'user_id' => $this->user->user_id,
                        'email' => $_POST['email'],
                        'name' => $_POST['name'],
                        'source' => 'account',
                    ]);
                }

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                redirect('account');
            }

        }

        /* Store the potential secret */
        $_SESSION['twofa_potential_secret'] = $twofa_secret;

        /* Get the account header menu */
        $menu = new \Altum\View('partials/account_header_menu', (array) $this);
        $this->add_view_content('account_header_menu', $menu->run());

        /* Prepare the view */
        $data = [
            'twofa_secret'  => $twofa_secret,
            'twofa_image'   => $twofa_image
        ];

        $view = new \Altum\View('account/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
