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

class WebhookRazorpay extends Controller {

    public function index() {

        if((strtoupper($_SERVER['REQUEST_METHOD']) != 'POST') || !isset($_SERVER['HTTP_X_RAZORPAY_SIGNATURE'])) {
            die();
        }

        $payload = trim(@file_get_contents('php://input'));

        if($_SERVER['HTTP_X_RAZORPAY_SIGNATURE'] !== hash_hmac('sha256', $payload, settings()->razorpay->webhook_secret)) {
            die();
        }

        $data = json_decode($payload);

        if(!$data) {
            die();
        }

        if($data->event == 'payment_link.paid') {

            /* Start getting the payment details */
            $payment_subscription_id = null;
            $external_payment_id = $data->payload->payment_link->entity->id;
            $payment_total = $data->payload->payment_link->entity->amount / 100;
            $payment_currency = $data->payload->payment_link->entity->currency;
            $payment_type = 'one_time';

            /* Payment payer details */
            $payer_email = $data->payload->payment_link->entity->customer->email;
            $payer_name = $data->payload->payment_link->entity->customer->name;

            /* Process meta data */
            $metadata = $data->payload->payment_link->entity->notes;
            $user_id = (int) $metadata->user_id;
            $plan_id = (int) $metadata->plan_id;
            $payment_frequency = $metadata->payment_frequency;
            $code = isset($metadata->code) ? $metadata->code : '';
            $discount_amount = isset($metadata->discount_amount) ? $metadata->discount_amount : 0;
            $base_amount = isset($metadata->base_amount) ? $metadata->base_amount : 0;
            $taxes_ids = isset($metadata->taxes_ids) ? $metadata->taxes_ids : null;

            (new Payments())->webhook_process_payment(
                'razorpay',
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

            die();
        }

        if($data->event == 'subscription.charged') {

            /* Start getting the payment details */
            $external_payment_id = $data->payload->payment->entity->id;
            $payment_total = $data->payload->payment->entity->amount / 100;
            $payment_currency = $data->payload->payment->entity->currency;
            $payment_type = 'recurring';
            $payment_subscription_id = $data->payload->subscription->entity->id;

            /* Payment payer details */
            $payer_email = $data->payload->payment->entity->email;
            $payer_name = '';

            /* Process meta data */
            $metadata = $data->payload->subscription->entity->notes;
            $user_id = (int) $metadata->user_id;
            $plan_id = (int) $metadata->plan_id;
            $payment_frequency = $metadata->payment_frequency;
            $code = isset($metadata->code) ? $metadata->code : '';
            $discount_amount = isset($metadata->discount_amount) ? $metadata->discount_amount : 0;
            $base_amount = isset($metadata->base_amount) ? $metadata->base_amount : 0;
            $taxes_ids = isset($metadata->taxes_ids) ? $metadata->taxes_ids : null;

            (new Payments())->webhook_process_payment(
                'razorpay',
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
