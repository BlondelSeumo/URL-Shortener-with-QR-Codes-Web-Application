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

class AdminUserUpdate extends Controller {

    public function index() {

        $user_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Check if user exists */
        if(!$user = db()->where('user_id', $user_id)->getOne('users')) {
            redirect('admin/users');
        }

        $user->plan_settings = json_decode($user->plan_settings);

        $additional_domains = db()->where('is_enabled', 1)->where('type', 1)->get('domains');
        $biolinks_templates = (new \Altum\Models\BiolinksTemplates())->get_biolinks_templates();

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['status'] = (int) $_POST['status'];
            $_POST['type'] = (int) $_POST['type'];
            $_POST['plan_trial_done'] = (int) isset($_POST['plan_trial_done']);

            if(\Altum\Plugin::is_active('affiliate')) {
                $_POST['referred_by'] = !empty($_POST['referred_by']) ? (int) $_POST['referred_by'] : null;
            }

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            switch($_POST['plan_id']) {
                case 'free':

                    $plan_settings = json_encode(settings()->plan_free->settings ?? '');

                    break;

                case 'custom':

                    /* Determine the enabled biolink blocks */
                    $enabled_biolink_blocks = [];

                    foreach(require APP_PATH . 'includes/biolink_blocks.php' as $key => $value) {
                        $enabled_biolink_blocks[$key] = isset($_POST['enabled_biolink_blocks']) && in_array($key, $_POST['enabled_biolink_blocks']);
                    }

                    $settings = [
                        'url_minimum_characters' => (int) $_POST['url_minimum_characters'],
                        'url_maximum_characters' => (int) $_POST['url_maximum_characters'],
                        'additional_domains' => $_POST['additional_domains'] ?? [],
                        'biolinks_templates' => $_POST['biolinks_templates'] ?? [],
                        'custom_url' => isset($_POST['custom_url']),
                        'deep_links' => isset($_POST['deep_links']),
                        'no_ads' => isset($_POST['no_ads']),
                        'removable_branding' => isset($_POST['removable_branding']),
                        'custom_branding' => isset($_POST['custom_branding']),
                        'statistics' => isset($_POST['statistics']),
                        'temporary_url_is_enabled' => isset($_POST['temporary_url_is_enabled']),
                        'cloaking_is_enabled' => isset($_POST['cloaking_is_enabled']),
                        'app_linking_is_enabled' => isset($_POST['app_linking_is_enabled']),
                        'seo' => isset($_POST['seo']),
                        'utm' => isset($_POST['utm']),
                        'fonts' => isset($_POST['fonts']),
                        'password' => isset($_POST['password']),
                        'sensitive_content' => isset($_POST['sensitive_content']),
                        'leap_link' => isset($_POST['leap_link']),
                        'api_is_enabled' => isset($_POST['api_is_enabled']),
                        'dofollow_is_enabled' => isset($_POST['dofollow_is_enabled']),
                        'biolink_blocks_limit' => (int) $_POST['biolink_blocks_limit'],
                        'projects_limit' => (int) $_POST['projects_limit'],
                        'splash_pages_limit' => (int) $_POST['splash_pages_limit'],
                        'pixels_limit' => (int) $_POST['pixels_limit'],
                        'qr_codes_limit' => (int) $_POST['qr_codes_limit'],
                        'qr_codes_bulk_limit' => (int) $_POST['qr_codes_bulk_limit'],
                        'biolinks_limit' => (int) $_POST['biolinks_limit'],
                        'links_limit' => (int) $_POST['links_limit'],
                        'files_limit' => (int) $_POST['files_limit'],
                        'vcards_limit' => (int) $_POST['vcards_limit'],
                        'events_limit' => (int) $_POST['events_limit'],
                        'static_limit' => (int) $_POST['static_limit'],
                        'domains_limit' => (int) $_POST['domains_limit'],
                        'payment_processors_limit' => (int) $_POST['payment_processors_limit'],
                        'signatures_limit' => (int) $_POST['signatures_limit'],
                        'teams_limit' => (int) $_POST['teams_limit'],
                        'team_members_limit' => (int) $_POST['team_members_limit'],
                        'affiliate_commission_percentage' => (int) $_POST['affiliate_commission_percentage'],
                        'track_links_retention' => (int) $_POST['track_links_retention'],
                        'custom_css_is_enabled' => isset($_POST['custom_css_is_enabled']),
                        'custom_js_is_enabled' => isset($_POST['custom_js_is_enabled']),
                        'enabled_biolink_blocks' => $enabled_biolink_blocks,
                        'exclusive_personal_api_keys'       => isset($_POST['exclusive_personal_api_keys']),
                        'documents_model'                   => $_POST['documents_model'],
                        'documents_per_month_limit'         => (int) $_POST['documents_per_month_limit'],
                        'words_per_month_limit'             => (int) $_POST['words_per_month_limit'],
                        'images_api'                        => $_POST['images_api'],
                        'images_per_month_limit'            => (int) $_POST['images_per_month_limit'],
                        'transcriptions_per_month_limit'    => (int) $_POST['transcriptions_per_month_limit'],
                        'transcriptions_file_size_limit'    => $_POST['transcriptions_file_size_limit'] > get_max_upload() || $_POST['transcriptions_file_size_limit'] < 0 || $_POST['transcriptions_file_size_limit'] > 25 ? (get_max_upload() > 25 ? 25 : get_max_upload()) : (float) $_POST['transcriptions_file_size_limit'],
                        'chats_model'                       => $_POST['chats_model'],
                        'chats_per_month_limit'             => (int) $_POST['chats_per_month_limit'],
                        'chat_messages_per_chat_limit'      => (int) $_POST['chat_messages_per_chat_limit'],
                        'chat_image_size_limit'    => $_POST['chat_image_size_limit'] > get_max_upload() || $_POST['chat_image_size_limit'] < 0 || $_POST['chat_image_size_limit'] > 20 ? (get_max_upload() > 20 ? 20 : get_max_upload()) : (float) $_POST['chat_image_size_limit'],
                        'syntheses_api'                     => $_POST['syntheses_api'],
                        'syntheses_per_month_limit'         => (int) $_POST['syntheses_per_month_limit'],
                        'synthesized_characters_per_month_limit' => (int) $_POST['synthesized_characters_per_month_limit'],
                    ];

                    foreach(require APP_PATH . 'includes/links_types.php' as $key => $value) {
                        $settings['force_splash_page_on_' . $key] = isset($_POST['force_splash_page_on_' . $key]);
                    }

                    $plan_settings = json_encode($settings);

                    break;

                default:

                    $_POST['plan_id'] = (int) $_POST['plan_id'];

                    /* Make sure this plan exists */
                    if(!$plan_settings = db()->where('plan_id', $_POST['plan_id'])->getValue('plans', 'settings')) {
                        redirect('admin/user-update/' . $user->user_id);
                    }

                    break;
            }

            $_POST['plan_expiration_date'] = (new \DateTime($_POST['plan_expiration_date']))->format('Y-m-d H:i:s');

            /* Check for any errors */
            $required_fields = ['name', 'email'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }
            if(mb_strlen($_POST['name']) < 1 || mb_strlen($_POST['name']) > 64) {
                Alerts::add_field_error('name', l('admin_users.error_message.name_length'));
            }
            if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) == false) {
                //ALTUMCODE:DEMO if(DEMO) {
                Alerts::add_field_error('email', l('global.error_message.invalid_email'));
                //ALTUMCODE:DEMO }
            }
            if(db()->where('email', $_POST['email'])->has('users') && $_POST['email'] !== $user->email) {
                Alerts::add_field_error('email', l('admin_users.error_message.email_exists'));
            }

            if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                if(mb_strlen($_POST['new_password']) < 6 || mb_strlen($_POST['new_password']) > 64) {
                    Alerts::add_field_error('new_password', l('global.error_message.password_length'));
                }
                if($_POST['new_password'] !== $_POST['repeat_password']) {
                    Alerts::add_field_error('repeat_password', l('global.error_message.passwords_not_matching'));
                }
            }

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Update the basic user settings */
                db()->where('user_id', $user->user_id)->update('users', [
                    'name' => $_POST['name'],
                    'email' => $_POST['email'],
                    'status' => $_POST['status'],
                    'type' => $_POST['type'],
                    'plan_id' => $_POST['plan_id'],
                    'plan_expiration_date' => $_POST['plan_expiration_date'],
                    'plan_expiry_reminder' => $user->plan_expiration_date != $_POST['plan_expiration_date'] ? 0 : 1,
                    'plan_settings' => $plan_settings,
                    'plan_trial_done' => $_POST['plan_trial_done'],
                    'referred_by' => $user->referred_by != $_POST['referred_by'] ? $_POST['referred_by'] : $user->referred_by,
                ]);

                /* Update the password if set */
                if(!empty($_POST['new_password']) && !empty($_POST['repeat_password'])) {
                    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

                    /* Database query */
                    db()->where('user_id', $user->user_id)->update('users', ['password' => $new_password]);
                }

                /* Update all websites if any */
                if(settings()->sso->is_enabled && count((array) settings()->sso->websites)) {
                    foreach(settings()->sso->websites as $website) {
                        $response = \Unirest\Request::post(
                            $website->url . 'admin-api/sso/update',
                            ['Authorization' => 'Bearer ' . $website->api_key],
                            \Unirest\Request\Body::form([
                                'name' => $_POST['name'],
                                'email' => $user->email,
                                'new_email' => $_POST['email'],
                            ])
                        );
                    }
                }

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $user->user_id);

                redirect('admin/user-update/' . $user->user_id);
            }

        }

        /* Get all the plans available */
        $plans = db()->where('status', 0, '<>')->get('plans');

        /* Main View */
        $data = [
            'user' => $user,
            'plans' => $plans,
            'additional_domains' => $additional_domains,
            'biolinks_templates' => $biolinks_templates,
        ];

        $view = new \Altum\View('admin/user-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
