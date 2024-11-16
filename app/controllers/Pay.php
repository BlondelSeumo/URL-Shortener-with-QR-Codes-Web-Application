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
use Altum\Models\User;
use Altum\PaymentGateways\Coinbase;
use Altum\PaymentGateways\Paddle;
use Altum\PaymentGateways\Paystack;
use Altum\Response;
use Altum\Title;
use Razorpay\Api\Api;

class Pay extends Controller {
    public $plan_id;
    public $return_type;
    public $payment_processor;
    public $plan;
    public $plan_taxes;
    public $applied_taxes_ids = [];
    public $code = null;
    public $payment_extra_data = null;

    public function index() {

        \Altum\Authentication::guard();

        if(!settings()->payment->is_enabled) {
            redirect('not-found');
        }

        $payment_processors = require APP_PATH . 'includes/payment_processors.php';
        $this->plan_id = isset($this->params[0]) ? $this->params[0] : null;
        $this->return_type = isset($_GET['return_type']) && in_array($_GET['return_type'], ['success', 'cancel']) ? $_GET['return_type'] : null;
        $this->payment_processor = isset($_GET['payment_processor']) && array_key_exists($_GET['payment_processor'], $payment_processors) ? $_GET['payment_processor'] : null;

        /* ^_^ */
        switch($this->plan_id) {
            case 'free':

                $this->plan = settings()->plan_free;

                if($this->user->plan_id == 'free') {
                    Alerts::add_info(l('pay.free.free_already'));
                } else {
                    Alerts::add_info(l('pay.free.other_plan_not_expired'));
                }

                redirect('plan');

                break;

            default:

                $this->plan_id = (int) $this->plan_id;

                $plans = (new \Altum\Models\Plan())->get_plans();

                /* Check if plan exists */
                $this->plan = $plans[$this->plan_id] ?? null;
                if(!$this->plan) {
                    redirect('plan');
                }

                /* Check for potential taxes */
                $this->plan_taxes = (new \Altum\Models\Plan())->get_plan_taxes_by_taxes_ids($this->plan->taxes_ids);

                /* Filter them out */
                if($this->plan_taxes) {
                    foreach($this->plan_taxes as $key => $value) {

                        /* Type */
                        if($value->billing_type != $this->user->billing->type && $value->billing_type != 'both') {
                            unset($this->plan_taxes[$key]);
                        }

                        /* Countries */
                        if($value->countries && !in_array($this->user->billing->country, $value->countries)) {
                            unset($this->plan_taxes[$key]);
                        }

                        if(isset($this->plan_taxes[$key])) {
                            $this->applied_taxes_ids[] = (int) $value->tax_id;
                        }

                    }

                    $this->plan_taxes = array_values($this->plan_taxes);
                }

                break;
        }

        /* Make sure the plan is enabled */
        if(!$this->plan->status) {
            redirect('plan');
        }

        if(
            settings()->payment->taxes_and_billing_is_enabled
            && ($this->user->plan_trial_done || !$this->plan->trial_days || isset($_GET['trial_skip']))
            && (empty($this->user->billing->name) || empty($this->user->billing->address) || empty($this->user->billing->city) || empty($this->user->billing->county) || empty($this->user->billing->zip))
        ) {
            redirect('pay-billing/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

        /* Form submission processing */
        /* Make sure that this only runs on user click submit post and not on callbacks / webhooks */
        if(!empty($_POST) && !$this->return_type) {

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');
            //ALTUMCODE:DEMO if(DEMO) redirect('pay/' . $this->plan_id . (isset($_GET['trial_skip']) ? '?trial_skip=true' : null));

            /* Check for code usage */
            if(settings()->payment->codes_is_enabled && isset($_POST['code'])) {
                $_POST['code'] = query_clean($_POST['code']);
                $this->code = database()->query("SELECT * FROM `codes` WHERE `code` = '{$_POST['code']}' AND `redeemed` < `quantity`")->fetch_object();

                if($this->code) {
                    $this->code->plans_ids = json_decode($this->code->plans_ids ?? '');

                    if(db()->where('user_id', $this->user->user_id)->where('code_id', $this->code->code_id)->has('redeemed_codes')) {
                        $this->code = null;
                    }

                    if(!in_array($this->plan_id, $this->code->plans_ids)) {
                        $this->code = null;
                    }
                }
            }

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Process further */
            if($this->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip'])) {
                /* :) */
            } else if($this->code && $this->code->type == 'redeemable' && in_array($this->plan_id, $this->code->plans_ids)) {

                /* Cancel current subscription if needed */
                if($this->user->plan_id != $this->plan->plan_id) {
                    try {
                        (new User())->cancel_subscription($this->user->user_id);
                    } catch (\Exception $exception) {
                        Alerts::add_error($exception->getCode() . ':' . $exception->getMessage());
                        redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                    }
                }

            } else {
                $_POST['payment_frequency'] = query_clean($_POST['payment_frequency']);
                $_POST['payment_processor'] = query_clean($_POST['payment_processor']);
                $_POST['payment_type'] = query_clean($_POST['payment_type']);

                /* Make sure the chosen option comply */
                if(!in_array($_POST['payment_frequency'], ['monthly', 'annual', 'lifetime'])) {
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                if(!array_key_exists($_POST['payment_processor'], $payment_processors)) {
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                } else {

                    /* Make sure the payment processor is active */
                    if(!settings()->{$_POST['payment_processor']}->is_enabled) {
                        redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                    }

                }

                if(!in_array($_POST['payment_type'], ['one_time', 'recurring'])) {
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Lifetime */
                if($_POST['payment_frequency'] == 'lifetime') {
                    $_POST['payment_type'] = 'one_time';
                }

                /* Make sure recurring is available for the payment processor */
                if(!in_array('recurring', $payment_processors[$_POST['payment_processor']]['payment_type'])) {
                    $_POST['payment_type'] = 'one_time';
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                /* Check if we should start the trial or not */
                if($this->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip'])) {

                    /* Determine the expiration date of the plan */
                    $plan_expiration_date = (new \DateTime())->modify('+' . $this->plan->trial_days . ' days')->format('Y-m-d H:i:s');
                    $plan_settings = json_encode($this->plan->settings ?? '');

                    /* Database query */
                    db()->where('user_id', $this->user->user_id)->update('users', [
                        'plan_id' => $this->plan_id,
                        'plan_settings' => $plan_settings,
                        'plan_expiration_date' => $plan_expiration_date,
                        'plan_trial_done' => 1,
                    ]);

                    /* Clear the cache */
                    cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                    /* Success message and redirect */
                    $this->redirect_pay_thank_you();
                }

                /* Redeem */
                else if($this->code && $this->code->type == 'redeemable' && in_array($this->plan_id, $this->code->plans_ids)) {

                    $datetime = $this->user->plan_id == $this->plan->plan_id ? $this->user->plan_expiration_date : '';
                    $plan_expiration_date = (new \DateTime($datetime))->modify('+' . $this->code->days . ' days')->format('Y-m-d H:i:s');
                    $plan_settings = json_encode($this->plan->settings ?? '');

                    /* Database query */
                    db()->where('user_id', $this->user->user_id)->update('users', [
                        'plan_id' => $this->plan_id,
                        'plan_expiration_date' => $plan_expiration_date,
                        'plan_settings' => $plan_settings,
                        'plan_expiry_reminder' => 0,
                    ]);

                    /* Update the code usage */
                    db()->where('code_id', $this->code->code_id)->update('codes', ['redeemed' => db()->inc()]);

                    /* Add log for the redeemed code */
                    db()->insert('redeemed_codes', [
                        'code_id'   => $this->code->code_id,
                        'user_id'   => $this->user->user_id,
                        'datetime'  => get_date()
                    ]);

                    /* Send webhook notification if needed */
                    if(settings()->webhooks->code_redeemed) {
                        \Unirest\Request::post(settings()->webhooks->code_redeemed, [], [
                            'user_id' => $this->user->user_id,
                            'email' => $this->user->email,
                            'name' => $this->user->name,
                            'plan_id' => $this->plan_id,
                            'plan_expiration_date' => $plan_expiration_date,
                            'code_id' => $this->code->code_id,
                            'code' => $this->code->code,
                            'code_name' => $this->code->name,
                            'redeemed_days' => $this->code->days,
                        ]);
                    }

                    /* Clear the cache */
                    cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                    /* Success message and redirect */
                    $this->redirect_pay_thank_you();
                }

                else {
                    $this->{$_POST['payment_processor']}();
                }
            }

        }

        /* Include the detection of callbacks processing */
        $this->payment_return_process();

        /* Set a custom title */
        Title::set(sprintf(l('pay.title'), $this->plan->translations->{\Altum\Language::$name}->name ?? $this->plan->name));

        /* Prepare the view */
        $data = [
            'plan_id'           => $this->plan_id,
            'plan'              => $this->plan,
            'plan_taxes'        => $this->plan_taxes,
            'payment_processors'=> $payment_processors,
            'payment_extra_data'=> $this->payment_extra_data,
        ];

        $view = new \Altum\View('pay/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    private function paypal() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Make sure the price is right depending on the currency */
        $price = in_array(currency(), ['JPY', 'TWD', 'HUF']) ? number_format($price, 0, '.', '') : number_format($price, 2, '.', '');

        try {
            $paypal_api_url = \Altum\PaymentGateways\Paypal::get_api_url();
            $headers = \Altum\PaymentGateways\Paypal::get_headers();
        } catch (\Exception $exception) {
            Alerts::add_error($exception->getMessage());
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

        $custom_id = $this->user->user_id . '&' . $this->plan_id . '&' . $_POST['payment_frequency'] . '&' . $base_amount . '&' . $code . '&' . $discount_amount . '&' . json_encode($this->applied_taxes_ids);

        switch($_POST['payment_type']) {
            case 'one_time':

                /* Create an order */
                $response = \Unirest\Request::post($paypal_api_url . 'v2/checkout/orders', $headers, \Unirest\Request\Body::json([
                    'intent' => 'CAPTURE',
                    'purchase_units' => [[
                        'amount' => [
                            'currency_code' => currency(),
                            'value' => $price,
                            'breakdown' => [
                                'item_total' => [
                                    'currency_code' => currency(),
                                    'value' => $price
                                ]
                            ]
                        ],
                        'description' => l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'custom_id' => $custom_id,
                        'items' => [[
                            'name' => settings()->business->brand_name . ' - ' . $this->plan->name,
                            'description' => l('plan.custom_plan.' . $_POST['payment_frequency']),
                            'quantity' => 1,
                            'unit_amount' => [
                                'currency_code' => currency(),
                                'value' => $price
                            ]
                        ]]
                    ]],
                    'application_context' => [
                        'brand_name' => settings()->business->brand_name ?: settings()->main->title,
                        'landing_page' => 'NO_PREFERENCE',
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'PAY_NOW',
                        'return_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'cancel_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount))
                    ]
                ]));

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->name . ':' . $response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                $paypal_payment_url = $response->body->links[1]->href;

                header('Location: ' . $paypal_payment_url); die();

                break;

            case 'recurring':

                /* Generate the plan id with the proper parameters */
                $paypal_plan_id = $this->plan_id . '_' . $_POST['payment_frequency'] . '_' . $price . '_' . currency();

                /* Product */
                $response = \Unirest\Request::get($paypal_api_url . 'v1/catalogs/products/' . $paypal_plan_id, $headers);

                /* Check against errors */
                if($response->code == 404) {
                    /* Create the product if not existing */
                    $response = \Unirest\Request::post($paypal_api_url . 'v1/catalogs/products', $headers, \Unirest\Request\Body::json([
                        'id' => $paypal_plan_id,
                        'name' => settings()->business->brand_name . ' - ' . $this->plan->name,
                        'type' => 'DIGITAL',
                    ]));

                    /* Check against errors */
                    if($response->code >= 400) {
                        if(DEBUG || \Altum\Authentication::is_admin()) {
                            Alerts::add_error($response->body->name . ':' . $response->body->message);
                        } else {
                            Alerts::add_error(l('pay.error_message.failed_payment'));
                        }
                        redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                    }
                }

                /* Create a new plan */
                $response = \Unirest\Request::post($paypal_api_url . 'v1/billing/plans', $headers, \Unirest\Request\Body::json([
                    'product_id' => $paypal_plan_id,
                    'name' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency']),
                    'description' => l('plan.custom_plan.' . $_POST['payment_frequency']),
                    'status' => 'ACTIVE',
                    'billing_cycles' => [[
                        'pricing_scheme' => [
                            'fixed_price' => [
                                'currency_code' => currency(),
                                'value' => $price
                            ]
                        ],
                        'frequency' => [
                            'interval_unit' => 'DAY',
                            'interval_count' => $_POST['payment_frequency'] == 'monthly' ? 30 : 365
                        ],
                        'tenure_type' => 'REGULAR',
                        'sequence' => 1,
                        'total_cycles' => $_POST['payment_frequency'] == 'monthly' ? 60 : 5,
                    ]],
                    'payment_preferences' => [
                        'auto_bill_outstanding' => true,
                        'setup_fee' => [
                            'currency_code' => currency(),
                            'value' => $price
                        ],
                        'setup_fee_failure_action' => 'CANCEL',
                        'payment_failure_threshold' => 0
                    ]
                ]));

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->name . ':' . $response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Create a new subscription */
                $response = \Unirest\Request::post($paypal_api_url . 'v1/billing/subscriptions', $headers, \Unirest\Request\Body::json([
                    'plan_id' => $response->body->id,
                    'start_time' => (new \DateTime())->modify($_POST['payment_frequency'] == 'monthly' ? '+30 days' : '+1 year')->format(DATE_ISO8601),
                    'quantity' => 1,
                    'custom_id' => $custom_id,
                    'payment_method' => [
                        'payer_selected' => 'PAYPAL',
                        'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                    ],
                    'application_context' => [
                        'brand_name' => settings()->business->brand_name ?: settings()->main->title,
                        'shipping_preference' => 'NO_SHIPPING',
                        'user_action' => 'SUBSCRIBE_NOW',
                        'return_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'cancel_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount))
                    ]
                ]));

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->name . ':' . $response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                $paypal_payment_url = $response->body->links[0]->href;

                header('Location: ' . $paypal_payment_url); die();

                break;
        }


    }

    private function stripe() {

        /* Initiate Stripe */
        \Stripe\Stripe::setApiKey(settings()->stripe->secret_key);
        \Stripe\Stripe::setApiVersion('2023-10-16');

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $stripe_formatted_price = in_array(currency(), ['MGA', 'BIF', 'CLP', 'PYG', 'DJF', 'RWF', 'GNF', 'UGX', 'JPY', 'VND', 'VUV', 'XAF', 'KMF', 'KRW', 'XOF', 'XPF']) ? number_format($price, 0, '.', '') : number_format($price, 2, '.', '') * 100;

        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                try {
                    $stripe_session = \Stripe\Checkout\Session::create([
                        'mode' => 'payment',
                        'customer_email' => $this->user->email,
                        'currency' => currency(),

                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' => currency(),
                                    'product_data' => [
                                        'name' => settings()->business->brand_name . ' - ' . $this->plan->name,
                                        'description' => l('plan.custom_plan.' . $_POST['payment_frequency']),
                                    ],
                                    'unit_amount' => $stripe_formatted_price,
                                ],
                                'quantity' => 1
                            ],
                        ],

                        'metadata' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],

                        'success_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'cancel_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount)),
                    ]);
                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                break;

            case 'recurring':

                try {
                    $stripe_session = \Stripe\Checkout\Session::create([
                        'mode' => 'subscription',
                        'customer_email' => $this->user->email,
                        'currency' => currency(),

                        'line_items' => [
                            [
                                'price_data' => [
                                    'currency' => currency(),
                                    'product_data' => [
                                        'name' => settings()->business->brand_name . ' - ' . $this->plan->name,
                                        'description' => l('plan.custom_plan.' . $_POST['payment_frequency']),
                                    ],
                                    'unit_amount' => $stripe_formatted_price,
                                    'recurring' => [
                                        'interval' => 'day',
                                        'interval_count' => $_POST['payment_frequency'] == 'monthly' ? 30 : 365,
                                    ]
                                ],
                                'quantity' => 1
                            ],
                        ],

                        'metadata' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],

                        'subscription_data' => [
                            'metadata' => [
                                'user_id' => $this->user->user_id,
                                'plan_id' => $this->plan_id,
                                'payment_frequency' => $_POST['payment_frequency'],
                                'base_amount' => $base_amount,
                                'code' => $code,
                                'discount_amount' => $discount_amount,
                                'taxes_ids' => json_encode($this->applied_taxes_ids)
                            ],
                        ],

                        'success_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'cancel_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount)),
                    ]);
                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                break;
        }

        header('Location: ' . $stripe_session->url); die();
    }

    private function coinbase() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price = number_format($price, 2, '.', '');

        $response = \Unirest\Request::post(
            Coinbase::get_api_url() . 'charges',
            Coinbase::get_headers(),
            \Unirest\Request\Body::json([
                'name' => settings()->business->brand_name . ' - ' . $this->plan->name,
                'description' => l('plan.custom_plan.' . $_POST['payment_frequency']),
                'local_price' => [
                    'amount' => $price,
                    'currency' => currency()
                ],
                'pricing_type' => 'fixed_price',
                'metadata' => [
                    'user_id' => $this->user->user_id,
                    'plan_id' => $this->plan_id,
                    'payment_frequency' => $_POST['payment_frequency'],
                    'base_amount' => $base_amount,
                    'code' => $code,
                    'discount_amount' => $discount_amount,
                    'taxes_ids' => json_encode($this->applied_taxes_ids)
                ],
                'redirect_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                'cancel_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount)),
            ])
        );

        /* Check against errors */
        if($response->code >= 400) {
            if(DEBUG || \Altum\Authentication::is_admin()) {
                Alerts::add_error($response->body->error->type . ':' . $response->body->error->message);
            } else {
                Alerts::add_error(l('pay.error_message.failed_payment'));
            }
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

        header('Location: ' . $response->body->data->hosted_url); die();
    }

    private function offline_payment() {

        /* Return confirmation processing if successfully */
        if($this->return_type && $this->payment_processor && $this->return_type == 'success' && $this->payment_processor == 'offline_payment') {

            /* Redirect to the thank you page */
            $this->redirect_pay_thank_you();
        }

        extract($this->get_price_details());

        /* Taxes */
        $price = number_format($this->calculate_price_with_taxes($price), 2, '.', '');

        /* Other vars */
        $payment_id = md5($this->user->user_id . $this->plan_id . $_POST['payment_type'] . $_POST['payment_frequency'] . $this->user->email . get_date());
        $offline_payment_proof = (!empty($_FILES['offline_payment_proof']['name']));

        /* Error checks */
        if(!$offline_payment_proof) {
            Alerts::add_error(l('pay.error_message.offline_payment_proof_missing'));
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

        $offline_payment_proof_file = \Altum\Uploads::process_upload(null, 'offline_payment_proofs', 'offline_payment_proof', 'offline_payment_proof_remove', settings()->offline_payment->proof_size_limit);

        if(Alerts::has_field_errors() && !Alerts::has_errors()) {
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

        /* Add a log into the database */
        db()->insert('payments', [
            'user_id' => $this->user->user_id,
            'plan_id' => $this->plan_id,
            'processor' => 'offline_payment',
            'type' => $_POST['payment_type'],
            'frequency' => $_POST['payment_frequency'],
            'code' => $code,
            'discount_amount' => $discount_amount,
            'base_amount' => $base_amount,
            'email' => $this->user->email,
            'payment_id' => $payment_id,
            'name' => $this->user->name,
            'plan' => json_encode(db()->where('plan_id', $this->plan_id)->getOne('plans', ['plan_id', 'name'])),
            'billing' => settings()->payment->taxes_and_billing_is_enabled && $this->user->billing ? json_encode($this->user->billing) : null,
            'business' => json_encode(settings()->business),
            'taxes_ids' => !empty($this->applied_taxes_ids) ? json_encode($this->applied_taxes_ids) : null,
            'total_amount' => $price,
            'currency' => currency(),
            'payment_proof' => $offline_payment_proof_file,
            'status' => 0,
            'datetime' => get_date()
        ]);

        /* Send notification to admin if needed */
        if(settings()->email_notifications->new_payment && !empty(settings()->email_notifications->emails)) {

            $email_template = get_email_template(
                [
                    '{{PROCESSOR}}' => 'offline_payment',
                    '{{TOTAL_AMOUNT}}' => $price,
                    '{{CURRENCY}}' => currency(),
                ],
                l('global.emails.admin_new_payment_notification.subject'),
                [
                    '{{PROCESSOR}}' => 'offline_payment',
                    '{{TOTAL_AMOUNT}}' => $price,
                    '{{CURRENCY}}' => currency(),
                    '{{NAME}}' => $this->user->name,
                    '{{EMAIL}}' => $this->user->email,
                ],
                l('global.emails.admin_new_payment_notification.body')
            );

            send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);

        }

        redirect('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount));

    }

    private function payu() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price =  number_format($price, 2, '.', '');

        \OpenPayU_Configuration::setEnvironment(settings()->payu->mode);
        \OpenPayU_Configuration::setMerchantPosId(settings()->payu->merchant_pos_id);
        \OpenPayU_Configuration::setSignatureKey(settings()->payu->signature_key);
        \OpenPayU_Configuration::setOauthClientId(settings()->payu->oauth_client_id);
        \OpenPayU_Configuration::setOauthClientSecret(settings()->payu->oauth_client_secret);
        \OpenPayU_Configuration::setOauthTokenCache(new \OauthCacheFile(UPLOADS_PATH . 'cache'));

        $payment_id = md5($this->user->user_id . $this->plan_id . $_POST['payment_type'] . $_POST['payment_frequency'] . $this->user->email . get_date());

        $order = [
            'notifyUrl' => SITE_URL . 'webhook-payu',
            'continueUrl' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
            'customerIp' => get_ip(),
            'merchantPosId' => \OpenPayU_Configuration::getOauthClientId() ? \OpenPayU_Configuration::getOauthClientId() : \OpenPayU_Configuration::getMerchantPosId(),

            'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']),
            'currencyCode' => currency(),
            'totalAmount' => $price * 100,
            'extOrderId' => $payment_id,

            'products' => [
                [
                    'name' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']),
                    'unitPrice' => $price * 100,
                    'quantity' => 1
                ]
            ],

            'buyer' => [
                'email' => $this->user->email,
                'firstName' => $this->user->name,
            ]
        ];

        try {
            $response = \OpenPayU_Order::create($order);
            $status_description = \OpenPayU_Util::statusDesc($response->getStatus());

            if($response->getStatus() != 'SUCCESS') {
                if(DEBUG || \Altum\Authentication::is_admin()) {
                    Alerts::add_error($status_description);
                } else {
                    Alerts::add_error(l('pay.error_message.failed_payment'));
                }
                redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
            }

            /* Add a log into the database */
            db()->insert('payments', [
                'user_id' => $this->user->user_id,
                'plan_id' => $this->plan_id,
                'processor' => 'payu',
                'type' => $_POST['payment_type'],
                'frequency' => $_POST['payment_frequency'],
                'code' => $code,
                'discount_amount' => $discount_amount,
                'base_amount' => $base_amount,
                'email' => $this->user->email,
                'payment_id' => $payment_id,
                'name' => $this->user->name,
                'plan' => json_encode(db()->where('plan_id', $this->plan_id)->getOne('plans', ['plan_id', 'name'])),
                'billing' => settings()->payment->taxes_and_billing_is_enabled && $this->user->billing ? json_encode($this->user->billing) : null,
                'business' => json_encode(settings()->business),
                'taxes_ids' => !empty($this->applied_taxes_ids) ? json_encode($this->applied_taxes_ids) : null,
                'total_amount' => $price,
                'currency' => currency(),
                'status' => 0,
                'datetime' => get_date()
            ]);

            /* Redirect to the payment url */
            header('Location: ' . $response->getResponse()->redirectUri); die();

        } catch (\OpenPayU_Exception $exception) {
            if(DEBUG || \Altum\Authentication::is_admin()) {
                Alerts::add_error($exception->getMessage());
            } else {
                Alerts::add_error(l('pay.error_message.failed_payment'));
            }
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }
    }

    private function iyzico() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price =  number_format($price, 2, '.', '');

        /* Custom payment id */
        $payment_id = md5($this->user->user_id . $this->plan_id . $_POST['payment_type'] . $_POST['payment_frequency'] . $this->user->email . get_date());

        /* Add a log into the database */
        $id = db()->insert('payments', [
            'user_id' => $this->user->user_id,
            'plan_id' => $this->plan_id,
            'processor' => 'iyzico',
            'type' => $_POST['payment_type'],
            'frequency' => $_POST['payment_frequency'],
            'code' => $code,
            'discount_amount' => $discount_amount,
            'base_amount' => $base_amount,
            'email' => $this->user->email,
            'payment_id' => $payment_id,
            'name' => $this->user->name,
            'plan' => json_encode(db()->where('plan_id', $this->plan_id)->getOne('plans', ['plan_id', 'name'])),
            'billing' => settings()->payment->taxes_and_billing_is_enabled && $this->user->billing ? json_encode($this->user->billing) : null,
            'business' => json_encode(settings()->business),
            'taxes_ids' => !empty($this->applied_taxes_ids) ? json_encode($this->applied_taxes_ids) : null,
            'total_amount' => $price,
            'currency' => currency(),
            'status' => 0,
            'datetime' => get_date()
        ]);


        $options = new \Iyzipay\Options();
        $options->setApiKey(settings()->iyzico->api_key);
        $options->setSecretKey(settings()->iyzico->secret_key);
        if(settings()->iyzico->mode == 'live') {
            $options->setBaseUrl("https://api.iyzipay.com");
        } else {
            $options->setBaseUrl("https://sandbox-api.iyzipay.com");
        }

        $request = new \Iyzipay\Request\Iyzilink\IyziLinkSaveProductRequest();
        $request->setLocale(\Iyzipay\Model\Locale::EN);
        $request->setConversationId($id);
        $request->setName(currency());
        $request->setDescription(settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']));
        $request->setBase64EncodedImage('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==');
        $request->setPrice($price);
        $request->setCurrency(currency());
        $request->setAddressIgnorable(false);
        $request->setSoldLimit(1);
        $request->setInstallmentRequest(false);
        $response = \Iyzipay\Model\Iyzilink\IyziLinkSaveProduct::create($request, $options);

        if($response->getStatus() != 'success') {
            if(DEBUG || \Altum\Authentication::is_admin()) {
                Alerts::add_error($response->getErrorCode() . ':' . $response->getErrorMessage());
            } else {
                Alerts::add_error(l('pay.error_message.failed_payment'));
            }
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

        /* Redirect to the payment url */
        header('Location: ' . $response->getUrl()); die();
    }

    private function paystack() {

        Paystack::$secret_key = settings()->paystack->secret_key;

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                /* Generate the payment link */
                $response = \Unirest\Request::post(Paystack::$api_url . 'transaction/initialize', Paystack::get_headers(), \Unirest\Request\Body::json([
                    'key' => settings()->paystack->public_key,
                    'email' => $this->user->email,
                    'first_name' => $this->user->name,
                    'amount' => (int) ($price * 100),
                    'currency' => currency(),
                    'metadata' => [
                        'user_id' => $this->user->user_id,
                        'plan_id' => $this->plan_id,
                        'payment_frequency' => $_POST['payment_frequency'],
                        'base_amount' => $base_amount,
                        'code' => $code,
                        'discount_amount' => $discount_amount,
                        'taxes_ids' => json_encode($this->applied_taxes_ids)
                    ],
                    'callback_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                ]));

                if(!$response->body->status) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response->body->data->authorization_url); die();

                break;

            case 'recurring':

                $response = \Unirest\Request::post(Paystack::$api_url . 'plan', Paystack::get_headers(), \Unirest\Request\Body::json([
                    'name' => $this->plan->name,
                    'interval' => $_POST['payment_frequency'] == 'monthly' ? 'monthly' : 'annually',
                    'amount' => (int) ($price * 100),
                    'currency' => currency(),
                ]));

                if(!$response->body->status) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                $paystack_plan_code = $response->body->data->plan_code;

                /* Generate the payment link */
                $response = \Unirest\Request::post(Paystack::$api_url . 'transaction/initialize', Paystack::get_headers(), \Unirest\Request\Body::json([
                    'key' => settings()->paystack->public_key,
                    'email' => $this->user->email,
                    'first_name' => $this->user->name,
                    'currency' => currency(),
                    'amount' => (int) ($price * 100),
                    'metadata' => [
                        'user_id' => $this->user->user_id,
                        'plan_id' => $this->plan_id,
                        'payment_frequency' => $_POST['payment_frequency'],
                        'base_amount' => $base_amount,
                        'code' => $code,
                        'discount_amount' => $discount_amount,
                        'taxes_ids' => json_encode($this->applied_taxes_ids)
                    ],
                    'callback_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                    'plan' => $paystack_plan_code
                ]));

                if(!$response->body->status) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response->body->data->authorization_url); die();

                break;
        }

        die();
    }

    private function razorpay() {

        $razorpay = new Api(settings()->razorpay->key_id, settings()->razorpay->key_secret);

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                /* Generate the payment link */
                try {
                    $response = $razorpay->paymentLink->create([
                        'amount' => $price * 100,
                        'currency' => currency(),
                        'accept_partial' => false,
                        'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'customer' => [
                            'name' => $this->user->name,
                            'email' => $this->user->email,
                        ],
                        'notify' => [
                            'sms' => false,
                            'email' => false,
                        ],
                        'reminder_enable' => false,
                        'notes' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'callback_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'callback_method' => 'get'
                    ]);
                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response['short_url']); die();

                break;

            case 'recurring':

                try {
                    $plan = $razorpay->plan->create([
                        'period' => 'daily',
                        'interval' => $_POST['payment_frequency'] == 'monthly' ? 30 : 365,
                        'item' => [
                            'name' => $this->plan->name,
                            'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']),
                            'amount' => $price * 100,
                            'currency' => currency(),
                        ],
                    ]);
                }  catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Generate the payment link */
                try {
                    $response = $razorpay->subscription->create([
                        'plan_id' => $plan['id'],
                        'total_count' => $_POST['payment_frequency'] == 'monthly' ? 60 : 5,
                        'quantity' => 1,
                        'notes' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ]
                    ]);
                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response['short_url']); die();

                break;
        }

        die();
    }

    private function mollie() {

        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey(settings()->mollie->api_key);

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                /* Generate the payment link */
                try {
                    $payment = $mollie->payments->create([
                        'amount' => [
                            'currency' => currency(),
                            'value' => $price,
                        ],
                        'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'metadata' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'redirectUrl' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'webhookUrl'  => SITE_URL . 'webhook-mollie',
                    ]);

                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $payment->getCheckoutUrl()); die();

                break;

            case 'recurring':

                /* Generate the customer */
                try {
                    $customer = $mollie->customers->create([
                        'name' => $this->user->name,
                        'email' => $this->user->email,
                    ]);
                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Generate the payment link */
                try {
                    $payment = $customer->createPayment([
                        'sequenceType' => 'first',
                        'amount' => [
                            'currency' => currency(),
                            'value' => $price,
                        ],
                        'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' .  l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'metadata' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'redirectUrl' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'webhookUrl'  => SITE_URL . 'webhook-mollie',
                    ]);
                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $payment->getCheckoutUrl()); die();

                break;
        }

        die();
    }

    private function crypto_com() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price = number_format($price, 2, '.', '') * 100;

        switch($_POST['payment_type']) {
            case 'one_time':

                \Unirest\Request::auth(settings()->crypto_com->secret_key, '');

                $response = \Unirest\Request::post(
                    'https://pay.crypto.com/api/payments',
                    [],
                    \Unirest\Request\Body::Form([
                        'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'amount' => $price,
                        'currency' => currency(),
                        'metadata' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'return_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'cancel_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount)),
                    ])
                );

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->error->type . ':' . $response->body->error->error_message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                header('Location: ' . $response->body->payment_url); die();

                break;
        }
    }

    private function paddle() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                $custom_id = $this->user->user_id . '&' . $this->plan_id . '&' . $_POST['payment_frequency'] . '&' . $base_amount . '&' . $code . '&' . $discount_amount . '&' . json_encode($this->applied_taxes_ids);

                $response = \Unirest\Request::post(
                    Paddle::get_api_url() . '2.0/product/generate_pay_link',
                    [],
                    \Unirest\Request\Body::Form([
                        'vendor_id' => settings()->paddle->vendor_id,
                        'vendor_auth_code' => settings()->paddle->api_key,
                        'title' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'webhook_url' => SITE_URL . 'webhook-paddle',
                        'prices' => [currency() . ':' . $price],
                        'customer_email' => $this->user->email,
                        'passthrough' => $custom_id,
                        'return_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'image_url' => settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != '' ? \Altum\Uploads::get_full_url('logo_' . \Altum\ThemeStyle::get()) . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} : '',
                        'quantity_variable' => 0,
                    ])
                );

                /* Check against errors */
                if(!$response->body->success) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->error->code . ':' . $response->body->error->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                $this->payment_extra_data = [
                    'payment_processor' => 'paddle',
                    'url' => $response->body->response->url,
                ];

                break;
        }
    }

    private function yookassa() {

        $yookassa = new \YooKassa\Client();
        $yookassa->setAuth(settings()->yookassa->shop_id, settings()->yookassa->secret_key);

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                /* Generate the payment link */
                try {
                    $payment = $yookassa->createPayment([
                        'amount' => [
                            'currency' => currency(),
                            'value' => $price,
                        ],
                        'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency']),
                        'metadata' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'receipt' => [
                            'customer' => [
                                'email' => $this->user->email,
                            ],
                            'items' => [
                                [
                                    'description' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency']),
                                    'quantity' => 1,
                                    'amount' => [
                                        'currency' => currency(),
                                        'value' => $price,
                                    ],
                                    'vat_code' => 1,
                                    'payment_subject' => 'commodity',
                                    'payment_mode' => 'full_payment',
                                ]
                            ]
                        ],
                        'confirmation' => [
                            'type' => 'redirect',
                            'return_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        ],
                        'capture' => true,
                    ], uniqid('', true));

                } catch (\Exception $exception) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($exception->getMessage());
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id);
                }

                /* Redirect to payment */
                header('Location: ' . $payment->getConfirmation()->getConfirmationUrl()); die();

                break;
        }

        die();
    }

    private function mercadopago() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price = number_format($price, 2, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                $custom_id = $this->user->user_id . '&' . $this->plan_id . '&' . $_POST['payment_frequency'] . '&' . $base_amount . '&' . $code . '&' . $discount_amount . '&' . json_encode($this->applied_taxes_ids);

                $response = \Unirest\Request::post(
                    'https://api.mercadopago.com/checkout/preferences',
                    [
                        'Authorization' => 'Bearer: ' . settings()->mercadopago->access_token,
                        'Content-Type' => 'application/json'
                    ],
                    \Unirest\Request\Body::json([
                        'items' => [
                            [
                                'id' => $this->plan->plan_id,
                                'title' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency']),
                                'quantity' => 1,
                                'currency_id' => currency(),
                                'unit_price' => (float) $price,
                            ]
                        ],
                        'back_urls' => [
                            'success' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                            'pending' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                            'failure' => url('pay/' . $this->plan_id . $this->return_url_parameters('cancel', $base_amount, $price, $code, $discount_amount)),
                        ],
                        'external_reference' => $custom_id,
                        'notification_url' => SITE_URL . 'webhook-mercadopago',
                    ])
                );

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->error . ':' . $response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response->body->init_point); die();

                break;
        }
    }

    private function midtrans() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price = number_format($price, 0, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                $custom_id = $this->user->user_id . '&' . $this->plan_id . '&' . $_POST['payment_frequency'] . '&' . $base_amount . '&' . $code . '&' . $discount_amount . '&' . json_encode($this->applied_taxes_ids);
                $payment_id = md5($this->user->user_id . $this->plan_id . $_POST['payment_type'] . $_POST['payment_frequency'] . $this->user->email . get_date());

                $response = \Unirest\Request::post(
                    'https://api' . (settings()->midtrans->mode == 'sandbox' ? 'sandbox' : null) . '.midtrans.com/v1/payment-links',
                    [
                        'Authorization' => 'Basic ' . base64_encode(settings()->midtrans->server_key . ':'),
                        'Content-Type' => 'application/json',
                        'X-Override-Notification' => SITE_URL . 'webhook-midtrans',

                    ],
                    \Unirest\Request\Body::json([
                        'transaction_details' => [
                            'order_id' => md5($payment_id),
                            'gross_amount' => $price,
                        ],
                        'expiry' => [
                            'duration' => 1,
                            'unit' => 'days'
                        ],
                        'item_details' => [[
                            'price' => $price,
                            'quantity' => 1,
                            'name' => settings()->business->brand_name . ' - ' . $this->plan->name . ' - ' . l('plan.custom_plan.' . $_POST['payment_frequency'])
                        ]],
                        'custom_field1' => $custom_id,
                    ])
                );

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->error_messages);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response->body->payment_url); die();

                break;
        }
    }

    private function flutterwave() {

        extract($this->get_price_details());

        /* Taxes */
        $price = $this->calculate_price_with_taxes($price);

        /* Final price */
        $price = number_format($price, 0, '.', '');

        switch($_POST['payment_type']) {
            case 'one_time':

                $payment_id = md5($this->user->user_id . $this->plan_id . $_POST['payment_type'] . $_POST['payment_frequency'] . $this->user->email . get_date());

                $response = \Unirest\Request::post(
                    'https://api.flutterwave.com/v3/payments',
                    [
                        'Authorization' => 'Bearer ' . settings()->flutterwave->secret_key,
                        'Content-Type' => 'application/json',
                    ],
                    \Unirest\Request\Body::json([
                        'tx_ref' => $payment_id,
                        'amount' => $price,
                        'currency' => currency(),
                        'redirect_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'meta' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'customer' => [
                            'email' => $this->user->email,
                            'name' => $this->user->name
                        ],
                    ])
                );

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response->body->data->link); die();

                break;

            case 'recurring':

                /* Get all available payment plans */
                $response = \Unirest\Request::get(
                    'https://api.flutterwave.com/v3/payment-plans',
                    [
                        'Authorization' => 'Bearer ' . settings()->flutterwave->secret_key,
                        'Content-Type' => 'application/json',
                    ],
                );

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                $payment_plan = null;

                /* Go through each plan and try to find the one */
                foreach($response->body->data as $plan) {
                    if($plan->amount == $price && $plan->interval == ($_POST['payment_frequency'] == 'monthly' ? 'monthly' : 'yearly')) {
                        $payment_plan = $plan;
                    }
                }

                if(!$payment_plan) {
                    /* Generate a new payment plan */
                    $response = \Unirest\Request::post(
                        'https://api.flutterwave.com/v3/payment-plans',
                        [
                            'Authorization' => 'Bearer ' . settings()->flutterwave->secret_key,
                            'Content-Type' => 'application/json',
                        ],
                        \Unirest\Request\Body::json([
                            'name' => settings()->business->brand_name . ' - ' . $this->plan->name,
                            'amount' => $price,
                            'currency' => currency(),
                            'interval' => $_POST['payment_frequency'] == 'monthly' ? 'monthly' : 'yearly',
                        ])
                    );

                    /* Check against errors */
                    if($response->code >= 400) {
                        if(DEBUG || \Altum\Authentication::is_admin()) {
                            Alerts::add_error($response->body->message);
                        } else {
                            Alerts::add_error(l('pay.error_message.failed_payment'));
                        }
                        redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                    }

                    $payment_plan = $response->body->data;
                }

                $payment_id = md5($this->user->user_id . $this->plan_id . $_POST['payment_type'] . $_POST['payment_frequency'] . $this->user->email . get_date());

                $response = \Unirest\Request::post(
                    'https://api.flutterwave.com/v3/payments',
                    [
                        'Authorization' => 'Bearer ' . settings()->flutterwave->secret_key,
                        'Content-Type' => 'application/json',
                    ],
                    \Unirest\Request\Body::json([
                        'payment_plan' => $payment_plan->id,
                        'tx_ref' => $payment_id,
                        'amount' => $price,
                        'currency' => currency(),
                        'redirect_url' => url('pay/' . $this->plan_id . $this->return_url_parameters('success', $base_amount, $price, $code, $discount_amount)),
                        'meta' => [
                            'user_id' => $this->user->user_id,
                            'plan_id' => $this->plan_id,
                            'payment_frequency' => $_POST['payment_frequency'],
                            'base_amount' => $base_amount,
                            'code' => $code,
                            'discount_amount' => $discount_amount,
                            'taxes_ids' => json_encode($this->applied_taxes_ids)
                        ],
                        'customer' => [
                            'email' => $this->user->email,
                            'name' => $this->user->name
                        ],
                    ])
                );

                /* Check against errors */
                if($response->code >= 400) {
                    if(DEBUG || \Altum\Authentication::is_admin()) {
                        Alerts::add_error($response->body->message);
                    } else {
                        Alerts::add_error(l('pay.error_message.failed_payment'));
                    }
                    redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
                }

                /* Redirect to payment */
                header('Location: ' . $response->body->data->link); die();

                break;

        }
    }

    private function payment_return_process() {

        /* Return confirmation processing if successfully */
        if($this->return_type && $this->payment_processor && $this->return_type == 'success') {

            /* Redirect to the thank you page */
            $this->redirect_pay_thank_you();
        }

        /* Return confirmation processing if failed */
        if($this->return_type && $this->payment_processor && $this->return_type == 'cancel') {
            Alerts::add_error(l('pay.error_message.canceled_payment'));
            redirect('pay/' . $this->plan_id . '?' . (isset($_GET['trial_skip']) ? '&trial_skip=true' : null) . (isset($_GET['code']) ? '&code=' . $_GET['code'] : null));
        }

    }

    /* Ajax to check if discount codes are available */
    public function code() {
        \Altum\Authentication::guard();

        $_POST = json_decode(file_get_contents('php://input'), true);

        if(!\Altum\Csrf::check('global_token')) {
            die();
        }

        if(!settings()->payment->is_enabled || !settings()->payment->codes_is_enabled) {
            die();
        }

        if(empty($_POST)) {
            die();
        }

        $_POST['plan_id'] = (int) $_POST['plan_id'];
        $_POST['code'] = trim(query_clean($_POST['code']));

        if(!$plan = db()->where('plan_id', $_POST['plan_id'])->getOne('plans')) {
            Response::json(l('pay.error_message.code_invalid'), 'error');
        }

        /* Make sure the discount code exists */
        $code = database()->query("SELECT * FROM `codes` WHERE `code` = '{$_POST['code']}' AND `redeemed` < `quantity`")->fetch_object();

        if(!$code) {
            Response::json(l('pay.error_message.code_invalid'), 'error');
        }

        $code->plans_ids = json_decode($code->plans_ids ?? '');

        if(!in_array($_POST['plan_id'], $code->plans_ids)) {
            Response::json(l('pay.error_message.code_invalid'), 'error');
        }

        if(db()->where('user_id', $this->user->user_id)->where('code_id', $code->code_id)->has('redeemed_codes')) {
            Response::json(l('pay.error_message.code_used'), 'error');
        }

        Response::json(
            sprintf(l('pay.success_message.code'), '<strong>' . $code->discount . '%</strong>'),
            'success',
            [
                'code' => $code,
                'submit_text' => $code->type == 'redeemable' ? sprintf(l('pay.custom_plan.code_redeemable'), $code->days) : l('pay.custom_plan.pay')
            ]
        );
    }

    /* Generate the generic return url parameters */
    private function return_url_parameters($return_type, $base_amount, $total_amount, $code, $discount_amount) {
        return
            '&return_type=' . $return_type
            . '&payment_processor=' . $_POST['payment_processor']
            . '&payment_frequency=' . $_POST['payment_frequency']
            . '&payment_type=' . $_POST['payment_type']
            . '&code=' . $code
            . '&discount_amount=' . $discount_amount
            . '&base_amount=' . $base_amount
            . '&total_amount=' . $total_amount
            . '&currency=' . currency();
    }

    /* Simple url generator to return the thank you page */
    private function redirect_pay_thank_you() {
        $thank_you_url_parameters_raw = array_filter($_GET, function($key) {
            return $key != 'altum';
        }, ARRAY_FILTER_USE_KEY);

        $thank_you_url_parameters = '&plan_id=' . $this->plan_id;
        $thank_you_url_parameters .= '&user_id=' . $this->user->user_id;

        /* Trial */
        if($this->plan->trial_days && !$this->user->plan_trial_done && !isset($_GET['trial_skip'])) {
            $thank_you_url_parameters .= '&trial_days=' . $this->plan->trial_days;
        }

        /* Redeemed */
        if($this->code && $this->code->type == 'redeemable' && in_array($this->plan_id, $this->code->plans_ids)) {
            $thank_you_url_parameters .= '&code_days=' . $this->code->days;
        }

        foreach($thank_you_url_parameters_raw as $key => $value) {
            $thank_you_url_parameters .= '&' . $key . '=' . $value;
        }

        $thank_you_url_parameters .= '&unique_transaction_identifier=' . md5(\Altum\Date::get('', 4) . $thank_you_url_parameters);

        redirect('pay-thank-you?' . $thank_you_url_parameters);
    }

    private function get_price_details() {
        /* Payment details */
        $price = $base_amount = (float) $this->plan->prices->{$_POST['payment_frequency']}->{currency()};
        $code = '';
        $discount_amount = 0;

        /* Check for code usage */
        if($this->code) {
            /* Discount amount */
            $discount_amount = number_format(($price * $this->code->discount / 100), 2, '.', '');

            /* Calculate the new price */
            $price = $price - $discount_amount;

            $code = $this->code->code;
        }

        return [
            'base_amount' => $base_amount,
            'price' => $price,
            'code' => $code,
            'discount_amount' => $discount_amount,
        ];
    }

    private function calculate_price_with_taxes($discounted_price) {

        $price = $discounted_price;

        if($this->plan_taxes) {

            /* Check for the inclusives */
            $inclusive_taxes_total_percentage = 0;

            foreach($this->plan_taxes as $row) {
                if($row->type == 'exclusive') continue;

                $inclusive_taxes_total_percentage += $row->value;
            }

            $total_inclusive_tax = $price - ($price / (1 + $inclusive_taxes_total_percentage / 100));

            $price_without_inclusive_taxes = $price - $total_inclusive_tax;

            /* Check for the exclusives */
            $exclusive_taxes_array = [];

            foreach($this->plan_taxes as $row) {

                if($row->type == 'inclusive') {
                    continue;
                }

                $exclusive_tax = $row->value_type == 'percentage' ? $price_without_inclusive_taxes * ($row->value / 100) : $row->value;

                $exclusive_taxes_array[] = $exclusive_tax;

            }

            $exclusive_taxes = array_sum($exclusive_taxes_array);

            /* Price with all the taxes */
            $price_with_taxes = $price + $exclusive_taxes;

            $price = $price_with_taxes;
        }

        return $price;

    }
}
