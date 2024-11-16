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

class AdminTaxes extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['type', 'value_type', 'billing_type'], ['name', 'description'], ['name', 'value', 'datetime']));
        $filters->set_default_order_by('tax_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `taxes` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/taxes?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $taxes = [];
        $taxes_result = database()->query("
            SELECT
                *
            FROM
                `taxes`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $taxes_result->fetch_object()) {
            $taxes[] = $row;
        }

        /* Export handler */
        process_export_json($taxes, 'include', ['tax_id', 'name', 'description', 'value', 'value_type', 'type', 'billing_type', 'countries', 'datetime']);
        process_export_csv($taxes, 'include', ['tax_id', 'name', 'description', 'value', 'value_type', 'type', 'billing_type', 'countries', 'datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'taxes' => $taxes,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/taxes/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $tax_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$tax = db()->where('tax_id', $tax_id)->getOne('taxes', ['tax_id', 'name'])) {
            redirect('admin/taxes');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the tax */
            db()->where('tax_id', $tax_id)->delete('taxes');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $tax->name . '</strong>'));

        }

        redirect('admin/taxes');
    }

}
