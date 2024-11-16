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

class AdminBiolinksBlocks extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['is_enabled', 'user_id', 'link_id', 'type'], ['location_url'], ['order', 'last_datetime', 'datetime', 'location_url', 'clicks']));
        $filters->set_default_order_by('biolink_block_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `biolinks_blocks` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/biolinks-blocks?' . $filters->get_get() . '&page=%d')));

        /* Get the users */
        $biolinks_blocks = [];
        $biolinks_blocks_result = database()->query("
            SELECT
                `biolinks_blocks`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `biolinks_blocks`
            LEFT JOIN
                `users` ON `biolinks_blocks`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('biolinks_blocks')}
                {$filters->get_sql_order_by('biolinks_blocks')}
            {$paginator->get_sql_limit()}
        ");
        while($row = $biolinks_blocks_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $biolinks_blocks[] = $row;
        }

        /* Export handler */
        process_export_csv($biolinks_blocks, 'include', ['biolink_block_id', 'link_id', 'user_id', 'type', 'location_url', 'order', 'start_date', 'end_date', 'clicks', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_links.title')));
        process_export_json($biolinks_blocks, 'include', ['biolink_block_id', 'link_id', 'user_id', 'type', 'location_url', 'order', 'settings', 'start_date', 'end_date', 'clicks', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('admin_links.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'biolinks_blocks' => $biolinks_blocks,
            'filters' => $filters,
            'pagination' => $pagination,
            'biolink_blocks' => require APP_PATH . 'includes/biolink_blocks.php',
        ];

        $view = new \Altum\View('admin/biolinks-blocks/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/biolinks-blocks');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/biolinks-blocks');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/biolinks-blocks');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $biolink_block_id) {
                        (new \Altum\Models\BiolinkBlock())->delete($biolink_block_id);
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/biolinks-blocks');
    }

    public function delete() {

        $biolink_block_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$link = db()->where('biolink_block_id', $biolink_block_id)->getOne('biolinks_blocks', ['biolink_block_id'])) {
            redirect('admin/biolinks-blocks');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            (new \Altum\Models\BiolinkBlock())->delete($link->biolink_block_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $link->url . '</strong>'));

        }

        redirect('admin/biolinks-blocks');
    }

}
