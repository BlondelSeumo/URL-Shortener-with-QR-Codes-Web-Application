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

class AdminBiolinkThemeCreate extends Controller {

    public function index() {

        $biolink_fonts = require APP_PATH . 'includes/biolink_fonts.php';
        $biolink_backgrounds = require APP_PATH . 'includes/biolink_backgrounds.php';
        $links_types = require APP_PATH . 'includes/links_types.php';

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for errors & process potential uploads */
            $background_new_name = \Altum\Uploads::process_upload(null, 'biolink_background', 'biolink_background_image', 'background_remove', null);

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                $settings = json_encode([
                    'biolink' => [
                        'background_type' => $_POST['biolink_background_type'] ?? 'preset',
                        'background' => $background_new_name ?? $_POST['biolink_background'] ?? 'one',
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
                db()->insert('biolinks_themes', [
                    'name' => $_POST['name'],
                    'settings' => $settings,
                    'is_enabled' => $_POST['is_enabled'],
                    'order' => $_POST['order'],
                    'datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Clear the cache */
                cache()->deleteItem('biolinks_themes');

                redirect('admin/biolinks-themes');
            }
        }

        $values = [
            'name' => $_POST['name'] ?? null,
            'order' => $_POST['order'] ?? 0,
            'is_enabled' => $_POST['is_enabled'] ?? 1,
            'biolink_background_type' => $_POST['biolink_background_type'] ?? null,
            'biolink_background' => $_POST['biolink_background'] ?? null,
            'biolink_background_color_one' => $_POST['biolink_background_color_one'] ?? null,
            'biolink_background_color_two' => $_POST['biolink_background_color_two'] ?? null,
            'biolink_font' => $_POST['biolink_font'] ?? null,
            'biolink_font_size' => $_POST['biolink_font_size'] ?? 16,
            'biolink_background_blur' => $_POST['biolink_background_blur'] ?? 0,
            'biolink_background_brightness' => $_POST['biolink_background_brightness'] ?? 100,
            'biolink_block_text_color' => $_POST['biolink_block_text_color'] ?? '#ffffff',
            'biolink_block_description_color' => $_POST['biolink_block_description_color'] ?? '#ffffff',
            'biolink_block_background_color' => $_POST['biolink_block_background_color'] ?? '#000000',
            'biolink_block_border_width' => $_POST['biolink_block_border_width'] ?? 0,
            'biolink_block_border_color' => $_POST['biolink_block_border_color'] ?? null,
            'biolink_block_border_radius' => $_POST['biolink_block_border_radius'] ?? null,
            'biolink_block_border_style' => $_POST['biolink_block_border_style'] ?? null,
            'biolink_block_border_shadow_offset_x' => $_POST['biolink_block_border_shadow_offset_x'] ?? 0,
            'biolink_block_border_shadow_offset_y' => $_POST['biolink_block_border_shadow_offset_y'] ?? 0,
            'biolink_block_border_shadow_blur' => $_POST['biolink_block_border_shadow_blur'] ?? 20,
            'biolink_block_border_shadow_spread' => $_POST['biolink_block_border_shadow_spread'] ?? 0,
            'biolink_block_border_shadow_color' => $_POST['biolink_block_border_shadow_color'] ?? '#00000010',
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'biolink_backgrounds' => $biolink_backgrounds,
            'biolink_fonts' => $biolink_fonts,
            'links_types' => $links_types,
        ];

        $view = new \Altum\View('admin/biolink-theme-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
