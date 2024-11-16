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

class ImageCreate extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.images')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('images');
        }

        /* Check for the plan limit */
        $images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_images_current_month`');
        if($this->user->plan_settings->images_per_month_limit != -1 && $images_current_month >= $this->user->plan_settings->images_per_month_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('images');
        }

        /* Check for exclusive personal API usage limitation */
        $api_key = $this->user->plan_settings->images_api == 'clipdrop' ? 'clipdrop_api_key' : 'openai_api_key';
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->{$api_key})) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.' . $api_key), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Ai image models */
        $ai_image_models = require \Altum\Plugin::get('aix')->path . 'includes/ai_image_models.php';

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Lighting */
        $ai_images_lighting = require \Altum\Plugin::get('aix')->path . 'includes/ai_images_lighting.php';

        /* Style */
        $ai_images_styles = require \Altum\Plugin::get('aix')->path . 'includes/ai_images_styles.php';

        /* Mood */
        $ai_images_moods = require \Altum\Plugin::get('aix')->path . 'includes/ai_images_moods.php';

        /* Selected AI model */
        $this->user->plan_settings->images_api = $this->user->plan_settings->images_api ?? 'dall_e_2';
        $ai_model = $ai_image_models[$this->user->plan_settings->images_api];

        /* Clear $_GET */
        foreach($_GET as $key => $value) {
            $_GET[$key] = input_clean($value);
        }

        $values = [
            'name' => $_POST['name'] ?? $_GET['name'] ?? sprintf(l('image_create.name_x'), \Altum\Date::get()),
            'input' => $_GET['input'] ?? $_POST['input'] ?? '',
            'style' => $_GET['style'] ?? $_POST['style'] ?? null,
            'artist' => $_GET['artist'] ?? $_POST['artist'] ?? null,
            'lighting' => $_GET['lighting'] ?? $_POST['lighting'] ?? null,
            'mood' => $_GET['mood'] ?? $_POST['mood'] ?? null,
            'size' => $_GET['size'] ?? $_POST['size'] ?? reset($ai_model['sizes']),
            'variants' => $_GET['variants'] ?? $_POST['variants'] ?? 1,
            'project_id' => $_GET['project_id'] ?? $_POST['project_id'] ?? null,
        ];

        /* Prepare the view */
        $data = [
            'values' => $values,
            'ai_images_lighting' => $ai_images_lighting,
            'ai_images_styles' => $ai_images_styles,
            'ai_images_moods' => $ai_images_moods,
            'ai_model' => $ai_model,
            'projects' => $projects ?? [],
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/image-create/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));

    }

    public function create_ajax() {
        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Response::json('Please create an account on the demo to test out this function.', 'error');

        if(empty($_POST)) {
            redirect();
        }

        set_time_limit(0);

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->images_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.images')) {
            Response::json(l('global.info_message.team_no_access'), 'error');
        }

        /* Check for the plan limit */
        $images_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_images_current_month`');
        if($this->user->plan_settings->images_per_month_limit != -1 && $images_current_month >= $this->user->plan_settings->images_per_month_limit) {
            Response::json(l('global.info_message.plan_feature_limit'), 'error');
        }

        /* Get available projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Ai image models */
        $ai_image_models = require \Altum\Plugin::get('aix')->path . 'includes/ai_image_models.php';

        /* Lighting */
        $ai_images_lighting = require \Altum\Plugin::get('aix')->path . 'includes/ai_images_lighting.php';

        /* Style */
        $ai_images_styles = require \Altum\Plugin::get('aix')->path . 'includes/ai_images_styles.php';

        /* Mood */
        $ai_images_moods = require \Altum\Plugin::get('aix')->path . 'includes/ai_images_moods.php';

        /* Selected AI model */
        $this->user->plan_settings->images_api = $this->user->plan_settings->images_api ?? 'dall_e_2';
        $ai_model = $ai_image_models[$this->user->plan_settings->images_api];

        $_POST['name'] = input_clean($_POST['name'], 64);
        $_POST['input'] = input_clean($_POST['input'], $ai_model['max_length']);
        $_POST['size'] = $_POST['size'] && in_array($_POST['size'], $ai_model['sizes']) ? $_POST['size'] : reset($ai_model['sizes']);
        $_POST['variants'] = (int) $_POST['variants'] < 0 || (int) $_POST['variants'] > reset($ai_model['variants']) ? 1 : (int) $_POST['variants'];
        $_POST['project_id'] = !empty($_POST['project_id']) && array_key_exists($_POST['project_id'], $projects) ? (int) $_POST['project_id'] : null;
        $_POST['artist'] = !empty($_POST['artist']) && in_array($_POST['artist'], settings()->aix->images_available_artists) ? $_POST['artist'] : null;
        $_POST['lighting'] = !empty($_POST['lighting']) && array_key_exists($_POST['lighting'], $ai_images_lighting) ? $_POST['lighting'] : null;
        $_POST['style'] = !empty($_POST['style']) && array_key_exists($_POST['style'], $ai_images_styles) ? $_POST['style'] : null;
        $_POST['mood'] = !empty($_POST['mood']) && array_key_exists($_POST['mood'], $ai_images_moods) ? $_POST['mood'] : null;

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

        if(!is_writable(UPLOADS_PATH . Uploads::get_path('images'))) {
            Response::json(sprintf(l('global.error_message.directory_not_writable'), UPLOADS_PATH . Uploads::get_path('images')), 'error');
        }

        /* Check for timeouts */
        if(settings()->aix->input_moderation_is_enabled) {
        $cache_instance = cache()->getItem('user?flagged=' . $this->user->user_id);
            if(!is_null($cache_instance->get())) {
                Response::json(l('documents.error_message.timed_out'), 'error');
            }
        }

        /* Input modification */
        $input = $_POST['input'];

        if($_POST['style']) {
            $input .= ', ' . $ai_images_styles[$_POST['style']] . ' style';
        }

        if($_POST['artist']) {
            $input .= ', by ' . $_POST['artist'];
        }

        if($_POST['lighting']) {
            $input .= ', ' . $ai_images_lighting[$_POST['lighting']] . ' lighting';
        }

        if($_POST['mood']) {
            $input .= ', ' . $ai_images_moods[$_POST['mood']] . ' mood';
        }

        /* Try to increase the database timeout as well */
        database()->query("set session wait_timeout=600;");

        /* Do not use sessions anymore to not lockout the user from doing anything else on the site */
        session_write_close();

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
                        'input' => $input,
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
                    Response::json(l('documents.error_message.flagged'), 'error');
                }

            } catch (\Exception $exception) {
                Response::json($exception->getMessage(), 'error');
            }
        }

        /* Variants */
        $variants_ids = [];

        /* Request based on the chosen API */
        switch($this->user->plan_settings->images_api) {
            case 'dall-e-2':
            case 'dall-e-3':

                try {
                    $response = \Unirest\Request::post(
                        'https://api.openai.com/v1/images/generations',
                        [
                            'Authorization' => 'Bearer '  . get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->openai_api_key : settings()->aix->openai_api_key),
                            'Content-Type' => 'application/json',
                        ],
                        \Unirest\Request\Body::json([
                            'model' => $this->user->plan_settings->images_api,
                            'prompt' => $input,
                            'size' => $_POST['size'],
                            'n' => $_POST['variants'],
                            'quality' => 'hd',
                            'response_format' => 'b64_json',
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

                /* Go through each result */
                foreach($response->body->data as $key => $result) {
                    /* Save the image temporarily */
                    $temp_image_name = md5(uniqid()) . '.png';
                    file_put_contents(Uploads::get_full_path('images') . $temp_image_name , base64_decode($result->b64_json));

                    /* Fake uploaded image */
                    $_FILES['image'] = [
                        'name' => 'altum.png',
                        'tmp_name' => Uploads::get_full_path('images') . $temp_image_name,
                        'error' => null,
                        'size' => 0,
                    ];

                    $image = \Altum\Uploads::process_upload_fake('images', 'image', 'json_error', null);

                    $settings = json_encode([
                        'variants' => $_POST['variants'],
                    ]);

                    /* Prepare a custom name if needed */
                    $name = $_POST['name'];

                    if($_POST['variants'] > 1) {
                        $name .= ' - ' . ($key + 1) . '/' . count($response->body->data);
                    }

                    /* Database query */
                    $image_id = db()->insert('images', [
                        'user_id' => $this->user->user_id,
                        'project_id' => $_POST['project_id'],
                        'name' => $name,
                        'input' => $_POST['input'],
                        'image' => $image,
                        'style' => $_POST['style'],
                        'artist' => $_POST['artist'],
                        'lighting' => $_POST['lighting'],
                        'mood' => $_POST['mood'],
                        'size' => $_POST['size'],
                        'settings' => $settings,
                        'api' => $this->user->plan_settings->images_api,
                        'api_response_time' => $api_response_time,
                        'datetime' => get_date(),
                    ]);

                    /* Add variant to the array */
                    $variants_ids[] = $image_id;
                }

                break;

            case 'clipdrop':

                //$width = $height = strstr($_POST['size'], 'x', true);

                try {
                    $response = \Unirest\Request::post(
                        'https://clipdrop-api.co/text-to-image/v1',
                        [
                            'x-api-key' => get_random_line_from_text($this->user->plan_settings->exclusive_personal_api_keys ? $this->user->preferences->clipdrop_api_key : settings()->aix->clipdrop_api_key),
                        ],
                        \Unirest\Request\Body::multipart([
                            'prompt' => $input,
                            //'width' => $width,
                            //'height' => $height,
                            //'samples' => $_POST['variants'],
                        ])
                    );

                    if($response->code >= 400) {
                        Response::json($response->body->error, 'error');
                    }

                } catch (\Exception $exception) {
                    Response::json($exception->getMessage(), 'error');
                }

                /* Get info after the request */
                $info = \Unirest\Request::getInfo();

                /* Some needed variables */
                $api_response_time = $info['total_time'] * 1000;

                /* Save the image temporarily */
                $temp_image_name = md5(uniqid()) . '.png';
                file_put_contents(Uploads::get_full_path('images') . $temp_image_name , $response->raw_body);

                /* Fake uploaded image */
                $_FILES['image'] = [
                    'name' => 'altum.png',
                    'tmp_name' => Uploads::get_full_path('images') . $temp_image_name,
                    'error' => null,
                    'size' => 0,
                ];

                $image = \Altum\Uploads::process_upload_fake('images', 'image', 'json_error', null);

                $settings = json_encode([
                    'variants' => $_POST['variants'],
                ]);

                /* Prepare a custom name if needed */
                $name = $_POST['name'];

                /* Database query */
                $image_id = db()->insert('images', [
                    'user_id' => $this->user->user_id,
                    'project_id' => $_POST['project_id'],
                    'name' => $name,
                    'input' => $_POST['input'],
                    'image' => $image,
                    'style' => $_POST['style'],
                    'artist' => $_POST['artist'],
                    'lighting' => $_POST['lighting'],
                    'mood' => $_POST['mood'],
                    'size' => $_POST['size'],
                    'settings' => $settings,
                    'api' => $this->user->plan_settings->images_api,
                    'api_response_time' => $api_response_time,
                    'datetime' => get_date(),
                ]);

                /* Add variant to the array */
                $variants_ids[] = $image_id;

                break;
        }

        /* Go through each generated image to link them up */
        $variants_ids_jsoned = json_encode($variants_ids);
        foreach($variants_ids as $image_id) {
            db()->where('image_id', $image_id)->update('images', [
                'variants_ids' => $variants_ids_jsoned,
            ]);
        }

        /* Database query */
        db()->where('user_id', $this->user->user_id)->update('users', [
            'aix_images_current_month' => db()->inc($_POST['variants'])
        ]);

        /* Set a nice success message */
        Response::json(sprintf(l('global.success_message.create1'), '<strong>' . $_POST['name'] . '</strong>'), 'success', ['url' => url('image-update/' . $image_id)]);

    }

}
