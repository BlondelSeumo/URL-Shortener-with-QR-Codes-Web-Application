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

class SplashPageCreate extends Controller {

    public function index() {

        if(!settings()->links->splash_page_is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.splash_pages')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('splash-pages');
        }

        /* Check for the plan limit */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `splash_pages` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;

        if($this->user->plan_settings->splash_pages_limit != -1 && $total_rows >= $this->user->plan_settings->splash_pages_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('splash-pages');
        }

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['title'] = input_clean($_POST['title'], 256);
            $_POST['description'] = input_clean($_POST['description'], 2048);
            $_POST['secondary_button_name'] = input_clean($_POST['secondary_button_name'], 256);
            $_POST['secondary_button_url'] = input_clean($_POST['secondary_button_url'], 1024);
            $_POST['custom_css'] = mb_substr(trim($_POST['custom_css']), 0, 8192);
            $_POST['custom_js'] = mb_substr(trim($_POST['custom_js']), 0, 8192);
            $_POST['ads_header'] = mb_substr(trim($_POST['ads_header']), 0, 8192);
            $_POST['ads_footer'] = mb_substr(trim($_POST['ads_footer']), 0, 8192);
            $_POST['link_unlock_seconds'] = (int) $_POST['link_unlock_seconds'];
            $_POST['auto_redirect'] = (int) isset($_POST['auto_redirect']);

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

            /* Image uploads */
            $logo = \Altum\Uploads::process_upload(null, 'splash_pages', 'logo', 'logo_remove', settings()->links->avatar_size_limit);
            $favicon = \Altum\Uploads::process_upload(null, 'splash_pages', 'favicon', 'favicon_remove', settings()->links->favicon_size_limit);
            $opengraph = \Altum\Uploads::process_upload(null, 'splash_pages', 'opengraph', 'opengraph_remove', settings()->links->seo_image_size_limit);

            /* Check for any errors */
            $required_fields = ['name'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $settings = json_encode([
                    'logo' => $logo,
                    'favicon' => $favicon,
                    'opengraph' => $opengraph,
                    'secondary_button_name' => $_POST['secondary_button_name'],
                    'secondary_button_url' => $_POST['secondary_button_url'],
                    'custom_css' => $_POST['custom_css'],
                    'custom_js' => $_POST['custom_js'],
                    'ads_header' => $_POST['ads_header'],
                    'ads_footer' => $_POST['ads_footer'],
                ]);

                /* Database query */
                db()->insert('splash_pages', [
                    'user_id' => $this->user->user_id,
                    'name' => $_POST['name'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'link_unlock_seconds' => $_POST['link_unlock_seconds'],
                    'auto_redirect' => $_POST['auto_redirect'],
                    'settings' => $settings,
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('splash_pages?user_id=' . $this->user->user_id);

                redirect('splash-pages');
            }
        }

        $values = [
            'name' => $_POST['name'] ?? '',
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'secondary_button_name' => $_POST['secondary_button_name'] ?? '',
            'secondary_button_url' => $_POST['secondary_button_url'] ?? '',
            'link_unlock_seconds' => $_POST['link_unlock_seconds'] ?? 5,
            'auto_redirect' => $_POST['auto_redirect'] ?? false,
            'custom_css' => $_POST['custom_css'] ?? false,
            'custom_js' => $_POST['custom_js'] ?? false,
            'ads_header' => $_POST['ads_header'] ?? false,
            'ads_footer' => $_POST['ads_footer'] ?? false,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values
        ];

        $view = new \Altum\View('splash-page-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
