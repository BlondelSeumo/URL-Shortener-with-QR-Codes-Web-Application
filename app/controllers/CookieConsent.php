<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class CookieConsent extends Controller {

    public function index() {

        if(!settings()->cookie_consent->is_enabled || !settings()->cookie_consent->logging_is_enabled) {
            redirect('not-found');
        }

        $payload = @file_get_contents('php://input');
        $_POST = json_decode($payload, true);

        if(!\Altum\Csrf::check('global_token')) {
            redirect();
        }

        /* Detect extra details about the user */
        $whichbrowser = new \WhichBrowser\Parser($_SERVER['HTTP_USER_AGENT']);

        /* Do not track bots */
        if($whichbrowser->device->type == 'bot') {
            return;
        }

        $allowed_levels = ['necessary', 'analytics', 'targeting'];
        $levels = array_filter($_POST['level'], function($level) use ($allowed_levels) {
            return in_array($level, $allowed_levels);
        });

        /* Generate new CSV line */
        $browser_name = $whichbrowser->browser->name ?? null;
        $os_name = $whichbrowser->os->name ?? null;
        $browser_language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? mb_substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2) : null;
        $device_type = get_device_type($_SERVER['HTTP_USER_AGENT']);
        $ip = get_ip();
        $date = (new \DateTime())->format('Y-m-d');
        $time = (new \DateTime())->format('H:i:s') . ' UTC';
        $accepted_levels = implode('+', $levels);

        $new_line = implode(',', [$ip, $date, $time, $accepted_levels, $device_type, $browser_language, $browser_name, $os_name]);

        if(!file_exists(UPLOADS_PATH . 'cookie_consent/data.csv')) {
            $first_line = 'IP,Date,Time,Accepted cookies,Device type,Browser language,Browser name,OS Name';
            file_put_contents(UPLOADS_PATH . 'cookie_consent/data.csv', $first_line . PHP_EOL , FILE_APPEND | LOCK_EX);
        }

        file_put_contents(UPLOADS_PATH . 'cookie_consent/data.csv', $new_line . PHP_EOL , FILE_APPEND | LOCK_EX);

        /* Generate .htaccess if not existing */
        if(!file_exists(UPLOADS_PATH . 'cookie_consent/.htaccess')) {
            file_put_contents(UPLOADS_PATH . 'cookie_consent/.htaccess', 'Deny from all');
        }

        die();
    }

}
