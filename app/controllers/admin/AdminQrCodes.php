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

class AdminQrCodes extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'type'], ['name'], ['qr_code_id', 'type', 'name', 'datetime', 'last_datetime']));
        $filters->set_default_order_by($this->user->preferences->qr_codes_default_order_by, $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `qr_codes` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/qr-codes?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $qr_codes = [];
        $qr_codes_result = database()->query("
            SELECT
                `qr_codes`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `qr_codes`
            LEFT JOIN
                `users` ON `qr_codes`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('qr_codes')}
                {$filters->get_sql_order_by('qr_codes')}

            {$paginator->get_sql_limit()}
        ");
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
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        $available_qr_codes = require APP_PATH . 'includes/qr_codes.php';

        /* Main View */
        $data = [
            'available_qr_codes' => $available_qr_codes,
            'qr_codes' => $qr_codes,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/qr-codes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/qr-codes');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/qr-codes');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/qr-codes');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $qr_code_id) {
                        /* Delete the qr_code */
                        (new QrCode())->delete($qr_code_id);
                    }

                    break;

                case 'download':

                    $files = [];

                    foreach($_POST['selected'] as $qr_code_id) {
                        if($qr_code = db()->where('qr_code_id', $qr_code_id)->getOne('qr_codes', ['qr_code'])) {
                            $files[$qr_code->qr_code] = \Altum\Uploads::get_path('qr_code');
                        }
                    }

                    \Altum\Uploads::download_files_as_zip($files, l('global.download'));

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/qr-codes');
    }

    public function delete() {

        $qr_code_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$qr_code = db()->where('qr_code_id', $qr_code_id)->getOne('qr_codes', ['qr_code_id', 'name'])) {
            redirect('admin/qr-codes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the qr_code */
            (new QrCode())->delete($qr_code->qr_code_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $qr_code->name . '</strong>'));

        }

        redirect('admin/qr-codes');
    }

    public function transfer() {

        if(empty($_POST)) {
            redirect('admin/qr-codes');
        }

        $qr_code_id = (int) $_POST['qr_code_id'];
        $_POST['email'] = mb_substr(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), 0, 320);

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$qr_code = db()->where('qr_code_id', $qr_code_id)->getOne('qr_codes', ['qr_code_id', 'user_id', 'name'])) {
            redirect('admin/qr-codes');
        }

        if(!$current_user = db()->where('user_id', $qr_code->user_id)->getOne('users', ['user_id', 'email'])) {
            redirect('admin/qr-codes');
        }

        if(!$new_user = db()->where('email', $_POST['email'])->getOne('users', ['user_id', 'email'])) {
            redirect('admin/qr-codes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Update the database */
            db()->where('qr_code_id', $qr_code->qr_code_id)->update('qr_codes', [
                'user_id' => $new_user->user_id,
            ]);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('transfer_modal.success_message'), '<strong>' . input_clean($qr_code->name) . '</strong>', '<strong>' . input_clean($current_user->email) . '</strong>', '<strong>' . input_clean($new_user->email) . '</strong>'));

            /* Clear the cache */
            cache()->deleteItemsByTag('user_id=' . $current_user->user_id);
            cache()->deleteItemsByTag('user_id=' . $new_user->user_id);

            /* Redirect */
            redirect('admin/qr-codes');

        }

        redirect('admin/qr-codes');
    }

}
