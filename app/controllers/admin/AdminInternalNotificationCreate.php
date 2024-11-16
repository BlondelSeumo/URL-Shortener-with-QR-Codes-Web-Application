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

class AdminInternalNotificationCreate extends Controller {

    public function index() {

        $plans = (new \Altum\Models\Plan())->get_plans();

        /* Clear $_GET */
        foreach($_GET as $key => $value) {
            $_GET[$key] = input_clean($value);
        }

        if(!empty($_POST)) {
            set_time_limit(0);

            /* Filter some the variables */
            $_POST['title'] = input_clean($_POST['title'], 128);
            $_POST['description'] = input_clean($_POST['description'], 1024);
            $_POST['url'] = get_url($_POST['url'], 512);
            $_POST['icon'] = input_clean($_POST['icon'], 64);

            $_POST['users_ids'] = trim($_POST['users_ids'] ?? '');
            if($_POST['users_ids']) {
                $_POST['users_ids'] = explode(',', $_POST['users_ids'] ?? '');
                if(count($_POST['users_ids'])) {
                    $_POST['users_ids'] = array_map(function ($user_id) {
                        return (int) $user_id;
                    }, $_POST['users_ids']);
                    $_POST['users_ids'] = array_unique($_POST['users_ids']);
                }
            }

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            $required_fields = ['title', 'description'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Get all the users needed */
                switch($_POST['segment']) {
                    case 'all':
                        $users = db()->get('users', null, ['user_id', 'name', 'email', 'continent_code', 'country', 'city_name', 'device_type', 'os_name', 'browser_name', 'browser_language']);
                        break;

                    case 'custom':
                        $users = db()->where('user_id', $_POST['users_ids'], 'IN')->get('users', null, ['user_id', 'name', 'email', 'continent_code', 'country', 'city_name', 'device_type', 'os_name', 'browser_name', 'browser_language']);
                        break;

                    case 'filter':

                        $query = db();

                        $has_filters = false;

                        /* Is subscribed */
                        $_GET['filters_is_newsletter_subscribed'] = isset($_GET['filters_is_newsletter_subscribed']) ? (bool) $_GET['filters_is_newsletter_subscribed'] : 0;

                        if($_GET['filters_is_newsletter_subscribed']) {
                            $has_filters = true;
                            $query->where('is_newsletter_subscribed', 1);
                        }

                        /* Plans */
                        if(isset($_GET['filters_plans'])) {
                            $has_filters = true;
                            $query->where('plan_id', $_GET['filters_plans'], 'IN');
                        }

                        /* Status */
                        if(isset($_GET['filters_status'])) {
                            $has_filters = true;
                            $query->where('status', $_GET['filters_status'], 'IN');
                        }

                        /* Countries */
                        if(isset($_GET['filters_countries'])) {
                            $has_filters = true;
                            $query->where('country', $_GET['filters_countries'], 'IN');
                        }

                        /* Continents */
                        if(isset($_GET['filters_continents'])) {
                            $has_filters = true;
                            $query->where('continent_code', $_GET['filters_continents'], 'IN');
                        }

                        /* Source */
                        if(isset($_GET['filters_source'])) {
                            $has_filters = true;
                            $query->where('source', $_GET['filters_source'], 'IN');
                        }

                        /* Device type */
                        if(isset($_GET['filters_device_type'])) {
                            $has_filters = true;
                            $query->where('device_type', $_GET['filters_device_type'], 'IN');
                        }

                        /* Languages */
                        if(isset($_GET['filters_languages'])) {
                            $has_filters = true;
                            $query->where('language', $_GET['filters_languages'], 'IN');
                        }

                        /* Cities */
                        if(!empty($_GET['filters_cities'])) {
                            $_GET['filters_cities'] = is_array($_GET['filters_cities']) ? $_GET['filters_cities'] : explode(',', $_GET['filters_cities']);

                            if(count($_GET['filters_cities'])) {
                                $_GET['filters_cities'] = array_map(function($city) {
                                    return query_clean($city);
                                }, $_GET['filters_cities']);
                                $_GET['filters_cities'] = array_unique($_GET['filters_cities']);

                                $has_filters = true;
                                $query->where('city_name', $_GET['filters_cities'], 'IN');
                            }
                        }

                        /* Languages */
                        if(isset($_GET['filters_browser_languages'])) {
                            $_GET['filters_browser_languages'] = array_filter($_GET['filters_browser_languages'], function($locale) {
                                return array_key_exists($locale, get_locale_languages_array());
                            });

                            $has_filters = true;
                            $query->where('browser_language', $_GET['filters_browser_languages'], 'IN');
                        }

                        /* Filters operating systems */
                        if (isset($_GET['filters_operating_systems'])) {
                            $_GET['filters_operating_systems'] = array_filter($_GET['filters_operating_systems'], function($os_name) {
                                return in_array($os_name, ['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS']);
                            });

                            $has_filters = true;
                            $query->where('os_name', $_GET['filters_operating_systems'], 'IN');
                        }

                        /* Filters browsers */
                        if (isset($_GET['filters_browsers'])) {
                            $_GET['filters_browsers'] = array_filter($_GET['filters_browsers'], function($browser_name) {
                                return in_array($browser_name, ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet']);
                            });

                            $has_filters = true;
                            $query->where('browser_name', $_GET['filters_browsers'], 'IN');
                        }

                        $users = $has_filters ? $query->get('users', null, ['user_id', 'name', 'email', 'continent_code', 'country', 'city_name', 'device_type', 'os_name', 'browser_name', 'browser_language']) : [];

                        break;
                }

                foreach($users as $user) {
                    $replacers = [
                        '{{WEBSITE_TITLE}}' => settings()->main->title,
                        '{{USER:NAME}}' => $user->name,
                        '{{USER:EMAIL}}' => $user->email,
                        '{{USER:CONTINENT_NAME}}' => get_continent_from_continent_code($user->continent_code),
                        '{{USER:COUNTRY_NAME}}' => get_country_from_country_code($user->country),
                        '{{USER:CITY_NAME}}' => $user->city_name,
                        '{{USER:DEVICE_TYPE}}' => l('global.device.' . $user->device_type),
                        '{{USER:OS_NAME}}' => $user->os_name,
                        '{{USER:BROWSER_NAME}}' => $user->browser_name,
                        '{{USER:BROWSER_LANGUAGE}}' => get_language_from_locale($user->browser_language),
                    ];

                    $title = process_spintax(str_replace(
                        array_keys($replacers),
                        array_values($replacers),
                        $_POST['title']
                    ));

                    $description = process_spintax(str_replace(
                        array_keys($replacers),
                        array_values($replacers),
                        $_POST['description']
                    ));

                    /* Database query */
                    $internal_notification_id = db()->insert('internal_notifications', [
                        'user_id' => $user->user_id,
                        'for_who' => 'user',
                        'from_who' => 'admin',
                        'title' => $title,
                        'description' => $description,
                        'url' => $_POST['url'],
                        'icon' => $_POST['icon'],
                        'datetime' => get_date(),
                    ]);

                    /* Database query */
                    db()->where('user_id', $user->user_id )->update('users', [
                        'has_pending_internal_notifications' => 1
                    ]);
                }

                /* Clear the cache */
                cache()->clear();

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['title'] . '</strong>'));

                redirect('admin/internal-notifications');
            }
        }

        $values = [
            'title' => $_GET['title'] ?? $_POST['title'] ?? null,
            'description' => $_GET['description'] ?? $_POST['description'] ?? null,
            'url' => $_GET['url'] ?? $_POST['url'] ?? null,
            'icon' => $_GET['icon'] ?? $_POST['icon'] ?? null,
            'segment' => $_GET['segment'] ?? $_POST['segment'] ?? 'all',
            'users_ids' => $_GET['users_ids'] ?? $_POST['users_ids'] ?? null,
            'filters_is_newsletter_subscribed' => $_POST['filters_is_newsletter_subscribed'] ?? [],
            'filters_plans' => $_POST['filters_plans'] ?? [],
            'filters_status' => $_POST['filters_status'] ?? [],
            'filters_source' => $_POST['filters_source'] ?? [],
            'filters_device_type' => $_POST['filters_device_type'] ?? [],
            'filters_continents' => $_POST['filters_continents'] ?? [],
            'filters_countries' => $_POST['filters_countries'] ?? [],
            'filters_cities' => implode(',', $_POST['filters_cities'] ?? []),
            'filters_browser_languages' => $_POST['filters_browser_languages'] ?? [],
            'filters_languages' => $_POST['filters_languages'] ?? [],
            'filters_operating_systems' => $_POST['filters_operating_systems'] ?? [],
            'filters_browsers' => $_POST['filters_browsers'] ?? [],
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'plans' => $plans,
        ];

        $view = new \Altum\View('admin/internal-notification-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
