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
use Altum\Language;

class AdminLanguageUpdate extends Controller {

    public function index() {

        $language_name = isset($this->params[0]) ? $this->params[0] : null;
        $type = isset($this->params[1]) && in_array($this->params[1], ['app', 'admin']) ? $this->params[1] : null;

        /* Check if language exists */
        if(!isset(Language::$languages[$language_name])) {
            redirect('admin/languages');
        }

        /* Make sure to load up in memory the language that is being edited and the main language */
        Language::get(Language::$main_name);
        Language::get($language_name);

        $language = Language::$languages[$language_name];

        function count_matched_translation_variables($string) {
            $re = '/(%\d+\$s|%s)+/';
            return preg_match_all($re, $string, $matches);
        }

        if(!empty($_POST)) {
            /* Clean some posted variables */
            $_POST['language_name'] = input_clean($_POST['language_name']);
            $_POST['language_code'] = trim(mb_strtolower(input_clean($_POST['language_code'])));
            $_POST['language_flag'] = trim(input_clean($_POST['language_flag']));
            $_POST['status'] = isset($_POST['status']) && in_array($_POST['status'], ['active', 'disabled']) ? $_POST['status'] : 'active';
            $_POST['order'] = (int) $_POST['order'];

            /* New language strings content for the translation files */
            $language_strings = '';
            $admin_language_strings = '';

            /* Go through each keys of the original translation file */
            foreach(\Altum\Language::$languages[\Altum\Language::$main_name]['content'] as $key => $value) {
                $form_key = str_replace('.', 'ALTUM', $key);

                /* Check for already existing original translation value */

                /* Check if new translation for the field is submitted */
                if(isset($_POST[$form_key]) && !empty($_POST[$form_key])) {
                    $values[$form_key] = $_POST[$form_key];
                    $_POST[$form_key] = addcslashes($_POST[$form_key], "'");

                    /* Make sure the new translated string contains the required variables if existing */
                    $translated_string = $_POST[$form_key];
                    $original_translation_string = addcslashes(\Altum\Language::$languages[\Altum\Language::$main_name]['content'][$key], "'");

                    /* Revert to default if the required variables are not introduced */
                    if(count_matched_translation_variables($translated_string) != count_matched_translation_variables($original_translation_string)) {
                        $_POST[$form_key] = $original_translation_string;
                    }

                    if(string_starts_with('admin_', $key)) {
                        $admin_language_strings .= "\t'{$key}' => '{$_POST[$form_key]}',\n";
                    } else {
                        $language_strings .= "\t'{$key}' => '{$_POST[$form_key]}',\n";
                    }
                }

                /* Check if the translation already exists in the file, if not submitted in the form */
                else {
                    $translation_exists = array_key_exists($key, $language['content']);

                    if($translation_exists) {
                        $potential_already_existing_value = addcslashes($language['content'][$key], "'");

                        if(string_starts_with('admin_', $key)) {
                            $admin_language_strings .= "\t'{$key}' => '{$potential_already_existing_value}',\n";
                        } else {
                            $language_strings .= "\t'{$key}' => '{$potential_already_existing_value}',\n";
                        }
                    }

                }
            }

            $language_content = function($language_strings) {
                return <<<ALTUM
<?php

return [
{$language_strings}
];
ALTUM;
            };

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            /* Check for any errors */
            $required_fields = ['language_name', 'language_code'];
            foreach($required_fields as $field) {
                if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                    Alerts::add_field_error($field, l('global.error_message.empty_field'));
                }
            }

            if(!is_writable(Language::$path)) {
                Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), Language::$path));
            }

            if(!is_writable(Language::$path . 'admin/')) {
                Alerts::add_error(sprintf(l('global.error_message.directory_not_writable'), Language::$path . 'admin/'));
            }

            if(($_POST['language_name'] != $language_name && in_array($_POST['language_name'], Language::$languages))) {
                Alerts::add_error(sprintf(l('admin_languages.error_message.language_exists'), $_POST['language_name'], $_POST['language_code']));
            }

            if($_POST['language_code'] != $language['code']) {
                foreach(Language::$languages as $lang) {
                    if($lang['code'] == $_POST['language_code']) {
                        Alerts::add_error(sprintf(l('admin_languages.error_message.language_exists'), $_POST['language_name'], $_POST['language_code']));
                        break;
                    }
                }
            }

            /* If there are no errors, continue */
            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                switch($type) {
                    case 'app':
                        file_put_contents(Language::$path . $_POST['language_name'] . '#' . $_POST['language_code'] . '.php', $language_content($language_strings));
                        chmod(Language::$path . $_POST['language_name'] . '#' . $_POST['language_code'] . '.php', 0777);
                        sleep(3);
                        break;

                    case 'admin':
                        file_put_contents(Language::$path . 'admin/' . $_POST['language_name'] . '#' . $_POST['language_code'] . '.php', $language_content($admin_language_strings));
                        chmod(Language::$path . 'admin/' . $_POST['language_name'] . '#' . $_POST['language_code'] . '.php', 0777);
                        sleep(3);
                        break;

                    default:

                        /* Change the name of the file if needed */
                        if($_POST['language_code'] != $language['code'] || $_POST['language_name'] != $language['name']) {
                            if(file_exists(Language::$path . $language['name'] . '#' . $language['code'] . '.php')) {
                                rename(Language::$path . $language['name'] . '#' . $language['code'] . '.php', Language::$path . $_POST['language_name'] . '#' . $_POST['language_code'] . '.php');
                                rename(Language::$path . 'admin/' . $language['name'] . '#' . $language['code'] . '.php', Language::$path . 'admin/' . $_POST['language_name'] . '#' . $_POST['language_code'] . '.php');
                            }
                        }

                        /* Update all languages in the settings table */
                        $settings_languages = [];
                        foreach(Language::$languages as $lang) {
                            $settings_languages[$lang['name']] = [
                                'status' => $lang['name'] == $_POST['language_name'] ? $_POST['status'] : (settings()->languages->{$lang['name']}->status ?? 'active'),
                                'order' => $lang['name'] == $_POST['language_name'] ? $_POST['order'] : (settings()->languages->{$lang['name']}->order ?? 1),
                                'language_flag' => $lang['name'] == $_POST['language_name'] ? $_POST['language_flag'] : (settings()->languages->{$lang['name']}->language_flag ?? ''),
                            ];
                        }

                        /* Update the database */
                        db()->where('`key`', 'languages')->update('settings', ['value' => json_encode($settings_languages)]);

                        /* Clear the cache */
                        cache()->deleteItem('settings');

                        break;
                }

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['language_name'] . '</strong>'));

                /* Redirect */
                redirect('admin/language-update/' . replace_space_with_plus($_POST['language_name']) . '/' . $type);
            }

        }

        /* Main View */
        $data = [
            'language' => $language,
            'type' => $type,
        ];

        $view = new \Altum\View('admin/language-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
