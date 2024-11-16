<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;

use Altum\Alerts;

class AccountPreferences extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!empty($_POST)) {

            /* Clean some posted variables */
            $_POST['default_results_per_page'] = isset($_POST['default_results_per_page']) && in_array($_POST['default_results_per_page'], [10, 25, 50, 100, 250, 500, 1000]) ? (int) $_POST['default_results_per_page'] : settings()->main->default_results_per_page;
            $_POST['default_order_type'] = isset($_POST['default_order_type']) && in_array($_POST['default_order_type'], ['ASC', 'DESC']) ? $_POST['default_order_type'] : settings()->main->default_order_type;

            /* Custom */
            $_POST['links_default_order_by'] = isset($_POST['links_default_order_by']) && in_array($_POST['links_default_order_by'], ['link_id', 'datetime', 'last_datetime', 'clicks', 'url',]) ? $_POST['links_default_order_by'] : 'link_id';
            $_POST['qr_codes_default_order_by'] = isset($_POST['qr_codes_default_order_by']) && in_array($_POST['qr_codes_default_order_by'], ['qr_code_id', 'datetime', 'last_datetime', 'name', 'type']) ? $_POST['qr_codes_default_order_by'] : 'qr_code_id';

            $_POST['openai_api_key'] = input_clean($_POST['openai_api_key'] ?? null);
            $_POST['clipdrop_api_key'] = input_clean($_POST['clipdrop_api_key'] ?? null);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $preferences = json_encode([
                    'default_results_per_page' => $_POST['default_results_per_page'],
                    'default_order_type' => $_POST['default_order_type'],
                    'links_default_order_by' => $_POST['links_default_order_by'],
                    'qr_codes_default_order_by' => $_POST['qr_codes_default_order_by'],

                    'openai_api_key' => $_POST['openai_api_key'],
                    'clipdrop_api_key' => $_POST['clipdrop_api_key'],
                ]);

                /* Database query */
                db()->where('user_id', $this->user->user_id)->update('users', [
                    'preferences' => $preferences,
                ]);

                /* Set a nice success message */
                Alerts::add_success(l('account_preferences.success_message'));

                /* Clear the cache */
                cache()->deleteItemsByTag('user_id=' . $this->user->user_id);

                /* Send webhook notification if needed */
                if(settings()->webhooks->user_update) {
                    \Unirest\Request::post(settings()->webhooks->user_update, [], [
                        'user_id' => $this->user->user_id,
                        'email' => $this->user->email,
                        'name' => $this->user->name,
                        'source' => 'account_preferences',
                    ]);
                }

                redirect('account-preferences');
            }

        }

        /* Get the account header menu */
        $menu = new \Altum\View('partials/account_header_menu', (array) $this);
        $this->add_view_content('account_header_menu', $menu->run());

        /* Prepare the view */
        $data = [];

        $view = new \Altum\View('account-preferences/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
