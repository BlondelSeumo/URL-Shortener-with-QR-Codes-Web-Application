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
use Altum\Title;

class Chat extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('update.chats')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('dashboard');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        $chat_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Get chat details */
        if(!$chat = db()->where('chat_id', $chat_id)->where('user_id', $this->user->user_id)->getOne('chats')) {
            redirect();
        }

        $chat->settings = json_decode($chat->settings ?? '');

        /* Get all the existing chat messages */
        $chat_messages = db()->where('chat_id', $chat->chat_id)->get('chats_messages');

        /* Check for the plan limit */
        $sent_chat_messages = 0;
        foreach($chat_messages as $chat_message) {
            if($chat_message->role == 'user') $sent_chat_messages++;
        }

        /* Chats assistants */
        $chats_assistants = (new \Altum\Models\ChatsAssistants())->get_chats_assistants();
        $chat_assistant = $chats_assistants[$chat->chat_assistant_id];

        /* Set a custom title */
        Title::set(sprintf(l('chat.title'), $chat->name));

        /* Main View */
        $data = [
            'chat' => $chat,
            'chat_assistant' => $chat_assistant,
            'chat_messages' => $chat_messages,
            'sent_chat_messages' => $sent_chat_messages,
            'content' => input_clean($_GET['content'] ?? ''),
            'parsedown' => new \Parsedown(),
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/chat/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        set_time_limit(0);

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->chats_is_enabled) {
            redirect('not-found');
        }

        $_POST['chat_id'] = (int) $_POST['chat_id'];
        $_POST['context_length'] = isset($_POST['context_length']) && in_array($_POST['context_length'], [0, 1, 3, 5, 7, 9]) ? (int) $_POST['context_length'] : 0;
        $_POST['creativity_level'] = $_POST['creativity_level'] && in_array($_POST['creativity_level'], ['none', 'low', 'optimal', 'high', 'maximum', 'custom']) ? $_POST['creativity_level'] : 'optimal';
        $_POST['creativity_level_custom'] = isset($_POST['creativity_level_custom']) ? ((float) $_POST['creativity_level_custom'] < 0 || (float) $_POST['creativity_level_custom'] > 2 ? 0.8 : (float) $_POST['creativity_level_custom']) : null;

        /* Get chat details */
        if(!$chat = db()->where('chat_id', $_POST['chat_id'])->where('user_id', $this->user->user_id)->getOne('chats')) {
            redirect();
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.chats')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Chats assistants */
        $chats_assistants = (new \Altum\Models\ChatsAssistants())->get_chats_assistants();
        $chat_assistant = $chats_assistants[$chat->chat_assistant_id];

        /* Ai image models */
        $ai_chats_models = require \Altum\Plugin::get('aix')->path . 'includes/ai_chat_models.php';

        /* Selected AI model */
        $this->user->plan_settings->chats_model = $this->user->plan_settings->chats_model ?? 'gpt-3.5-turbo-1106';
        $ai_model = $ai_chats_models[$this->user->plan_settings->chats_model];

        /* */
        $_POST['content'] = trim(mb_substr($_POST['content'], 0, 2048));

        /* Vision */
        $image = null;
        if(in_array($this->user->plan_settings->chats_model, ['gpt-4o', 'gpt-4o-mini'])) {
            $image = \Altum\Uploads::process_upload(null, 'chats_images', 'image', 'image_remove', $this->user->plan_settings->chat_image_size_limit, 'json_error');
        }

        /* Check for any errors */
        $required_fields = ['content'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* Check for timeouts */
        if(settings()->aix->input_moderation_is_enabled) {
            $cache_instance = cache()->getItem('user?flagged=' . $this->user->user_id);
            if(!is_null($cache_instance->get())) {
                Response::json(l('documents.error_message.timed_out'), 'error');
            }
        }

        /* Get all the existing chat messages */
        $chat_messages = db()->where('chat_id', $chat->chat_id)->get('chats_messages');

        /* Check for the plan limit */
        $sent_chat_messages = 0;
        foreach($chat_messages as $chat_message) {
            if($chat_message->role == 'user') $sent_chat_messages++;
        }

        if($this->user->plan_settings->chat_messages_per_chat_limit != -1 && $sent_chat_messages >= $this->user->plan_settings->chat_messages_per_chat_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Limit the context if needed */
        if($_POST['context_length'] != 0) {
            $chat_messages = array_slice($chat_messages, -3, 3, false);
        }

        /* Check for moderation */
        if(settings()->aix->input_moderation_is_enabled) {
            try {
                $response = \Unirest\Request::post(
                    'https://api.openai.com/v1/moderations',
                    [
                        'Authorization' => 'Bearer '  . get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->openai_api_key : settings()->aix->openai_api_key),
                        'Content-Type' => 'application/json',
                    ],
                    \Unirest\Request\Body::json([
                        'input' => $_POST['content'],
                    ])
                );

                if($response->code >= 400) {
                    Response::json($response->body->error->message, 'error');
                }

                if($response->body->results[0]->flagged ?? null) {
                    /* Time out the user for a few minutes */
                    cache()->save(
                        $cache_instance->set('true')->expiresAfter(3 * 60)->addTag('user_id=' . $this->user->user_id)
                    );

                    /* Return the error */
                    Response::json(l('chats.error_message.flagged'), 'error');
                }

            } catch (\Exception $exception) {
                Response::json($exception->getMessage(), 'error');
            }
        }


        /* Prepare the main API request */
        $api_endpoint_url = 'https://api.openai.com/v1/chat/completions';

        /* Build the messages array */
        $messages = [
            [
                'role' => 'system',
                'content' => $chat_assistant->prompt
            ]
        ];

        foreach($chat_messages as $chat_message) {
            $messages[] = [
                'role' => $chat_message->role,
                'content' => $chat_message->content,
            ];
        }

        /* Prepare sent content */
        $content = $image ? [
            [
                'type' => 'text',
                'text' => $_POST['content']
            ],
            [
                'type' => 'image_url',
                'image_url' => [
                    'url' => \Altum\Uploads::get_full_url('chats_images') . $image
                ]
            ]
        ] : $_POST['content'];

        $messages[] = [
            'role' => 'user',
            'content' => $content,
        ];

        /* Temperature */
        $temperature = 0.8;
        switch($_POST['creativity_level']) {
            case 'none': $temperature = 0; break;
            case 'low': $temperature = 0.5; break;
            case 'optimal': $temperature = 0.8; break;
            case 'high': $temperature = 1.4; break;
            case 'maximum': $temperature = 2; break;
            case 'custom:': $temperature = number_format($_POST['creativity_level'], 1); break;
        }

        $body = [
            'model' => $this->user->plan_settings->chats_model,
            'messages' => $messages,
            'temperature' => $temperature,
            'user' => 'user_id:' . $this->user->user_id,
        ];

        if(in_array($this->user->plan_settings->chats_model, ['gpt-4o', 'gpt-4o-mini'])) {
            $body['max_tokens'] = $ai_model['max_tokens'];
        }

        /* Try to increase the database timeout as well */
        database()->query("set session wait_timeout=600;");

        /* Do not use sessions anymore to not lockout the user from doing anything else on the site */
        session_write_close();

        try {
            $response = \Unirest\Request::post(
                $api_endpoint_url,
                [
                    'Authorization' => 'Bearer '  . get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->openai_api_key : settings()->aix->openai_api_key),
                    'Content-Type' => 'application/json',
                ],
                \Unirest\Request\Body::json($body)
            );

            if($response->code >= 400) {

                /* Check for context length errors */
                if($response->body->error->code == 'context_length_exceeded') {
                    Response::json('context_length_exceeded', 'error');
                }

                Response::json($response->body->error->message, 'error');
            }

        } catch (\Exception $exception) {
            Response::json($exception->getMessage(), 'error');
        }

        /* Get info after the request */
        $info = \Unirest\Request::getInfo();

        /* Some needed variables */
        $api_response_time = $info['total_time'] * 1000;

        $content = trim($response->body->choices[0]->message->content);
        $role = trim($response->body->choices[0]->message->role);

        /* Database query */
        db()->insert('chats_messages', [
            'user_id' => $this->user->user_id,
            'chat_id' => $chat->chat_id,
            'role' => 'user',
            'content' => $_POST['content'],
            'image' => $image,
            'model' => $response->body->model,
            'api_response_time' => 0,
            'datetime' => get_date(),
        ]);

        /* Database query */
        db()->insert('chats_messages', [
            'user_id' => $this->user->user_id,
            'chat_id' => $chat->chat_id,
            'role' => $role,
            'content' => $content,
            'model' => $response->body->model,
            'api_response_time' => $api_response_time,
            'datetime' => get_date(),
        ]);

        /* Settings */
        $settings = json_encode([
            'context_length' => $_POST['context_length'],
            'creativity_level' => $_POST['creativity_level'],
            'creativity_level_custom' => $_POST['creativity_level_custom'],
        ]);

        /* Database query */
        db()->where('chat_id', $chat->chat_id)->update('chats', [
            'total_messages' => db()->inc(2),
            'settings' => $settings,
            'used_tokens' => db()->inc($response->body->usage->total_tokens)
        ]);

        /* Use parsedown to pars the potential markup */
        $parsedown = new \Parsedown();
        $content = $parsedown->text($content);

        /* Set a nice success message */
        Response::json(
            l('global.success_message.create2'),
            'success',
            [
                'role' => $role,
                'content' => $content,
                'image_url' => $image ? \Altum\Uploads::get_full_url('chats_images') . $image : null,
                'datetime_his' => \Altum\Date::get(get_date(), 3),
                'datetime_full' => \Altum\Date::get(get_date(), 1)
            ]
        );

    }

}
