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

class AdminTemplatesCategories extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['name']));
        $filters->set_default_order_by('template_category_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `templates_categories` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/templates-categories?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $templates_categories = [];
        $templates_categories_result = database()->query("
            SELECT
                *
            FROM
                `templates_categories`
            WHERE
                1 = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $templates_categories_result->fetch_object()) {
            $templates_categories[] = $row;
        }

        /* Export handler */
        process_export_json($templates_categories, 'include', ['template_category_id', 'name', 'settings', 'icon', 'emoji', 'color', 'background', 'order', 'is_enabled', 'datetime', 'last_datetime']);
        process_export_csv($templates_categories, 'include', ['template_category_id', 'name', 'icon', 'emoji', 'color', 'background', 'order', 'is_enabled', 'datetime', 'last_datetime']);

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'templates_categories' => $templates_categories,
            'paginator' => $paginator,
            'pagination' => $pagination,
            'filters' => $filters
        ];

        $view = new \Altum\View('admin/templates-categories/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        $template_category_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$template_category = db()->where('template_category_id', $template_category_id)->getOne('templates_categories', ['template_category_id', 'name'])) {
            redirect('admin/templates-categories');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('template_category_id', $template_category_id)->delete('templates_categories');

            /* Clear the cache */
            cache()->deleteItem('templates_categories');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $template_category->name . '</strong>'));

        }

        redirect('admin/templates-categories');
    }

}
