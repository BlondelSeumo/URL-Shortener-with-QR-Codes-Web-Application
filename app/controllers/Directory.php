<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class Directory extends Controller {

    public function index() {
        if(!settings()->links->biolinks_is_enabled || !settings()->links->directory_is_enabled) {
            redirect('not-found');
        }

        if(settings()->links->directory_access == 'users') {
            \Altum\Authentication::guard();
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_verified'], ['url'], ['clicks', 'url']));
        $filters->set_default_order_by('clicks', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Which bio link pages to display? */
        $directory_display_where = settings()->links->directory_display == 'all' ? null : 'AND `is_verified` = 1';

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `links` WHERE `type` = 'biolink' AND `is_enabled` = 1 {$directory_display_where} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('directory?' . $filters->get_get() . '&page=%d')));

        /* Get the links list for the project */
        $links_result = database()->query("
            SELECT 
                `links`.*, `domains`.`scheme`, `domains`.`host`, `domains`.`link_id` as `domain_link_id`
            FROM 
                `links`
            LEFT JOIN 
                `domains` ON `links`.`domain_id` = `domains`.`domain_id`
            WHERE 
                `links`.`type` = 'biolink'
                AND `links`.`is_enabled` = 1
                {$directory_display_where}
                {$filters->get_sql_where('links')}
                {$filters->get_sql_order_by('links')}
            {$paginator->get_sql_limit()}
        ");

        /* Iterate over the links */
        $links = [];

        while($row = $links_result->fetch_object()) {
            $row->full_url = $row->domain_id ? $row->scheme . $row->host . '/' . ($row->domain_link_id == $row->link_id ? null : $row->url) : SITE_URL . $row->url;
            $row->settings = json_decode($row->settings ?? '');

            $links[] = $row;
        }

        /* Export handler */
        process_export_csv($links, 'include', ['url', 'full_url', 'clicks', 'is_verified'], sprintf(l('links.title')));
        process_export_json($links, 'include', ['url', 'full_url', 'clicks', 'is_verified'], sprintf(l('links.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'links'             => $links,
            'pagination'        => $pagination,
            'filters'           => $filters,
        ];

        $view = new \Altum\View('directory/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


