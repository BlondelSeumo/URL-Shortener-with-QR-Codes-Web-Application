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
use Altum\Logger;

class ActivateUser extends Controller {

    public function index() {

        $md5email = isset($_GET['email']) ? $_GET['email'] : null;
        $email_activation_code = isset($_GET['email_activation_code']) ? $_GET['email_activation_code'] : null;
        $type = isset($_GET['type']) && in_array($_GET['type'], ['user_activation', 'user_pending_email']) ? $_GET['type'] : 'user_activation';
        $redirect = process_and_get_redirect_params() ?? 'dashboard';

        if(!$md5email || !$email_activation_code) redirect();

        /* Check if the activation code is correct */
        switch($type) {
            case 'user_activation':

                if(!$user = db()->where('email_activation_code', $email_activation_code)->getOne('users', ['user_id', 'email', 'name', 'password', 'source', 'is_newsletter_subscribed'])) {
                    redirect();
                }

                if(md5($user->email) != $md5email) {
                    redirect();
                }

                /* Activate the account and reset the email_activation_code */
                db()->where('user_id', $user->user_id)->update('users', [
                    'status' => 1,
                    'email_activation_code' => null,
                    'total_logins' => db()->inc()
                ]);

                /* Send a welcome email if needed */
                if(settings()->users->welcome_email_is_enabled) {
                    $email_template = get_email_template(
                        [],
                        l('global.emails.user_welcome.subject'),
                        [
                            '{{NAME}}' => $user->name,
                            '{{URL}}' => url(),
                        ],
                        l('global.emails.user_welcome.body')
                    );

                    send_mail($user->email, $email_template->subject, $email_template->body);
                }

                /* Send notification to admin if needed */
                if(settings()->email_notifications->new_user && !empty(settings()->email_notifications->emails)) {
                    /* Prepare the email */
                    $email_template = get_email_template(
                        [],
                        l('global.emails.admin_new_user_notification.subject'),
                        [
                            '{{NAME}}' => str_replace('.', '. ', $user->name),
                            '{{EMAIL}}' => $user->email,
                        ],
                        l('global.emails.admin_new_user_notification.body')
                    );

                    send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);
                }

                /* Send webhook notification if needed */
                if(settings()->webhooks->user_new) {
                    \Unirest\Request::post(settings()->webhooks->user_new, [], [
                        'user_id' => $user->user_id,
                        'email' => $user->email,
                        'name' => $user->name,
                        'source' => $user->source,
                        'is_newsletter_subscribed' => $user->is_newsletter_subscribed,
                    ]);
                }

                /* Send internal notification if needed */
                if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_user) {
                    db()->insert('internal_notifications', [
                        'for_who' => 'admin',
                        'from_who' => 'system',
                        'icon' => 'fas fa-user',
                        'title' => l('global.notifications.new_user.title'),
                        'description' => sprintf(l('global.notifications.new_user.description'), $user->name, $user->email),
                        'url' => 'admin/user-view/' . $user->user_id,
                        'datetime' => get_date(),
                    ]);
                }

                Logger::users($user->user_id, 'activate.success');

                /* Login and set a successful message */
                $_SESSION['user_id'] = $user->user_id;
                $_SESSION['user_password_hash'] = md5($user->password);

                /* Set a nice success message */
                Alerts::add_success(l('activate_user.user_activation'));

                Logger::users($user->user_id, 'login.success');

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $user->user_id);

                redirect($redirect . '&welcome=' . $user->user_id);

                break;

            case 'user_pending_email':

                if(!$user = db()->where('email_activation_code', $email_activation_code)->getOne('users', ['user_id', 'pending_email', 'email'])) {
                    redirect();
                }

                if(md5($user->pending_email) != $md5email) {
                    redirect();
                }

                /* Confirm the new email address and reset the email_activation_code */
                db()->where('user_id', $user->user_id)->update('users', [
                    'email' => $user->pending_email,
                    'pending_email' => null,
                    'email_activation_code' => null,
                ]);

                /* Update all websites if any */
                if(settings()->sso->is_enabled && count((array) settings()->sso->websites)) {
                    foreach(settings()->sso->websites as $website) {
                        $response = \Unirest\Request::post(
                            $website->url . 'admin-api/sso/update',
                            ['Authorization' => 'Bearer ' . $website->api_key],
                            \Unirest\Request\Body::form([
                                'email' => $user->email,
                                'new_email' => $user->pending_email,
                            ])
                        );
                    }
                }

                Logger::users($user->user_id, 'email_change.success');

                /* Set a nice success message */
                Alerts::add_success(l('activate_user.user_pending_email'));

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $user->user_id);

                redirect('account');

                break;
        }

    }

}
