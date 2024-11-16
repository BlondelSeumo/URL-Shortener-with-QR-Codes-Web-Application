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

class AdminBiolinksTemplates extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled'], ['name'], ['datetime', 'last_datetime', 'name', 'order', 'total_usage']));
        $filters->set_default_order_by('biolink_template_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `biolinks_templates` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/biolinks-templates?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $biolinks_templates = [];
        $biolinks_templates_result = database()->query("
            SELECT
                `biolinks_templates`.*
            FROM
                `biolinks_templates`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}

            {$paginator->get_sql_limit()}
        ");
        while($row = $biolinks_templates_result->fetch_object()) {
            $biolinks_templates[] = $row;
        }

        /* Export handler */
        process_export_csv($biolinks_templates, 'include', ['biolink_template_id', 'name', 'is_enabled', 'total_usage', 'last_datetime', 'datetime'], sprintf(l('admin_biolinks_templates.title')));
        process_export_json($biolinks_templates, 'include', ['biolink_template_id', 'name', 'settings', 'is_enabled', 'total_usage', 'last_datetime', 'datetime'], sprintf(l('admin_biolinks_templates.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'biolinks_templates' => $biolinks_templates,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/biolinks-templates/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/biolinks-templates');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/biolinks-templates');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/biolinks-templates');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $biolink_template_id) {
                        $biolink_template = db()->where('biolink_template_id', $biolink_template_id)->getOne('biolinks_templates');

                        if(!$biolink_template) {
                            continue;
                        }

                        /* Delete the resource */
                        db()->where('biolink_template_id', $biolink_template_id)->delete('biolinks_templates');
                    }

                    /* Clear the cache */
                    cache()->deleteItem('biolinks_templates');

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/biolinks-templates');
    }

    public function delete() {

        $biolink_template_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$biolink_template = db()->where('biolink_template_id', $biolink_template_id)->getOne('biolinks_templates')) {
            redirect('admin/biolinks-templates');
        }

        $biolink_template->settings = json_decode($biolink_template->settings ?? '');

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('biolink_template_id', $biolink_template->biolink_template_id)->delete('biolinks_templates');

            /* Clear the cache */
            cache()->deleteItem('biolinks_templates');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $biolink_template->name . '</strong>'));

        }

        redirect('admin/biolinks-templates');
    }

}
