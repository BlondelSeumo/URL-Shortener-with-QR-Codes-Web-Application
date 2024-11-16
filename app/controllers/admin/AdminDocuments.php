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

class AdminDocuments extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'template_id', 'template_category_id'], ['name'], ['last_datetime', 'datetime', 'name', 'words']));
        $filters->set_default_order_by('document_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `documents` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/documents?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $documents = [];
        $documents_result = database()->query("
            SELECT
                `documents`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `documents`
            LEFT JOIN
                `users` ON `documents`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('documents')}
                {$filters->get_sql_order_by('documents')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $documents_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $documents[] = $row;
        }

        /* Export handler */
        process_export_csv($documents, 'include', ['document_id', 'template_id', 'template_category_id', 'project_id', 'user_id', 'name', 'type', 'content', 'words', 'model', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('documents.title')));
        process_export_json($documents, 'include', ['document_id', 'template_id', 'template_category_id', 'project_id', 'user_id', 'name', 'type', 'content', 'words', 'model', 'api_response_time', 'settings', 'datetime', 'last_datetime'], sprintf(l('documents.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get available templates categories */
        $templates_categories = (new \Altum\Models\TemplatesCategories())->get_templates_categories();

        /* Templates */
        $templates = (new \Altum\Models\Templates())->get_templates();

        /* Main View */
        $data = [
            'documents' => $documents,
            'filters' => $filters,
            'pagination' => $pagination,
            'templates' => $templates,
            'templates_categories' => $templates_categories,
        ];

        $view = new \Altum\View('admin/documents/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/documents');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/documents');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/documents');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $document_id) {

                        $document = db()->where('document_id', $document_id)->getOne('documents', ['document_id']);

                        /* Delete the resource */
                        db()->where('document_id', $document->document_id)->delete('documents');

                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/documents');
    }

    public function delete() {

        $document_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$document = db()->where('document_id', $document_id)->getOne('documents', ['document_id', 'user_id', 'name'])) {
            redirect('admin/documents');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('document_id', $document->document_id)->delete('documents');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $document->name . '</strong>'));

        }

        redirect('admin/documents');
    }

}
