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

class AdminPixels extends Controller {

    public function index() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'type'], ['name', 'pixel'], ['last_datetime', 'name', 'datetime']));
        $filters->set_default_order_by('pixel_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `pixels` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/pixels?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $pixels = [];
        $pixels_result = database()->query("
            SELECT
                `pixels`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `pixels`
            LEFT JOIN
                `users` ON `pixels`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('pixels')}
                {$filters->get_sql_order_by('pixels')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $pixels_result->fetch_object()) {
            $pixels[] = $row;
        }

        /* Export handler */
        process_export_csv($pixels, 'include', ['pixel_id', 'user_id', 'type', 'name', 'pixel', 'last_datetime', 'datetime'], sprintf(l('admin_pixels.title')));
        process_export_json($pixels, 'include', ['pixel_id', 'user_id', 'type', 'name', 'pixel', 'last_datetime', 'datetime'], sprintf(l('admin_pixels.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'pixels' => $pixels,
            'filters' => $filters,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('admin/pixels/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/pixels');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/pixels');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/pixels');
        }

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $pixel_id) {
                        /* Delete the resource */
                        db()->where('pixel_id', $pixel_id)->delete('pixels');

                        /* Clear the cache */
                        cache()->deleteItemsByTag('pixel_id=' . $pixel_id);
                    }

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/pixels');
    }

    public function delete() {

        $pixel_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$pixel = db()->where('pixel_id', $pixel_id)->getOne('pixels', ['pixel_id', 'name'])) {
            redirect('admin/pixels');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            db()->where('pixel_id', $pixel->pixel_id)->delete('pixels');

            /* Clear the cache */
            cache()->deleteItemsByTag('pixel_id=' . $pixel->pixel_id);

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $pixel->name . '</strong>'));

        }

        redirect('admin/pixels');
    }

}
