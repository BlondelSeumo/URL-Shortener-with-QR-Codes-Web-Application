<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\controllers;

use Altum\Models\Domain;

class BiolinksTemplates extends Controller {

    public function index() {

        if(!settings()->links->biolinks_is_enabled || !settings()->links->biolinks_templates_is_enabled) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], ['name'], ['name']));
        $filters->set_default_order_by('order', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `biolinks_templates` WHERE `is_enabled` = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('biolinks-templates?' . $filters->get_get() . '&page=%d')));

        /* Get the links list for the project */
        $result = database()->query("
            SELECT 
                *
            FROM 
                `biolinks_templates`
            WHERE 
                `is_enabled` = 1
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the links */
        $biolinks_templates = [];

        while($row = $result->fetch_object()) {
            $biolinks_templates[] = $row;
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Get domains */
        $domains = (new Domain())->get_available_domains_by_user($this->user);

        /* Create Link Modal */
        $view = new \Altum\View('links/create_link_modals', (array) $this);
        \Altum\Event::add_content($view->run(['domains' => $domains]), 'modals');

        /* Prepare the view */
        $data = [
            'biolinks_templates' => $biolinks_templates,
            'domains'            => $domains,
            'pagination'         => $pagination,
            'filters'            => $filters,
        ];

        $view = new \Altum\View('biolinks-templates/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


