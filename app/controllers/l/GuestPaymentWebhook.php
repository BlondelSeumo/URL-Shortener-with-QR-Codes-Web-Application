<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class GuestPaymentWebhook extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            http_response_code(400); die('payment-blocks plugin is disabled.');
        }

        $_GET['processor'] = in_array(($_GET['processor'] ?? null), ['paypal', 'stripe', 'crypto_com', 'razorpay', 'paystack', 'mollie']) ? input_clean($_GET['processor']) : null;
        $_GET['payment_processor_id'] = (int) $_GET['payment_processor_id'];

        if(!$_GET['processor']) {
            http_response_code(400); die('$_GET processor value is invalid.');
        }

        if(!$_GET['payment_processor_id']) {
            http_response_code(400); die('$_GET payment_processor_id value is invalid.');
        }

        /* Get the payment processor */
        $payment_processor = (new \Altum\Models\PaymentProcessor())->get_payment_processor_by_payment_processor_id($_GET['payment_processor_id']);

        switch($_GET['processor']) {
            case 'paypal':

                $payload = @file_get_contents('php://input');
                $data = json_decode($payload);

                /* Initiate PayPal */
                \Unirest\Request::auth($payment_processor->settings->client_id, $payment_processor->settings->secret);

                /* Get API URL */
                $paypal_api_url = $payment_processor->settings->mode == 'live' ? 'https://api-m.paypal.com/' : 'https://api-m.sandbox.paypal.com/';

                /* Try to get access token */
                try {
                    $response = \Unirest\Request::post($paypal_api_url . 'v1/oauth2/token', [], \Unirest\Request\Body::form(['grant_type' => 'client_credentials']));

                    /* Check against errors */
                    if($response->code >= 400) {
                        throw new \Exception($response->body->name . ':' . $response->body->message);
                    }

                } catch (\Exception $exception) {
                    http_response_code(400); die($exception->getMessage());
                }

                $paypal_access_token = $response->body->access_token;

                /* Set future request headers */
                $paypal_headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $paypal_access_token
                ];

                if(!in_array($data->event_type, ['CHECKOUT.ORDER.APPROVED'])) {
                    die('webhook event type not allowed.');
                }

                $response = \Unirest\Request::post($paypal_api_url . 'v2/checkout/orders/' . $data->resource->id . '/capture', $paypal_headers);

                /* Check against errors */
                if($response->code >= 400) {
                    http_response_code(400); die($response->body->name . ':' . $response->body->message);
                }

                /* Start getting the payment details */
                $payment_id = $response->body->id;
                $payment_total_amount = $response->body->purchase_units[0]->payments->captures[0]->amount->value;
                $payment_currency = $response->body->purchase_units[0]->payments->captures[0]->amount->currency_code;

                /* Payment payer details */
                $payer_email = $response->body->payer->email_address;
                $payer_name = $response->body->payer->name->given_name . $response->body->payer->name->surname;

                /* Parse metadata */
                $guest_payment_id = (int) $response->body->purchase_units[0]->payments->captures[0]->custom_id;

                break;

            case 'stripe':

                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey($payment_processor->settings->secret_key);
                \Stripe\Stripe::setApiVersion('2023-10-16');

                $payload = @file_get_contents('php://input');
                $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
                $event = null;

                try {
                    $event = \Stripe\Webhook::constructEvent($payload, $sig_header, $payment_processor->settings->webhook_secret);
                } catch(\Exception $exception) {
                    /* Invalid payload */
                    echo $exception->getMessage(); http_response_code(400); die();
                }

                if(!in_array($event->type, ['checkout.session.completed'])) {
                    die('Event type not needed to be handled, returning ok.');
                }

                $session = $event->data->object;

                $payer_email = $session->customer_details->email;
                $payer_name = $session->customer_details->name;

                $payment_id = $session->id;
                $payment_currency = mb_strtoupper($session->currency);
                $payment_total_amount = in_array($payment_currency, ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF']) ? $session->amount_total : $session->amount_total / 100;

                /* Metadata */
                $guest_payment_id = $session->metadata->guest_payment_id;

                break;

            case 'crypto_com':

                /* Verify the source of the webhook event */
                $headers = getallheaders();
                $signature_header = isset($headers['Pay-Signature']) ? $headers['Pay-Signature'] : null;
                $payload = trim(@file_get_contents('php://input'));

                /* Make sure the signature is correct */
                $time_string = explode(',', $signature_header)[0];
                $time_value = explode('=', $time_string)[1];
                $signature_string = explode(',', $signature_header)[1];
                $signature_value = explode('=', $signature_string)[1];
                $signed_payload = $time_value . '.' . $payload;
                $computed_signature = \hash_hmac('sha256', $signed_payload, $payment_processor->settings->webhook_secret);

                if(!hash_equals($signature_value, $computed_signature)) {
                    http_response_code(400); die();
                };

                $data = json_decode($payload);

                if($data->type != 'payment.captured') {
                    http_response_code(400); die('Payment type not accepted');
                }

                $payer_email = '';
                $payer_name = '';

                $payment_id = $data->id;
                $payment_total_amount = $data->data->object->amount / 100;
                $payment_currency = $data->data->object->currency;

                /* Metadata */
                $guest_payment_id = $data->data->object->metadata->guest_payment_id;

                break;

            case 'razorpay':

                if((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !isset($_SERVER['HTTP_X_RAZORPAY_SIGNATURE'])) {
                    http_response_code(400); die();
                }

                $payload = trim(@file_get_contents('php://input'));

                if($_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] !== hash_hmac('sha256', $payload, $payment_processor->settings->webhook_secret)) {
                    http_response_code(400); die();
                }

                $data = json_decode($payload);

                if(!$data) {
                    http_response_code(400); die();
                }

                if($data->event != 'payment_link.paid') {
                    http_response_code(400); die();
                }

                $payer_email = $data->customer->email;
                $payer_name = $data->customer->first_name . $data->customer->last_name;

                $payment_id = $data->payload->payment_link->entity->id;
                $payment_total_amount = $data->payload->payment_link->entity->amount / 100;
                $payment_currency = $data->payload->payment_link->entity->currency;

                /* Metadata */
                $guest_payment_id = $data->payload->payment_link->entity->notes->guest_payment_id;

                break;

            case 'paystack':

                if((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST' ) || !isset($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'])) {
                    http_response_code(400); die();
                }

                $payload = @file_get_contents('php://input');

                if($_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] !== hash_hmac('sha512', $payload, $payment_processor->settings->secret_key)) {
                    http_response_code(400); die();
                }

                $data = json_decode($payload);

                if(!$data) {
                    http_response_code(400); die();
                }

                if($data->event != 'charge.success') {
                    http_response_code(400); die();
                }

                $payer_email = $data->data->customer->email;
                $payer_name = $data->data->customer->first_name . $data->data->customer->last_name;

                $payment_id = $data->data->id;
                $payment_total_amount = $data->data->amount / 100;
                $payment_currency = $data->data->currency;

                /* Metadata */
                $guest_payment_id = $data->data->metadata->guest_payment_id;

                break;

            case 'mollie':
                $mollie = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey($payment_processor->settings->api_key);

                /* Retrieve the payment */
                $payment = $mollie->payments->get($_POST['id']);

                /* Do some checks on the payment status */
                if(!$payment->isPaid() || $payment->hasRefunds() || $payment->hasChargebacks()) {
                    http_response_code(400); die();
                }

                /* Make sure it's a one time payment */
                if($payment->sequenceType !== 'oneoff') {
                    http_response_code(400);
                    die();
                }

                $payer_email = '';
                $payer_name = '';

                $payment_id = $payment->id;
                $payment_total_amount = $payment->amount->value;
                $payment_currency = $payment->amount->currency;

                /* Metadata */
                $guest_payment_id = $payment->metadata->guest_payment_id;

                break;
        }

        /* Make sure the transaction exists and it is not already enabled */
        if(!$guest_payment = db()->where('guest_payment_id', $guest_payment_id)->getOne('guests_payments')) {
            http_response_code(400); die('guest_payment_id not existing.');
        }
        $guest_payment->data = json_decode($guest_payment->data);

        if($guest_payment->status == 1) {
            http_response_code(400); die('guest_payment_id already processed.');
        }

        /* Make sure the account still exists */
        $user = db()->where('user_id', $guest_payment->user_id)->getOne('users');

        if(!$user) {
            http_response_code(400); die('user_id not existing.');
        }

        /* Make sure the biolink block still exists */
        $biolink_block = db()->where('biolink_block_id', $guest_payment->biolink_block_id)->getOne('biolinks_blocks');

        if(!$biolink_block) {
            die('biolink_block_id not existing.');
        }

        $biolink_block->settings = json_decode($biolink_block->settings ?? '');

        /* Get link */
        $link = db()->where('link_id', $biolink_block->link_id)->getOne('links');

        if(!$link) {
            die('link_id not existing.');
        }

        /* Update the guest payment */
        db()->where('guest_payment_id', $guest_payment_id)->update('guests_payments', [
            'payment_id' => $payment_id,
            'email' => $guest_payment->email ? $guest_payment->email : $payer_email,
            'name' => $payer_name,
            'total_amount' => $payment_total_amount,
            'currency' => $payment_currency,
            'datetime' => get_date(),
            'status' => 1
        ]);

        /* Send notifications based on the type of block */
        switch($biolink_block->type) {
            case 'donation':
                /* Send email notifications if needed to the owner */
                if($biolink_block->settings->email_notification) {
                    $email_template = get_email_template(
                        [
                            '{{DONATION_TITLE}}' => $biolink_block->settings->title,
                            '{{TOTAL_AMOUNT}}' => $payment_total_amount,
                            '{{CURRENCY}}' => $payment_currency,
                        ],
                        l('global.emails.user_guest_payment_donation.subject', $user->language),
                        [
                            '{{DONATION_TITLE}}' => $biolink_block->settings->title,
                            '{{EMAIL}}' => $payer_email,
                            '{{NAME}}' => $payer_name,
                            '{{TOTAL_AMOUNT}}' => $payment_total_amount,
                            '{{CURRENCY}}' => $payment_currency,
                            '{{PROCESSOR}}' => l('pay.custom_plan.' . $payment_processor->processor, $user->language),
                            '{{MESSAGE}}' => $guest_payment->data->message ?? null,
                            '{{GUESTS_PAYMENTS_LINK}}' => url('guests-payments'),
                        ],
                        l('global.emails.user_guest_payment_donation.body', $user->language)
                    );

                    send_mail($biolink_block->settings->email_notification, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);
                }

                /* Send webhook notifications if needed to the owner */
                if($biolink_block->settings->webhook_url) {
                    try {
                        \Unirest\Request::post($biolink_block->settings->webhook_url, [], [
                            'biolink_block_id' => $biolink_block->biolink_block_id,
                            'processor' => $payment_processor->processor,
                            'total_amount' => $payment_total_amount,
                            'currency' => $payment_currency,
                            'email' => $payer_email,
                            'name' => $payer_name,
                            'message' => $guest_payment->data->message ?? null,
                        ]);
                    } catch (\Exception $exception) {
                        // :)
                    }
                }
                break;

            case 'product':
                /* Send email notifications if needed to the owner */
                if($biolink_block->settings->email_notification) {
                    $email_template = get_email_template(
                        [
                            '{{PRODUCT_TITLE}}' => $biolink_block->settings->title,
                            '{{TOTAL_AMOUNT}}' => $payment_total_amount,
                            '{{CURRENCY}}' => $payment_currency,
                        ],
                        l('global.emails.user_guest_payment_product.subject', $user->language),
                        [
                            '{{PRODUCT_TITLE}}' => $biolink_block->settings->title,
                            '{{EMAIL}}' => $guest_payment->email,
                            '{{NAME}}' => $payer_name,
                            '{{TOTAL_AMOUNT}}' => $payment_total_amount,
                            '{{CURRENCY}}' => $payment_currency,
                            '{{PROCESSOR}}' => l('pay.custom_plan.' . $payment_processor->processor, $user->language),
                            '{{GUESTS_PAYMENTS_LINK}}' => url('guests-payments'),
                        ],
                        l('global.emails.user_guest_payment_product.body', $user->language)
                    );

                    send_mail($biolink_block->settings->email_notification, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);
                }

                /* Send email notifications to the customer */
                $email_template = get_email_template(
                    [
                        '{{PRODUCT_TITLE}}' => $biolink_block->settings->title,
                    ],
                    l('global.emails.guest_guest_payment_product.subject', $user->language),
                    [
                        '{{NAME}}' => $payer_name,
                        '{{DOWNLOAD_LINK}}' => url('l/guest-payment-download?guest_payment_id=' . $guest_payment->guest_payment_id . '&key=' . md5($payment_id)),
                    ],
                    l('global.emails.guest_guest_payment_product.body', $user->language)
                );

                send_mail($guest_payment->email, $email_template->subject, $email_template->body);

                /* Send webhook notifications if needed to the owner */
                if($biolink_block->settings->webhook_url) {
                    try {
                        \Unirest\Request::post($biolink_block->settings->webhook_url, [], [
                            'biolink_block_id' => $biolink_block->biolink_block_id,
                            'processor' => $payment_processor->processor,
                            'total_amount' => $payment_total_amount,
                            'currency' => $payment_currency,
                            'email' => $guest_payment->email,
                            'name' => $payer_name,
                        ]);
                    } catch (\Exception $exception) {
                        // :)
                    }
                }
                break;

            case 'service':
                /* Send email notifications if needed to the owner */
                if($biolink_block->settings->email_notification) {
                    $email_template = get_email_template(
                        [
                            '{{SERVICE_TITLE}}' => $biolink_block->settings->title,
                            '{{TOTAL_AMOUNT}}' => $payment_total_amount,
                            '{{CURRENCY}}' => $payment_currency,
                        ],
                        l('global.emails.user_guest_payment_service.subject', $user->language),
                        [
                            '{{SERVICE_TITLE}}' => $biolink_block->settings->title,
                            '{{EMAIL}}' => $guest_payment->email,
                            '{{NAME}}' => $payer_name,
                            '{{TOTAL_AMOUNT}}' => $payment_total_amount,
                            '{{CURRENCY}}' => $payment_currency,
                            '{{PROCESSOR}}' => l('pay.custom_plan.' . $payment_processor->processor, $user->language),
                            '{{MESSAGE}}' => $guest_payment->data->message ?? null,
                            '{{GUESTS_PAYMENTS_LINK}}' => url('guests-payments'),
                        ],
                        l('global.emails.user_guest_payment_service.body', $user->language)
                    );

                    send_mail($biolink_block->settings->email_notification, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);
                }

                /* Send email notifications to the customer */
                $email_template = get_email_template(
                    [
                        '{{SERVICE_TITLE}}' => $biolink_block->settings->title,
                    ],
                    l('global.emails.guest_guest_payment_service.subject', $user->language),
                    [
                        '{{NAME}}' => $payer_name,
                    ],
                    l('global.emails.guest_guest_payment_service.body', $user->language)
                );

                send_mail($guest_payment->email, $email_template->subject, $email_template->body);

                /* Send webhook notifications if needed to the owner */
                if($biolink_block->settings->webhook_url) {
                    try {
                        \Unirest\Request::post($biolink_block->settings->webhook_url, [], [
                            'biolink_block_id' => $biolink_block->biolink_block_id,
                            'processor' => $payment_processor->processor,
                            'total_amount' => $payment_total_amount,
                            'currency' => $payment_currency,
                            'email' => $guest_payment->email,
                            'name' => $payer_name,
                            'message' => $guest_payment->data->message ?? null,
                        ]);
                    } catch (\Exception $exception) {
                        // :)
                    }
                }
                break;

        }

        echo 'successful';
    }

}
