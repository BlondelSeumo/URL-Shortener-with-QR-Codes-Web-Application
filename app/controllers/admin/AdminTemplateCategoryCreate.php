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

class AdminTemplateCategoryCreate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['icon'] = input_clean($_POST['icon'], 64);
            $_POST['emoji'] = input_clean($_POST['emoji'], 64);
            $_POST['color'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['color']) ? '#ffffff' : $_POST['color'];
            $_POST['background'] = !preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['background']) ? '#000000' : $_POST['background'];
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            $settings = json_encode(['translations' => $_POST['translations']]);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->insert('templates_categories', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'icon' => $_POST['icon'],
                    'emoji' => $_POST['emoji'],
                    'color' => $_POST['color'],
                    'background' => $_POST['background'],
                    'order' => $_POST['order'],
                    'is_enabled' => $_POST['is_enabled'],
                    'datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('templates_categories');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('admin/templates-categories');
            }
        }

        /* Main View */
        $data = [];

        $view = new \Altum\View('admin/template-category-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
