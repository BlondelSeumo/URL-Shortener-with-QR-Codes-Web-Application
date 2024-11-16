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
use Altum\Response;
use Altum\Uploads;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use SimpleSoftwareIO\QrCode\Generator;
use SVG\Nodes\Embedded\SVGImage;
use SVG\Nodes\Shapes\SVGRect;
use SVG\Nodes\Structures\SVGDefs;
use SVG\Nodes\Structures\SVGGroup;
use SVG\Nodes\Structures\SVGPattern;
use SVG\SVG;

class QrCodeGenerator extends Controller {

    public function index() {

        if(!settings()->codes->qr_codes_is_enabled) {
            redirect('not-found');
        }

        if(empty($_POST)) {
            die();
        }

        if(isset($_POST['json'])) {
            $_POST = json_decode($_POST['json'], true);
        }

        /* Check for the API Key */
        $user = db()->where('api_key', $_POST['api_key'])->where('status', 1)->getOne('users');

        if(!$user) {
            die();
        }

        $this->user = $user;
        $user->plan_settings = json_decode($user->plan_settings);

        $available_qr_codes = require APP_PATH . 'includes/enabled_qr_codes.php';

        /* Process variables */
        $_POST['type'] = isset($_POST['type']) && array_key_exists($_POST['type'], $available_qr_codes) ? $_POST['type'] : 'text';
        $_POST['style'] = isset($_POST['style']) && in_array($_POST['style'], ['square', 'dot', 'round', 'diamond', 'heart']) ? $_POST['style'] : 'square';
        $_POST['inner_eye_style'] = isset($_POST['inner_eye_style']) && in_array($_POST['inner_eye_style'], ['square', 'dot', 'rounded', 'diamond', 'flower', 'leaf',]) ? $_POST['inner_eye_style'] : 'square';
        $_POST['outer_eye_style'] = isset($_POST['outer_eye_style']) && in_array($_POST['outer_eye_style'], ['square', 'circle', 'rounded', 'flower', 'leaf',]) ? $_POST['outer_eye_style'] : 'square';
        $_POST['foreground_type'] = isset($_POST['foreground_type']) && in_array($_POST['foreground_type'], ['color', 'gradient']) ? $_POST['foreground_type'] : 'color';
        $_POST['background_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? '#ffffff' : $_POST['background_color'];
        $_POST['background_color_transparency'] = isset($_POST['background_color_transparency']) && in_array($_POST['background_color_transparency'], range(0, 100)) ? (int) $_POST['background_color_transparency'] : 0;
        $_POST['qr_code_background_transparency'] = isset($_POST['qr_code_background_transparency']) && in_array($_POST['qr_code_background_transparency'], range(0, 99)) ? (int) $_POST['qr_code_background_transparency'] : 0;
        $_POST['qr_code_foreground_transparency'] = isset($_POST['qr_code_foreground_transparency']) && in_array($_POST['qr_code_foreground_transparency'], range(0, 99)) ? (int) $_POST['qr_code_foreground_transparency'] : 0;
        $_POST['custom_eyes_color'] = (int) (bool) ($_POST['custom_eyes_color'] ?? 0);
        if($_POST['custom_eyes_color']) {
            $_POST['eyes_inner_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['eyes_inner_color']) ? null : $_POST['eyes_inner_color'];
            $_POST['eyes_outer_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['eyes_outer_color']) ? null : $_POST['eyes_outer_color'];
        }
        $qr_code_logo = !empty($_FILES['qr_code_logo']['name']) && !(int) isset($_POST['qr_code_logo_remove']);
        $_POST['qr_code_logo'] = $_POST['qr_code_logo'] ?? null;
        $_POST['qr_code_logo_size'] = isset($_POST['qr_code_logo_size']) && in_array($_POST['qr_code_logo_size'], range(5, 40)) ? (int) $_POST['qr_code_logo_size'] : 25;
        $qr_code_background = !empty($_FILES['qr_code_background']['name']) && !(int) isset($_POST['qr_code_background_remove']);
        $_POST['qr_code_background'] = $_POST['qr_code_background'] ?? null;
        $qr_code_foreground = !empty($_FILES['qr_code_foreground']['name']) && !(int) isset($_POST['qr_code_foreground_remove']);
        $_POST['qr_code_foreground'] = $_POST['qr_code_foreground'] ?? null;
        $_POST['size'] = isset($_POST['size']) && in_array($_POST['size'], range(50, 2000)) ? (int) $_POST['size'] : 500;
        $_POST['margin'] = isset($_POST['margin']) && in_array($_POST['margin'], range(0, 25)) ? (int) $_POST['margin'] : 0;
        $_POST['ecc'] = isset($_POST['ecc']) && in_array($_POST['ecc'], ['L', 'M', 'Q', 'H']) ? $_POST['ecc'] : 'M';
        $_POST['encoding'] = isset($_POST['encoding']) && in_array($_POST['encoding'], [
            'ISO-8859-1',
            'ISO-8859-2',
            'ISO-8859-3',
            'ISO-8859-4',
            'ISO-8859-5',
            'ISO-8859-6',
            'ISO-8859-7',
            'ISO-8859-8',
            'ISO-8859-9',
            'ISO-8859-10',
            'ISO-8859-11',
            'ISO-8859-12',
            'ISO-8859-13',
            'ISO-8859-14',
            'ISO-8859-15',
            'ISO-8859-16',
            'SHIFT-JIS',
            'WINDOWS-1250',
            'WINDOWS-1251',
            'WINDOWS-1252',
            'WINDOWS-1256',
            'UTF-16BE',
            'UTF-8',
            'ASCII',
            'GBK',
            'EUC-KR',
        ]) ? $_POST['encoding'] : 'UTF-8';
        $_POST['is_bulk'] = (int) (bool) ($_POST['is_bulk'] ?? 0);

        switch($_POST['type']) {
            case 'text':
                //$_POST['text'] = input_clean($_POST['text']);
                if($_POST['is_bulk']) {
                    $_POST['text'] = preg_split('/\r\n|\r|\n/', $_POST['text'])[0];
                }

                $data = $_POST['text'];
                break;

            case 'url':
                $_POST['url'] = get_url($_POST['url']);
                $data = $_POST['url'];
                break;

            case 'phone':
                //$_POST['phone'] = input_clean($_POST['phone']);
                $data = 'tel:' . $_POST['phone'];
                break;

            case 'sms':
                //$_POST['sms'] = input_clean($_POST['sms']);
                //$_POST['sms_body'] = input_clean($_POST['sms_body']);
                $data = 'SMSTO:' . $_POST['sms'] . ':' . $_POST['sms_body'];
                break;

            case 'email':
                $_POST['email'] = input_clean($_POST['email']);
                //$_POST['email_subject'] = input_clean($_POST['email_subject']);
                //$_POST['email_body'] = input_clean($_POST['email_body']);
                $data = 'MATMSG:TO:' . $_POST['email'] . ';SUB:' . $_POST['email_subject'] . ';BODY:' . $_POST['email_body'] . ';;';
                break;

            case 'whatsapp':
                //$_POST['whatsapp'] = input_clean($_POST['whatsapp']);
                //$_POST['whatsapp_body'] = input_clean($_POST['whatsapp_body']);
                $data = 'https://wa.me/' . $_POST['whatsapp'] . '?text=' . urlencode($_POST['whatsapp_body']);
                break;

            case 'facetime':
                //$_POST['facetime'] = input_clean($_POST['facetime']);
                $data = 'facetime:' . $_POST['facetime'];
                break;

            case 'location':
                $_POST['location_latitude'] = (float) $_POST['location_latitude'];
                $_POST['location_longitude'] = (float) $_POST['location_longitude'];
                $data = 'geo:' . $_POST['location_latitude'] . ',' . $_POST['location_longitude'] . '?q=' . $_POST['location_latitude'] . ',' . $_POST['location_longitude'];
                break;

            case 'wifi':
                //$_POST['wifi_ssid'] = input_clean($_POST['wifi_ssid']);
                $_POST['wifi_encryption'] = isset($_POST['wifi_encryption']) && in_array($_POST['wifi_encryption'], ['nopass', 'WEP', 'WPA/WPA2']) ? $_POST['wifi_encryption'] : 'nopass';
                if($_POST['wifi_encryption'] == 'WPA/WPA2') $_POST['wifi_encryption'] = 'WPA';
                //$_POST['wifi_password'] = input_clean($_POST['wifi_password']);
                $_POST['wifi_is_hidden'] = (int) $_POST['wifi_is_hidden'];

                $data_to_be_rendered = 'WIFI:S:' . $_POST['wifi_ssid'] . ';';
                $data_to_be_rendered .= 'T:' . $_POST['wifi_encryption'] . ';';
                if($_POST['wifi_password']) $data_to_be_rendered .= 'P:' . $_POST['wifi_password'] . ';';
                if($_POST['wifi_is_hidden']) $data_to_be_rendered .= 'H:' . (bool) $_POST['wifi_is_hidden'] . ';';
                $data_to_be_rendered .= ';';

                $data = $data_to_be_rendered;
                break;

            case 'event':
                //$_POST['event'] = input_clean($_POST['event']);
                //$_POST['event_location'] = input_clean($_POST['event_location']);
                $_POST['event_url'] = get_url($_POST['event_url']);
                //$_POST['event_note'] = input_clean($_POST['event_note']);
                $_POST['event_timezone'] = in_array($_POST['event_timezone'], \DateTimeZone::listIdentifiers()) ? $_POST['event_timezone'] : settings()->main->default_timezone;

                function event_parse_datetime($dateString, $timezone) {
                    try {
                        return (new \DateTime($dateString, new \DateTimeZone($timezone)))
                            ->setTimezone(new \DateTimeZone('UTC'))
                            ->format('Ymd\THis\Z');
                    } catch (\Exception $exception) {
                        return null;
                    }
                }

                $_POST['event_start_datetime'] = event_parse_datetime($_POST['event_start_datetime'], $_POST['event_timezone']);
                $_POST['event_end_datetime'] = empty($_POST['event_end_datetime']) ? null : event_parse_datetime($_POST['event_end_datetime'], $_POST['event_timezone']);
                $_POST['event_first_alert_datetime'] = empty($_POST['event_first_alert_datetime']) ? null : event_parse_datetime($_POST['event_first_alert_datetime'], $_POST['event_timezone']);
                $_POST['event_second_alert_datetime'] = empty($_POST['event_second_alert_datetime']) ? null : event_parse_datetime($_POST['event_second_alert_datetime'], $_POST['event_timezone']);


                $data_to_be_rendered = 'BEGIN:VEVENT' . "\n";
                $data_to_be_rendered .= 'UID:' . string_generate(32) . "\n";
                $data_to_be_rendered .= 'SUMMARY:' . $_POST['event'] . "\n";
                $data_to_be_rendered .= 'LOCATION:' . $_POST['event_location'] . "\n";
                $data_to_be_rendered .= 'URL:' . $_POST['event_url'] . "\n";
                $data_to_be_rendered .= 'DESCRIPTION:' . $_POST['event_note'] . "\n";
                $data_to_be_rendered .= 'DTSTART:' . $_POST['event_start_datetime'] . "\n";
                if($_POST['event_end_datetime']) $data_to_be_rendered .= 'DTEND:' . $_POST['event_end_datetime'] . "\n";

                if($_POST['event_first_alert_datetime']) {
                    $data_to_be_rendered .= 'BEGIN:VALARM' . "\n";;
                    $data_to_be_rendered .= 'ACTION:DISPLAY' . "\n";;
                    $data_to_be_rendered .= 'TRIGGER;VALUE=DATE-TIME:' . $_POST['event_first_alert_datetime'] . "\n";
                    $data_to_be_rendered .= 'END:VALARM' . "\n";;
                }

                if($_POST['event_second_alert_datetime']) {
                    $data_to_be_rendered .= 'BEGIN:VALARM' . "\n";;
                    $data_to_be_rendered .= 'ACTION:DISPLAY' . "\n";;
                    $data_to_be_rendered .= 'TRIGGER;VALUE=DATE-TIME:' . $_POST['event_second_alert_datetime'] . "\n";
                    $data_to_be_rendered .= 'END:VALARM' . "\n";;
                }

                $data_to_be_rendered .= 'END:VEVENT';

                $data = $data_to_be_rendered;
                break;

            case 'crypto':
                $_POST['crypto_coin'] = isset($_POST['crypto_coin']) && array_key_exists($_POST['crypto_coin'], $available_qr_codes['crypto']['coins']) ? $_POST['crypto_coin'] : array_key_first($available_qr_codes['crypto']['coins']);;
                //$_POST['crypto_address'] = input_clean($_POST['crypto_address']);
                $_POST['crypto_amount'] = isset($_POST['crypto_amount']) ? (float) $_POST['crypto_amount'] : null;
                $data = $_POST['crypto_coin'] . ':' . $_POST['crypto_address'] . ($_POST['crypto_amount'] ? '?amount=' . $_POST['crypto_amount'] : null);

                break;

            case 'vcard':
                $_POST['vcard_email'] = filter_var($_POST['vcard_email'], FILTER_SANITIZE_EMAIL);
                $_POST['vcard_url'] = get_url($_POST['vcard_url']);

                if(!isset($_POST['vcard_phone_number_label'])) {
                    $_POST['vcard_phone_number_label'] = [];
                    $_POST['vcard_phone_number_value'] = [];
                }

                if(!isset($_POST['vcard_social_label'])) {
                    $_POST['vcard_social_label'] = [];
                    $_POST['vcard_social_value'] = [];
                }

                $vcard = new \JeroenDesloovere\VCard\VCard();
                $vcard->addName($_POST['vcard_last_name'], $_POST['vcard_first_name']);
                if($_POST['vcard_email']) $vcard->addEmail($_POST['vcard_email']);
                if($_POST['vcard_url']) $vcard->addURL($_POST['vcard_url']);
                if($_POST['vcard_company']) $vcard->addCompany($_POST['vcard_company']);
                if($_POST['vcard_job_title']) $vcard->addJobtitle($_POST['vcard_job_title']);
                if($_POST['vcard_birthday']) $vcard->addBirthday($_POST['vcard_birthday']);
                if($_POST['vcard_note']) $vcard->addNote($_POST['vcard_note']);

                /* Address */
                if($_POST['vcard_street'] || $_POST['vcard_city'] || $_POST['vcard_region'] || $_POST['vcard_zip'] || $_POST['vcard_country']) {
                    $vcard->addAddress(null, null, $_POST['vcard_street'], $_POST['vcard_city'], $_POST['vcard_region'], $_POST['vcard_zip'], $_POST['vcard_country']);
                }

                /* Phone numbers */
                if(isset($_POST['vcard_phone_numbers'])) {
                    foreach($_POST['vcard_phone_numbers'] as $key => $phone_number) {
                        $_POST['vcard_phone_number_label'][$key] = $phone_number['label'];
                        $_POST['vcard_phone_number_value'][$key] = $phone_number['value'];
                    }
                }

                foreach($_POST['vcard_phone_number_label'] as $key => $value) {
                    $label = mb_substr($value, 0, $available_qr_codes['vcard']['phone_number_value']['max_length']);
                    $value = mb_substr($_POST['vcard_phone_number_value'][$key], 0, $available_qr_codes['vcard']['phone_number_value']['max_length']);

                    /* Custom label */
                    if($label) {
                        $vcard->setProperty(
                            'item' . $key . '.TEL',
                            'item' . $key . '.TEL',
                            $value
                        );
                        $vcard->setProperty(
                            'item' . $key . '.X-ABLabel',
                            'item' . $key . '.X-ABLabel',
                            $label
                        );
                    }

                    /* Default label */
                    else {
                        $vcard->addPhoneNumber($value);
                    }
                }

                /* Socials */
                if(isset($_POST['vcard_socials'])) {
                    foreach($_POST['vcard_socials'] as $key => $social) {
                        $_POST['vcard_social_label'][$key] = $social['label'];
                        $_POST['vcard_social_value'][$key] = $social['value'];
                    }
                }

                foreach($_POST['vcard_social_label'] as $key => $value) {
                    if(empty(trim($value))) continue;
                    if($key >= 20) continue;

                    $label = mb_substr($value, 0, $available_qr_codes['vcard']['social_value']['max_length']);
                    $value = mb_substr($_POST['vcard_social_value'][$key], 0, $available_qr_codes['vcard']['social_value']['max_length']);

                    $vcard->addURL(
                        $value,
                        'TYPE=' . $label
                    );
                }

                $data = $vcard->buildVCard();
                break;

            case 'paypal':
                $_POST['paypal_type'] = isset($_POST['paypal_type']) && array_key_exists($_POST['paypal_type'], $available_qr_codes['paypal']['type']) ? $_POST['paypal_type'] : array_key_first($available_qr_codes['paypal']['type']);;
                //$_POST['paypal_email'] = filter_var($_POST['paypal_email'], FILTER_SANITIZE_EMAIL);
                //$_POST['paypal_title'] = input_clean($_POST['paypal_title']);
                //$_POST['paypal_currency'] = input_clean($_POST['paypal_currency']);
                $_POST['paypal_price'] = (float) $_POST['paypal_price'];
                $_POST['paypal_thank_you_url'] = get_url($_POST['paypal_thank_you_url']);
                $_POST['paypal_cancel_url'] = get_url($_POST['paypal_cancel_url']);

                if($_POST['paypal_type'] == 'add_to_cart') {
                    $data = sprintf('https://www.paypal.com/cgi-bin/webscr?business=%s&cmd=%s&currency_code=%s&amount=%s&item_name=%s&button_subtype=products&add=1&return=%s&cancel_return=%s', $_POST['paypal_email'], $available_qr_codes['paypal']['type'][$_POST['paypal_type']], $_POST['paypal_currency'], $_POST['paypal_price'], $_POST['paypal_title'], $_POST['paypal_thank_you_url'], $_POST['paypal_cancel_url']);
                } else {
                    $data = sprintf('https://www.paypal.com/cgi-bin/webscr?business=%s&cmd=%s&currency_code=%s&amount=%s&item_name=%s&return=%s&cancel_return=%s', $_POST['paypal_email'], $available_qr_codes['paypal']['type'][$_POST['paypal_type']], $_POST['paypal_currency'], $_POST['paypal_price'], $_POST['paypal_title'], $_POST['paypal_thank_you_url'], $_POST['paypal_cancel_url']);
                }

                break;

            case 'upi':
                $_POST['upi_currency'] = in_array($_POST['upi_currency'], ['INR']) ? $_POST['upi_currency'] : 'INR';
                $_POST['upi_amount'] = isset($_POST['upi_amount']) ? (float) $_POST['upi_amount'] : null;
                $_POST['upi_thank_you_url'] = get_url($_POST['upi_thank_you_url']);

                $data = sprintf('upi://pay?pa=%s&pn=%s&cu=%s', $_POST['upi_payee_id'], $_POST['upi_payee_name'], $_POST['upi_currency']);

                if($_POST['upi_amount']){
                    $data .= '&am=' . $_POST['upi_amount'];
                }

                if($_POST['upi_transaction_id']){
                    $data .= '&tid=' . $_POST['upi_transaction_id'];
                }

                if($_POST['upi_transaction_reference']){
                    $data .= '&tr=' . $_POST['upi_transaction_reference'];
                }

                if($_POST['upi_transaction_note']){
                    $data .= '&tn=' . $_POST['upi_transaction_note'];
                }

                if($_POST['upi_thank_you_url']){
                    $data .= '&url=' . $_POST['upi_thank_you_url'];
                }

                break;

            case 'epc':
                $_POST['epc_amount'] = (float) $_POST['epc_amount'];
                $_POST['epc_currency'] = in_array($_POST['epc_currency'], ['EUR']) ? $_POST['epc_currency'] : 'EUR';
                $_POST['epc_amount'] = isset($_POST['epc_amount']) ? (float) $_POST['epc_amount'] : null;

                $data = 'BCD' . "\n";
                $data .= '002' . "\n";
                $data .= '2' . "\n";
                $data .= 'SCT' . "\n";
                $data .= ($_POST['epc_bic'] ?? null) . "\n";
                $data .= ($_POST['epc_payee_name'] ?? null) . "\n";
                $data .= ($_POST['epc_iban'] ?? null) . "\n";
                $data .= $_POST['epc_currency'] . $_POST['epc_amount'] . "\n";
                $data .= "\n";
                $data .= ($_POST['epc_remittance_reference'] ?? null) . "\n";
                $data .= ($_POST['epc_remittance_text'] ?? null) . "\n";
                $data .= ($_POST['epc_information'] ?? null) . "\n";

                break;

            case 'pix':

                function generatePixCode($key, $name, $city, $amount = 0, $description = "", $transactionID = "***") {
//                    Validate required fields
//                    if (empty($key) || empty($name) || empty($city)) {
//                        throw new Exception("Key, Name, and City must not be empty");
//                    }
//                    if (mb_strlen($name) > 25) {
//                        throw new Exception("Name must be at most 25 characters long");
//                    }
//                    if (mb_strlen($city) > 15) {
//                        throw new Exception("City must be at most 15 characters long");
//                    }

                    // Build Pix data map
                    $data = [
                        0 => "01", // Payload Format Indicator
                        26 => [
                            0 => "BR.GOV.BCB.PIX",
                            1 => $key,
                            2 => $description
                        ],
                        52 => "0000", // Merchant Category Code
                        53 => "986",  // Transaction Currency (Brazilian Real - ISO4217)
                        54 => number_format($amount, 2, '.', ''), // Transaction Amount
                        58 => "BR",  // Country Code (ISO3166-1 alpha 2)
                        59 => $name, // Merchant Name (Max 25 chars)
                        60 => $city, // Merchant City (Max 15 chars)
                        62 => [
                            5 => $transactionID,
                            50 => [
                                0 => "BR.GOV.BCB.BRCODE",
                                1 => "1.0.0"
                            ]
                        ]
                    ];

                    // Recursively parse data into Pix format
                    $pixString = parsePixData($data);

                    // Add CRC16 checksum
                    $pixString .= "6304";
                    $pixString .= calculateCRC16($pixString);

                    return $pixString;
                }

                function parsePixData($data) {
                    $result = "";
                    ksort($data);

                    foreach ($data as $key => $value) {
                        if (is_array($value)) {
                            $value = parsePixData($value);
                        } else {
                            $value = strval($value);
                        }
                        $result .= sprintf("%02d%02d%s", $key, strlen($value), $value);
                    }

                    return $result;
                }

                function calculateCRC16($str) {
                    $crc = 0xFFFF;
                    $poly = 0x1021;

                    for ($i = 0; $i < strlen($str); $i++) {
                        $byte = ord($str[$i]);
                        $crc ^= ($byte << 8);
                        for ($j = 0; $j < 8; $j++) {
                            if ($crc & 0x8000) {
                                $crc = ($crc << 1) ^ $poly;
                            } else {
                                $crc <<= 1;
                            }
                        }
                    }

                    return strtoupper(dechex($crc & 0xFFFF));
                }

                $_POST['pix_amount'] = (float) $_POST['pix_amount'];
                $_POST['pix_currency'] = in_array($_POST['pix_currency'], ['BRL']) ? $_POST['pix_currency'] : 'BRL';
                $_POST['pix_amount'] = isset($_POST['pix_amount']) ? (float) $_POST['pix_amount'] : null;

                $data = generatePixCode(
                    $_POST['pix_payee_key'],
                    $_POST['pix_payee_name'],
                    $_POST['pix_city'],
                    $_POST['pix_amount'],
                    $_POST['pix_description']
                );

                break;
        }

        /* Are we using a frame ? */
        $frames = require APP_PATH . 'includes/qr_codes_frames.php';
        $frame = $_POST['frame'] = isset($_POST['frame']) && array_key_exists($_POST['frame'], $frames) ? input_clean($_POST['frame']) : null;

        /* Make the margins more relaxed when using a frame */
        if($frame) {
            $_POST['margin'] = floor($_POST['margin'] / 2);
        }

        /* :) */
        $qr = new Generator;
        $qr->size($_POST['size']);
        $qr->errorCorrection($_POST['ecc']);
        $qr->encoding($_POST['encoding']);
        $qr->margin($_POST['margin']);

        /* Style */
        switch($_POST['style']) {
            case 'heart':
                $qr->style(\Altum\QrCodes\HeartModule::class, 0.8);
                break;

            case 'diamond':
                $qr->style(\Altum\QrCodes\DiamondModule::class, 0.9);
                break;

            default:
                $qr->style($_POST['style'], 0.9);
                break;
        }

        $qr->eye(\Altum\QrCodes\EyeCombiner::instance($_POST['inner_eye_style'], $_POST['outer_eye_style']));

        /* Colors */
        $background_color = hex_to_rgb($_POST['background_color']);
        if(isset($background_color['r'], $background_color['g'], $background_color['b'])) {
            $qr->backgroundColor($background_color['r'], $background_color['g'], $background_color['b'], 100 - $_POST['background_color_transparency']);
        }

        /* Eyes */
        if($_POST['custom_eyes_color']) {
            $eyes_inner_color = hex_to_rgb($_POST['eyes_inner_color']);
            $eyes_outer_color = hex_to_rgb($_POST['eyes_outer_color']);

            $qr->eyeColor(0, $eyes_outer_color['r'], $eyes_outer_color['g'], $eyes_outer_color['b'], $eyes_inner_color['r'], $eyes_inner_color['g'], $eyes_inner_color['b']);
            $qr->eyeColor(1, $eyes_outer_color['r'], $eyes_outer_color['g'], $eyes_outer_color['b'], $eyes_inner_color['r'], $eyes_inner_color['g'], $eyes_inner_color['b']);
            $qr->eyeColor(2, $eyes_outer_color['r'], $eyes_outer_color['g'], $eyes_outer_color['b'], $eyes_inner_color['r'], $eyes_inner_color['g'], $eyes_inner_color['b']);
        }

        /* Foreground */
        switch($_POST['foreground_type']) {
            case 'color':
                $_POST['foreground_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_color']) ? '#000000' : $_POST['foreground_color'];
                $foreground_color = hex_to_rgb($_POST['foreground_color']);
                $qr->color($foreground_color['r'], $foreground_color['g'], $foreground_color['b']);
                break;

            case 'gradient':
                $_POST['foreground_gradient_style'] = isset($_POST['foreground_gradient_style']) && in_array($_POST['foreground_gradient_style'], ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial']) ? $_POST['foreground_gradient_style'] : 'horizontal';
                $_POST['foreground_gradient_one'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_gradient_one'] ?? null) ? '#000000' : $_POST['foreground_gradient_one'];
                $_POST['foreground_gradient_two'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_gradient_two'] ?? null) ? '#000000' : $_POST['foreground_gradient_two'];

                $foreground_gradient_one = hex_to_rgb($_POST['foreground_gradient_one']);
                $foreground_gradient_two = hex_to_rgb($_POST['foreground_gradient_two']);
                $qr->gradient($foreground_gradient_one['r'], $foreground_gradient_one['g'], $foreground_gradient_one['b'], $foreground_gradient_two['r'], $foreground_gradient_two['g'], $foreground_gradient_two['b'], $_POST['foreground_gradient_style']);
                break;
        }

        /* Check if data is empty */
        if(!trim($data)) {
            $data = get_domain_from_url(SITE_URL);
            //Response::json(l('qr_codes.empty_error_message'), 'error');
        }

        /* Generate the first SVG */
        try {
            $svg = $qr->generate($data);

        } catch (\Exception $exception) {
            Response::json($exception->getMessage(), 'error');
        }

        /* Qr code foreground image */
        if(($_POST['qr_code_foreground'] || $qr_code_foreground) && !isset($_POST['qr_code_foreground_remove'])) {

            /* When background is fully transparent, change the index as the background svg code is missing */
            $child_index = $_POST['background_color_transparency'] == 100 ? 0 : 1;

            /* Get original generated QR svg group */
            $svg_object = SVG::fromString($svg);
            $original_qr_group = $svg_object->getDocument()->getChild($child_index);

            /* Start doing custom changes to the output SVG */
            $custom_svg_object = SVG::fromString($svg);
            $custom_svg_doc = $custom_svg_object->getDocument();

            /* Add the original group again as a new layer */
            $custom_svg_doc->addChild($original_qr_group);

            /* Already existing qr code foreground */
            if($_POST['qr_code_foreground']) {
                $qr_code_foreground_name = $_POST['qr_code_foreground'];
                $qr_code_foreground_link = $_POST['qr_code_foreground'];
            }

            /* Freshly uploaded qr code foreground */
            if($qr_code_foreground) {
                $qr_code_foreground_name = $_FILES['qr_code_foreground']['name'];
                $file_extension = explode('.', $qr_code_foreground_name);
                $file_extension = mb_strtolower(end($file_extension));
                $qr_code_foreground_link = $_FILES['qr_code_foreground']['tmp_name'];

                if($_FILES['qr_code_foreground']['error'] == UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit));
                }

                if($_FILES['qr_code_foreground']['error'] && $_FILES['qr_code_foreground']['error'] != UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(l('global.error_message.file_upload'));
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_foreground'))) {
                    Alerts::add_error(l('global.error_message.invalid_file_type'));
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(Uploads::get_full_path('qr_code_foreground'))) {
                        Response::json(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_foreground')), 'error');
                    }
                }

                if($_FILES['qr_code_foreground']['size'] > settings()->codes->background_size_limit * 1000000) {
                    Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 'error');
                }
            }

            /* Process uploaded foreground image */
            $qr_code_foreground_extension = explode('.', $qr_code_foreground_name);
            $qr_code_foreground_extension = mb_strtolower(end($qr_code_foreground_extension));
            $foreground = file_get_contents($qr_code_foreground_link);
            $foreground_base64 = 'data:image/' . $qr_code_foreground_extension . ';base64,' . base64_encode($foreground);

            /* Manually get sizing, as :) QR library has no such option directly */
            $qr_encoder = Encoder::encode($data, ErrorCorrectionLevel::{$_POST['ecc']}(), $_POST['encoding']);
            $qr_matrix = $qr_encoder->getMatrix();
            $qr_width = $qr_matrix->getWidth();

            /* Create a pattern with the foreground image */
            $scaled_size = $_POST['size'] * 23.81 / 500;
            $scaled_size = 21;
            $qr_defs = new SVGDefs();
            $qr_pattern = (new SVGPattern())
                ->setAttribute('id', 'foreground')
                ->setAttribute('x', 0)
                ->setAttribute('y', 0)
                ->setAttribute('width', $qr_width)
                ->setAttribute('height', $qr_width)
                ->setAttribute('patternUnits', 'userSpaceOnUse');

            $foreground_transparency = (float) number_format((100 - $_POST['qr_code_foreground_transparency']) / 100, 2, '.', '');
            $foreground = (new SVGImage($foreground_base64, 0, 0, $qr_width, $qr_width))->setAttribute('opacity', $foreground_transparency);

            /* Add it to the SVG */
            $qr_pattern->addChild($foreground);
            $qr_defs->addChild($qr_pattern);
            $custom_svg_doc->addChild($qr_defs, 1);

            /* When background is fully transparent, change the index as the background svg code is missing */
            $child_index = $_POST['background_color_transparency'] == 100 ? 2 : 3;

            /* Edit the original SVG to set the fill with the new foreground */
            $qr_group = $custom_svg_doc->getChild($child_index)->getChild(0);
            $qr_group->getChild($qr_group->countChildren() - 1)->setStyle('fill', 'url(#foreground)');

            /* Export the qr code with the foreground on top */
            $svg = $custom_svg_object->toXMLString();
        }

        if(($_POST['qr_code_logo'] || $qr_code_logo) && !isset($_POST['qr_code_logo_remove'])) {
            $logo_width_percentage = $_POST['qr_code_logo_size'];

            /* Start doing custom changes to the output SVG */
            $custom_svg_object = SVG::fromString($svg);
            $custom_svg_doc = $custom_svg_object->getDocument();

            /* Already existing qr code logo */
            if($_POST['qr_code_logo']) {
                $qr_code_logo_name = $_POST['qr_code_logo'];
                $qr_code_logo_link = $_POST['qr_code_logo'];
            }

            /* Freshly uploaded qr code logo */
            if($qr_code_logo) {
                $qr_code_logo_name = $_FILES['qr_code_logo']['name'];
                $file_extension = explode('.', $qr_code_logo_name);
                $file_extension = mb_strtolower(end($file_extension));
                $qr_code_logo_link = $_FILES['qr_code_logo']['tmp_name'];

                if($_FILES['qr_code_logo']['error'] == UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit));
                }

                if($_FILES['qr_code_logo']['error'] && $_FILES['qr_code_logo']['error'] != UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(l('global.error_message.file_upload'));
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_logo'))) {
                    Alerts::add_error(l('global.error_message.invalid_file_type'));
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(Uploads::get_full_path('qr_code_logo'))) {
                        Response::json(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_logo')), 'error');
                    }
                }

                if($_FILES['qr_code_logo']['size'] > settings()->codes->logo_size_limit * 1000000) {
                    Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit), 'error');
                }
            }

            /* Process uploaded logo image */
            $qr_code_logo_extension = explode('.', $qr_code_logo_name);
            $qr_code_logo_extension = mb_strtolower(end($qr_code_logo_extension));
            $logo = file_get_contents($qr_code_logo_link);
            $logo_base64 = 'data:image/' . $qr_code_logo_extension . ';base64,' . base64_encode($logo);

            /* Size of the logo */
            list($logo_width, $logo_height) = getimagesize($qr_code_logo_link);
            if($logo_width && $logo_height) {
                $logo_ratio = $logo_height / $logo_width;
                $logo_new_width = $_POST['size'] * $logo_width_percentage / 100;
                $logo_new_height = $logo_new_width * $logo_ratio;

                /* Calculate center of the QR code */
                $logo_x = $_POST['size'] / 2 - $logo_new_width / 2;
                $logo_y = $_POST['size'] / 2 - $logo_new_height / 2;

                /* Add the logo to the QR code */
                $logo = new SVGImage($logo_base64, $logo_x, $logo_y, $logo_new_width, $logo_new_height);
                $custom_svg_doc->addChild($logo);

                /* Export the QR code with the logo on top */
                $svg = $custom_svg_object->toXMLString();
            }
        }

        if(($_POST['qr_code_background'] || $qr_code_background) && !isset($_POST['qr_code_background_remove'])) {

            /* Start doing custom changes to the output SVG */
            $custom_svg_object = SVG::fromString($svg);
            $custom_svg_doc = $custom_svg_object->getDocument();

            /* Already existing qr code background */
            if($_POST['qr_code_background']) {
                $qr_code_background_name = $_POST['qr_code_background'];
                $qr_code_background_link = $_POST['qr_code_background'];
            }

            /* Freshly uploaded qr code background */
            if($qr_code_background) {
                $qr_code_background_name = $_FILES['qr_code_background']['name'];
                $file_extension = explode('.', $qr_code_background_name);
                $file_extension = mb_strtolower(end($file_extension));
                $qr_code_background_link = $_FILES['qr_code_background']['tmp_name'];

                if($_FILES['qr_code_background']['error'] == UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit));
                }

                if($_FILES['qr_code_background']['error'] && $_FILES['qr_code_background']['error'] != UPLOAD_ERR_INI_SIZE) {
                    Alerts::add_error(l('global.error_message.file_upload'));
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_background'))) {
                    Alerts::add_error(l('global.error_message.invalid_file_type'));
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(Uploads::get_full_path('qr_code_background'))) {
                        Response::json(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_background')), 'error');
                    }
                }

                if($_FILES['qr_code_background']['size'] > settings()->codes->background_size_limit * 1000000) {
                    Response::json(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 'error');
                }
            }

            /* Process uploaded background image */
            $qr_code_background_extension = explode('.', $qr_code_background_name);
            $qr_code_background_extension = mb_strtolower(end($qr_code_background_extension));
            $background = file_get_contents($qr_code_background_link);
            $background_base64 = 'data:image/' . $qr_code_background_extension . ';base64,' . base64_encode($background);

            /* Add the background to the QR code */
            $background_transparency = (float) number_format((100 - $_POST['qr_code_background_transparency']) / 100, 2, '.', '');
            $background = (new SVGImage($background_base64, 0, 0, $_POST['size'], $_POST['size']))->setAttribute('opacity', $background_transparency);

            /* When background is fully transparent, change the index as the background svg code is missing */
            $background_index = $_POST['background_color_transparency'] == 100 ? 0 : 1;

            /* Add the background */
            $custom_svg_doc->addChild($background, $background_index);

            /* Export the qr code with the background on top */
            $svg = $custom_svg_object->toXMLString();
        }

        /* QR code branding */
        if(settings()->codes->qr_codes_branding_logo && !$this->user->plan_settings->removable_branding) {
            /* Start doing custom changes to the output SVG */
            $custom_svg_object = SVG::fromString($svg);
            $custom_svg_doc = $custom_svg_object->getDocument();

            /* Process uploaded logo image */
            $qr_code_branding_logo_link = Uploads::get_full_url('qr_code_logo') . settings()->codes->qr_codes_branding_logo;
            $qr_code_branding_logo_extension = explode('.', $qr_code_branding_logo_link);
            $qr_code_branding_logo_extension = mb_strtolower(end($qr_code_branding_logo_extension));
            $branding_logo = file_get_contents($qr_code_branding_logo_link);
            $branding_logo_base64 = 'data:image/' . $qr_code_branding_logo_extension . ';base64,' . base64_encode($branding_logo);

            /* Size of the branding_logo */
            list($branding_logo_width, $branding_logo_height) = getimagesize($qr_code_branding_logo_link);
            $branding_logo_ratio = $branding_logo_height / $branding_logo_width;
            $branding_logo_new_width = $_POST['size'] * 10 / 100;
            $branding_logo_new_height = $branding_logo_new_width * $branding_logo_ratio;

            /* Calculate center of the QR code */
            $branding_logo_x = $_POST['size'] - $branding_logo_new_width;
            $branding_logo_y = $_POST['size'] - $branding_logo_new_height;

            /* Add the branding_logo to the QR code */
            $branding_logo = new SVGImage($branding_logo_base64, $branding_logo_x, $branding_logo_y, $branding_logo_new_width, $branding_logo_new_height);
            $custom_svg_doc->addChild($branding_logo);

            /* Export the QR code with the branding_logo on top */
            $svg = $custom_svg_object->toXMLString();
        }

        /* Frame processing */
        if($frame) {
            /* Frame */
            $_POST['frame_custom_colors'] = (int) isset($_POST['frame_custom_colors']);
            if($_POST['frame_custom_colors']) {
                $_POST['frame_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['frame_color']) ? null : $_POST['frame_color'];
                $_POST['frame_text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['frame_text_color']) ? null : $_POST['frame_text_color'];
            }

            /* Variables */
            $frame_width = $_POST['size'];
            $frame_height = number_format($_POST['size'] * $frames[$frame]['frame_height_scale'], 2, '.', '');
            $frame_scale = number_format($_POST['size'] / $frames[$frame]['frame_scale'], 2, '.', '');

            $qr_scale = number_format($frames[$frame]['qr_scale'], 2, '.', '');
            $qr_translate_x = number_format($_POST['size'] / $frames[$frame]['qr_translate_x'], 2, '.', '');
            $qr_translate_y = number_format($_POST['size'] / $frames[$frame]['qr_translate_y'], 2, '.', '');

            $qr_background_scale = number_format($_POST['size'] / $frames[$frame]['qr_background_scale'], 2, '.', '');
            $qr_background_x = number_format($_POST['size'] / $frames[$frame]['qr_background_x'], 2, '.', '');
            $qr_background_y = number_format($_POST['size'] / $frames[$frame]['qr_background_y'], 2, '.', '');

            /* Custom SVG for Frame */
            $frame_background = $_POST['frame_color'] ?? $_POST['foreground_color'] ?? 'url(#g1)';
            $frame_svg = sprintf($frames[$frame]['svg'], $frame_width, $frame_height, $frame_scale, $frame_background);

            /* Start doing custom changes to the output SVG */
            $frame_svg_object = SVG::fromString($frame_svg);
            $frame_svg_doc = $frame_svg_object->getDocument();

            /* Regenerate the background */
            $frame_background_object = (new SVGRect($qr_background_x, $qr_background_y, $qr_background_scale, $qr_background_scale))
                ->setAttribute('fill', $_POST['background_color'])
                ->setAttribute('fill-opacity', (1 - ($_POST['background_color_transparency'] / 100)));
            $frame_svg_doc->addChild($frame_background_object, 0);

            /* Load generated SVG */
            $svg_object = SVG::fromString($svg);
            $svg_doc = $svg_object->getDocument();

            /* Remove original background */
            $svg_doc->removeChild(0);

            /* Create a wrapper group around the main qr svg */
            $qr_group_object = new SVGGroup();
            $qr_group_object->setAttribute('transform', 'scale(' . $qr_scale . ') translate(' . $qr_translate_x . ' ' . $qr_translate_y . ')');
            $qr_group_object->addChild($svg_doc);

            /* Add the qr code to the frame */
            $frame_svg_doc->addChild($qr_group_object);

            /* Create text on top if needed */
            $frame_text = $_POST['frame_text'] = input_clean($_POST['frame_text'], 64);

            if($frame_text) {
                $frames_fonts = require APP_PATH . 'includes/qr_codes_frames_text_fonts.php';

                $frame_text_font = isset($_POST['frame_text_font']) && array_key_exists($_POST['frame_text_font'], $frames_fonts) ? $_POST['frame_text_font'] : array_key_first($frames_fonts);
                $frame_text_font_character_width = $frames_fonts[$frame_text_font]['character_width'];
                $frame_text_font = $frames_fonts[$frame_text_font]['font-family'];
                $frame_text_color = $_POST['frame_text_color'] ?? $_POST['background_color'];
                $frame_text_x = $frames[$frame]['frame_text_x'];
                $frame_text_y = $frames[$frame]['frame_text_y'];

                /* Frame text size multiplier from the user */
                $frame_text_size = in_array($_POST['frame_text_size'] ?? 0, range(-5, 5)) ? (int) $_POST['frame_text_size'] : 0;
                if($frame_text_size == 0) {
                    $frame_text_multiplier = 1;
                }
                if($frame_text_size < 0) {
                    $frame_text_multiplier = round($frame_text_size / 10 + 1, 2);
                }
                if($frame_text_size > 0) {
                    $frame_text_multiplier = round(($frame_text_size / 10) + 1, 2);
                }

                /* Text length to help make sure it fits the frame */
                $frame_text_lowercase = mb_strtolower($frame_text);
                $frame_text_length_lowercase = mb_strlen($frame_text_lowercase);
                $frame_text_length_uppercase = $frame_text_length_lowercase - similar_text($frame_text, $frame_text_lowercase);
                $frame_text_length_lowercase = $frame_text_length_lowercase - $frame_text_length_uppercase;

                /* Determine a minimum px text size */
                $frame_text_px_min = number_format($_POST['size'] / $frames[$frame]['frame_text_size_min_scale'], 2, '.', '');

                /* Default text px */
                $frame_text_px = number_format($_POST['size'] / $frames[$frame]['frame_text_size_scale'] * $frame_text_multiplier, 2, '.', '');

                /* Calculate text frame */
                /* Uppercase characters calculated at 30% bigger than lowercase ones */
                $frame_text_px_width_approximate = $frame_text_px * (($frame_text_font_character_width * $frame_text_length_lowercase) + ($frame_text_font_character_width * $frame_text_length_uppercase * 1.3));

                /* Determine the maximum text container width */
                $frame_text_container_width = $_POST['size'] / 1.065;

                /* Responsiveness */
                while($frame_text_px_width_approximate > $frame_text_container_width) {
                    $frame_text_px--;
                    $frame_text_px_width_approximate = $frame_text_px * (($frame_text_font_character_width * $frame_text_length_lowercase) + ($frame_text_font_character_width * $frame_text_length_uppercase * 1.3));

                    if($frame_text_px < $frame_text_px_min) {
                        break;
                    }
                }

                /* Make sure the minimum size is set if needed */
                $frame_text_px = $frame_text_px < $frame_text_px_min ? $frame_text_px_min : $frame_text_px;

                /* Append the text on to the frame */
                $text_svg_object = SVG::fromString('<svg><text x="' . $frame_text_x . '%" y="' . $frame_text_y . '%" dominant-baseline="middle" text-anchor="middle" style="font-weight:bold;font-size: ' . $frame_text_px . 'px;fill:' . $frame_text_color . ';font-family:' . $frame_text_font . ';">' . $frame_text . '</text></svg>');
                $frame_svg_doc->addChild($text_svg_object->getDocument());
            }

            /* Regenerate the final SVG */
            $svg = $frame_svg_object->toXMLString();
        }

        $image_data = 'data:image/svg+xml;base64,' . base64_encode($svg);

        Response::json('', 'success', ['data' => $image_data, 'embedded_data' => $data]);

    }

}
