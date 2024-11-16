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

class PaymentProcessorCreate extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.payment_processors')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('payment-processors');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payment_processors` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->payment_processors_limit != -1 && $total_rows >= $this->user->plan_settings->payment_processors_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('payment-processors');
        }

        if(!empty($_POST)) {
            $settings = [];

            $_POST['name'] = trim(query_clean($_POST['name']));
            $_POST['processor'] = isset($_POST['processor']) && in_array($_POST['processor'], ['paypal', 'stripe', 'crypto_com', 'razorpay', 'paystack', 'mollie']) ? query_clean($_POST['processor']) : 'https://';

            switch($_POST['processor']) {
                case 'paypal':
                    $settings['mode'] = $_POST['mode'] = in_array($_POST['mode'], ['live', 'sandbox']) ? $_POST['mode'] : 'live';
                    $settings['client_id'] = $_POST['client_id'] = input_clean($_POST['client_id']);
                    $settings['secret'] = $_POST['secret'] = input_clean($_POST['secret']);
                    break;

                case 'stripe':
                    $settings['publishable_key'] = $_POST['publishable_key'] = input_clean($_POST['publishable_key']);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret']);
                    break;

                case 'crypto_com':
                    $settings['publishable_key'] = $_POST['publishable_key'] = input_clean($_POST['publishable_key']);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret']);
                    break;

                case 'paystack':
                    $settings['public_key'] = $_POST['public_key'] = input_clean($_POST['public_key']);
                    $settings['secret_key'] = $_POST['secret_key'] = input_clean($_POST['secret_key']);
                    break;

                case 'razorpay':
                    $settings['key_id'] = $_POST['key_id'] = input_clean($_POST['key_id']);
                    $settings['key_secret'] = $_POST['key_secret'] = input_clean($_POST['key_secret']);
                    $settings['webhook_secret'] = $_POST['webhook_secret'] = input_clean($_POST['webhook_secret']);
                    break;
            }

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = json_encode($settings);

                /* Database query */
                $payment_processor_id = db()->insert('payment_processors', [
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'processor' => $_POST['processor'],
                    'settings' => $settings,
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItemsByTag('payment_processors?user_id=' . $this->user->user_id);

                redirect('payment-processor-update/' . $payment_processor_id);
            }
        }

        $values = [
            'name' => $_POST['name'] ?? null,
            'processor' => $_POST['processor'] ?? null,
            'mode' => $_POST['mode'] ?? null,
            'client_id' => $_POST['client_id'] ?? null,
            'secret' => $_POST['secret'] ?? null,
            'publishable_key' => $_POST['publishable_key'] ?? null,
            'secret_key' => $_POST['secret_key'] ?? null,
            'webhook_secret' => $_POST['webhook_secret'] ?? null,
            'public_key' => $_POST['public_key'] ?? null,
            'key_id' => $_POST['key_id'] ?? null,
            'key_secret' => $_POST['key_secret'] ?? null,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values
        ];

        $view = new \Altum\View('payment-processor-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
