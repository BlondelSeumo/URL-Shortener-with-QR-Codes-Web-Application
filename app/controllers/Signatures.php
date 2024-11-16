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

class Signatures extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('signature_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `signatures` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('signatures?' . $filters->get_get() . '&page=%d')));

        /* Get the signatures */
        $signatures = [];
        $signatures_result = database()->query("
            SELECT
                *
            FROM
                `signatures`
            WHERE
                `user_id` = {$this->user->user_id}
                {$filters->get_sql_where()}
            {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");
        while($row = $signatures_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $signatures[] = $row;
        }

        /* Export handler */
        process_export_csv($signatures, 'include', ['signature_id', 'project_id', 'user_id', 'name', 'datetime', 'last_datetime'], sprintf(l('signatures.title')));
        process_export_json($signatures, 'include', ['signature_id', 'project_id', 'user_id', 'name', 'settings', 'datetime', 'last_datetime'], sprintf(l('signatures.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        /* Signature templates */
        $signature_templates = require \Altum\Plugin::get('email-signatures')->path . 'includes/signature_templates.php';

        /* Prepare the view */
        $data = [
            'projects' => $projects,
            'signatures' => $signatures,
            'total_signatures' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
            'signature_templates' => $signature_templates,
        ];

        $view = new \Altum\View(\Altum\Plugin::get('email-signatures')->path . 'views/signatures/index', (array) $this, true);

        $this->add_view_content('content', $view->run($data));
    }

    public function duplicate() {

        if(!\Altum\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.signatures')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('signatures');
        }

        if(empty($_POST)) {
            redirect('signatures');
        }

        /* Make sure that the user didn't exceed the limit */
        $total_rows = db()->where('user_id', $this->user->user_id)->getValue('signatures', 'COUNT(*)') ?? 0;
        if($this->user->plan_settings->signatures_limit != -1 && $total_rows >= $this->user->plan_settings->signatures_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('signatures');
        }

        $signature_id = (int) $_POST['signature_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('signatures');
        }

        /* Verify the main resource */
        if(!$signature = db()->where('signature_id', $signature_id)->where('user_id', $this->user->user_id)->getOne('signatures')) {
            redirect('signatures');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Parse settings */
            $signature->settings = json_decode($signature->settings ?? '');

            /* Insert to database */
            $signature_id = db()->insert('signatures', [
                'user_id' => $this->user->user_id,
                'project_id' => $signature->project_id,
                'name' => string_truncate($signature->name . ' - ' . l('global.duplicated'), 64, null),
                'template' => $signature->template,
                'settings' => json_encode($signature->settings),
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($signature->name) . '</strong>'));

            /* Redirect */
            redirect('signature-update/' . $signature_id);

        }

        redirect('signatures');
    }

    public function delete() {

        if(!\Altum\Plugin::is_active('email-signatures') || !settings()->signatures->is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.signatures')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('signatures');
        }

        if(empty($_POST)) {
            redirect('signatures');
        }

        $signature_id = (int) query_clean($_POST['signature_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$signature = db()->where('signature_id', $signature_id)->where('user_id', $this->user->user_id)->getOne('signatures', ['signature_id', 'name'])) {
            redirect('signatures');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('signature_id', $signature_id)->delete('signatures');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $signature->name . '</strong>'));

            /* Clear the cache */
            cache()->deleteItem('signatures?user_id=' . $this->user->user_id);

            redirect('signatures');
        }

        redirect('signatures');
    }

}
