<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Models\Payments;

class WebhookMercadopago extends Controller {

    public function index() {

        if((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST')) {
            die();
        }

        $payload = @file_get_contents('php://input');

        $data = json_decode($payload, true);

        if(!$data) {
            die();
        }

        if(!in_array($data['action'], ['payment.created', 'payment.updated'])) {
            die();
        }

        /* Get payment data */
        $external_payment_id = $data['data']['id'];

        $response = \Unirest\Request::get(
            'https://api.mercadopago.com/v1/payments/' . $external_payment_id,
            ['Authorization' => 'Bearer ' . settings()->mercadopago->access_token]
        );

        /* Check against errors */
        if($response->code >= 400) {
            http_response_code(400); die($response->body->error . ':' . $response->body->message);
        }

        $payment = $response->body;

        /* Make sure payment is existing */
        if(!$payment) {
            http_response_code(400); die('payment not found');
        }

        /* Make sure payment is approved */
        if($payment->status != 'approved') {
            http_response_code(400); die('payment is not approved');
        }

        $payment_subscription_id = null;

        /* Start getting the payment details */
        $payment_total = $payment->transaction_details->total_paid_amount;
        $payment_currency = $payment->currency_id;
        $payment_type = $payment_subscription_id ? 'recurring' : 'one_time';

        /* Payment payer details */
        $payer_email = $payment->payer->email;
        $payer_name = $payment->payer->first_name . ' ' . $payment->payer->last_name;

        /* Process meta data */
        $metadata = explode('&', $payment->external_reference);
        $user_id = (int) $metadata[0];
        $plan_id = (int) $metadata[1];
        $payment_frequency = $metadata[2];
        $base_amount = $metadata[3];
        $code = $metadata[4];
        $discount_amount = $metadata[5] ? $metadata[5] : 0;
        $taxes_ids = $metadata[6] ?: null;

        (new Payments())->webhook_process_payment(
            'mercadopago',
            $external_payment_id,
            $payment_total,
            $payment_currency,
            $user_id,
            $plan_id,
            $payment_frequency,
            $code,
            $discount_amount,
            $base_amount,
            $taxes_ids,
            $payment_type,
            $payment_subscription_id,
            $payer_email,
            $payer_name
        );

        echo 'successful';

    }

}
