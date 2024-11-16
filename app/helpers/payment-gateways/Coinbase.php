<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\PaymentGateways;

/* Helper class for Coinbase */
class Coinbase {
    static public $api_url = 'https://api.commerce.coinbase.com/';
    static public $access_token = null;

    public static function get_api_url() {
        return self::$api_url;
    }

    public static function get_headers() {
        return [
            'Content-Type' => 'application/json',
            'X-CC-Api-Key' => settings()->coinbase->api_key,
            'X-CC-Version' => '2018-03-22'
        ];
    }

    public static function verify_webhook_signature($payload, $signature_header) {
        $data = \json_decode($payload);

        if(json_last_error()) {
            throw new \Exception('Invalid payload provided. No JSON object could be decoded.', $payload);
        }

        if(!isset($data->event)) {
            throw new \Exception('Invalid payload provided.', $payload);
        }

        $computed_signature = \hash_hmac('sha256', $payload, settings()->coinbase->webhook_secret);

        if(!self::hashEqual($signature_header, $computed_signature)) {
            throw new \Exception($computed_signature, $payload);
        }

        return $data;
    }

    public static function hashEqual($str1, $str2) {
        if(function_exists('hash_equals')) {
            return \hash_equals($str1, $str2);
        }

        if(strlen($str1) != strlen($str2)) {
            return false;
        } else {
            $res = $str1 ^ $str2;
            $ret = 0;

            for ($i = strlen($res) - 1; $i >= 0; $i--) {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}
