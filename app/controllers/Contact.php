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

class Contact extends Controller {

    public function index() {

        if(!settings()->email_notifications->contact || empty(settings()->email_notifications->emails)) {
            redirect('not-found');
        }

        /* Initiate captcha */
        $captcha = new Captcha();

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['email'] = mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);
            $_POST['subject'] = input_clean($_POST['subject'], 128);
            $_POST['message'] = input_clean($_POST['message'], 2048);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name', 'email', 'subject', 'message'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(settings()->captcha->contact_is_enabled && !$captcha->is_valid()) {
                Alerts::add_field_error('captcha', l('global.error_message.invalid_captcha'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Prepare the email */
                $email_template = get_email_template(
                    [
                        '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                        '{{SUBJECT}}' => $_POST['subject'],
                    ],
                    l('global.emails.admin_contact.subject'),
                    [
                        '{{NAME}}' => str_replace('.', '. ', $_POST['name']),
                        '{{EMAIL}}' => $_POST['email'],
                        '{{MESSAGE}}' => $_POST['message'],
                    ],
                    l('global.emails.admin_contact.body')
                );

                send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body, [], $_POST['email']);

                /* Send webhook notification if needed */
                if(settings()->webhooks->contact) {
                    \Unirest\Request::post(settings()->webhooks->contact, [], [
                        'name' => $_POST['name'],
                        'email' => $_POST['email'],
                        'subject' => $_POST['subject'],
                        'message' => $_POST['message'],
                    ]);
                }

                /* Set a nice success message */
                Alerts::add_success(l('contact.success_message'));

                redirect('contact');
            }
        }

        $values = [
            'name' => \Altum\Authentication::check() ? $this->user->name : ($_POST['name'] ??  ''),
            'email' => \Altum\Authentication::check() ? $this->user->email : ($_POST['email'] ??  ''),
            'subject' => $_POST['subject'] ?? '',
            'message' => $_POST['message'] ?? '',
        ];

        /* Prepare the view */
        $data = [
            'captcha' => $captcha,
            'values' => $values,
        ];

        $view = new \Altum\View('contact/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


