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
use Altum\Response;
use Altum\Uploads;

class SynthesisCreate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->syntheses_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.syntheses')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('syntheses');
        }

        /* Check for the plan limit */
        $syntheses_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_syntheses_current_month`');
        if($this->user->plan_settings->syntheses_per_month_limit != -1 && $syntheses_current_month >= $this->user->plan_settings->syntheses_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('syntheses');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* AI Syntheses */
        $ai_syntheses_apis = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_apis.php';

        /* Selected AI model */
        $this->user->plan_settings->syntheses_api = $this->user->plan_settings->syntheses_api ?? 'aws_polly';
        $ai_api = $ai_syntheses_apis[$this->user->plan_settings->syntheses_api];

        /* Languages */
        $ai_languages = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_languages.php';

        /* Voices */
        $ai_voices = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_voices.php';

        /* Engines/Models */
        $ai_engines = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_engines.php';

        /* Formats */
        $ai_formats = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_formats.php';

        /* Clear $_GET */
        foreach($_GET as $key => $value) {
            $_GET[$key] = input_clean($value);
        }

        $values = [
            'name' => $_POST['name'] ?? $_GET['name'] ?? sprintf(l('synthesis_create.name_x'), \Altum\Date::get()),
            'input' => $_GET['input'] ?? $_POST['input'] ?? '',
            'language' => $_GET['language'] ?? $_POST['language'] ?? 'en-US',
            'voice_id' => $_GET['voice_id'] ?? $_POST['voice_id'] ?? 'Joanna',
            'voice_engine' => $_GET['voice_engine'] ?? $_POST['voice_engine'] ?? reset($ai_engines),
            'format' => $_GET['format'] ?? $_POST['format'] ?? array_key_first($ai_formats),
            'project_id' => $_GET['project_id'] ?? $_POST['project_id'] ?? null,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values,
            'ai_languages' => $ai_languages,
            'ai_engines' => $ai_engines,
            'ai_voices' => $ai_voices,
            'ai_formats' => $ai_formats,
            'projects' => $projects ?? [],
            'ai_api' => $ai_api,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/synthesis-create/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));

    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        set_time_limit(0);

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->syntheses_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.syntheses')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        $_POST['name'] = input_clean($_POST['name'], 64);
        $_POST['input'] = trim(strip_tags(mb_substr($_POST['input'], 0, 6000)));
        $characters = mb_strlen($_POST['input']);

        /* Check for the plan limit */
        $syntheses_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_syntheses_current_month`');
        if($this->user->plan_settings->syntheses_per_month_limit != -1 && $syntheses_current_month >= $this->user->plan_settings->syntheses_per_month_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Check for the plan limit */
        $synthesized_characters_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_synthesized_characters_current_month`');
        if($this->user->plan_settings->synthesized_characters_per_month_limit != -1 && $synthesized_characters_current_month + $characters >= $this->user->plan_settings->synthesized_characters_per_month_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* AI Syntheses */
        $ai_syntheses_apis = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_apis.php';

        /* Selected AI model */
        $this->user->plan_settings->syntheses_api = $this->user->plan_settings->syntheses_api ?? 'aws_polly';
        $ai_api = $ai_syntheses_apis[$this->user->plan_settings->syntheses_api];

        /* Languages */
        $ai_languages = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_languages.php';

        /* Voices */
        $ai_voices = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_voices.php';

        /* Engines/Models */
        $ai_engines = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_engines.php';

        /* Formats */
        $ai_formats = require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_' . $this->user->plan_settings->syntheses_api . '_formats.php';

        /* Filter some the variables */
        $_POST['language'] = !empty($_POST['language']) && array_key_exists($_POST['language'], $ai_languages) ? $_POST['language'] : 'en-US';
        $_POST['voice_id'] = !empty($_POST['voice_id']) && array_key_exists($_POST['voice_id'], $ai_voices) ? $_POST['voice_id'] : 'Joanna';
        $_POST['voice_engine'] = !empty($_POST['voice_engine']) && in_array($_POST['voice_engine'], $ai_engines) ? $_POST['voice_engine'] : reset($ai_engines);
        $_POST['format'] = !empty($_POST['format']) && array_key_exists($_POST['format'], $ai_formats) ? $_POST['format'] : array_key_first($ai_formats);
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;

        /* Check for any errors */
        $required_fields = ['name', 'input'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                Response::json(l('global.error_message.empty_fields'), 'error');
            }
        }

        if(!\Altum\Csrf::check('global_token')) {
            Response::json(l('global.error_message.invalid_csrf_token'), 'error');
        }

        if(!is_writable(UPLOADS_PATH . Uploads::get_path('syntheses'))) {
            Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . Uploads::get_path('syntheses')), 'error');
        }

        /* Try to increase the database timeout as well */
        database()->query("set session wait_timeout=600;");

        /* Do not use sessions anymore to not lockout the user from doing anything else on the site */
        session_write_close();

        switch($this->user->plan_settings->syntheses_api) {
            case 'openai_audio':

                try {
                    $response = \Unirest\Request::post(
                        'https://api.openai.com/v1/audio/speech',
                        [
                            'Authorization' => 'Bearer '  . get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->openai_api_key : settings()->aix->openai_api_key),
                            'Content-Type' => 'application/json',
                        ],
                        \Unirest\Request\Body::json([
                            'model' => $_POST['voice_engine'],
                            'input' => $_POST['input'],
                            'voice' => $_POST['voice_id'],
                            'response_format' => $_POST['format'],
                            'user' => 'user_id:' . $this->user->user_id,
                        ])
                    );

                    if($response->code >= 400) {
                        Response::json($response->body->error->message, 'error');
                    }

                } catch (\Exception $exception) {
                    Response::json($exception->getMessage(), 'error');
                }

                /* Get info after the request */
                $info = \Unirest\Request::getInfo();

                /* Some needed variables */
                $api_response_time = $info['total_time'] * 1000;

                /* Save the synthesis temporarily */
                $temp_synthesis_name = md5(uniqid()) . '.' . $_POST['format'];
                file_put_contents(Uploads::get_full_path('syntheses') . $temp_synthesis_name , $response->raw_body);

                break;

            case 'aws_polly':

                $client = new \Aws\Polly\PollyClient([
                    'version' => 'latest',
                    'region' => settings()->aix->region,
                    'credentials' => [
                        'key' => settings()->aix->access_key,
                        'secret' => settings()->aix->secret_access_key,
                    ],
                ]);

                try {
                    $time_start = microtime(true);

                    $result = $client->synthesizeSpeech([
                        'Engine' => $_POST['voice_engine'],
                        'Text' => $_POST['input'],
                        'OutputFormat' => $_POST['format'],
                        'VoiceId' => $_POST['voice_id'],
                        'languageCode' => $_POST['language'],
                    ]);

                    $result_data = $result->get('AudioStream')->getContents();

                    $time_end = microtime(true);
                } catch (\Aws\Exception\AwsException $exception) {
                    Response::json($exception->getMessage(), 'error');
                }

                /* Some needed variables */
                $api_response_time = floor(($time_end - $time_start) * 1000);

                /* Save the synthesis temporarily */
                $temp_synthesis_name = md5(uniqid()) . '.' . $_POST['format'];
                file_put_contents(Uploads::get_full_path('syntheses') . $temp_synthesis_name , $result_data);

                break;
        }

        /* Fake uploaded synthesis */
        $_FILES['synthesis'] = [
            'name' => 'altum.' . $ai_formats[$_POST['format']],
            'tmp_name' => Uploads::get_full_path('syntheses') . $temp_synthesis_name,
            'error' => null,
            'size' => 0,
        ];

        $file = \Altum\Uploads::process_upload_fake('syntheses', 'synthesis', 'json_error', null);
        sleep(1);

        $settings = json_encode([]);

        /* Prepare a custom name if needed */
        $name = $_POST['name'];

        /* Database query */
        $synthesis_id = db()->insert('syntheses', [
            'user_id' => $this->user->user_id,
            'project_id' => $_POST['project_id'],
            'name' => $name,
            'input' => $_POST['input'],
            'file' => $file,
            'language' => $_POST['language'],
            'format' => $_POST['format'],
            'voice_id' => $_POST['voice_id'],
            'voice_gender' => $ai_voices[$_POST['voice_id']]['gender'],
            'voice_engine' => $_POST['voice_engine'],
            'settings' => $settings,
            'characters' => $characters,
            'api_response_time' => $api_response_time,
            'datetime' => get_date(),
        ]);

        /* Database query */
        db()->where('user_id', $this->user->user_id)->update('users', [
            'aix_syntheses_current_month' => db()->inc(),
            'aix_synthesized_characters_current_month' => db()->inc($characters)
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['url' => url('synthesis-update/' . $synthesis_id)]);

    }

}
