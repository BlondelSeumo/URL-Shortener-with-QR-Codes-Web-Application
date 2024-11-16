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
use Altum\Title;

class SignatureUpdate extends Controller {

    public function index() {
        if(!\Altum\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.signatures')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        $signature_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get signature details */
        if(!$signature = db()->where('signature_id', $signature_id)->getOne('signatures')) {
            redirect();
        }

        $signature->settings = json_decode($signature->settings ?? '');

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Signature templates */
        $signature_templates = require \Altum\Plugin::get('email-signatures')->path . 'includes/signature_templates.php';

        /* Signature fonts */
        $signature_fonts = require \Altum\Plugin::get('email-signatures')->path . 'includes/signature_fonts.php';

        /* Signature socials */
        $signature_socials = require \Altum\Plugin::get('email-signatures')->path . 'includes/signature_socials.php';

        if(!empty($_POST)) {
            $_POST['name'] = input_clean($_POST['name']);
            $_POST['template'] = array_key_exists($_POST['template'], $signature_templates) ? $_POST['template'] : array_key_first($signature_templates);
            $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
            $_POST['is_removed_branding'] = $this->user->plan_settings->removable_branding ? (bool) $_POST['is_removed_branding'] : 0;

            //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

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
                $_POST['direction'] = in_array($_POST['direction'], ['rtl', 'ltr']) ? $_POST['direction'] : 'ltr';
                $_POST['image_url'] = get_url($_POST['image_url'], 1024);
                $_POST['sign_off'] = input_clean($_POST['sign_off'], 64);
                $_POST['full_name'] = input_clean($_POST['full_name'], 64);
                $_POST['job_title'] = input_clean($_POST['job_title'], 64);
                $_POST['department'] = input_clean($_POST['department'], 64);
                $_POST['company'] = input_clean($_POST['company'], 64);
                $_POST['email'] = input_clean($_POST['email'], 320);
                $_POST['website_url'] = input_clean($_POST['website_url'], 256);
                $_POST['address'] = input_clean($_POST['address'], 256);
                $_POST['address_url'] = input_clean($_POST['address_url'], 512);
                $_POST['phone_number'] = input_clean($_POST['phone_number'], 32);
                $_POST['whatsapp'] = $_POST['whatsapp'] ? (int) input_clean($_POST['whatsapp'], 32) : null;
                $_POST['facebook_messenger'] = input_clean($_POST['facebook_messenger'], 64);
                $_POST['telegram'] = input_clean($_POST['telegram'], 64);
                $_POST['disclaimer'] = input_clean($_POST['disclaimer'], 1024);
                foreach($signature_socials as $key => $social) {
                    $_POST[$key] = input_clean($_POST[$key], $social['value_max_length']);
                }
                $_POST['font_family'] = array_key_exists($_POST['font_family'], $signature_fonts) ? query_clean($_POST['font_family']) : array_key_first($signature_fonts);
                $_POST['font_size'] = (int) $_POST['font_size'] < 12 || (int) $_POST['font_size'] > 18 ? 14 : (int) $_POST['font_size'];
                $_POST['width'] = (int) $_POST['width'] < 300 || (int) $_POST['width'] > 600 ? 500 : (int) $_POST['width'];
                $_POST['image_width'] = (int) $_POST['image_width'] < 45 || (int) $_POST['image_width'] > 150 ? 50 : (int) $_POST['image_width'];
                $_POST['image_border_radius'] = (int) $_POST['image_border_radius'] < 0 || (int) $_POST['image_border_radius'] > 100 ? 0 : (int) $_POST['image_border_radius'];
                $_POST['socials_width'] = (int) $_POST['socials_width'] < 15 || (int) $_POST['socials_width'] > 30 ? 20 : (int) $_POST['socials_width'];
                $_POST['socials_padding'] = (int) $_POST['socials_padding'] < 5 || (int) $_POST['socials_padding'] > 15 ? 10 : (int) $_POST['socials_padding'];
                $_POST['separator_size'] = (int) $_POST['separator_size'] < 0 || (int) $_POST['separator_size'] > 5 ? 1 : (int) $_POST['separator_size'];
                $_POST['full_name_color'] = isset($_POST['full_name_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['full_name_color']) ? $_POST['full_name_color'] : '#000000';
                $_POST['text_color'] = isset($_POST['text_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['text_color']) ? $_POST['text_color'] : '#000000';
                $_POST['link_color'] = isset($_POST['link_color']) && preg_match('/#([A-Fa-f0-9]{3,4}){1,2}\b/i', $_POST['link_color']) ? $_POST['link_color'] : '#000000';

                /* Prepare settings */
                $settings = [
                    'direction' => $_POST['direction'],
                    'is_removed_branding' => $_POST['is_removed_branding'],
                    'image_url' => $_POST['image_url'],
                    'sign_off' => $_POST['sign_off'],
                    'full_name' => $_POST['full_name'],
                    'job_title' => $_POST['job_title'],
                    'department' => $_POST['department'],
                    'company' => $_POST['company'],
                    'email' => $_POST['email'],
                    'website_url' => $_POST['website_url'],
                    'address' => $_POST['address'],
                    'address_url' => $_POST['address_url'],
                    'phone_number' => $_POST['phone_number'],
                    'whatsapp' => $_POST['whatsapp'],
                    'facebook_messenger' => $_POST['facebook_messenger'],
                    'telegram' => $_POST['telegram'],
                    'disclaimer' => $_POST['disclaimer'],
                    'font_family' => $_POST['font_family'],
                    'font_size' => $_POST['font_size'],
                    'width' => $_POST['width'],
                    'image_width' => $_POST['image_width'],
                    'image_border_radius' => $_POST['image_border_radius'],
                    'socials_width' => $_POST['socials_width'],
                    'socials_padding' => $_POST['socials_padding'],
                    'separator_size' => $_POST['separator_size'],
                    'theme_color' => $_POST['theme_color'],
                    'full_name_color' => $_POST['full_name_color'],
                    'text_color' => $_POST['text_color'],
                    'link_color' => $_POST['link_color'],
                ];

                foreach($signature_socials as $key => $social) {
                    $settings[$key] = $_POST[$key];
                }

                $settings = json_encode($settings);

                /* Database query */
                db()->where('signature_id', $signature->signature_id)->update('signatures', [
                    'project_id' => $_POST['project_id'],
                    'name' => $_POST['name'],
                    'template' => $_POST['template'],
                    'settings' => $settings,
                    'last_datetime' => get_date(),
                ]);

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                redirect('signature-update/' . $signature->signature_id);
            }
        }

        /* Set a custom title */
        Title::set(sprintf(l('signature_update.title'), $signature->name));

        /* Main View */
        $data = [
            'signature' => $signature,
            'projects' => $projects ?? [],
            'signature_templates' => $signature_templates,
            'signature_socials' => $signature_socials,
            'signature_fonts' => $signature_fonts,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('email-signatures')->path . 'views/signature-update/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

}
