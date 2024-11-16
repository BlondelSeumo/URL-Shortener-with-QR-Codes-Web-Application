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

class Transcriptions extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->transcriptions_is_enabled) {
            redirect('not-found');
        }

        /* Check for exclusive personal API usage limitation */
        if($this->user->plan_settings->exclusive_personal_api_keys && empty($this->user->preferences->openai_api_key)) {
            Alerts::add_error(sprintf(l('account_preferences.error_message.aix.openai_api_key'), '<a href="' . url('account-preferences') . '"><strong>' . l('account_preferences.menu') . '</strong></a>'));
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'language'], ['name'], ['last_datetime', 'datetime', 'name', 'words']));
        $filters->set_default_order_by('transcription_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `transcriptions` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('transcriptions?' . $filters->get_get() . '&page=%d')));

        /* Get the transcriptions */
        $transcriptions = [];
        $transcriptions_result = database()->query("
            SELECT
                *
            FROM
                `transcriptions`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $transcriptions_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $transcriptions[] = $row;
        }

        /* Export handler */
        process_export_csv($transcriptions, 'include', ['transcription_id', 'project_id', 'user_id', 'name', 'input', 'content', 'words', 'language', 'datetime', 'last_datetime'], sprintf(l('transcriptions.title')));
        process_export_json($transcriptions, 'include', ['transcription_id', 'project_id', 'user_id', 'name', 'input', 'content', 'words', 'language', 'settings', 'datetime', 'last_datetime'], sprintf(l('transcriptions.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Available transcriptions */
        $transcriptions_current_month = db()->where('user_id', $this->user->user_id)->getValue('users', '`aix_transcriptions_current_month`');
        $available_transcriptions = $this->user->plan_settings->transcriptions_per_month_limit - $transcriptions_current_month;

        /* AI Languages */
        $ai_transcriptions_languages = require \Altum\Plugin::get('aix')->path . 'includes/ai_transcriptions_languages.php';

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'transcriptions' => $transcriptions,
            'total_transcriptions' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'transcriptions_current_month' => $transcriptions_current_month,
            'available_transcriptions' => $available_transcriptions,
            'ai_transcriptions_languages' => $ai_transcriptions_languages,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('aix')->path . 'views/transcriptions/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function delete() {

        \Altum\Authentication::guard();

        if(!\Altum\Plugin::is_active('aix') || !settings()->aix->transcriptions_is_enabled) {
            redirect('not-found');
        }

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.transcriptions')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('transcriptions');
        }

        if(empty($_POST)) {
            redirect('transcriptions');
        }

        $transcription_id = (int) query_clean($_POST['transcription_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$transcription = db()->where('transcription_id', $transcription_id)->where('user_id', $this->user->user_id)->getOne('transcriptions', ['transcription_id', 'name'])) {
            redirect('transcriptions');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('transcription_id', $transcription_id)->delete('transcriptions');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $transcription->name . '</strong>'));

            redirect('transcriptions');
        }

        redirect('transcriptions');
    }

}
