<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

function settings() {
    if(!\Altum\Settings::$settings) {
        \Altum\Settings::initialize();
    }

    return \Altum\Settings::$settings;
}

function get_settings_custom_head_js($key = 'head_js') {
    $head_js = settings()->custom->{$key};

    /* Dynamic variables processing */
    $replacers = [
        '{{WEBSITE_TITLE}}' => settings()->main->title,
        '{{USER:NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->name : '',
        '{{USER:EMAIL}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->email : '',
        '{{USER:CONTINENT_NAME}}' => \Altum\Authentication::check() ? get_continent_from_continent_code(\Altum\Authentication::$user->continent_code) : '',
        '{{USER:COUNTRY_NAME}}' => \Altum\Authentication::check() ? get_country_from_country_code(\Altum\Authentication::$user->country) : '',
        '{{USER:CITY_NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->city_name : '',
        '{{USER:DEVICE_TYPE}}' => \Altum\Authentication::check() ? l('global.device.' . \Altum\Authentication::$user->device_type) : '',
        '{{USER:OS_NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->os_name : '',
        '{{USER:BROWSER_NAME}}' => \Altum\Authentication::check() ? \Altum\Authentication::$user->browser_name : '',
        '{{USER:BROWSER_LANGUAGE}}' => \Altum\Authentication::check() ? get_language_from_locale(\Altum\Authentication::$user->browser_language) : '',
        '{{USER:USER_ID}}' => json_encode(\Altum\Authentication::check() ? \Altum\Authentication::$user->user_id : ''),
        '{{USER:PLAN_ID}}' => json_encode(\Altum\Authentication::check() ? \Altum\Authentication::$user->plan_id : ''),
    ];

    $head_js = str_replace(
        array_keys($replacers),
        array_values($replacers),
        $head_js
    );

    return $head_js;
}

function db() {
    if(!\Altum\Database::$db) {
        \Altum\Database::initialize();
    }

    return \Altum\Database::$db;
}

function database() {
    if(!\Altum\Database::$database) {
        \Altum\Database::initialize();
    }

    return \Altum\Database::$database;
}

function language($language = null) {
    return \Altum\Language::get($language);
}

function l($key, $language = null, $null_coalesce = false) {
    return \Altum\Language::get($language)[$key] ?? \Altum\Language::get(\Altum\Language::$main_name)[$key] ?? ($null_coalesce ? null : 'missing_translation: ' . $key);
}

function currency() {
    if(!\Altum\Currency::$currency) {
        \Altum\Currency::initialize();
    }

    return \Altum\Currency::$currency;
}

function cache($adapter = 'adapter') {
    return \Altum\Cache::${$adapter};
}

function get_date($format = 'Y-m-d H:i:s') {
    return date($format);
}
