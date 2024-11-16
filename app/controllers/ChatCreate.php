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
use Altum\Response;

class ChatCreate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.chats')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('chats');
        }

        /* Check for the plan limit */
        $chats_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_chats_current_month`');
        if($this->user->plan_settings->chats_per_month_limit != -1 && $chats_current_month >= $this->user->plan_settings->chats_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('chats');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Chats assistants */
        $chats_assistants = (new \Altum\Models\ChatsAssistants())->get_chats_assistants();

        $values = [
            'name' => $_POST['name'] ?? $_GET['name'] ?? sprintf(l('chat_create.name_x'), \Altum\Date::get()),
            'chat_assistant_id' => $_GET['chat_assistant_id'] ?? $_POST['chat_assistant_id'] ?? array_key_first($chats_assistants),
        ];

        /* Prepare the view */
        $data = [
            'values' => $values,
            'chats_assistants' => $chats_assistants,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/chat-create/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));

    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.chats')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check for the plan limit */
        $chats_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_chats_current_month`');
        if($this->user->plan_settings->chats_per_month_limit != -1 && $chats_current_month >= $this->user->plan_settings->chats_per_month_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Chats assistants */
        $chats_assistants = (new \Altum\Models\ChatsAssistants())->get_chats_assistants();

        $_POST['name'] = input_clean($_POST['name'], 64);
        $_POST['chat_assistant_id'] = isset($_POST['chat_assistant_id']) && array_key_exists($_POST['chat_assistant_id'], $chats_assistants) ? (int) $_POST['chat_assistant_id'] : array_key_first($chats_assistants);

        /* Check for any errors */
        $required_fields = ['name', 'chat_assistant_id'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        $settings = json_encode([
            'context_length' => 0,
            'creativity_level' => 'optimal',
            'creativity_level_custom' => 0.8,
        ]);

        /* Database query */
        $chat_id = db()->insert('chats', [
            'user_id' => $this->user->user_id,
            'chat_assistant_id' => $_POST['chat_assistant_id'],
            'name' => $_POST['name'],
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Database query */
        db()->where('user_id', $this->user->user_id)->update('users', [
            'aix_chats_current_month' => db()->inc()
        ]);

        /* Database query */
        db()->where('chat_assistant_id', $_POST['chat_assistant_id'])->update('chats_assistants', [
            'total_usage' => db()->inc()
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['url' => url('chat/' . $chat_id)]);

    }

}
