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

class Syntheses extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->syntheses_is_enabled) {
            redirect('not-found');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'language', 'format', 'voice_id', 'voice_engine', 'voice_gender'], ['name'], ['last_datetime', 'datetime', 'name', 'characters']));
        $filters->set_default_order_by('synthesis_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `syntheses` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('syntheses?' . $filters->get_get() . '&page=%d')));

        /* Get the syntheses */
        $syntheses = [];
        $syntheses_result = database()->query("
            SELECT
                *
            FROM
                `syntheses`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        while($row = $syntheses_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $syntheses[] = $row;
        }

        /* Export handler */
        process_export_csv($syntheses, 'include', ['synthesis_id', 'project_id', 'user_id', 'name', 'input', 'file', 'language', 'format', 'voice_id', 'voice_engine', 'voice_gender',  'characters', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('syntheses.title')));
        process_export_json($syntheses, 'include', ['synthesis_id', 'project_id', 'user_id', 'name', 'input', 'file', 'language', 'format', 'voice_id', 'voice_engine', 'voice_gender', 'settings', 'characters', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('syntheses.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Current month syntheses */
        $syntheses_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_syntheses_current_month`');

        /* Available characters */
        $synthesized_characters_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_synthesized_characters_current_month`');
        $available_characters = $this->user->plan_settings->synthesized_characters_per_month_limit - $synthesized_characters_current_month;

        /* Languages */
        $ai_languages = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_languages.php');

        /* Voices */
        $ai_voices = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_voices.php');

        /* Engines/Models */
        $ai_engines = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_engines.php');

        /* Formats */
        $ai_formats = array_merge([], require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_openai_audio_formats.php');

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'syntheses' => $syntheses,
            'total_syntheses' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'syntheses_current_month' => $syntheses_current_month,
            'synthesized_characters_current_month' => $synthesized_characters_current_month,
            'available_characters' => $available_characters,
            'ai_languages' => $ai_languages,
            'ai_voices' => $ai_voices,
            'ai_engines' => $ai_engines,
            'ai_formats' => $ai_formats,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/syntheses/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->syntheses_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.syntheses')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('syntheses');
        }

        if(empty($_POST)) {
            redirect('syntheses');
        }

        $synthesis_id = (int) query_clean($_POST['synthesis_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$synthesis = db()->where('synthesis_id', $synthesis_id)->where('user_id', $this->user->user_id)->getOne('syntheses', ['synthesis_id', 'name', 'file'])) {
            redirect('syntheses');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($synthesis->file, 'syntheses');

            /* Delete the resource */
            db()->where('synthesis_id', $synthesis_id)->delete('syntheses');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $synthesis->name . '</strong>'));

            redirect('syntheses');
        }

        redirect('syntheses');
    }

}
