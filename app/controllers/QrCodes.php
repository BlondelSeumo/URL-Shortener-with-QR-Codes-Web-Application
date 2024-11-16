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
use Altum\Models\QrCode;

class QrCodes extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        if(!settings()->codes->qr_codes_is_enabled) {
            redirect('not-found');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['project_id', 'type'], ['name'], ['qr_code_id', 'type', 'name', 'datetime', 'last_datetime']));
        $filters->set_default_order_by($this->user->preferences->qr_codes_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `qr_codes` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('qr-codes?' . $filters->get_get() . '&page=%d')));

        /* Get the qr_codes list for the user */
        $qr_codes = [];
        $qr_codes_result = database()->query("SELECT * FROM `qr_codes` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $qr_codes_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $row->qr_code_url = $row->qr_code ?\Altum\Uploads::get_full_url('qr_code') . $row->qr_code : null;
            $row->qr_code_logo_url = $row->qr_code_logo ?\Altum\Uploads::get_full_url('qr_code_logo') . $row->qr_code_logo : null;
            $row->qr_code_background_url = $row->qr_code_background ?\Altum\Uploads::get_full_url('qr_code_background') . $row->qr_code_background : null;
            $row->qr_code_background_url = $row->qr_code_background ?\Altum\Uploads::get_full_url('qr_code_background') . $row->qr_code_background : null;
            $qr_codes[] = $row;
        }

        /* Export handler */
        process_export_csv($qr_codes, 'include', ['qr_code_id', 'user_id', 'project_id', 'type', 'name', 'qr_code', 'qr_code_url', 'qr_code_log', 'qr_code_logo_url', 'qr_code_background', 'qr_code_background_url', 'qr_code_foreground', 'qr_code_foreground_url', 'last_datetime', 'datetime'], sprintf(l('qr_codes.title')));
        process_export_json($qr_codes, 'include', ['qr_code_id', 'user_id', 'project_id', 'type', 'name', 'qr_code', 'qr_code_url', 'qr_code_log', 'qr_code_logo_url', 'qr_code_background', 'qr_code_background_url', 'qr_code_foreground', 'qr_code_foreground_url', 'embedded_data', 'settings','last_datetime', 'datetime'], sprintf(l('qr_codes.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Existing projects */
        $projects = (new \Altum\Models\Projects())->get_projects_by_user_id($this->user->user_id);

        $available_qr_codes = require APP_PATH . 'includes/enabled_qr_codes.php';

        /* Prepare the view */
        $data = [
            'available_qr_codes'  => $available_qr_codes,
            'qr_codes'            => $qr_codes,
            'total_qr_codes'      => $total_rows,
            'pagination'          => $pagination,
            'filters'             => $filters,
            'projects'            => $projects,
        ];

        $view = new \Altum\View('qr-codes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function duplicate() {
        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('create.qr_codes')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('qr-codes');
        }

        if(empty($_POST)) {
            redirect('qr-codes');
        }

        /* Make sure that the user didn't exceed the limit */
        $total_rows = db()->where('user_id', $this->user->user_id)->getValue('qr_codes', 'COUNT(*)') ?? 0;
        if($this->user->plan_settings->qr_codes_limit != -1 && $total_rows >= $this->user->plan_settings->qr_codes_limit) {
            Alerts::add_info(l('global.info_message.plan_feature_limit'));
            redirect('qr-codes');
        }

        $qr_code_id = (int) $_POST['qr_code_id'];

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('qr-codes');
        }

        /* Verify the main resource */
        if(!$qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->user->user_id)->getOne('qr_codes')) {
            redirect('qr-codes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Duplicate the files */
            $qr_code_image = \Altum\Uploads::copy_uploaded_file($qr_code->qr_code, \Altum\Uploads::get_path('qr_code'), \Altum\Uploads::get_path('qr_code'));
            $qr_code_logo = \Altum\Uploads::copy_uploaded_file($qr_code->qr_code_logo, \Altum\Uploads::get_path('qr_code_logo'), \Altum\Uploads::get_path('qr_code_logo'));
            $qr_code_background = \Altum\Uploads::copy_uploaded_file($qr_code->qr_code_background, \Altum\Uploads::get_path('qr_code_background'), \Altum\Uploads::get_path('qr_code_background'));
            $qr_code_foreground = \Altum\Uploads::copy_uploaded_file($qr_code->qr_code_foreground, \Altum\Uploads::get_path('qr_code_foreground'), \Altum\Uploads::get_path('qr_code_foreground'));

            /* Insert to database */
            $qr_code_id = db()->insert('qr_codes', [
                'user_id' => $this->user->user_id,
                'project_id' => $qr_code->project_id,
                'name' => string_truncate($qr_code->name . ' - ' . l('global.duplicated'), 64, null),
                'type' => $qr_code->type,
                'qr_code_logo' => $qr_code_logo,
                'qr_code_background' => $qr_code_background,
                'qr_code_foreground' => $qr_code_foreground,
                'qr_code' => $qr_code_image,
                'settings' => $qr_code->settings,
                'datetime' => get_date(),
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.create1'), '<strong>' . input_clean($qr_code->name) . '</strong>'));

            /* Redirect */
            redirect('qr-code-update/' . $qr_code_id);

        }

        redirect('qr-codes');
    }

    public function bulk() {

        \Altum\Authentication::guard();

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('qr-codes');
        }

        if(empty($_POST['selected'])) {
            redirect('qr-codes');
        }

        if(!isset($_POST['type'])) {
            redirect('qr-codes');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    /* Team checks */
                    if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.qr_codes')) {
                        Alerts::add_info(l('global.info_message.team_no_access'));
                        redirect('qr-codes');
                    }

                    foreach($_POST['selected'] as $qr_code_id) {
                        if($qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->user->user_id)->getOne('qr_codes', ['qr_code'])) {
                            /* Delete the qr_code */
                            (new QrCode())->delete($qr_code_id);
                        }
                    }

                    break;

                case 'download':

                    $files = [];

                    foreach($_POST['selected'] as $qr_code_id) {
                        if($qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->user->user_id)->getOne('qr_codes', ['qr_code'])) {
                            $files[$qr_code->qr_code] = \Altum\Uploads::get_path('qr_code');
                        }
                    }

                    \Altum\Uploads::download_files_as_zip($files, l('global.download'));

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('qr-codes');
    }

    public function delete() {
        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.qr_codes')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('qr-codes');
        }

        if(empty($_POST)) {
            redirect('qr-codes');
        }

        $qr_code_id = (int) query_clean($_POST['qr_code_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            redirect('qr-codes');
        }

        /* Make sure the vcard id is created by the logged in user */
        if(!$qr_code = db()->where('qr_code_id', $qr_code_id)->where('user_id', $this->user->user_id)->getOne('qr_codes', ['qr_code_id', 'name'])) {
            redirect('qr-codes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new QrCode())->delete($qr_code->qr_code_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $qr_code->name . '</strong>'));

            redirect('qr-codes');

        }

        redirect('qr-codes');
    }

}
