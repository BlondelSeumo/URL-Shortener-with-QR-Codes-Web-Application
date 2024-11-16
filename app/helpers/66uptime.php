<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

function ping($monitor) {

    /* Error details */
    $error = null;

    /* Local request, native server */
    switch($monitor->type) {

            /* Fsockopen */
            case 'port':

                $ping = new \JJG\Ping($monitor->target);
                $ping->setTimeout($monitor->settings->timeout_seconds);
                $ping->setPort($monitor->port);
                $latency = $ping->ping('fsockopen');

                if($latency !== false) {
                    $response_status_code = 0;
                    $response_time = $latency;

                    /*  :)  */
                    $is_ok = 1;
                } else {
                    $response_status_code = 0;
                    $response_time = 0;

                    /*  :)  */
                    $is_ok = 0;
                }

                break;

            /* Ping check */
            case 'ping':

                $ping = new \JJG\Ping($monitor->target);
                $ping->setTimeout($monitor->settings->timeout_seconds);
                $latency = $ping->ping('fsockopen');

                if($latency !== false) {
                    $response_status_code = 0;
                    $response_time = $latency;

                    /*  :)  */
                    $is_ok = 1;
                } else {
                    $response_status_code = 0;
                    $response_time = 0;

                    /*  :)  */
                    $is_ok = 0;
                }

                break;

            /* Websites check */
            case 'website':

                /* Set timeout */
                \Unirest\Request::timeout($monitor->settings->timeout_seconds);

                try {

                    /* Set auth */
                    \Unirest\Request::auth($monitor->settings->request_basic_auth_username ?? '', $monitor->settings->request_basic_auth_password ?? '');

                    /* Make the request to the website */
                    $method = mb_strtolower($monitor->settings->request_method ?? 'get');

                    if(in_array($method, ['post', 'put', 'patch'])) {
                        $response = \Unirest\Request::{$method}($monitor->target, $monitor->settings->request_headers ?? [], $monitor->settings->request_body ?? []);
                    } else {
                        $response = \Unirest\Request::{$method}($monitor->target, $monitor->settings->request_headers ?? []);
                    }

                    /* Get info after the request */
                    $info = \Unirest\Request::getInfo();

                    /* Some needed variables */
                    $response_status_code = $info['http_code'];
                    $response_time = $info['total_time'] * 1000;

                    /* Check the response to see how we interpret the results */
                    $is_ok = 1;

                    if($response_status_code != ($monitor->settings->response_status_code ?? 200)) {
                        $is_ok = 0;
                        $error = ['type' => 'response_status_code'];
                    }

                    if(isset($monitor->settings->response_body) && $monitor->settings->response_body && mb_strpos($response->raw_body, $monitor->settings->response_body) === false) {
                        $is_ok = 0;
                        $error = ['type' => 'response_body'];
                    }

                    if(isset($monitor->settings->response_headers)) {
                        foreach($monitor->settings->response_headers as $response_header) {
                            $response_header->name = mb_strtolower($response_header->name);

                            if(!isset($response->headers[$response_header->name]) || (isset($response->headers[$response_header->name]) && $response->headers[$response_header->name] != $response_header->value)) {
                                $is_ok = 0;
                                $error = ['type' => 'response_header'];
                                break;
                            }
                        }
                    }

                } catch (\Exception $exception) {
                    $response_status_code = 0;
                    $response_time = 0;
                    $error = [
                        'type' => 'exception',
                        'code' => curl_errno(\Unirest\Request::getCurlHandle()),
                        'message' => curl_error(\Unirest\Request::getCurlHandle()),
                    ];

                    /*  :)  */
                    $is_ok = 0;
                }

                break;
        }

    return [
        'is_ok' => $is_ok,
        'response_time' => $response_time,
        'response_status_code' => $response_status_code,
        'error' => $error
    ];

}


function get_website_certificate($url, $port = 443) {
    try {
        $domain = parse_url($url, PHP_URL_HOST);

        $get = stream_context_create([
            'ssl' => [
                'capture_peer_cert' => TRUE,
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            ]
        ]);

        $read = @stream_socket_client('ssl://' . $domain . ':' . $port, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $get);

        if(!$read || $errstr) return null;

        $certificate_params = stream_context_get_params($read);

        $certificate = openssl_x509_parse($certificate_params['options']['ssl']['peer_certificate']);

        if(empty($certificate)) return null;

        $start_datetime = $certificate['validFrom_time_t'] ? (new \DateTime())->setTimestamp($certificate['validFrom_time_t']) : null;
        $end_datetime = $certificate['validTo_time_t'] ? (new \DateTime())->setTimestamp($certificate['validTo_time_t']) : null;
        $current_datetime = (new \DateTime());
        $is_valid = $start_datetime && $end_datetime && $current_datetime > $start_datetime && $current_datetime < $end_datetime;

        return empty($certificate) ? null : [
            'organization' => $certificate['issuer']['O'] ?? null,
            'common_name' => $certificate['issuer']['CN'] ?? null,
            'issuer_country' => $certificate['issuer']['C'] ?? null,
            'start_datetime' => $start_datetime ? $start_datetime->format('Y-m-d H:i:s') : null,
            'end_datetime' => $end_datetime ? $end_datetime->format('Y-m-d H:i:s') : null,
            'signature_type' => $certificate['signatureTypeSN'] ?? null,
            'is_valid' => $is_valid,
        ];

    } catch (\Exception $exception) {
        return null;
    }
}
