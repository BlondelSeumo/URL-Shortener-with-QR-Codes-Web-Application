<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Date;
use Altum\Models\Payments;

class WebhookPayu extends Controller {

    public function index() {

        if($_SERVER['REQUEST_METHOD'] != 'POST') {
            die();
        }

        /* Verify the source of the webhook event */
        $payload = trim(@file_get_contents('php://input'));

        if(empty($payload)) {
            die();
        }

        try {
            \OpenPayU_Configuration::setEnvironment(settings()->payu->mode);
            \OpenPayU_Configuration::setMerchantPosId(settings()->payu->merchant_pos_id);
            \OpenPayU_Configuration::setSignatureKey(settings()->payu->signature_key);
            \OpenPayU_Configuration::setOauthClientId(settings()->payu->oauth_client_id);
            \OpenPayU_Configuration::setOauthClientSecret(settings()->payu->oauth_client_secret);
            \OpenPayU_Configuration::setOauthTokenCache(new \OauthCacheFile(UPLOADS_PATH . 'cache'));

            $result = \OpenPayU_Order::consumeNotification($payload);

            if($result->getResponse()->order->orderId) {

                /* Check if OrderId exists in Merchant Service, update Order data by OrderRetrieveRequest */
                $order = \OpenPayU_Order::retrieve($result->getResponse()->order->orderId);

                if($order->getStatus() == 'SUCCESS') {

                    /* details about the payment */
                    $payment_payment_id = $result->getResponse()->order->extOrderId;
                    $payment = db()->where('payment_id', $payment_payment_id)->where('status', 0)->getOne('payments');

                    if(!$payment) {
                        http_response_code(400); die();
                    }

                    /* details about the user who paid */
                    $user = db()->where('user_id', $payment->user_id)->getOne('users');

                    /* plan that the user has paid for */
                    $plan = (new \Altum\Models\Plan())->get_plan_by_id($payment->plan_id);

                    /* Make sure the code that was potentially used exists */
                    $codes_code = db()->where('code', $payment->code)->where('type', 'discount')->getOne('codes');

                    if($codes_code) {
                        /* Check if we should insert the usage of the code or not */
                        if(!db()->where('user_id', $payment->user_id)->where('code_id', $codes_code->code_id)->has('redeemed_codes')) {

                            /* Update the code usage */
                            db()->where('code_id', $codes_code->code_id)->update('codes', ['redeemed' => db()->inc()]);

                            /* Add log for the redeemed code */
                            db()->insert('redeemed_codes', [
                                'code_id'   => $codes_code->code_id,
                                'user_id'   => $user->user_id,
                                'datetime'  => get_date()
                            ]);
                        }
                    }

                    /* Give the plan to the user */
                    $current_plan_expiration_date = $payment->plan_id == $user->plan_id ? $user->plan_expiration_date : '';
                    switch($payment->frequency) {
                        case 'monthly':
                            $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify('+30 days')->format('Y-m-d H:i:s');
                            break;

                        case 'annual':
                            $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify('+12 months')->format('Y-m-d H:i:s');
                            break;

                        case 'lifetime':
                            $plan_expiration_date = (new \DateTime($current_plan_expiration_date))->modify('+100 years')->format('Y-m-d H:i:s');
                            break;
                    }

                    /* Database query */
                    db()->where('user_id', $user->user_id)->update('users', [
                        'plan_id' => $payment->plan_id,
                        'plan_settings' => json_encode($plan->settings),
                        'plan_expiration_date' => $plan_expiration_date,
                        'plan_expiry_reminder' => 0,
                        'payment_processor' => 'payu',
                        'payment_total_amount' => $payment->total_amount,
                        'payment_currency' => $payment->currency,
                    ]);

                    /* Clear the cache */
                    cache()->deleteItemsByTag('user_id=' . $user->user_id);

                    /* Send notification to the user */
                    $email_template = get_email_template(
                        [],
                        l('global.emails.user_payment.subject'),
                        [
                            '{{NAME}}' => $user->name,
                            '{{PLAN_EXPIRATION_DATE}}' => Date::get($plan_expiration_date, 2),
                            '{{USER_PLAN_LINK}}' => url('account-plan'),
                            '{{USER_PAYMENTS_LINK}}' => url('account-payments'),
                        ],
                        l('global.emails.user_payment.body')
                    );

                    send_mail($user->email, $email_template->subject, $email_template->body, ['anti_phishing_code' => $user->anti_phishing_code, 'language' => $user->language]);

                    /* Currency exchange in case its needed */
                    $total_amount_default_currency = $payment->total_amount;

                    if(settings()->payment->default_currency != $payment->currency && settings()->payment->currency_exchange_api_key) {
                        try {
                            $response = \Unirest\Request::get('https://api.freecurrencyapi.com/v1/latest?apikey=' . settings()->payment->currency_exchange_api_key . '&base_currency=' . $payment->currency . '&currencies=' . settings()->payment->default_currency);

                            if($response->code == 200) {
                                $total_amount_default_currency = $payment->total_amount * $response->body->data->{settings()->payment->default_currency};
                                $total_amount_default_currency = number_format($total_amount_default_currency, 2, '.', '');
                            }
                        } catch (\Exception $exception) {
                            /* :) */
                        }
                    }

                    /* Update the payment */
                    db()->where('id', $payment->id)->update('payments', [
                        'total_amount_default_currency' => $total_amount_default_currency,
                        'status' => 1,
                    ]);

                    /* Affiliate */
                    (new Payments())->affiliate_payment_check($payment->id, $total_amount_default_currency, settings()->payment->default_currency, $user);

                    die();
                }
            }
        } catch (\OpenPayU_Exception $exception) {
            if(DEBUG) {
                error_log($exception->getMessage());
            }
            echo $exception->getMessage();
            http_response_code(400); die();
        }

        die('successful');

    }

}
