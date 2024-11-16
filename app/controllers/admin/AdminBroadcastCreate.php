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

class AdminBroadcastCreate extends Controller {

    public function index() {

        $plans = (new \Altum\Models\Plan())->get_plans();

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['subject'] = input_clean($_POST['subject'], 128);
            $_POST['segment'] = in_array($_POST['segment'], ['all', 'subscribers', 'custom', 'filter']) ? input_clean($_POST['segment']) : 'subscribers';
            $_POST['is_system_email'] = (int) isset($_POST['is_system_email']);

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

            /* Preview email */
            if(isset($_POST['preview'])) {
                $_POST['preview_email'] = mb_substr(filter_var($_POST['preview_email'], FILTER_SANITIZE_EMAIL), 0, 320);

                $required_fields = ['subject', 'content', 'preview_email'];
                foreach($required_fields as $field) {
                    if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                        Alerts::add_field_error($field, l('global.error_message.empty_field'));
                    }
                }

                if(filter_var($_POST['preview_email'], FILTER_VALIDATE_EMAIL) == false) {
                    Alerts::add_field_error('preview_email', l('global.error_message.invalid_email'));
                }
            }

            /* Save draft or send */
            else {
                $required_fields = ['name', 'subject', 'content'];
                foreach($required_fields as $field) {
                    if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                        Alerts::add_field_error($field, l('global.error_message.empty_field'));
                    }
                }
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Preview email */
                if(isset($_POST['preview'])) {
                    $vars = [
                        '{{USER:NAME}}' => $this->user->name,
                        '{{USER:EMAIL}}' => $this->user->email,
                        '{{USER:CONTINENT_NAME}}' => get_continent_from_continent_code($this->user->continent_code),
                        '{{USER:COUNTRY_NAME}}' => get_country_from_country_code($this->user->country),
                        '{{USER:CITY_NAME}}' => $this->user->city_name,
                        '{{USER:DEVICE_TYPE}}' => l('global.device.' . $this->user->device_type),
                        '{{USER:OS_NAME}}' => $this->user->os_name,
                        '{{USER:BROWSER_NAME}}' => $this->user->browser_name,
                        '{{USER:BROWSER_LANGUAGE}}' => get_language_from_locale($this->user->browser_language),
                    ];

                    $email_template = get_email_template(
                        $vars,
                        htmlspecialchars_decode($_POST['subject']),
                        $vars,
                        convert_editorjs_json_to_html($_POST['content'])
                    );

                    send_mail($_POST['preview_email'], $email_template->subject, $email_template->body, ['is_broadcast' => true, 'is_system_email' => $_POST['is_system_email'], 'anti_phishing_code' => $this->user->anti_phishing_code, 'language' => $this->user->language], $_POST['preview_email']);

                    /* Set a nice success message */
                    Alerts::add_success(sprintf(l('admin_broadcast_create.success_message.preview'), '<strong>' . $_POST['preview_email'] . '</strong>'));
                }


                if(isset($_POST['save']) || isset($_POST['send'])) {
                    $settings = [
                        'is_system_email' => $_POST['is_system_email'],
                    ];

                    /* Get all the users needed */
                    switch($_POST['segment']) {
                        case 'all':
                            $users = db()->get('users', null, ['user_id']);
                            break;

                        case 'subscribers':
                            $users = db()->where('is_newsletter_subscribed', 1)->get('users', null, ['user_id']);
                            break;

                        case 'custom':
                            $users = db()->where('user_id', $_POST['users_ids'], 'IN')->get('users', null, ['user_id']);
                            break;

                        case 'filter':

                            $query = db();

                            $has_filters = false;

                            /* Is subscribed */
                            $_POST['filters_is_newsletter_subscribed'] = isset($_POST['filters_is_newsletter_subscribed']) ? (bool) $_POST['filters_is_newsletter_subscribed'] : 0;

                            if($_POST['filters_is_newsletter_subscribed']) {
                                $has_filters = true;
                                $query->where('is_newsletter_subscribed', 1);
                                $settings['filters_is_newsletter_subscribed'] = (int) $_POST['filters_is_newsletter_subscribed'];
                            }

                            /* Plans */
                            if(isset($_POST['filters_plans'])) {
                                $has_filters = true;
                                $query->where('plan_id', $_POST['filters_plans'], 'IN');
                                $settings['filters_plans'] = $_POST['filters_plans'];
                            }

                            /* Status */
                            if(isset($_POST['filters_status'])) {
                                $has_filters = true;
                                $query->where('status', $_POST['filters_status'], 'IN');
                                $settings['filters_status'] = $_POST['filters_status'];
                            }

                            /* Cities */
                            if(!empty($_POST['filters_cities'])) {
                                $_POST['filters_cities'] = explode(',', $_POST['filters_cities']);

                                if(count($_POST['filters_cities'])) {
                                    $_POST['filters_cities'] = array_map(function($city) {
                                        return query_clean($city);
                                    }, $_POST['filters_cities']);
                                    $_POST['filters_cities'] = array_filter(array_unique($_POST['filters_cities']));

                                    $has_filters = true;
                                    $query->where('city_name', $_POST['filters_cities'], 'IN');
                                    $settings['filters_cities'] = $_POST['filters_cities'];
                                }
                            }

                            /* Countries */
                            if(isset($_POST['filters_countries'])) {
                                $has_filters = true;
                                $query->where('country', $_POST['filters_countries'], 'IN');
                                $settings['filters_countries'] = $_POST['filters_countries'];
                            }

                            /* Continents */
                            if(isset($_POST['filters_continents'])) {
                                $has_filters = true;
                                $query->where('continent_code', $_POST['filters_continents'], 'IN');
                                $settings['filters_continents'] = $_POST['filters_continents'];
                            }

                            /* Source */
                            if(isset($_POST['filters_source'])) {
                                $has_filters = true;
                                $query->where('source', $_POST['filters_source'], 'IN');
                                $settings['filters_source'] = $_POST['filters_source'];
                            }

                            /* Device type */
                            if(isset($_GET['filters_device_type'])) {
                                $has_filters = true;
                                $query->where('device_type', $_GET['filters_device_type'], 'IN');
                                $settings['filters_device_type'] = $_POST['filters_device_type'];
                            }

                            /* Languages */
                            if(isset($_GET['filters_languages'])) {
                                $has_filters = true;
                                $query->where('language', $_GET['filters_languages'], 'IN');
                                $settings['filters_languages'] = $_POST['filters_languages'];
                            }

                            /* Browser languages */
                            if(isset($_POST['filters_browser_languages'])) {
                                $_POST['filters_browser_languages'] = array_filter($_POST['filters_browser_languages'], function($locale) {
                                    return array_key_exists($locale, get_locale_languages_array());
                                });

                                $has_filters = true;
                                $query->where('browser_language', $_POST['filters_browser_languages'], 'IN');
                                $settings['filters_browser_languages'] = $_POST['filters_browser_languages'];
                            }

                            /* Filters operating systems */
                            if (isset($_POST['filters_operating_systems'])) {
                                $_POST['filters_operating_systems'] = array_filter($_POST['filters_operating_systems'], function($os_name) {
                                    return in_array($os_name, ['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS']);
                                });

                                $has_filters = true;
                                $query->where('os_name', $_POST['filters_operating_systems'], 'IN');
                                $settings['filters_operating_systems'] = $_POST['filters_operating_systems'];
                            }

                            /* Filters browsers */
                            if (isset($_POST['filters_browsers'])) {
                                $_POST['filters_browsers'] = array_filter($_POST['filters_browsers'], function($browser_name) {
                                    return in_array($browser_name, ['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet']);
                                });

                                $has_filters = true;
                                $query->where('browser_name', $_POST['filters_browsers'], 'IN');
                                $settings['filters_browsers'] = $_POST['filters_browsers'];
                            }

                            $users = $has_filters ? $query->get('users', null, ['user_id']) : [];

                            break;
                    }

                    $users_ids = [];
                    foreach($users as $user) {
                        $users_ids[] = $user->user_id;
                    }

                    /* Database query */
                    $broadcast_id = db()->insert('broadcasts', [
                        'name' => $_POST['name'],
                        'subject' => $_POST['subject'],
                        'content' => $_POST['content'],
                        'segment' => $_POST['segment'],
                        'settings' => json_encode($settings),
                        'users_ids' => json_encode($users_ids),
                        'sent_users_ids' => '[]',
                        'total_emails' => count($users_ids),
                        'status' => isset($_POST['save']) ? 'draft' : 'processing',
                        'datetime' => get_date(),
                    ]);

                    if(isset($_POST['save'])) {
                        /* Set a nice success message */
                        Alerts::add_success(sprintf(l('admin_broadcast_create.success_message.save'), '<strong>' . $_POST['name'] . '</strong>'));
                    } else {
                        /* Set a nice success message */
                        Alerts::add_success(sprintf(l('admin_broadcast_create.success_message.send'), '<strong>' . $_POST['name'] . '</strong>'));
                    }

                    redirect('admin/broadcasts');
                }

            }
        }

        $values = [
            'name' => $_POST['name'] ?? null,
            'subject' => $_POST['subject'] ?? null,
            'is_system_email' => $_POST['is_system_email'] ?? false,
            'segment' => $_POST['segment'] ?? 'all',
            'users_ids' => $_POST['users_ids'] ?? null,
            'content' => $_POST['content'] ?? json_encode([
                    'blocks' => [
                        [
                            'type' => 'paragraph',
                            'data' => [
                                'text' => l('admin_broadcast_create.content_placeholder')
                            ]
                        ]
                    ]
                ]),
            'preview_email' => $_POST['preview_email'] ?? $this->user->email,
            'filters_is_newsletter_subscribed' => $_POST['filters_is_newsletter_subscribed'] ?? [],
            'filters_plans' => $_POST['filters_plans'] ?? [],
            'filters_status' => $_POST['filters_status'] ?? [],
            'filters_source' => $_POST['filters_source'] ?? [],
            'filters_device_type' => $_POST['filters_device_type'] ?? [],
            'filters_continents' => $_POST['filters_continents'] ?? [],
            'filters_countries' => $_POST['filters_countries'] ?? [],
            'filters_cities' => implode(',', $_POST['filters_cities'] ?? []),
            'filters_languages' => $_POST['filters_languages'] ?? [],
            'filters_browser_languages' => $_POST['filters_browser_languages'] ?? [],
            'filters_operating_systems' => $_POST['filters_operating_systems'] ?? [],
            'filters_browsers' => $_POST['filters_browsers'] ?? [],
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'plans' => $plans,
        ];

        $view = new \Altum\View('admin/broadcast-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
