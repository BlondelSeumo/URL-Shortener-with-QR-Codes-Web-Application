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
use Altum\Date;
use Altum\Models\QrCode;
use Altum\Response;
use Altum\Traits\Apiable;
use Altum\Uploads;
use Unirest\Request;

class ApiQrCodes extends Controller {
    use Apiable;

    public function index() {

        if(!settings()->codes->qr_codes_is_enabled) {
            redirect('not-found');
        }

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                $this->verify_request();

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

                break;

            case 'POST':

                /* Detect what method to use */
                if(isset($this->params[0])) {
                    $this->verify_request();

                    $this->patch();
                } else {
                    $this->post();
                }

                break;

            case 'DELETE':

                $this->verify_request();

                $this->delete();

                break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by($this->api_user->preferences->qr_codes_default_order_by, $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `qr_codes` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/qr-codes?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `qr_codes`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->qr_code_id,
                'user_id' => (int) $row->user_id,
                'type' => $row->type,
                'name' => $row->name,
                'qr_code' => \Altum\Uploads::get_full_url('qr_code') . $row->qr_code,
                'qr_code_logo' => \Altum\Uploads::get_full_url('qr_code_logo') . $row->qr_code_logo,
                'qr_code_background' => \Altum\Uploads::get_full_url('qr_code_background') . $row->qr_code_background,
                'qr_code_foreground' => \Altum\Uploads::get_full_url('qr_code_foreground') . $row->qr_code_foreground,
                'settings' => json_decode($row->settings),
                'embedded_data' => $row->embedded_data,
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime,
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $qr_code_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->api_user->user_id)->getOne('qr_codes');

        /* We haven't found the resource */
        if(!$qr_code) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $qr_code->qr_code_id,
            'user_id' => (int) $qr_code->user_id,
            'name' => $qr_code->name,
            'type' => $qr_code->type,
            'qr_code' => \Altum\Uploads::get_full_url('qr_code') . $qr_code->qr_code,
            'qr_code_logo' => \Altum\Uploads::get_full_url('qr_code_logo') . $qr_code->qr_code_logo,
            'qr_code_background' => \Altum\Uploads::get_full_url('qr_code_background') . $qr_code->qr_code_background,
            'qr_code_foreground' => \Altum\Uploads::get_full_url('qr_code_foreground') . $qr_code->qr_code_foreground,
            'settings' => json_decode($qr_code->settings),
            'embedded_data' => $qr_code->embedded_data,
            'last_datetime' => $qr_code->last_datetime,
            'datetime' => $qr_code->datetime,
        ];

        Response::jsonapi_success($data);

    }

    private function post() {

        if(isset($_POST['bulk_request'])) {
            $this->verify_request(false, false, false);
        } else {
            $this->verify_request();
        }

        /* Check for the plan limit */
        $total_rows = db()->where('user_id', $this->api_user->user_id)->getValue('qr_codes', 'count(`qr_code_id`)');

        if($this->api_user->plan_settings->qr_codes_limit != -1 && $total_rows >= $this->api_user->plan_settings->qr_codes_limit) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        $available_qr_codes = require APP_PATH . 'includes/enabled_qr_codes.php';
        $frames = require APP_PATH . 'includes/qr_codes_frames.php';
        $frames_fonts = require APP_PATH . 'includes/qr_codes_frames_text_fonts.php';

        /* Existing projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->api_user->user_id);

        $_POST['name'] = trim($_POST['name'] ?? null);
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
        $_POST['type'] = isset($_POST['type']) && array_key_exists($_POST['type'], $available_qr_codes) ? $_POST['type'] : 'text';

        /* Settings & qr code */
        $settings = [];
        $settings['inner_eye_style'] = $_POST['inner_eye_style'] = isset($_POST['inner_eye_style']) && in_array($_POST['inner_eye_style'], ['square', 'dot', 'rounded', 'diamond', 'flower', 'leaf',]) ? $_POST['inner_eye_style'] : 'square';
        $settings['outer_eye_style'] = $_POST['outer_eye_style'] = isset($_POST['outer_eye_style']) && in_array($_POST['outer_eye_style'], ['square', 'circle', 'rounded', 'flower', 'leaf',]) ? $_POST['outer_eye_style'] : 'square';
        $settings['style'] = $_POST['style'] = isset($_POST['style']) && in_array($_POST['style'], ['square', 'dot', 'round', 'diamond', 'heart']) ? $_POST['style'] : 'square';
        $settings['foreground_type'] = $_POST['foreground_type'] = isset($_POST['foreground_type']) && in_array($_POST['foreground_type'], ['color', 'gradient']) ? $_POST['foreground_type'] : 'color';
        switch($_POST['foreground_type']) {
            case 'color':
                $settings['foreground_color'] = $_POST['foreground_color'] = isset($_POST['foreground_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_color']) ? $_POST['foreground_color'] : '#000000';
                break;

            case 'gradient':
                $settings['foreground_gradient_style'] = $_POST['foreground_gradient_style'] = isset($_POST['foreground_gradient_style']) && in_array($_POST['foreground_gradient_style'], ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial']) ? $_POST['foreground_gradient_style'] : 'horizontal';
                $settings['foreground_gradient_one'] = $_POST['foreground_gradient_one'] = isset($_POST['foreground_gradient_one']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_gradient_one']) ? $_POST['foreground_gradient_one'] : '#000000';
                $settings['foreground_gradient_two'] = $_POST['foreground_gradient_two'] = isset($_POST['foreground_gradient_two']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_gradient_two']) ? $_POST['foreground_gradient_two'] : '#000000';
                break;
        }
        $settings['background_color'] = $_POST['background_color'] = isset($_POST['background_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? $_POST['background_color'] : '#ffffff';
        $settings['background_color_transparency'] = $_POST['background_color_transparency'] = isset($_POST['background_color_transparency']) && in_array($_POST['background_color_transparency'], range(0, 100)) ? (int) $_POST['background_color_transparency'] : 0;

        $settings['custom_eyes_color'] = $_POST['custom_eyes_color'] = (int) ($_POST['custom_eyes_color'] ?? 0);
        if($_POST['custom_eyes_color']) {
            $settings['eyes_inner_color'] = $_POST['eyes_inner_color'] = isset($_POST['eyes_inner_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['eyes_inner_color']) ? $_POST['eyes_inner_color'] : '#000000';
            $settings['eyes_outer_color'] = $_POST['eyes_outer_color'] = isset($_POST['eyes_outer_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['eyes_outer_color']) ? $_POST['eyes_outer_color'] : '#000000';
        }

        $_POST['qr_code_logo'] = !empty($_FILES['qr_code_logo']['name']) && !(int) isset($_POST['qr_code_logo_remove']);
        $settings['qr_code_logo_size'] = $_POST['qr_code_logo_size'] = isset($_POST['qr_code_logo_size']) && in_array($_POST['qr_code_logo_size'], range(5, 40)) ? (int) $_POST['qr_code_logo_size'] : 25;

        $_POST['qr_code_background'] = !empty($_FILES['qr_code_background']['name']) && !(int) isset($_POST['qr_code_background_remove']);
        $settings['qr_code_background_transparency'] = $_POST['qr_code_background_transparency'] = isset($_POST['qr_code_background_transparency']) && in_array($_POST['qr_code_background_transparency'], range(0, 99)) ? (int) $_POST['qr_code_background_transparency'] : 0;

        $_POST['qr_code_foreground'] = !empty($_FILES['qr_code_foreground']['name']) && !(int) isset($_POST['qr_code_foreground_remove']);
        $settings['qr_code_foreground_transparency'] = $_POST['qr_code_foreground_transparency'] = isset($_POST['qr_code_foreground_transparency']) && in_array($_POST['qr_code_foreground_transparency'], range(0, 99)) ? (int) $_POST['qr_code_foreground_transparency'] : 0;

        $settings['size'] = $_POST['size'] = isset($_POST['size']) && in_array($_POST['size'], range(50, 2000)) ? (int) $_POST['size'] : 500;
        $settings['margin'] = $_POST['margin'] = isset($_POST['margin']) && in_array($_POST['margin'], range(0, 25)) ? (int) $_POST['margin'] : 1;
        $settings['ecc'] = $_POST['ecc'] = isset($_POST['ecc']) && in_array($_POST['ecc'], ['L', 'M', 'Q', 'H']) ? $_POST['ecc'] : 'M';
        $settings['encoding'] = $_POST['encoding'] = isset($_POST['encoding']) && in_array($_POST['encoding'], [
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
        $_POST['is_bulk'] = (int) isset($_POST['is_bulk']);

        /* Frame */
        $settings['frame'] = $_POST['frame'] = isset($_POST['frame']) && array_key_exists($_POST['frame'], $frames) ? input_clean($_POST['frame']) : null;
        $settings['frame_text'] = $_POST['frame_text'] = input_clean($_POST['frame_text'] ?? null, 64);
        $settings['frame_text_font'] = $_POST['frame_text_font'] = isset($_POST['frame_text_font']) && array_key_exists($_POST['frame_text_font'], $frames_fonts) ? $_POST['frame_text_font'] : array_key_first($frames_fonts);
        $settings['frame_text_size'] = $_POST['frame_text_size'] = isset($_POST['frame_text_size']) && in_array($_POST['frame_text_size'], range(-5, 5)) ? (int) $_POST['frame_text_size'] : 0;

        $settings['frame_custom_colors'] = $_POST['frame_custom_colors'] = (int) isset($_POST['frame_custom_colors']);
        if($_POST['frame_custom_colors']) {
            $settings['frame_color'] = $_POST['frame_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['frame_color']) ? null : $_POST['frame_color'];
            $settings['frame_text_color'] = $_POST['frame_text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['frame_text_color']) ? null : $_POST['frame_text_color'];
        }

        /* Type dependant vars */
        switch($_POST['type']) {
            case 'text':
                $required_fields[] = 'text';
                $settings['text'] = $_POST['text'] = mb_substr(trim(input_clean($_POST['text'] ?? null)), 0, $available_qr_codes['text']['max_length']);
                break;

            case 'url':
                $required_fields[] = 'url';
                $settings['url'] = $_POST['url'] = mb_substr(trim(input_clean($_POST['url'] ?? null)), 0, $available_qr_codes['url']['max_length']);
                break;

            case 'phone':
                $required_fields[] = 'phone';
                $settings['phone'] = $_POST['phone'] = mb_substr(trim(input_clean($_POST['phone'] ?? null)), 0, $available_qr_codes['phone']['max_length']);
                break;

            case 'sms':
                $required_fields[] = 'sms';
                $settings['sms'] = $_POST['sms'] = mb_substr(trim(input_clean($_POST['sms'] ?? null)), 0, $available_qr_codes['sms']['max_length']);
                $settings['sms_body'] = $_POST['sms_body'] = mb_substr(trim(input_clean($_POST['sms_body'] ?? null)), 0, $available_qr_codes['sms']['body']['max_length']);
                break;

            case 'email':
                $required_fields[] = 'email';
                $settings['email'] = $_POST['email'] = mb_substr(trim(input_clean($_POST['email'] ?? null)), 0, $available_qr_codes['email']['max_length']);
                $settings['email_subject'] = $_POST['email_subject'] = mb_substr(trim(input_clean($_POST['email_subject'] ?? null)), 0, $available_qr_codes['email']['subject']['max_length']);
                $settings['email_body'] = $_POST['email_body'] = mb_substr(trim(input_clean($_POST['email_body'] ?? null)), 0, $available_qr_codes['email']['body']['max_length']);
                break;

            case 'whatsapp':
                $required_fields[] = 'whatsapp';
                $settings['whatsapp'] = $_POST['whatsapp'] = mb_substr(trim(input_clean($_POST['whatsapp'] ?? null)), 0, $available_qr_codes['whatsapp']['max_length']);
                $settings['whatsapp_body'] = $_POST['whatsapp_body'] = mb_substr(trim(input_clean($_POST['whatsapp_body'] ?? null)), 0, $available_qr_codes['whatsapp']['body']['max_length']);
                break;

            case 'facetime':
                $required_fields[] = 'facetime';
                $settings['facetime'] = $_POST['facetime'] = mb_substr(trim(input_clean($_POST['facetime'] ?? null)), 0, $available_qr_codes['facetime']['max_length']);
                break;

            case 'location':
                $required_fields[] = 'location_latitude';
                $required_fields[] = 'location_longitude';
                $settings['location_latitude'] = $_POST['location_latitude'] = (float) mb_substr(trim(input_clean($_POST['location_latitude'] ?? null)), 0, $available_qr_codes['location']['latitude']['max_length']);
                $settings['location_longitude'] = $_POST['location_longitude'] = (float) mb_substr(trim(input_clean($_POST['location_longitude'] ?? null)), 0, $available_qr_codes['location']['longitude']['max_length']);
                break;

            case 'wifi':
                $required_fields[] = 'wifi_ssid';
                $settings['wifi_ssid'] = $_POST['wifi_ssid'] = mb_substr(trim(input_clean($_POST['wifi_ssid'] ?? null)), 0, $available_qr_codes['wifi']['ssid']['max_length']);
                $settings['wifi_encryption'] = $_POST['wifi_encryption'] = isset($_POST['wifi_encryption']) && in_array($_POST['wifi_encryption'], ['nopass', 'WEP', 'WPA/WPA2']) ? $_POST['wifi_encryption'] : 'nopass';
                $settings['wifi_password'] = $_POST['wifi_password'] = mb_substr(trim(input_clean($_POST['wifi_password'] ?? null)), 0, $available_qr_codes['wifi']['password']['max_length']);
                $settings['wifi_is_hidden'] = $_POST['wifi_is_hidden'] = (int) ($_POST['wifi_is_hidden'] ?? 0);
                break;

            case 'event':
                $required_fields[] = 'event';
                $settings['event'] = $_POST['event'] = mb_substr(trim(input_clean($_POST['event'] ?? null)), 0, $available_qr_codes['event']['max_length']);
                $settings['event_location'] = $_POST['event_location'] = mb_substr(trim(input_clean($_POST['event_location'] ?? null)), 0, $available_qr_codes['event']['location']['max_length']);
                $settings['event_url'] = $_POST['event_url'] = mb_substr(trim(input_clean($_POST['event_url'] ?? null)), 0, $available_qr_codes['event']['url']['max_length']);
                $settings['event_note'] = $_POST['event_note'] = mb_substr(trim(input_clean($_POST['event_note'] ?? null)), 0, $available_qr_codes['event']['note']['max_length']);
                $settings['event_timezone'] = $_POST['event_timezone'] = in_array($_POST['event_timezone'], \DateTimeZone::listIdentifiers()) ? input_clean($_POST['event_timezone']) : Date::$default_timezone;
                $settings['event_start_datetime'] = $_POST['event_start_datetime'] = (new \DateTime($_POST['event_start_datetime']))->format('Y-m-d\TH:i:s');
                $settings['event_end_datetime'] = $_POST['event_end_datetime'] = (new \DateTime($_POST['event_end_datetime']))->format('Y-m-d\TH:i:s');
                $settings['event_first_alert_datetime'] = $_POST['event_first_alert_datetime'] = (new \DateTime($_POST['event_first_alert_datetime']))->format('Y-m-d\TH:i:s');
                $settings['event_second_alert_datetime'] = $_POST['event_second_alert_datetime'] = (new \DateTime($_POST['event_second_alert_datetime']))->format('Y-m-d\TH:i:s');
                break;

            case 'crypto':
                $required_fields[] = 'crypto_address';
                $settings['crypto_coin'] = $_POST['crypto_coin'] = isset($_POST['crypto_coin']) && array_key_exists($_POST['crypto_coin'], $available_qr_codes['crypto']['coins']) ? $_POST['crypto_coin'] : array_key_first($available_qr_codes['crypto']['coins']);
                $settings['crypto_address'] = $_POST['crypto_address'] = mb_substr(trim(input_clean($_POST['crypto_address'] ?? null)), 0, $available_qr_codes['crypto']['address']['max_length']);
                $settings['crypto_amount'] = $_POST['crypto_amount'] = isset($_POST['crypto_amount']) ? (float) $_POST['crypto_amount'] : null;
                break;

            case 'vcard':
                $settings['vcard_first_name'] = $_POST['vcard_first_name'] = mb_substr(trim(input_clean($_POST['vcard_first_name'] ?? null)), 0, $available_qr_codes['vcard']['first_name']['max_length']);
                $settings['vcard_last_name'] = $_POST['vcard_last_name'] = mb_substr(trim(input_clean($_POST['vcard_last_name'] ?? null)), 0, $available_qr_codes['vcard']['last_name']['max_length']);
                $settings['vcard_email'] = $_POST['vcard_email'] = mb_substr(trim(input_clean($_POST['vcard_email'] ?? null)), 0, $available_qr_codes['vcard']['email']['max_length']);
                $settings['vcard_url'] = $_POST['vcard_url'] = mb_substr(trim(input_clean($_POST['vcard_url'] ?? null)), 0, $available_qr_codes['vcard']['url']['max_length']);
                $settings['vcard_company'] = $_POST['vcard_company'] = mb_substr(trim(input_clean($_POST['vcard_company'] ?? null)), 0, $available_qr_codes['vcard']['company']['max_length']);
                $settings['vcard_job_title'] = $_POST['vcard_job_title'] = mb_substr(trim(input_clean($_POST['vcard_job_title'] ?? null)), 0, $available_qr_codes['vcard']['job_title']['max_length']);
                $settings['vcard_birthday'] = $_POST['vcard_birthday'] = mb_substr(trim(input_clean($_POST['vcard_birthday'] ?? null)), 0, $available_qr_codes['vcard']['birthday']['max_length']);
                $settings['vcard_street'] = $_POST['vcard_street'] = mb_substr(trim(input_clean($_POST['vcard_street'] ?? null)), 0, $available_qr_codes['vcard']['street']['max_length']);
                $settings['vcard_city'] = $_POST['vcard_city'] = mb_substr(trim(input_clean($_POST['vcard_city'] ?? null)), 0, $available_qr_codes['vcard']['city']['max_length']);
                $settings['vcard_zip'] = $_POST['vcard_zip'] = mb_substr(trim(input_clean($_POST['vcard_zip'] ?? null)), 0, $available_qr_codes['vcard']['zip']['max_length']);
                $settings['vcard_region'] = $_POST['vcard_region'] = mb_substr(trim(input_clean($_POST['vcard_region'] ?? null)), 0, $available_qr_codes['vcard']['region']['max_length']);
                $settings['vcard_country'] = $_POST['vcard_country'] = mb_substr(trim(input_clean($_POST['vcard_country'] ?? null)), 0, $available_qr_codes['vcard']['country']['max_length']);
                $settings['vcard_note'] = $_POST['vcard_note'] = mb_substr(trim(input_clean($_POST['vcard_note'] ?? null)), 0, $available_qr_codes['vcard']['note']['max_length']);

                /* Phone numbers */
                if(!isset($_POST['vcard_phone_number_label'])) {
                    $_POST['vcard_phone_number_label'] = [];
                    $_POST['vcard_phone_number_value'] = [];
                }
                $vcard_phone_numbers = [];
                foreach($_POST['vcard_phone_number_label'] as $key => $value) {
                    if($key >= 20) continue;

                    $vcard_phone_numbers[] = [
                        'label' => mb_substr(input_clean($value), 0, $available_qr_codes['vcard']['phone_number_value']['max_length']),
                        'value' => mb_substr(input_clean($_POST['vcard_phone_number_value'][$key]), 0, $available_qr_codes['vcard']['phone_number_value']['max_length'])
                    ];
                }
                $settings['vcard_phone_numbers'] = $vcard_phone_numbers;

                /* Socials */
                if(!isset($_POST['vcard_social_label'])) {
                    $_POST['vcard_social_label'] = [];
                    $_POST['vcard_social_value'] = [];
                }

                $vcard_socials = [];
                foreach($_POST['vcard_social_label'] as $key => $value) {
                    if(empty(trim($value))) continue;
                    if($key >= 20) continue;

                    $vcard_socials[] = [
                        'label' => mb_substr(input_clean($value), 0, $available_qr_codes['vcard']['social_value']['max_length']),
                        'value' => mb_substr(input_clean($_POST['vcard_social_value'][$key]), 0, $available_qr_codes['vcard']['social_value']['max_length'])
                    ];
                }
                $settings['vcard_socials'] = $vcard_socials;
                break;

            case 'paypal':
                $required_fields[] = 'paypal_email';
                $required_fields[] = 'paypal_title';
                $required_fields[] = 'paypal_currency';
                $required_fields[] = 'paypal_price';
                $settings['paypal_type'] = $_POST['paypal_type'] = isset($_POST['paypal_type']) && array_key_exists($_POST['paypal_type'] ?? 'buy_now', $available_qr_codes['paypal']['type']) ? $_POST['paypal_type'] : array_key_first($available_qr_codes['paypal']['type']);
                $settings['paypal_email'] = $_POST['paypal_email'] = mb_substr(trim(input_clean($_POST['paypal_email'] ?? null)), 0, $available_qr_codes['paypal']['email']['max_length']);
                $settings['paypal_title'] = $_POST['paypal_title'] = mb_substr(trim(input_clean($_POST['paypal_title'] ?? null)), 0, $available_qr_codes['paypal']['title']['max_length']);
                $settings['paypal_currency'] = $_POST['paypal_currency'] = mb_substr(trim(input_clean($_POST['paypal_currency'] ?? null)), 0, $available_qr_codes['paypal']['currency']['max_length']);
                $settings['paypal_price'] = $_POST['paypal_price'] = (float) $_POST['paypal_price'] ?? 0;
                $settings['paypal_thank_you_url'] = $_POST['paypal_thank_you_url'] = mb_substr(trim(input_clean($_POST['paypal_thank_you_url'] ?? null)), 0, $available_qr_codes['paypal']['thank_you_url']['max_length']);
                $settings['paypal_cancel_url'] = $_POST['paypal_cancel_url'] = mb_substr(trim(input_clean($_POST['paypal_cancel_url'] ?? null)), 0, $available_qr_codes['paypal']['cancel_url']['max_length']);
                break;

            case 'upi':
                $required_fields[] = 'upi_payee_id';
                $required_fields[] = 'upi_payee_name';
                $settings['upi_payee_id'] = $_POST['upi_payee_id'] = mb_substr(input_clean($_POST['upi_payee_id'] ?? null), 0, $available_qr_codes['upi']['payee_id']['max_length']);
                $settings['upi_payee_name'] = $_POST['upi_payee_name'] = mb_substr(input_clean($_POST['upi_payee_name'] ?? null), 0, $available_qr_codes['upi']['payee_name']['max_length']);
                $settings['upi_currency'] = $_POST['upi_currency'] = in_array($_POST['upi_currency'], ['INR']) ? $_POST['upi_currency'] : 'INR';
                $settings['upi_amount'] = isset($_POST['upi_amount']) ? (float) $_POST['upi_amount'] : null;
                $settings['upi_transaction_id'] = $_POST['upi_transaction_id'] = mb_substr(input_clean($_POST['upi_transaction_id'] ?? null), 0, $available_qr_codes['upi']['transaction_id']['max_length']);
                $settings['upi_transaction_note'] = $_POST['upi_transaction_note'] = mb_substr(input_clean($_POST['upi_transaction_note'] ?? null), 0, $available_qr_codes['upi']['transaction_note']['max_length']);
                $settings['upi_transaction_reference'] = $_POST['upi_transaction_reference'] = mb_substr(input_clean($_POST['upi_transaction_reference'] ?? null), 0, $available_qr_codes['upi']['transaction_reference']['max_length']);
                $settings['upi_thank_you_url'] = $_POST['upi_thank_you_url'] = mb_substr(input_clean($_POST['upi_thank_you_url'] ?? null), 0, $available_qr_codes['upi']['thank_you_url']['max_length']);
                break;

            case 'epc':
                $required_fields[] = 'epc_iban';
                $required_fields[] = 'epc_payee_name';
                $settings['epc_iban'] = $_POST['epc_iban'] = mb_substr(input_clean($_POST['epc_iban']), 0, $available_qr_codes['epc']['iban']['max_length']);
                $settings['epc_payee_name'] = $_POST['epc_payee_name'] = mb_substr(input_clean($_POST['epc_payee_name']), 0, $available_qr_codes['epc']['payee_name']['max_length']);
                $settings['epc_currency'] = $_POST['epc_currency'] = in_array($_POST['epc_currency'], ['EUR']) ? $_POST['epc_currency'] : 'EUR';
                $settings['epc_amount'] = isset($_POST['epc_amount']) ? (float) $_POST['epc_amount'] : null;
                $settings['epc_bic'] = $_POST['epc_bic'] = mb_substr(input_clean($_POST['epc_bic']), 0, $available_qr_codes['epc']['bic']['max_length']);
                $settings['epc_remittance_reference'] = $_POST['epc_remittance_reference'] = mb_substr(input_clean($_POST['epc_remittance_reference']), 0, $available_qr_codes['epc']['remittance_reference']['max_length']);
                $settings['epc_remittance_text'] = $_POST['epc_remittance_text'] = mb_substr(input_clean($_POST['epc_remittance_text']), 0, $available_qr_codes['epc']['remittance_text']['max_length']);
                $settings['information'] = $_POST['information'] = mb_substr(input_clean($_POST['information']), 0, $available_qr_codes['epc']['information']['max_length']);
                break;

            case 'pix':
                $required_fields[] = 'pix_payee_key';
                $required_fields[] = 'pix_payee_name';
                $required_fields[] = 'pix_city';
                $required_fields[] = 'pix_transaction_id';
                $settings['pix_payee_key'] = $_POST['pix_payee_key'] = mb_substr(input_clean($_POST['pix_payee_key']), 0, $available_qr_codes['pix']['payee_key']['max_length']);
                $settings['pix_payee_name'] = $_POST['pix_payee_name'] = mb_substr(input_clean($_POST['pix_payee_name']), 0, $available_qr_codes['pix']['payee_name']['max_length']);
                $settings['pix_currency'] = $_POST['pix_currency'] = in_array($_POST['pix_currency'], ['BRL']) ? $_POST['pix_currency'] : 'BRL';
                $settings['pix_amount'] = isset($_POST['pix_amount']) ? (float) $_POST['pix_amount'] : null;
                $settings['pix_city'] = $_POST['pix_city'] = mb_substr(input_clean($_POST['pix_city']), 0, $available_qr_codes['pix']['city']['max_length']);
                $settings['pix_transaction_id'] = $_POST['pix_transaction_id'] = mb_substr(input_clean($_POST['pix_transaction_id']), 0, $available_qr_codes['pix']['transaction_id']['max_length']);
                $settings['pix_description'] = $_POST['pix_description'] = mb_substr(input_clean($_POST['pix_description']), 0, $available_qr_codes['pix']['description']['max_length']);
                break;
        }

        /* Check for any errors */
        $required_fields = ['type', 'name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        /* Bulk processing */
        if($_POST['is_bulk'] && $_POST['type'] == 'text') {
            $data_rows = preg_split('/\r\n|\r|\n/', $_POST['text']);

            /* Foreach row, generate one QR code */
            $i = 1;
            $data = [
                'ids' => []
            ];

            foreach($data_rows as $data_row) {
                /* Skip empty lines */
                if(empty(trim($data_row))) {
                    continue;
                }

                /* Set the QR data */
                $settings['text'] = $data_row;

                /* Generate the QR Code */
                $request_files = [];

                if($_POST['qr_code_logo']) $request_files['qr_code_logo'] = $_FILES['qr_code_logo']['tmp_name'];
                if($_POST['qr_code_background']) $request_files['qr_code_background'] = $_FILES['qr_code_background']['tmp_name'];
                if($_POST['qr_code_foreground']) $request_files['qr_code_foreground'] = $_FILES['qr_code_foreground']['tmp_name'];

                try {
                    $response = Request::post(
                        url('api/qr-codes'),
                        ['Authorization' => 'Bearer ' . $this->api_user->api_key],
                        Request\Body::multipart(
                            array_merge([
                                'api_key' => $this->api_user->api_key,
                                'type' => $_POST['type'],
                                'project_id' => $_POST['project_id'],
                                'name' => $_POST['name'] . ' - #' . $i,
                            ], $settings),
                            $request_files
                        )
                    );
                } catch (\Exception $exception) {
                    $this->response_error($exception->getMessage(), 401);
                    break;
                }

                if(isset($response->body->errors)) {
                    $this->response_error($response->body->errors[0]->title, 401);
                    break;
                }

                if($response->code == 201) {
                    $data['ids'][] = $response->body->data->id;
                }

                /* Do not allow more than 10 at once */
                if($i >= $this->user->plan_settings->qr_codes_bulk_limit) {
                    break;
                }
                $i++;
            }
        }

        else {

            /* Generate the QR Code */
            $request_data = array_merge([
                'api_key' => $this->api_user->api_key,
                'type' => $_POST['type'],
            ], $settings);

            $request_data = json_encode($request_data);

            $request_files = [];

            if($_POST['qr_code_logo']) $request_files['qr_code_logo'] = $_FILES['qr_code_logo']['tmp_name'];
            if($_POST['qr_code_background']) $request_files['qr_code_background'] = $_FILES['qr_code_background']['tmp_name'];
            if($_POST['qr_code_foreground']) $request_files['qr_code_foreground'] = $_FILES['qr_code_foreground']['tmp_name'];

            try {
                $response = Request::post(url('qr-code-generator'), [], Request\Body::multipart(['json' => $request_data], $request_files));

            } catch (\Exception $exception) {
                $this->response_error($exception->getMessage(), 401);
            }

            if($response->body->status == 'error') {
                $this->response_error($response->body->message, 401);
            }

            $qr_code_logo = null;
            if($_POST['qr_code_logo']) {
                $file_name = $_FILES['qr_code_logo']['name'];
                $file_extension = explode('.', $file_name);
                $file_extension = mb_strtolower(end($file_extension));
                $file_temp = $_FILES['qr_code_logo']['tmp_name'];

                if($_FILES['qr_code_logo']['error'] == UPLOAD_ERR_INI_SIZE) {
                    $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit), 401);
                }

                if($_FILES['qr_code_logo']['error'] && $_FILES['qr_code_logo']['error'] != UPLOAD_ERR_INI_SIZE) {
                    $this->response_error(l('global.error_message.file_upload'), 401);
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_logo'))) {
                    $this->response_error(l('global.error_message.invalid_file_type'), 401);
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(Uploads::get_full_path('qr_code_logo'))) {
                        $this->response_error(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_logo')), 401);
                    }
                }

                if($_FILES['qr_code_logo']['size'] > settings()->codes->logo_size_limit * 1000000) {
                    $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit), 401);
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_logo') . $image_new_name,
                                'ContentType' => mime_content_type($file_temp),
                                'SourceFile' => $file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            $this->response_error($exception->getMessage(), 401);
                        }
                    } /* Local uploading */
                    else {
                        /* Upload the original */
                        move_uploaded_file($file_temp, Uploads::get_full_path('qr_code_logo') . $image_new_name);
                    }

                    $qr_code_logo = $image_new_name;
                }
            }

            $qr_code_background = null;
            if($_POST['qr_code_background']) {
                $file_name = $_FILES['qr_code_background']['name'];
                $file_extension = explode('.', $file_name);
                $file_extension = mb_strtolower(end($file_extension));
                $file_temp = $_FILES['qr_code_background']['tmp_name'];

                if($_FILES['qr_code_background']['error'] == UPLOAD_ERR_INI_SIZE) {
                    $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
                }

                if($_FILES['qr_code_background']['error'] && $_FILES['qr_code_background']['error'] != UPLOAD_ERR_INI_SIZE) {
                    $this->response_error(l('global.error_message.file_upload'), 401);
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_background'))) {
                    $this->response_error(l('global.error_message.invalid_file_type'), 401);
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(Uploads::get_full_path('qr_code_background'))) {
                        $this->response_error(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_background')), 401);
                    }
                }

                if($_FILES['qr_code_background']['size'] > settings()->codes->background_size_limit * 1000000) {
                    $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_background') . $image_new_name,
                                'ContentType' => mime_content_type($file_temp),
                                'SourceFile' => $file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            $this->response_error($exception->getMessage(), 401);
                        }
                    } /* Local uploading */
                    else {
                        /* Upload the original */
                        move_uploaded_file($file_temp, Uploads::get_full_path('qr_code_background') . $image_new_name);
                    }

                    $qr_code_background = $image_new_name;
                }
            }

            $qr_code_foreground = null;
            if($_POST['qr_code_foreground']) {
                $file_name = $_FILES['qr_code_foreground']['name'];
                $file_extension = explode('.', $file_name);
                $file_extension = mb_strtolower(end($file_extension));
                $file_temp = $_FILES['qr_code_foreground']['tmp_name'];

                if($_FILES['qr_code_foreground']['error'] == UPLOAD_ERR_INI_SIZE) {
                    $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
                }

                if($_FILES['qr_code_foreground']['error'] && $_FILES['qr_code_foreground']['error'] != UPLOAD_ERR_INI_SIZE) {
                    $this->response_error(l('global.error_message.file_upload'), 401);
                }

                if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_foreground'))) {
                    $this->response_error(l('global.error_message.invalid_file_type'), 401);
                }

                if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                    if(!is_writable(Uploads::get_full_path('qr_code_foreground'))) {
                        $this->response_error(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_foreground')), 401);
                    }
                }

                if($_FILES['qr_code_foreground']['size'] > settings()->codes->background_size_limit * 1000000) {
                    $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
                }

                if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                    /* Generate new name for image */
                    $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                    /* Offload uploading */
                    if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                        try {
                            $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                            /* Upload image */
                            $result = $s3->putObject([
                                'Bucket' => settings()->offload->storage_name,
                                'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_foreground') . $image_new_name,
                                'ContentType' => mime_content_type($file_temp),
                                'SourceFile' => $file_temp,
                                'ACL' => 'public-read'
                            ]);
                        } catch (\Exception $exception) {
                            $this->response_error($exception->getMessage(), 401);
                        }
                    } /* Local uploading */
                    else {
                        /* Upload the original */
                        move_uploaded_file($file_temp, Uploads::get_full_path('qr_code_foreground') . $image_new_name);
                    }

                    $qr_code_foreground = $image_new_name;
                }
            }

            /* QR Code image */
            $_POST['qr_code'] = base64_decode(mb_substr($response->body->details->data, mb_strlen('data:image/svg+xml;base64,')));

            /* Embedded data */
            $_POST['embedded_data'] = input_clean($response->body->details->embedded_data, 10000);

            /* Generate new name for image */
            $image_new_name = md5(time() . rand()) . '.svg';

            /* Offload uploading */
            if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                try {
                    $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                    /* Upload image */
                    $result = $s3->putObject([
                        'Bucket' => settings()->offload->storage_name,
                        'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code') . $image_new_name,
                        'ContentType' => 'image/svg+xml',
                        'Body' => $_POST['qr_code'],
                        'ACL' => 'public-read'
                    ]);
                } catch (\Exception $exception) {
                    $this->response_error($exception->getMessage(), 401);
                }
            } /* Local uploading */
            else {
                /* Upload the original */
                file_put_contents(Uploads::get_full_path('qr_code') . $image_new_name, $_POST['qr_code']);
            }
            $qr_code = $image_new_name;

            $settings = json_encode($settings);

            /* Database query */
            $qr_code_id = db()->insert('qr_codes', [
                'user_id' => $this->api_user->user_id,
                'project_id' => $_POST['project_id'],
                'name' => $_POST['name'],
                'type' => $_POST['type'],
                'settings' => $settings,
                'embedded_data' => $_POST['embedded_data'],
                'qr_code' => $qr_code,
                'qr_code_logo' => $qr_code_logo,
                'qr_code_background' => $qr_code_background,
                'qr_code_foreground' => $qr_code_foreground,
                'datetime' => get_date(),
            ]);

            /* Clear the cache */
            cache()->deleteItem('qr_codes_total?user_id=' . $this->api_user->user_id);

            /* Prepare the data */
            $data = [
                'id' => $qr_code_id
            ];
        }

        Response::jsonapi_success($data, null, 201);

    }

    private function patch() {

        $qr_code_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->api_user->user_id)->getOne('qr_codes');

        /* We haven't found the resource */
        if(!$qr_code) {
            $this->return_404();
        }
        $qr_code->settings = json_decode($qr_code->settings ?? '');

        $available_qr_codes = require APP_PATH . 'includes/enabled_qr_codes.php';
        $frames = require APP_PATH . 'includes/qr_codes_frames.php';
        $frames_fonts = require APP_PATH . 'includes/qr_codes_frames_text_fonts.php';

        /* Existing projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->api_user->user_id);

        $_POST['name'] = trim($_POST['name'] ?? $qr_code->name);
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : $qr_code->project_id;
        $_POST['type'] = isset($_POST['type']) && array_key_exists($_POST['type'], $available_qr_codes) ? $_POST['type'] : $qr_code->type;

        /* Settings & qr code */
        $settings = [];
        $settings['inner_eye_style'] = $_POST['inner_eye_style'] = isset($_POST['inner_eye_style']) && in_array($_POST['inner_eye_style'], ['square', 'dot', 'rounded', 'diamond', 'flower', 'leaf',]) ? $_POST['inner_eye_style'] : $qr_code->settings->inner_eye_style;
        $settings['outer_eye_style'] = $_POST['outer_eye_style'] = isset($_POST['outer_eye_style']) && in_array($_POST['outer_eye_style'], ['square', 'circle', 'rounded', 'flower', 'leaf',]) ? $_POST['outer_eye_style'] : $qr_code->settings->outer_eye_style;
        $settings['style'] = $_POST['style'] = isset($_POST['style']) && in_array($_POST['style'], ['square', 'dot', 'round', 'diamond', 'heart']) ? $_POST['style'] : $qr_code->settings->style;
        $settings['foreground_type'] = $_POST['foreground_type'] = isset($_POST['foreground_type']) && in_array($_POST['foreground_type'], ['color', 'gradient']) ? $_POST['foreground_type'] : $qr_code->settings->foreground_type;
        switch($_POST['foreground_type']) {
            case 'color':
                $settings['foreground_color'] = $_POST['foreground_color'] = isset($_POST['foreground_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_color']) ? $_POST['foreground_color'] : $qr_code->settings->foreground_color;
                break;

            case 'gradient':
                $settings['foreground_gradient_style'] = $_POST['foreground_gradient_style'] = isset($_POST['foreground_gradient_style']) && in_array($_POST['foreground_gradient_style'], ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial']) ? $_POST['foreground_gradient_style'] : $qr_code->settings->foreground_gradient_style;
                $settings['foreground_gradient_one'] = $_POST['foreground_gradient_one'] = isset($_POST['foreground_gradient_one']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_gradient_one']) ? $_POST['foreground_gradient_one'] : $qr_code->settings->foreground_gradient_one;
                $settings['foreground_gradient_two'] = $_POST['foreground_gradient_two'] = isset($_POST['foreground_gradient_two']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['foreground_gradient_two']) ? $_POST['foreground_gradient_two'] : $qr_code->settings->foreground_gradient_two;
                break;
        }
        $settings['background_color'] = $_POST['background_color'] = isset($_POST['background_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background_color']) ? $_POST['background_color'] : $qr_code->settings->background_color;
        $settings['background_color_transparency'] = $_POST['background_color_transparency'] = isset($_POST['background_color_transparency']) && in_array($_POST['background_color_transparency'], range(0, 100)) ? (int) $_POST['background_color_transparency'] : 0;
        $settings['custom_eyes_color'] = $_POST['custom_eyes_color'] = (int) ($_POST['custom_eyes_color'] ?? $qr_code->settings->custom_eyes_color);
        if($_POST['custom_eyes_color']) {
            $settings['eyes_inner_color'] = $_POST['eyes_inner_color'] = isset($_POST['eyes_inner_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['eyes_inner_color']) ? $_POST['eyes_inner_color'] : $qr_code->settings->eyes_inner_color;
            $settings['eyes_outer_color'] = $_POST['eyes_outer_color'] = isset($_POST['eyes_outer_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['eyes_outer_color']) ? $_POST['eyes_outer_color'] : $qr_code->settings->eyes_outer_color;
        }

        $_POST['qr_code_logo'] = !empty($_FILES['qr_code_logo']['name']) && !(int) isset($_POST['qr_code_logo_remove']);
        $settings['qr_code_logo_size'] = $_POST['qr_code_logo_size'] = isset($_POST['qr_code_logo_size']) && in_array($_POST['qr_code_logo_size'], range(5, 35)) ? (int) $_POST['qr_code_logo_size'] : $qr_code->settings->qr_code_logo_size;

        $_POST['qr_code_background'] = !empty($_FILES['qr_code_background']['name']) && !(int) isset($_POST['qr_code_background_remove']);
        $settings['qr_code_background_transparency'] = $_POST['qr_code_background_transparency'] = isset($_POST['qr_code_background_transparency']) && in_array($_POST['qr_code_background_transparency'], range(0, 99)) ? (int) $_POST['qr_code_background_transparency'] : $qr_code->settings->qr_code_background_transparency;

        $_POST['qr_code_foreground'] = !empty($_FILES['qr_code_foreground']['name']) && !(int) isset($_POST['qr_code_foreground_remove']);
        $settings['qr_code_foreground_transparency'] = $_POST['qr_code_foreground_transparency'] = isset($_POST['qr_code_foreground_transparency']) && in_array($_POST['qr_code_foreground_transparency'], range(0, 99)) ? (int) $_POST['qr_code_foreground_transparency'] : $qr_code->settings->qr_code_foreground_transparency;

        $settings['size'] = $_POST['size'] = isset($_POST['size']) && in_array($_POST['size'], range(50, 2000)) ? (int) $_POST['size'] : $qr_code->settings->size;
        $settings['margin'] = $_POST['margin'] = isset($_POST['margin']) && in_array($_POST['margin'], range(0, 25)) ? (int) $_POST['margin'] : $qr_code->settings->margin;
        $settings['ecc'] = $_POST['ecc'] = isset($_POST['ecc']) && in_array($_POST['ecc'], ['L', 'M', 'Q', 'H']) ? $_POST['ecc'] : $qr_code->settings->ecc;
        $settings['encoding'] = $_POST['encoding'] = isset($_POST['encoding']) && in_array($_POST['encoding'], [
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
        ]) ? $_POST['encoding'] : $qr_code->settings->encoding;

        /* Frame */
        $settings['frame'] = $_POST['frame'] = isset($_POST['frame']) && array_key_exists($_POST['frame'], $frames) ? input_clean($_POST['frame']) : $qr_code->settings->frame;
        $settings['frame_text'] = $_POST['frame_text'] = input_clean($_POST['frame_text'] ?? $qr_code->settings->frame_text, 64);
        $settings['frame_text_font'] = $_POST['frame_text_font'] = isset($_POST['frame_text_font']) && array_key_exists($_POST['frame_text_font'], $frames_fonts) ? $_POST['frame_text_font'] : $qr_code->settings->frame_text_font;
        $settings['frame_text_size'] = $_POST['frame_text_size'] = isset($_POST['frame_text_size']) && in_array($_POST['frame_text_size'], range(-5, 5)) ? (int) $_POST['frame_text_size'] : $qr_code->settings->frame_text_size;

        $settings['frame_custom_colors'] = $_POST['frame_custom_colors'] = (int) ($_POST['frame_custom_colors'] ?? $qr_code->settings->frame_custom_colors);
        if($_POST['frame_custom_colors']) {
            $settings['frame_color'] = $_POST['frame_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['frame_color']) ? $qr_code->settings->frame_color : $_POST['frame_color'];
            $settings['frame_text_color'] = $_POST['frame_text_color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['frame_text_color']) ? $qr_code->settings->frame_text_color : $_POST['frame_text_color'];
        }

        /* Type dependant vars */
        switch($_POST['type']) {
            case 'text':
                $settings['text'] = $_POST['text'] = mb_substr(trim(input_clean($_POST['text'] ?? $qr_code->settings->text)), 0, $available_qr_codes['text']['max_length']);
                break;

            case 'url':
                $settings['url'] = $_POST['url'] = mb_substr(trim(input_clean($_POST['url'] ?? $qr_code->settings->url)), 0, $available_qr_codes['url']['max_length']);
                break;

            case 'phone':
                $settings['phone'] = $_POST['phone'] = mb_substr(trim(input_clean($_POST['phone'] ?? $qr_code->settings->phone)), 0, $available_qr_codes['phone']['max_length']);
                break;

            case 'sms':
                $settings['sms'] = $_POST['sms'] = mb_substr(trim(input_clean($_POST['sms'] ?? $qr_code->settings->sms)), 0, $available_qr_codes['sms']['max_length']);
                $settings['sms_body'] = $_POST['sms_body'] = mb_substr(trim(input_clean($_POST['sms_body'] ?? $qr_code->settings->sms_body)), 0, $available_qr_codes['sms']['body']['max_length']);
                break;

            case 'email':
                $settings['email'] = $_POST['email'] = mb_substr(trim(input_clean($_POST['email'] ?? $qr_code->settings->email)), 0, $available_qr_codes['email']['max_length']);
                $settings['email_subject'] = $_POST['email_subject'] = mb_substr(trim(input_clean($_POST['email_subject'] ?? $qr_code->settings->email_subject)), 0, $available_qr_codes['email']['subject']['max_length']);
                $settings['email_body'] = $_POST['email_body'] = mb_substr(trim(input_clean($_POST['email_body'] ?? $qr_code->settings->email_body)), 0, $available_qr_codes['email']['body']['max_length']);
                break;

            case 'whatsapp':
                $settings['whatsapp'] = $_POST['whatsapp'] = mb_substr(trim(input_clean($_POST['whatsapp'] ?? $qr_code->settings->whatsapp)), 0, $available_qr_codes['whatsapp']['max_length']);
                $settings['whatsapp_body'] = $_POST['whatsapp_body'] = mb_substr(trim(input_clean($_POST['whatsapp_body'] ?? $qr_code->settings->whatsapp_body)), 0, $available_qr_codes['whatsapp']['body']['max_length']);
                break;

            case 'facetime':
                $settings['facetime'] = $_POST['facetime'] = mb_substr(trim(input_clean($_POST['facetime'] ?? $qr_code->settings->facetime)), 0, $available_qr_codes['facetime']['max_length']);
                break;

            case 'location':
                $settings['location_latitude'] = $_POST['location_latitude'] = (float) mb_substr(trim(input_clean($_POST['location_latitude'] ?? $qr_code->settings->location_latitude)), 0, $available_qr_codes['location']['latitude']['max_length']);
                $settings['location_longitude'] = $_POST['location_longitude'] = (float) mb_substr(trim(input_clean($_POST['location_longitude'] ?? $qr_code->settings->location_longitude)), 0, $available_qr_codes['location']['longitude']['max_length']);
                break;

            case 'wifi':
                $settings['wifi_ssid'] = $_POST['wifi_ssid'] = mb_substr(trim(input_clean($_POST['wifi_ssid'] ?? $qr_code->settings->wifi_ssid)), 0, $available_qr_codes['wifi']['ssid']['max_length']);
                $settings['wifi_encryption'] = $_POST['wifi_encryption'] = isset($_POST['wifi_encryption']) && in_array($_POST['wifi_encryption'], ['nopass', 'WEP', 'WPA/WPA2']) ? $_POST['wifi_encryption'] : $qr_code->settings->wifi_encryption;
                $settings['wifi_password'] = $_POST['wifi_password'] = mb_substr(trim(input_clean($_POST['wifi_password'] ?? $qr_code->settings->wifi_password)), 0, $available_qr_codes['wifi']['password']['max_length']);
                $settings['wifi_is_hidden'] = $_POST['wifi_is_hidden'] = (int) $_POST['wifi_is_hidden'] ?? $qr_code->settings->wifi_is_hidden;
                break;

            case 'event':
                $settings['event'] = $_POST['event'] = mb_substr(trim(input_clean($_POST['event'] ?? $qr_code->settings->event)), 0, $available_qr_codes['event']['max_length']);
                $settings['event_location'] = $_POST['event_location'] = mb_substr(trim(input_clean($_POST['event_location'] ?? $qr_code->settings->event_location)), 0, $available_qr_codes['event']['location']['max_length']);
                $settings['event_url'] = $_POST['event_url'] = mb_substr(trim(input_clean($_POST['event_url'] ?? $qr_code->settings->event_url)), 0, $available_qr_codes['event']['url']['max_length']);
                $settings['event_note'] = $_POST['event_note'] = mb_substr(trim(input_clean($_POST['event_note'] ?? $qr_code->settings->event_note)), 0, $available_qr_codes['event']['note']['max_length']);
                $settings['event_timezone'] = $_POST['event_timezone'] = in_array($_POST['event_timezone'], \DateTimeZone::listIdentifiers()) ? input_clean($_POST['event_timezone']) : $qr_code->settings->event_timezone;
                $settings['event_start_datetime'] = $_POST['event_start_datetime'] = (new \DateTime($_POST['event_start_datetime'] ?? $qr_code->settings->event_start_datetime))->format('Y-m-d\TH:i:s');
                $settings['event_end_datetime'] = $_POST['event_end_datetime'] = (new \DateTime($_POST['event_end_datetime'] ?? $qr_code->settings->event_end_datetime))->format('Y-m-d\TH:i:s');
                $settings['event_first_alert_datetime'] = $_POST['event_first_alert_datetime'] = (new \DateTime($_POST['event_first_alert_datetime'] ?? $qr_code->settings->event_first_alert_datetime))->format('Y-m-d\TH:i:s');
                $settings['event_second_alert_datetime'] = $_POST['event_second_alert_datetime'] = (new \DateTime($_POST['event_second_alert_datetime'] ?? $qr_code->settings->event_second_alert_datetime))->format('Y-m-d\TH:i:s');
                break;

            case 'crypto':
                $settings['crypto_coin'] = $_POST['crypto_coin'] = isset($_POST['crypto_coin']) && array_key_exists($_POST['crypto_coin'], $available_qr_codes['crypto']['coins']) ? $_POST['crypto_coin'] : $qr_code->settings->crypto_coin;
                $settings['crypto_address'] = $_POST['crypto_address'] = mb_substr(trim(input_clean($_POST['crypto_address'] ?? $qr_code->settings->crypto_address)), 0, $available_qr_codes['crypto']['address']['max_length']);
                $settings['crypto_amount'] = $_POST['crypto_amount'] = isset($_POST['crypto_amount']) ? (float) $_POST['crypto_amount'] : $qr_code->settings->crypto_address;
                break;

            case 'vcard':
                $settings['vcard_first_name'] = $_POST['vcard_first_name'] = mb_substr(trim(input_clean($_POST['vcard_first_name'] ?? $qr_code->settings->vcard_first_name)), 0, $available_qr_codes['vcard']['first_name']['max_length']);
                $settings['vcard_last_name'] = $_POST['vcard_last_name'] = mb_substr(trim(input_clean($_POST['vcard_last_name'] ?? $qr_code->settings->vcard_last_name)), 0, $available_qr_codes['vcard']['last_name']['max_length']);
                $settings['vcard_email'] = $_POST['vcard_email'] = mb_substr(trim(input_clean($_POST['vcard_email'] ?? $qr_code->settings->vcard_email)), 0, $available_qr_codes['vcard']['email']['max_length']);
                $settings['vcard_url'] = $_POST['vcard_url'] = mb_substr(trim(input_clean($_POST['vcard_url'] ?? $qr_code->settings->vcard_url)), 0, $available_qr_codes['vcard']['url']['max_length']);
                $settings['vcard_company'] = $_POST['vcard_company'] = mb_substr(trim(input_clean($_POST['vcard_company'] ?? $qr_code->settings->vcard_company)), 0, $available_qr_codes['vcard']['company']['max_length']);
                $settings['vcard_job_title'] = $_POST['vcard_job_title'] = mb_substr(trim(input_clean($_POST['vcard_job_title'] ?? $qr_code->settings->vcard_job_title)), 0, $available_qr_codes['vcard']['job_title']['max_length']);
                $settings['vcard_birthday'] = $_POST['vcard_birthday'] = mb_substr(trim(input_clean($_POST['vcard_birthday'] ?? $qr_code->settings->vcard_birthday)), 0, $available_qr_codes['vcard']['birthday']['max_length']);
                $settings['vcard_street'] = $_POST['vcard_street'] = mb_substr(trim(input_clean($_POST['vcard_street'] ?? $qr_code->settings->vcard_street)), 0, $available_qr_codes['vcard']['street']['max_length']);
                $settings['vcard_city'] = $_POST['vcard_city'] = mb_substr(trim(input_clean($_POST['vcard_city'] ?? $qr_code->settings->vcard_city)), 0, $available_qr_codes['vcard']['city']['max_length']);
                $settings['vcard_zip'] = $_POST['vcard_zip'] = mb_substr(trim(input_clean($_POST['vcard_zip'] ?? $qr_code->settings->vcard_zip)), 0, $available_qr_codes['vcard']['zip']['max_length']);
                $settings['vcard_region'] = $_POST['vcard_region'] = mb_substr(trim(input_clean($_POST['vcard_region'] ?? $qr_code->settings->vcard_region)), 0, $available_qr_codes['vcard']['region']['max_length']);
                $settings['vcard_country'] = $_POST['vcard_country'] = mb_substr(trim(input_clean($_POST['vcard_country'] ?? $qr_code->settings->vcard_country)), 0, $available_qr_codes['vcard']['country']['max_length']);
                $settings['vcard_note'] = $_POST['vcard_note'] = mb_substr(trim(input_clean($_POST['vcard_note'] ?? $qr_code->settings->vcard_note)), 0, $available_qr_codes['vcard']['note']['max_length']);

                /* Phone numbers */
                if(!isset($_POST['vcard_phone_number_label'])) {
                    $_POST['vcard_phone_number_label'] = [];
                    $_POST['vcard_phone_number_value'] = [];
                }
                $vcard_phone_numbers = [];
                foreach($_POST['vcard_phone_number_label'] as $key => $value) {
                    if($key >= 20) continue;

                    $vcard_phone_numbers[] = [
                        'label' => mb_substr(input_clean($value), 0, $available_qr_codes['vcard']['phone_number_value']['max_length']),
                        'value' => mb_substr(input_clean($_POST['vcard_phone_number_value'][$key]), 0, $available_qr_codes['vcard']['phone_number_value']['max_length'])
                    ];
                }
                $settings['vcard_phone_numbers'] = $vcard_phone_numbers;

                /* Socials */
                if(!isset($_POST['vcard_social_label'])) {
                    $_POST['vcard_social_label'] = [];
                    $_POST['vcard_social_value'] = [];
                }

                $vcard_socials = [];
                foreach($_POST['vcard_social_label'] as $key => $value) {
                    if(empty(trim($value))) continue;
                    if($key >= 20) continue;

                    $vcard_socials[] = [
                        'label' => mb_substr(input_clean($value), 0, $available_qr_codes['vcard']['social_value']['max_length']),
                        'value' => mb_substr(input_clean($_POST['vcard_social_value'][$key]), 0, $available_qr_codes['vcard']['social_value']['max_length'])
                    ];
                }
                $settings['vcard_socials'] = $vcard_socials;
                break;

            case 'paypal':
                $settings['paypal_type'] = $_POST['paypal_type'] = isset($_POST['paypal_type']) && array_key_exists($_POST['paypal_type'] ?? $qr_code->settings->paypal_type, $available_qr_codes['paypal']['type']) ? $_POST['paypal_type'] : array_key_first($available_qr_codes['paypal']['type']);
                $settings['paypal_email'] = $_POST['paypal_email'] = mb_substr(trim(input_clean($_POST['paypal_email'] ?? $qr_code->settings->paypal_email)), 0, $available_qr_codes['paypal']['email']['max_length']);
                $settings['paypal_title'] = $_POST['paypal_title'] = mb_substr(trim(input_clean($_POST['paypal_title'] ?? $qr_code->settings->paypal_title)), 0, $available_qr_codes['paypal']['title']['max_length']);
                $settings['paypal_currency'] = $_POST['paypal_currency'] = mb_substr(trim(input_clean($_POST['paypal_currency'] ?? $qr_code->settings->paypal_currency)), 0, $available_qr_codes['paypal']['currency']['max_length']);
                $settings['paypal_price'] = $_POST['paypal_price'] = (float) $_POST['paypal_price'] ?? $qr_code->settings->paypal_price;
                $settings['paypal_thank_you_url'] = $_POST['paypal_thank_you_url'] = mb_substr(trim(input_clean($_POST['paypal_thank_you_url'] ?? $qr_code->settings->paypal_thank_you_url)), 0, $available_qr_codes['paypal']['thank_you_url']['max_length']);
                $settings['paypal_cancel_url'] = $_POST['paypal_cancel_url'] = mb_substr(trim(input_clean($_POST['paypal_cancel_url'] ?? $qr_code->settings->paypal_cancel_url)), 0, $available_qr_codes['paypal']['cancel_url']['max_length']);
                break;

            case 'upi':
                $settings['upi_payee_id'] = $_POST['upi_payee_id'] = mb_substr(input_clean($_POST['upi_payee_id'] ?? $qr_code->settings->upi_payee_id), 0, $available_qr_codes['upi']['payee_id']['max_length']);
                $settings['upi_payee_name'] = $_POST['upi_payee_name'] = mb_substr(input_clean($_POST['upi_payee_name'] ?? $qr_code->settings->upi_payee_name), 0, $available_qr_codes['upi']['payee_name']['max_length']);
                $settings['upi_currency'] = $_POST['upi_currency'] = in_array($_POST['upi_currency'], ['INR']) ? $_POST['upi_currency'] : 'INR';
                $settings['upi_amount'] = isset($_POST['upi_amount']) ? (float) $_POST['upi_amount'] : $qr_code->settings->upi_amount;
                $settings['upi_transaction_id'] = $_POST['upi_transaction_id'] = mb_substr(input_clean($_POST['upi_transaction_id'] ?? $qr_code->settings->upi_transaction_id), 0, $available_qr_codes['upi']['transaction_id']['max_length']);
                $settings['upi_transaction_note'] = $_POST['upi_transaction_note'] = mb_substr(input_clean($_POST['upi_transaction_note'] ?? $qr_code->settings->upi_transaction_note), 0, $available_qr_codes['upi']['transaction_note']['max_length']);
                $settings['upi_transaction_reference'] = $_POST['upi_transaction_reference'] = mb_substr(input_clean($_POST['upi_transaction_reference'] ?? $qr_code->settings->upi_transaction_reference), 0, $available_qr_codes['upi']['transaction_reference']['max_length']);
                $settings['upi_thank_you_url'] = $_POST['upi_thank_you_url'] = mb_substr(input_clean($_POST['upi_thank_you_url'] ?? $qr_code->settings->upi_thank_you_url), 0, $available_qr_codes['upi']['thank_you_url']['max_length']);
                break;

            case 'epc':
                $required_fields[] = 'epc_iban';
                $required_fields[] = 'epc_payee_name';
                $settings['epc_iban'] = $_POST['epc_iban'] = mb_substr(input_clean($_POST['epc_iban'] ?? $qr_code->settings->epc_iban), 0, $available_qr_codes['epc']['iban']['max_length']);
                $settings['epc_payee_name'] = $_POST['epc_payee_name'] = mb_substr(input_clean($_POST['epc_payee_name'] ?? $qr_code->settings->epc_payee_name), 0, $available_qr_codes['epc']['payee_name']['max_length']);
                $settings['epc_currency'] = $_POST['epc_currency'] = in_array($_POST['epc_currency'], ['EUR']) ? $_POST['epc_currency'] : $qr_code->settings->epc_currency;
                $settings['epc_amount'] = isset($_POST['epc_amount']) ? (float) $_POST['epc_amount'] : $qr_code->settings->epc_amount;
                $settings['epc_bic'] = $_POST['epc_bic'] = mb_substr(input_clean($_POST['epc_bic'] ?? $qr_code->settings->epc_bic), 0, $available_qr_codes['epc']['bic']['max_length']);
                $settings['epc_remittance_reference'] = $_POST['epc_remittance_reference'] = mb_substr(input_clean($_POST['epc_remittance_reference'] ?? $qr_code->settings->epc_remittance_reference), 0, $available_qr_codes['epc']['remittance_reference']['max_length']);
                $settings['epc_remittance_text'] = $_POST['epc_remittance_text'] = mb_substr(input_clean($_POST['epc_remittance_text'] ?? $qr_code->settings->epc_remittance_text), 0, $available_qr_codes['epc']['remittance_text']['max_length']);
                $settings['information'] = $_POST['information'] = mb_substr(input_clean($_POST['information'] ?? $qr_code->settings->information), 0, $available_qr_codes['epc']['information']['max_length']);
                break;

            case 'pix':
                $required_fields[] = 'pix_payee_key';
                $required_fields[] = 'pix_payee_name';
                $required_fields[] = 'pix_city';
                $required_fields[] = 'pix_transaction_id';
                $settings['pix_payee_key'] = $_POST['pix_payee_key'] = mb_substr(input_clean($_POST['pix_payee_key'] ?? $qr_code->settings->pix_payee_key), 0, $available_qr_codes['pix']['payee_key']['max_length']);
                $settings['pix_payee_name'] = $_POST['pix_payee_name'] = mb_substr(input_clean($_POST['pix_payee_name'] ?? $qr_code->settings->pix_payee_name), 0, $available_qr_codes['pix']['payee_name']['max_length']);
                $settings['pix_currency'] = $_POST['pix_currency'] = in_array($_POST['pix_currency'], ['BRL']) ? $_POST['pix_currency'] : $qr_code->settings->pix_currency;
                $settings['pix_amount'] = isset($_POST['pix_amount']) ? (float) $_POST['pix_amount'] : $qr_code->settings->pix_amount;
                $settings['pix_city'] = $_POST['pix_city'] = mb_substr(input_clean($_POST['pix_city'] ?? $qr_code->settings->pix_city), 0, $available_qr_codes['pix']['city']['max_length']);
                $settings['pix_transaction_id'] = $_POST['pix_transaction_id'] = mb_substr(input_clean($_POST['pix_transaction_id'] ?? $qr_code->settings->pix_transaction_id), 0, $available_qr_codes['pix']['transaction_id']['max_length']);
                $settings['pix_description'] = $_POST['pix_description'] = mb_substr(input_clean($_POST['pix_description'] ?? $qr_code->settings->pix_description), 0, $available_qr_codes['pix']['description']['max_length']);
                break;
        }

        /* Generate the QR Code */
        $request_data = array_merge([
            'api_key' => $this->api_user->api_key,
            'type' => $_POST['type'],
        ], $settings);

        /* Attach old images if needed */
        if($qr_code->qr_code_logo) $request_data['qr_code_logo'] = \Altum\Uploads::get_full_url('qr_code_logo') . $qr_code->qr_code_logo;
        if($qr_code->qr_code_background) $request_data['qr_code_background'] = \Altum\Uploads::get_full_url('qr_code_background') . $qr_code->qr_code_background;
        if($qr_code->qr_code_foreground) $request_data['qr_code_foreground'] = \Altum\Uploads::get_full_url('qr_code_foreground') . $qr_code->qr_code_foreground;

        $request_data = json_encode($request_data);

        $request_files = [];

        /* New posted files */
        if($_POST['qr_code_logo'] && !isset($_POST['qr_code_logo_remove'])) $request_files['qr_code_logo'] = $_FILES['qr_code_logo']['tmp_name'];
        if($_POST['qr_code_background'] && !isset($_POST['qr_code_background_remove'])) $request_files['qr_code_background'] = $_FILES['qr_code_background']['tmp_name'];
        if($_POST['qr_code_foreground'] && !isset($_POST['qr_code_foreground_remove'])) $request_files['qr_code_foreground'] = $_FILES['qr_code_foreground']['tmp_name'];

        try {
            $response = Request::post(url('qr-code-generator'), [], Request\Body::multipart(['json' => $request_data], $request_files));
        } catch (\Exception $exception) {
            $this->response_error($exception->getMessage(), 401);
        }

        if($response->body->status == 'error') {
            $this->response_error($response->body->message, 401);
        }

        /* QR code logo processing */
        if($_POST['qr_code_logo']) {
            $file_name = $_FILES['qr_code_logo']['name'];
            $file_extension = explode('.', $file_name);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES['qr_code_logo']['tmp_name'];

            if($_FILES['qr_code_logo']['error'] == UPLOAD_ERR_INI_SIZE) {
                $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit), 401);
            }

            if($_FILES['qr_code_logo']['error'] && $_FILES['qr_code_logo']['error'] != UPLOAD_ERR_INI_SIZE) {
                $this->response_error(l('global.error_message.file_upload'), 401);
            }

            if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_logo'))) {
                $this->response_error(l('global.error_message.invalid_file_type'), 401);
            }

            if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                if(!is_writable(Uploads::get_full_path('qr_code_logo'))) {
                    $this->response_error(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_logo')), 401);
                }
            }

            if($_FILES['qr_code_logo']['size'] > settings()->codes->logo_size_limit * 1000000) {
                $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->logo_size_limit), 401);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Generate new name for image */
                $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                /* Offload uploading */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    try {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                        /* Delete current image */
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_logo') . $qr_code->qr_code_logo,
                        ]);

                        /* Upload image */
                        $result = $s3->putObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_logo') . $image_new_name,
                            'ContentType' => mime_content_type($file_temp),
                            'SourceFile' => $file_temp,
                            'ACL' => 'public-read'
                        ]);
                    } catch (\Exception $exception) {
                        $this->response_error($exception->getMessage(), 401);
                    }
                }

                /* Local uploading */
                else {
                    /* Delete current image */
                    if(!empty($qr_code->qr_code_logo) && file_exists(Uploads::get_full_path('qr_code_logo') . $qr_code->qr_code_logo)) {
                        unlink(Uploads::get_full_path('qr_code_logo') . $qr_code->qr_code_logo);
                    }

                    /* Upload the original */
                    move_uploaded_file($file_temp, Uploads::get_full_path('qr_code_logo') . $image_new_name);
                }

                $qr_code->qr_code_logo = $image_new_name;
            }
        }

        /* Check for the removal of the already uploaded file */
        if(isset($_POST['qr_code_logo_remove'])) {
            \Altum\Uploads::delete_uploaded_file($qr_code->qr_code_logo, 'qr_code_logo');
            $qr_code->qr_code_logo = '';
        }

        /* QR code background processing */
        if($_POST['qr_code_background']) {
            $file_name = $_FILES['qr_code_background']['name'];
            $file_extension = explode('.', $file_name);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES['qr_code_background']['tmp_name'];

            if($_FILES['qr_code_background']['error'] == UPLOAD_ERR_INI_SIZE) {
                $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
            }

            if($_FILES['qr_code_background']['error'] && $_FILES['qr_code_background']['error'] != UPLOAD_ERR_INI_SIZE) {
                $this->response_error(l('global.error_message.file_upload'), 401);
            }

            if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_background'))) {
                $this->response_error(l('global.error_message.invalid_file_type'), 401);
            }

            if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                if(!is_writable(Uploads::get_full_path('qr_code_background'))) {
                    $this->response_error(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_background')), 401);
                }
            }

            if($_FILES['qr_code_background']['size'] > settings()->codes->background_size_limit * 1000000) {
                $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Generate new name for image */
                $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                /* Offload uploading */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    try {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                        /* Delete current image */
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_background') . $qr_code->qr_code_background,
                        ]);

                        /* Upload image */
                        $result = $s3->putObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_background') . $image_new_name,
                            'ContentType' => mime_content_type($file_temp),
                            'SourceFile' => $file_temp,
                            'ACL' => 'public-read'
                        ]);
                    } catch (\Exception $exception) {
                        $this->response_error($exception->getMessage(), 401);
                    }
                }

                /* Local uploading */
                else {
                    /* Delete current image */
                    if(!empty($qr_code->qr_code_background) && file_exists(Uploads::get_full_path('qr_code_background') . $qr_code->qr_code_background)) {
                        unlink(Uploads::get_full_path('qr_code_background') . $qr_code->qr_code_background);
                    }

                    /* Upload the original */
                    move_uploaded_file($file_temp, Uploads::get_full_path('qr_code_background') . $image_new_name);
                }

                $qr_code->qr_code_background = $image_new_name;
            }
        }

        /* Check for the removal of the already uploaded file */
        if(isset($_POST['qr_code_background_remove'])) {
            \Altum\Uploads::delete_uploaded_file($qr_code->qr_code_background, 'qr_code_background');
            $qr_code->qr_code_background = '';
        }

        /* QR code foreground processing */
        if($_POST['qr_code_foreground']) {
            $file_name = $_FILES['qr_code_foreground']['name'];
            $file_extension = explode('.', $file_name);
            $file_extension = mb_strtolower(end($file_extension));
            $file_temp = $_FILES['qr_code_foreground']['tmp_name'];

            if($_FILES['qr_code_foreground']['error'] == UPLOAD_ERR_INI_SIZE) {
                $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
            }

            if($_FILES['qr_code_foreground']['error'] && $_FILES['qr_code_foreground']['error'] != UPLOAD_ERR_INI_SIZE) {
                $this->response_error(l('global.error_message.file_upload'), 401);
            }

            if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('qr_code_foreground'))) {
                $this->response_error(l('global.error_message.invalid_file_type'), 401);
            }

            if(!\Altum\Plugin::is_active('offload') || (\Altum\Plugin::is_active('offload') && !settings()->offload->uploads_url)) {
                if(!is_writable(Uploads::get_full_path('qr_code_foreground'))) {
                    $this->response_error(sprintf(l('global.error_message.directory_not_writable'), Uploads::get_full_path('qr_code_foreground')), 401);
                }
            }

            if($_FILES['qr_code_foreground']['size'] > settings()->codes->background_size_limit * 1000000) {
                $this->response_error(sprintf(l('global.error_message.file_size_limit'), settings()->codes->background_size_limit), 401);
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Generate new name for image */
                $image_new_name = md5(time() . rand()) . '.' . $file_extension;

                /* Offload uploading */
                if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
                    try {
                        $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                        /* Delete current image */
                        $s3->deleteObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_foreground') . $qr_code->qr_code_foreground,
                        ]);

                        /* Upload image */
                        $result = $s3->putObject([
                            'Bucket' => settings()->offload->storage_name,
                            'Key' => UPLOADS_URL_PATH . Uploads::get_path('qr_code_foreground') . $image_new_name,
                            'ContentType' => mime_content_type($file_temp),
                            'SourceFile' => $file_temp,
                            'ACL' => 'public-read'
                        ]);
                    } catch (\Exception $exception) {
                        $this->response_error($exception->getMessage(), 401);
                    }
                }

                /* Local uploading */
                else {
                    /* Delete current image */
                    if(!empty($qr_code->qr_code_foreground) && file_exists(Uploads::get_full_path('qr_code_foreground') . $qr_code->qr_code_foreground)) {
                        unlink(Uploads::get_full_path('qr_code_foreground') . $qr_code->qr_code_foreground);
                    }

                    /* Upload the original */
                    move_uploaded_file($file_temp, Uploads::get_full_path('qr_code_foreground') . $image_new_name);
                }

                $qr_code->qr_code_foreground = $image_new_name;
            }
        }

        /* Check for the removal of the already uploaded file */
        if(isset($_POST['qr_code_foreground_remove'])) {
            \Altum\Uploads::delete_uploaded_file($qr_code->qr_code_foreground, 'qr_code_foreground');
            $qr_code->qr_code_foreground = '';
        }

        /* QR Code image */
        $_POST['qr_code'] = base64_decode(mb_substr($response->body->details->data, mb_strlen('data:image/svg+xml;base64,')));

        /* Embedded data */
        $_POST['embedded_data'] = input_clean($response->body->details->embedded_data, 10000);

        /* Generate new name for image */
        $image_new_name = md5(time() . rand()) . '.svg';

        /* Offload uploading */
        if(\Altum\Plugin::is_active('offload') && settings()->offload->uploads_url) {
            try {
                $s3 = new \Aws\S3\S3Client(get_aws_s3_config());

                /* Delete current image */
                $s3->deleteObject([
                    'Bucket' => settings()->offload->storage_name,
                    'Key' =>  UPLOADS_URL_PATH . Uploads::get_path('qr_code') . $qr_code->qr_code,
                ]);

                /* Upload image */
                $result = $s3->putObject([
                    'Bucket' => settings()->offload->storage_name,
                    'Key' =>  UPLOADS_URL_PATH . Uploads::get_path('qr_code') . $image_new_name,
                    'ContentType' => 'image/svg+xml',
                    'Body' => $_POST['qr_code'],
                    'ACL' => 'public-read'
                ]);
            } catch (\Exception $exception) {
                $this->response_error($exception->getMessage(), 401);
            }
        }

        /* Local uploading */
        else {
            /* Delete current image */
            if(!empty($qr_code->qr_code) && file_exists(Uploads::get_full_path('qr_code') . $qr_code->qr_code)) {
                unlink(Uploads::get_full_path('qr_code') . $qr_code->qr_code);
            }

            /* Upload the original */
            file_put_contents(Uploads::get_full_path('qr_code') . $image_new_name, $_POST['qr_code']);
        }
        $qr_code->qr_code = $image_new_name;

        $settings = json_encode($settings);

        /* Database query */
        db()->where('qr_code_id', $qr_code->qr_code_id)->update('qr_codes', [
            'project_id' => $_POST['project_id'],
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'settings' => $settings,
            'embedded_data' => $_POST['embedded_data'],
            'qr_code' => $qr_code->qr_code,
            'qr_code_logo' => $qr_code->qr_code_logo,
            'qr_code_background' => $qr_code->qr_code_background,
            'qr_code_foreground' => $qr_code->qr_code_foreground,
            'last_datetime' => get_date(),
        ]);

        /* Prepare the data */
        $data = [
            'id' => $qr_code->qr_code_id
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $qr_code_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->api_user->user_id)->getOne('qr_codes');

        /* We haven't found the resource */
        if(!$qr_code) {
            $this->return_404();
        }

        (new QrCode())->delete($qr_code->qr_code_id);

        http_response_code(200);
        die();

    }

}
