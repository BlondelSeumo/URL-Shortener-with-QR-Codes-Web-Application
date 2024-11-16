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

class AdminImages extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('aix')) {
            redirect('not-found');
        }

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['user_id', 'project_id', 'size', 'artist', 'lighting', 'style', 'mood'], ['name'], ['last_datetime', 'datetime', 'name']));
        $filters->set_default_order_by('image_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `images` WHERE 1 = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('admin/images?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $images = [];
        $images_result = database()->query("
            SELECT
                `images`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email`
            FROM
                `images`
            LEFT JOIN
                `users` ON `images`.`user_id` = `users`.`user_id`
            WHERE
                1 = 1
                {$filters->get_sql_where('images')}
                {$filters->get_sql_order_by('images')}

            {$paginator->get_sql_limit()}
        ");
        while($row = $images_result->fetch_object()) {
            $row->settings = json_decode($row->settings ?? '');
            $row->image_url = $row->image ? \Altum\Uploads::get_full_url('images') . $row->image : null;
            $images[] = $row;
        }

        /* Export handler */
        process_export_csv($images, 'include', ['image_id', 'project_id', 'user_id', 'name', 'input', 'image', 'image_url', 'style', 'artist', 'lighting', 'mood', 'size', 'api', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('images.title')));
        process_export_json($images, 'include', ['image_id', 'project_id', 'user_id', 'name', 'input', 'image', 'image_url', 'style', 'artist', 'lighting', 'mood', 'size', 'settings', 'api', 'api_response_time', 'datetime', 'last_datetime'], sprintf(l('images.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/admin_pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Main View */
        $data = [
            'images' => $images,
            'filters' => $filters,
            'pagination' => $pagination,
            'ai_images_lighting' => require \Altum\Plugin::get('aix')->path . 'includes/ai_images_lighting.php',
            'ai_images_styles' => require \Altum\Plugin::get('aix')->path . 'includes/ai_images_styles.php',
            'ai_images_moods' => require \Altum\Plugin::get('aix')->path . 'includes/ai_images_moods.php',
        ];

        $view = new \Altum\View('admin/images/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/images');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/images');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/images');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $image_id) {

                        $image = db()->where('image_id', $image_id)->getOne('images', ['user_id', 'image', 'image_id']);

                        /* Delete file */
                        \Altum\Uploads::delete_uploaded_file($image->image, 'images');

                        /* Delete the resource */
                        db()->where('image_id', $image->image_id)->delete('images');

                    }

                    break;

                case 'download':

                    $files = [];

                    foreach($_POST['selected'] as $image_id) {
                        if($image = db()->where('image_id', $image_id)->getOne('images', ['image'])) {
                            $files[$image->image] = \Altum\Uploads::get_path('images');
                        }
                    }

                    \Altum\Uploads::download_files_as_zip($files, l('global.download'));

                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/images');
    }

    public function delete() {

        $image_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$image = db()->where('image_id', $image_id)->getOne('images', ['image_id', 'user_id', 'name', 'image'])) {
            redirect('admin/images');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete file */
            \Altum\Uploads::delete_uploaded_file($image->image, 'images');

            /* Delete the resource */
            db()->where('image_id', $image->image_id)->delete('images');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $image->name . '</strong>'));

        }

        redirect('admin/images');
    }

}
