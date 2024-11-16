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
use Altum\Uploads;

class TranscriptionCreate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->transcriptions_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.transcriptions')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('transcriptions');
        }

        /* Check for the plan limit */
        $transcriptions_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_transcriptions_current_month`');
        if($this->user->plan_settings->transcriptions_per_month_limit != -1 && $transcriptions_current_month >= $this->user->plan_settings->transcriptions_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('transcriptions');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Clear $_GET */
        foreach($_GET as $key => $value) {
            $_GET[$key] = input_clean($value);
        }

        $values = [
            'name' => $_POST['name'] ?? $_GET['name'] ?? sprintf(l('transcription_create.name_x'), \Altum\Date::get()),
            'input' => $_GET['input'] ?? $_POST['input'] ?? '',
            'language' => $_GET['language'] ?? $_POST['language'] ?? null,
            'project_id' => $_GET['project_id'] ?? $_POST['project_id'] ?? null,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values,
            'projects' => $projects ?? [],
            'ai_transcriptions_languages' => require \Altum\Plugin::get('aix')->path . 'includes/ai_transcriptions_languages.php',
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/transcription-create/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));

    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        set_time_limit(0);

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->transcriptions_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.transcriptions')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check for the plan limit */
        $transcriptions_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_transcriptions_current_month`');
        if($this->user->plan_settings->transcriptions_per_month_limit != -1 && $transcriptions_current_month >= $this->user->plan_settings->transcriptions_per_month_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Languages */
        $ai_transcriptions_languages = require \Altum\Plugin::get('aix')->path . 'includes/ai_transcriptions_languages.php';

        $_POST['name'] = input_clean($_POST['name'], 64);
        $_POST['input'] = input_clean($_POST['input'], 1000);
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
        $_POST['language'] = !empty($_POST['language']) && array_key_exists($_POST['language'], $ai_transcriptions_languages) ? $_POST['language'] : null;

        /* Check for any errors */
        $required_fields = ['name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(empty($_FILES['file']['name'])) {
            Response::json(l('global.error_message.empty_fields'), 'error');
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        /* Process the uploaded file */
        $file_extension = explode('.', $_FILES['file']['name']);
        $file_extension = mb_strtolower(end($file_extension));
        $file_temp = $_FILES['file']['tmp_name'];

        /* Check for any file errors */
        if($_FILES['file']['error'] == UPLOAD_ERR_INI_SIZE) {
            Response::json(sprintf(l('global.error_message.file_size_limit'), get_max_upload()), 'error');
        }

        if($_FILES['file']['error'] && $_FILES['file']['error'] != UPLOAD_ERR_INI_SIZE) {
            Response::json(l('global.error_message.file_upload'), 'error');
        }

        if(!in_array($file_extension, Uploads::get_whitelisted_file_extensions('transcriptions'))) {
            Response::json(l('global.error_message.invalid_file_type'), 'error');
        }

        if($_FILES['file']['size'] > $this->user->plan_settings->transcriptions_file_size_limit * 1000000) {
            Response::json(sprintf(l('global.error_message.file_size_limit'), $this->user->plan_settings->transcriptions_file_size_limit), 'error');
        }

        /* Generate new name for file */
        $file_new_name = md5(time() . rand() . rand()) . '.' . $file_extension;

        /* Upload the original */
        move_uploaded_file($file_temp, UPLOADS_PATH . Uploads::get_path('transcriptions') . $file_new_name);

        /* Try to increase the database timeout as well */
        database()->query("set session wait_timeout=600;");

        /* Do not use sessions anymore to not lockout the user from doing anything else on the site */
        session_write_close();

        try {
            $response = \Unirest\Request::post(
                'https://api.openai.com/v1/audio/transcriptions',
                [
                    'Authorization' => 'Bearer '  . get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->openai_api_key : settings()->aix->openai_api_key),
                    'Content-Type' => 'multipart/form-data',
                ],
                \Unirest\Request\Body::multipart([
                    'model' => 'whisper-1',
                    'prompt' => $_POST['input'],
                    'language' => $_POST['language'],
                    'user' => 'user_id:' . $this->user->user_id,
                ], ['file' => UPLOADS_PATH . Uploads::get_path('transcriptions') . $file_new_name])
            );

            if($response->code >= 400) {
                /* Delete temp */
                unlink(UPLOADS_PATH . Uploads::get_path('transcriptions') . $file_new_name);

                Response::json($response->body->error->message, 'error');
            }

        } catch (\Exception $exception) {
            /* Delete temp */
            unlink(UPLOADS_PATH . Uploads::get_path('transcriptions') . $file_new_name);

            Response::json($exception->getMessage(), 'error');
        }

        /* Parse response */
        $content = $response->body->text;
        $words = count(explode(' ', ($content)));

        $settings = json_encode([]);

        /* Database query */
        $transcription_id = db()->insert('transcriptions', [
            'user_id' => $this->user->user_id,
            'project_id' => $_POST['project_id'],
            'name' => $_POST['name'],
            'input' => $_POST['input'],
            'content' => $content,
            'words' => $words,
            'language' => $_POST['language'],
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Delete temp */
        unlink(UPLOADS_PATH . Uploads::get_path('transcriptions') . $file_new_name);

        /* Database query */
        db()->where('user_id', $this->user->user_id)->update('users', [
            'aix_transcriptions_current_month' => db()->inc(1)
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['url' => url('transcription-update/' . $transcription_id)]);

    }

}
