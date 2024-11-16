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

class AdminChatAssistantUpdate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        $chat_assistant_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        if(!$chat_assistant = db()->where('chat_assistant_id', $chat_assistant_id)->getOne('chats_assistants')) {
            redirect('admin/chat_assistant-categories');
        }

        $chat_assistant->settings = json_decode($chat_assistant->settings ?? '');

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['prompt'] = input_clean($_POST['prompt'], 5000);
            $_POST['order'] = (int) $_POST['order'] ?? 0;
            $_POST['is_enabled'] = (int) isset($_POST['is_enabled']);

            /* Translations */
            foreach($_POST['translations'] as $language_name => $array) {
                foreach($array as $key => $value) {
                    $_POST['translations'][$language_name][$key] = input_clean($value);
                }
                if(!array_key_exists($language_name, \Altum\Language::$active_languages)) {
                    unset($_POST['translations'][$language_name]);
                }
            }

            /* Prepare settings JSON */
            $settings = json_encode([
                'translations' => $_POST['translations'],
            ]);

            $image = \Altum\Uploads::process_upload($chat_assistant->image, 'chats_assistants', 'image', 'image_remove', null);

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Database query */
                db()->where('chat_assistant_id', $chat_assistant_id)->update('chats_assistants', [
                    'name' => $_POST['name'],
                    'prompt' => $_POST['prompt'],
                    'settings' => $settings,
                    'image' => $image,
                    'order' => $_POST['order'],
                    'is_enabled' => $_POST['is_enabled'],
                    'last_datetime' => get_date(),
                ]);

                /* Clear the cache */
                cache()->deleteItem('chats_assistants');

                /* Set a nice success message */
                Alerts::add_success(sprintf(l('global.success_message.update1'), '<strong>' . $_POST['name'] . '</strong>'));

                /* Refresh the page */
                redirect('admin/chat-assistant-update/' . $chat_assistant_id);

            }

        }

        /* Main View */
        $data = [
            'chat_assistant_id' => $chat_assistant_id,
            'chat_assistant' => $chat_assistant,
        ];

        $view = new \Altum\View('admin/chat-assistant-update/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
