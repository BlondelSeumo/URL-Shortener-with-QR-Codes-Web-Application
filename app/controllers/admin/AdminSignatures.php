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

class AdminSignatures extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('signature_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `signatures` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/signatures?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $signatures = [];
        $signatures_result = database()->query("
            SELECT
                `signatures`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `signatures`
            LEFT JOIN
                `users` ON `signatures`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('signatures')}
                {$filters->get_sql_order_by('signatures')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $signatures_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $signatures[] = $row;
        }

        /* Export handler */
        process_export_csv($signatures, 'include', ['signature_id', 'project_id', 'user_id', 'name', 'datetime', 'last_datetime'], sprintf(l('signatures.title')));
        process_export_json($signatures, 'include', ['signature_id', 'project_id', 'user_id', 'name', 'datetime', 'last_datetime'], sprintf(l('signatures.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Signature templates */
        $signature_templates = require \Altum\Plugin::get('email-signatures')->path . 'includes/signature_templates.php';

        /* Main View */
        $data = [
            'signatures' => $signatures,
            'filters' => $filters,
            'pagination' => $pagination,
            'signature_templates' => $signature_templates,
        ];

        $view = new \Altum\View('admin/signatures/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/signatures');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/signatures');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/signatures');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $signature_id) {

                        $signature = db()->where('signature_id', $signature_id)->getOne('signatures', ['user_id']);

                        /* Delete the resource */
                        db()->where('signature_id', $signature->signature_id)->delete('signatures');

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/signatures');
    }

    public function delete() {

        $signature_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$signature = db()->where('signature_id', $signature_id)->getOne('signatures', ['signature_id', 'user_id', 'name'])) {
            redirect('admin/signatures');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('signature_id', $signature->signature_id)->delete('signatures');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $signature->name . '</strong>'));

        }

        redirect('admin/signatures');
    }

}
