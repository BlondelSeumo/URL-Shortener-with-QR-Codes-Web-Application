<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Response;
use Altum\Traits\Apiable;

class ApiPixels extends Controller {
    use Apiable;

    public function index() {

        if(!settings()->links->pixels_is_enabled) {
            redirect('not-found');
        }

        $this->verify_request();

        /* Decide what to continue with */
        switch($_SERVER['REQUEST_METHOD']) {
            case 'GET':

                /* Detect if we only need an object, or the whole list */
                if(isset($this->params[0])) {
                    $this->get();
                } else {
                    $this->get_all();
                }

            break;

            case 'POST':

                /* Detect what method to use */
                if(isset($this->params[0])) {
                    $this->patch();
                } else {
                    $this->post();
                }

            break;

            case 'DELETE':
                $this->delete();
            break;
        }

        $this->return_404();
    }

    private function get_all() {

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters([], [], []));
        $filters->set_default_order_by('pixel_id', $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `pixels` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/pixels?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `pixels`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->pixel_id,
                'user_id' => (int) $row->user_id,
                'type' => $row->type,
                'name' => $row->name,
                'pixel' => $row->pixel,
                'last_datetime' => $row->last_datetime,
                'datetime' => $row->datetime,
            ];

            $data[] = $row;
        }

        /* Prepare the data */
        $meta = [
            'page' => $_GET['page'] ?? 1,
            'total_pages' => $paginator->getNumPages(),
            'results_per_page' => $filters->get_results_per_page(),
            'total_results' => (int) $total_rows,
        ];

        /* Prepare the pagination links */
        $others = ['links' => [
            'first' => $paginator->getPageUrl(1),
            'last' => $paginator->getNumPages() ? $paginator->getPageUrl($paginator->getNumPages()) : null,
            'next' => $paginator->getNextUrl(),
            'prev' => $paginator->getPrevUrl(),
            'self' => $paginator->getPageUrl($_GET['page'] ?? 1)
        ]];

        Response::jsonapi_success($data, $meta, 200, $others);
    }

    private function get() {

        $pixel_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $pixel = db()->where('pixel_id', $pixel_id)->where('user_id', $this->api_user->user_id)->getOne('pixels');

        /* We haven't found the resource */
        if(!$pixel) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $pixel->pixel_id,
            'user_id' => (int) $pixel->user_id,
            'type' => $pixel->type,
            'name' => $pixel->name,
            'pixel' => $pixel->pixel,
            'last_datetime' => $pixel->last_datetime,
            'datetime' => $pixel->datetime,
        ];

        Response::jsonapi_success($data);

    }

    private function post() {

        /* Check for the plan limit */
        $total_rows = db()->where('user_id', $this->api_user->user_id)->getValue('pixels', 'count(`pixel_id`)');

        if($this->api_user->plan_settings->pixels_limit != -1 && $total_rows >= $this->api_user->plan_settings->pixels_limit) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        /* Check for any errors */
        $required_fields = ['type', 'name', 'pixel'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        $_POST['type'] = array_key_exists($_POST['type'], require APP_PATH . 'includes/v/pixels.php') ? $_POST['type'] : '';
        $_POST['name'] = trim($_POST['name']);
        $_POST['pixel'] = trim($_POST['pixel']);

        /* Database query */
        $pixel_id = db()->insert('pixels', [
            'user_id' => $this->api_user->user_id,
            'type' => $_POST['type'],
            'name' => $_POST['name'],
            'pixel' => $_POST['pixel'],
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('pixels?user_id=' . $this->api_user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $pixel_id
        ];

        Response::jsonapi_success($data, null, 201);

    }

    private function patch() {

        $pixel_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $pixel = db()->where('pixel_id', $pixel_id)->where('user_id', $this->api_user->user_id)->getOne('pixels');

        /* We haven't found the resource */
        if(!$pixel) {
            $this->return_404();
        }

        $_POST['type'] = array_key_exists($_POST['type'] ?? $pixel->type, require APP_PATH . 'includes/v/pixels.php') ? $_POST['type'] : '';
        $_POST['name'] = trim($_POST['name'] ?? $pixel->name);
        $_POST['pixel'] = trim($_POST['pixel'] ?? $pixel->pixel);

        /* Database query */
        db()->where('pixel_id', $pixel->pixel_id)->update('pixels', [
            'type' => $_POST['type'],
            'name' => $_POST['name'],
            'pixel' => $_POST['pixel'],
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItemsByTag('pixels?user_id=' . $this->api_user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $pixel->pixel_id
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $pixel_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $pixel = db()->where('pixel_id', $pixel_id)->where('user_id', $this->api_user->user_id)->getOne('pixels');

        /* We haven't found the resource */
        if(!$pixel) {
            $this->return_404();
        }

        /* Delete the resource */
        db()->where('pixel_id', $pixel_id)->delete('pixels');

        /* Clear the cache */
        cache()->deleteItemsByTag('pixels?user_id=' . $this->api_user->user_id);

        http_response_code(200);
        die();

    }

}
