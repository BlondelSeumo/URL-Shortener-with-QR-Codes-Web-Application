<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Models;

use Altum\Logger;
use Altum\PaymentGateways\Paystack;
use MaxMind\Db\Reader;
use Razorpay\Api\Api;

class User extends Model {

    public function get_user_by_user_id($user_id) {

        /* Try to check if the store exists via the cache */
        $cache_instance = cache()->getItem('user?user_id=' . $user_id);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = db()->where('user_id', $user_id)->getOne('users');

            if($data) {

                /* Parse the users plan settings */
                $data->plan_settings = json_decode($data->plan_settings ?? '');

                /* Parse billing details if existing */
                $data->billing = json_decode($data->billing ?? '');

                /* Parse preferences if existing */
                $data->preferences = json_decode($data->preferences ?? '');

                /* Save to cache */
                cache()->save(
                    $cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $data->user_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    public function get_user_by_user_id_and_token_code($user_id, $token_code) {

        /* Try to check if the store exists via the cache */
        $cache_instance = cache()->getItem('user?user_id=' . md5($user_id) . '&token_code=' . $token_code);

        /* Set cache if not existing */
        if(is_null($cache_instance->get())) {

            /* Get data from the database */
            $data = db()->where('user_id', $user_id)->where('token_code', $token_code)->getOne('users');

            if($data) {

                /* Parse the users plan settings */
                $data->plan_settings = json_decode($data->plan_settings ?? '');

                /* Parse billing details if existing */
                $data->billing = json_decode($data->billing ?? '');

                /* Parse preferences if existing */
                $data->preferences = json_decode($data->preferences ?? '');

                /* Save to cache */
                cache()->save(
                    $cache_instance->set($data)->expiresAfter(CACHE_DEFAULT_SECONDS)->addTag('user_id=' . $data->user_id)
                );
            }

        } else {

            /* Get cache */
            $data = $cache_instance->get();

        }

        return $data;
    }

    /* Requires full user variable */
    public function process_user_plan_expiration_by_user($user) {

        if((new \DateTime($user->plan_expiration_date)) < (new \DateTime()) && $user->plan_id != 'free') {

            /* Switch the user to the default plan */
            db()->where('user_id', $user->user_id)->update('users', [
                'plan_id' => 'free',
                'plan_settings' => json_encode(settings()->plan_free->settings),
                'payment_subscription_id' => ''
            ]);

            /* Clear the cache */
            cache()->deleteItemsByTag('user_id=' . $user->user_id);
        }

    }

    public function delete($user_id) {

        /* Cancel his active subscriptions if active */
        try {
            $this->cancel_subscription($user_id);
        } catch (\Exception $exception) {
            // :)
        }

        /* Send webhook notification if needed */
        if(settings()->webhooks->user_delete) {

            $user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'email', 'name']);

            \Unirest\Request::post(settings()->webhooks->user_delete, [], [
                'user_id' => $user->user_id,
                'email' => $user->email,
                'name' => $user->name
            ]);

        }

        /* Delete everything related to the domain that the user owns */
        $result = database()->query("SELECT `link_id` FROM `links` WHERE `user_id` = {$user_id}");
        while($link = $result->fetch_object()) {
            (new \Altum\Models\Link())->delete($link->link_id);
        }

        /* Delete everything related to the qr codes that the user owns */
        if(settings()->codes->qr_codes_is_enabled) {
            $result = database()->query("SELECT `qr_code_id` FROM `qr_codes` WHERE `user_id` = {$user_id}");

            while($qr_code = $result->fetch_object()) {
                (new \Altum\Models\QrCode())->delete($qr_code->qr_code_id);
            }
        }

        /* Delete everything related to the images that the user owns */
        if(\Altum\Plugin::is_active('aix') && settings()->aix->images_is_enabled) {
            $result = database()->query("SELECT `image_id`, `image` FROM `images` WHERE `user_id` = {$user_id}");

            while($image = $result->fetch_object()) {
                \Altum\Uploads::delete_uploaded_file($image->image, 'images');

                /* Delete the resource */
                db()->where('image_id', $image->image_id)->delete('images');
            }
        }

        /* Delete the record from the database */
        db()->where('user_id', $user_id)->delete('users');

        /* Clear the cache */
        cache()->deleteItemsByTag('user_id=' . $user_id);

    }

    public function update_last_activity($user_id) {
        db()->where('user_id', $user_id)->update('users', ['last_activity' => get_date()]);
        /* Clear the cache */
        cache()->deleteItemsByTag('user_id=' . $user_id);
    }

    public function verify_null_password($user_id, $email, $password) {
        if(empty($password)) {
            $lost_password_code = $lost_password_code ?? md5($email . microtime());
            db()->where('user_id', $user_id)->update('users', ['lost_password_code' => $lost_password_code]);
            redirect('reset-password/' . md5($email) . '/' . $lost_password_code);
        }

        return;
    }

    public function create(
        $email = '',
        $raw_password = '',
        $name = '',
        $status = 0,
        $source = null,
        $email_activation_code = null,
        $lost_password_code = null,
        $is_newsletter_subscribed = 0,
        $plan_id = 'free',
        $plan_settings = '',
        $plan_expiration_date = null,
        $timezone = 'UTC',
        $extra = '',
        $is_admin_created = false
    ) {

        /* Define some needed variables */
        $password = is_null($raw_password) ? null : password_hash($raw_password, PASSWORD_DEFAULT);
        $total_logins = $status == '1' && !$is_admin_created ? 1 : 0;
        $plan_expiration_date = $plan_expiration_date ?? get_date();
        $plan_trial_done = 0;
        $language = \Altum\Language::$name;
        $api_key = md5($email . microtime() . microtime());
        $referral_key = md5(rand() . $email . microtime() . $email. microtime());
        $ip = $is_admin_created ? null : get_ip();

        /* Detect the location */
        try {
            $maxmind = $is_admin_created ? null : (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get($ip);
        } catch(\Exception $exception) {
            /* :) */
        }
        $continent_code = isset($maxmind) && isset($maxmind['continent']) ? $maxmind['continent']['code'] : null;
        $country_code = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
        $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;

        /* Billing */
        $billing = json_encode(['type' => 'personal', 'name' => '', 'address' => '', 'city' => '', 'county' => '', 'zip' => '', 'country' => $country_code, 'phone' => '', 'tax_id' => '', 'notes' => '']);

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
        $browser_name = $whichbrowser->browser->name ?? null;
        $os_name = $whichbrowser->os->name ?? null;
        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);

        /* Check for potential referral cookie */
        $referred_by = null;
        if(!$is_admin_created && isset($_COOKIE['referred_by']) && $user = db()->where('referral_key', $_COOKIE['referred_by'])->getOne('users', ['user_id', 'referral_key'])) {
            $referred_by = $user->user_id;
        }

        /* Default preferences */
        $preferences = json_encode([
            'default_results_per_page' => 100,
            'default_order_type' => 'DESC',
            'links_default_order_by' => 'link_id',
            'qr_codes_default_order_by' => 'qr_code_id',
            'openai_api_key' => '',
            'clipdrop_api_key' => ''
        ]);

        /* Add the user to the database */
        $registered_user_id = db()->insert('users', [
            'password' => $password,
            'email' => $email,
            'name' => $name,
            'billing' => $billing,
            'api_key' => $api_key,
            'email_activation_code' => $email_activation_code,
            'lost_password_code' => $lost_password_code,
            'is_newsletter_subscribed' => (int) $is_newsletter_subscribed,
            'plan_id' => $plan_id,
            'plan_expiration_date' => $plan_expiration_date,
            'plan_settings' => $plan_settings,
            'plan_trial_done' => $plan_trial_done,
            'referral_key' => $referral_key,
            'referred_by' => $referred_by,
            'language' => $language,
            'timezone' => $timezone,
            'status' => $status,
            'source' => $source,
            'datetime' => get_date(),
            'ip' => $ip,
            'continent_code' => $continent_code,
            'country' => $country_code,
            'city_name' => $city_name,
            'device_type' => $device_type,
            'os_name' => $os_name,
            'browser_name' => $browser_name,
            'browser_language' => $browser_language,
            'total_logins' => $total_logins,
            'extra' => json_encode($extra),
            'preferences' => $preferences,
        ]);

        /* Clear out referral cookie if needed */
        if($referred_by) {
            setcookie('referred_by', '', time()-30, COOKIE_PATH);
        }

        \Altum\CustomHooks::user_finished_registration(['user_id' => $registered_user_id, 'email' => $email, 'plan_settings' => $plan_settings]);

        return [
            'user_id' => $registered_user_id,
            'password' => $password,
        ];
    }

    /*
    * Function to update a user with more details on a login action
    */
    public function login_aftermath_update($user_id, $method = 'default') {

        $ip = get_ip();


        /* Detect the location */
        try {
            $maxmind = (new Reader(APP_PATH . 'includes/GeoLite2-City.mmdb'))->get($ip);
        } catch(\Exception $exception) {
            /* :) */
        }
        $continent_code = isset($maxmind) && isset($maxmind['continent']) ? $maxmind['continent']['code'] : null;
        $country_name = isset($maxmind) && isset($maxmind['country']) ? $maxmind['country']['iso_code'] : null;
        $city_name = isset($maxmind) && isset($maxmind['city']) ? $maxmind['city']['names']['en'] : null;

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);
        $browser_name = $whichbrowser->browser->name ?? null;
        $os_name = $whichbrowser->os->name ?? null;
        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);

        /* Database query */
        db()->where('user_id', $user_id)->update('users', [
            'ip' => $ip,
            'continent_code' => $continent_code,
            'country' => $country_name,
            'city_name' => $city_name,
            'device_type' => $device_type,
            'os_name' => $os_name,
            'browser_name' => $browser_name,
            'browser_language' => $browser_language,
            'total_logins' => db()->inc(),
            'user_deletion_reminder' => 0,
        ]);

        Logger::users($user_id, 'login.' . $method . '.success');

        /* Clear the cache */
        cache()->deleteItemsByTag('user_id=' . $user_id);

    }

    public function cancel_subscription($user_id) {

        $user = db()->where('user_id', $user_id)->getOne('users', ['user_id', 'payment_subscription_id', 'payment_processor']);

        if(empty($user->payment_subscription_id)) {
            return true;
        }

        switch($user->payment_processor) {
            case 'stripe':

                /* Initiate Stripe */
                \Stripe\Stripe::setApiKey(settings()->stripe->secret_key);
                \Stripe\Stripe::setApiVersion('2023-10-16');

                /* Cancel the Stripe Subscription */
                $subscription = \Stripe\Subscription::retrieve($user->payment_subscription_id);
                $subscription->cancel();

                break;

            case 'paypal':

                $paypal_api_url = \Altum\PaymentGateways\Paypal::get_api_url();
                $headers = \Altum\PaymentGateways\Paypal::get_headers();

                $response = \Unirest\Request::post($paypal_api_url . 'v1/billing/subscriptions/' . $user->payment_subscription_id . '/cancel', $headers, \Unirest\Request\Body::json([
                    'reason' => sprintf(l('account_plan.cancel.reason'), settings()->main->title)
                ]));

                /* Check against errors */
                if($response->code >= 400) {
                    throw new \Exception($response->body->name . ':' . $response->body->message);
                }

                break;

            case 'paystack':

                Paystack::$secret_key = settings()->paystack->secret_key;

                $payment_subscription_id = explode('###', $user->payment_subscription_id);
                $code = $payment_subscription_id[0];
                $token = $payment_subscription_id[1];

                $response = \Unirest\Request::post(Paystack::$api_url . 'subscription/disable', Paystack::get_headers(), \Unirest\Request\Body::json([
                    'code' => $code,
                    'token' => $token,
                ]));

                if(!$response->body->status) {
                    throw new \Exception($response->body->message);
                }

                break;

            case 'razorpay':

                $razorpay = new Api(settings()->razorpay->key_id, settings()->razorpay->key_secret);

                $response = $razorpay->subscription->fetch($user->payment_subscription_id)->cancel();

                break;

            case 'mollie':

                $mollie = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey(settings()->mollie->api_key);

                $payment_subscription_id = explode('###', $user->payment_subscription_id);
                $customer_id = $payment_subscription_id[0];
                $subscription_id = $payment_subscription_id[1];

                $mollie->subscriptions->cancelForId($customer_id, $subscription_id);

                break;

            case 'flutterwave':

                $response = \Unirest\Request::put(
                    'https://api.flutterwave.com/v3/subscriptions/' . $user->payment_subscription_id . '/cancel',
                    [
                        'Authorization' => 'Bearer ' . settings()->flutterwave->secret_key,
                        'Content-Type' => 'application/json',
                    ],
                );

                /* Check against errors */
                if($response->code >= 400) {
                    throw new \Exception($response->body->message);
                }

                break;
        }

        /* Database query */
        db()->where('user_id', $user->user_id)->update('users', ['payment_subscription_id' => '']);

        /* Clear the cache */
        cache()->deleteItemsByTag('user_id=' . $user->user_id);

    }

}
