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

class AdminBiolinkThemeUpdate extends Controller {

    public function index() {

        $biolink_theme_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$biolink_theme = db()->where('biolink_theme_id', $biolink_theme_id)->getOne('biolinks_themes')) {
            redirect('admin/biolinks-themes');
        }
        $biolink_theme->settings = json_decode($biolink_theme->settings ?? '');

        $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php';
        $biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';
        $links_types = require APP_PATH . 'includes/links_types.php';

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            /* Check for errors & process  potential uploads */
            $background_new_name = $biolink_theme->settings->biolink->background_type == 'image' ? $biolink_theme->settings->biolink->background : null;

            if($_POST['biolink_background_type'] == 'image') {
                $background_new_name = \Altum\Uploads::process_upload($biolink_theme->settings->biolink->background, 'biolink_background', 'biolink_background_image', 'background_remove', null);
            }

            if($background_new_name && $_POST['biolink_background_type'] != 'image') {
                $background_new_name = null;
                \Altum\Uploads::delete_uploaded_file($background_new_name, 'biolink_background');
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {
                $biolink_background = $background_new_name ?? $_POST['biolink_background'] ?? null;

                $settings = json_encode([
                    'biolink' => [
                        'background_type' => $_POST['biolink_background_type'],
                        'background' => $biolink_background,
                        'background_color_one' => $_POST['biolink_background_color_one'],
                        'background_color_two' => $_POST['biolink_background_color_two'],
                        'font' => $_POST['biolink_font'],
                        'font_size' => $_POST['biolink_font_size'],
                        'background_blur' => (int) $_POST['biolink_background_blur'],
                        'background_brightness' => (int) $_POST['biolink_background_brightness'],
                    ],

                    'biolink_block' => [
                        'text_color' => $_POST['biolink_block_text_color'],
                        'description_color' => $_POST['biolink_block_description_color'],
                        'background_color' => $_POST['biolink_block_background_color'],
                        'border_width' => $_POST['biolink_block_border_width'],
                        'border_color' => $_POST['biolink_block_border_color'],
                        'border_radius' => $_POST['biolink_block_border_radius'],
                        'border_style' => $_POST['biolink_block_border_style'],
                        'border_shadow_offset_x' => $_POST['biolink_block_border_shadow_offset_x'],
                        'border_shadow_offset_y' => $_POST['biolink_block_border_shadow_offset_y'],
                        'border_shadow_blur' => $_POST['biolink_block_border_shadow_blur'],
                        'border_shadow_spread' => $_POST['biolink_block_border_shadow_spread'],
                        'border_shadow_color' => $_POST['biolink_block_border_shadow_color'],
                    ]
                ]);

                /* Database query */
                db()->where('biolink_theme_id', $biolink_theme_id)->update('biolinks_themes', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('biolinks_themes');

                /* Refresh the page */
                redirect('admin/biolink-theme-update/' . $biolink_theme_id);

            }

        }

        /* Main View */
        $data = [
            'biolink_theme_id' => $biolink_theme_id,
            'biolink_theme' => $biolink_theme,
            'biolink_backgrounds' => $biolink_backgrounds,
            'biolink_fonts' => $biolink_fonts,
            'links_types' => $links_types,
        ];

        $view = new \Altum\View('admin/biolink-theme-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
