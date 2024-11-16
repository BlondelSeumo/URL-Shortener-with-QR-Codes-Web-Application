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
use Altum\PaymentGateways\Coinbase;

class WebhookCoinbase extends Controller {

    public function index() {

        /* Verify the source of the webhook event */
        $headers = getallheaders();
        $signature_header = isset($headers['X-Cc-Webhook-Signature']) ? $headers['X-Cc-Webhook-Signature'] : null;
        $payload = trim(@file_get_contents('php://input'));

        try {
            $data = Coinbase::verify_webhook_signature($payload, $signature_header);
        } catch (\Exception $exception) {
            if(DEBUG) {
                error_log($exception->getMessage());
            }
            echo $exception->getMessage();
            http_response_code(400); die();
        }

        if($data->event->type == 'charge:confirmed') {

            /* Start getting the payment details */
            $payment_subscription_id = null;
            $external_payment_id = $data->event->data->id;
            $payment_total = $data->event->data->pricing->local->amount;
            $payment_currency = $data->event->data->pricing->local->currency;
            $payment_type = 'one_time';

            /* Payment payer details */
            $payer_email = '';
            $payer_name = '';

            /* Process meta data */
            $metadata = $data->event->data->metadata;
            $user_id = (int) $metadata->user_id;
            $plan_id = (int) $metadata->plan_id;
            $payment_frequency = $metadata->payment_frequency;
            $code = isset($metadata->code) ? $metadata->code : '';
            $discount_amount = isset($metadata->discount_amount) ? $metadata->discount_amount : 0;
            $base_amount = isset($metadata->base_amount) ? $metadata->base_amount : 0;
            $taxes_ids = isset($metadata->taxes_ids) ? $metadata->taxes_ids : null;

            (new Payments())->webhook_process_payment(
                'coinbase',
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

            die('successful');
        }

        die();
    }

}
