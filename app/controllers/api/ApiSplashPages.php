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

class ApiSplashPages extends Controller {
    use Apiable;

    public function index() {

        if(!settings()->links->splash_page_is_enabled) {
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
        $filters->set_default_order_by('splash_page_id', $this->api_user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->api_user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);
        $filters->process();

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `splash_pages` WHERE `user_id` = {$this->api_user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('api/splash-pages?' . $filters->get_get() . '&page=%d')));

        /* Get the data */
        $data = [];
        $data_result = database()->query("
            SELECT
                *
            FROM
                `splash_pages`
            WHERE
                `user_id` = {$this->api_user->user_id}
                {$filters->get_sql_where()}
                {$filters->get_sql_order_by()}
                  
            {$paginator->get_sql_limit()}
        ");
        while($row = $data_result->fetch_object()) {

            /* Prepare the data */
            $row = [
                'id' => (int) $row->splash_page_id,
                'user_id' => (int) $row->user_id,
                'name' => $row->name,
                'title' => $row->title,
                'description' => $row->description,
                'link_unlock_seconds' => $row->link_unlock_seconds,
                'auto_redirect' => (bool) $row->auto_redirect,
                'settings' => json_decode($row->settings),
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

        $splash_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $splash_page = db()->where('splash_page_id', $splash_page_id)->where('user_id', $this->api_user->user_id)->getOne('splash_pages');

        /* We haven't found the resource */
        if(!$splash_page) {
            $this->return_404();
        }

        /* Prepare the data */
        $data = [
            'id' => (int) $splash_page->splash_page_id,
            'user_id' => (int) $splash_page->user_id,
            'name' => $splash_page->name,
            'title' => $splash_page->title,
            'description' => $splash_page->description,
            'link_unlock_seconds' => $splash_page->link_unlock_seconds,
            'auto_redirect' => (bool) $splash_page->auto_redirect,
            'settings' => json_decode($splash_page->settings),
            'last_datetime' => $splash_page->last_datetime,
            'datetime' => $splash_page->datetime,
        ];

        Response::jsonapi_success($data);

    }

    private function post() {

        /* Check for the plan limit */
        $total_rows = db()->where('user_id', $this->api_user->user_id)->getValue('splash_pages', 'count(`splash_page_id`)');

        if($this->api_user->plan_settings->splash_pages_limit != -1 && $total_rows >= $this->api_user->plan_settings->splash_pages_limit) {
            $this->response_error(l('global.info_message.plan_feature_limit'), 401);
        }

        /* Check for any errors */
        $required_fields = ['name'];
        foreach($required_fields as $field) {
            if(!isset($_POST[$field]) || (isset($_POST[$field]) && empty($_POST[$field]) && $_POST[$field] != '0')) {
                $this->response_error(l('global.error_message.empty_fields'), 401);
                break 1;
            }
        }

        $_POST['name'] = input_clean($_POST['name'] ?? '', 64);
        $_POST['title'] = input_clean($_POST['title'] ?? '', 256);
        $_POST['description'] = input_clean($_POST['description'] ?? '', 2048);
        $_POST['secondary_button_name'] = input_clean($_POST['secondary_button_name'] ?? '', 256);
        $_POST['secondary_button_url'] = input_clean($_POST['secondary_button_url'] ?? '', 1024);
        $_POST['custom_css'] = mb_substr(trim($_POST['custom_css'] ?? ''), 0, 8192);
        $_POST['custom_js'] = mb_substr(trim($_POST['custom_js'] ?? ''), 0, 8192);
        $_POST['ads_header'] = mb_substr(trim($_POST['ads_header'] ?? ''), 0, 8192);
        $_POST['ads_footer'] = mb_substr(trim($_POST['ads_footer'] ?? ''), 0, 8192);
        $_POST['link_unlock_seconds'] = (int) ($_POST['link_unlock_seconds'] ?? 5);
        $_POST['auto_redirect'] = (int) isset($_POST['auto_redirect']);

        $logo = \Altum\Uploads::process_upload(null, 'splash_pages', 'logo', 'logo_remove', settings()->links->avatar_size_limit);
        $favicon = \Altum\Uploads::process_upload(null, 'splash_pages', 'favicon', 'favicon_remove', settings()->links->favicon_size_limit);
        $opengraph = \Altum\Uploads::process_upload(null, 'splash_pages', 'opengraph', 'opengraph_remove', settings()->links->seo_image_size_limit);

        $settings = json_encode([
            'logo' => $logo,
            'favicon' => $favicon,
            'opengraph' => $opengraph,
            'secondary_button_name' => $_POST['secondary_button_name'],
            'secondary_button_url' => $_POST['secondary_button_url'],
            'custom_css' => $_POST['custom_css'],
            'custom_js' => $_POST['custom_js'],
            'ads_header' => $_POST['ads_header'],
            'ads_footer' => $_POST['ads_footer'],
        ]);

        /* Database query */
        $splash_page_id = db()->insert('splash_pages', [
            'user_id' => $this->api_user->user_id,
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'link_unlock_seconds' => $_POST['link_unlock_seconds'],
            'auto_redirect' => $_POST['auto_redirect'],
            'settings' => $settings,
            'datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('splash_pages?user_id=' . $this->api_user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $splash_page_id
        ];

        Response::jsonapi_success($data, null, 201);

    }

    private function patch() {

        $splash_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $splash_page = db()->where('splash_page_id', $splash_page_id)->where('user_id', $this->api_user->user_id)->getOne('splash_pages');

        /* We haven't found the resource */
        if(!$splash_page) {
            $this->return_404();
        }

        $splash_page->settings = json_decode($splash_page->settings ?? '');

        $_POST['name'] = input_clean($_POST['name'] ?? $splash_page->name, 64);
        $_POST['title'] = input_clean($_POST['title'] ?? $splash_page->title, 256);
        $_POST['description'] = input_clean($_POST['description'] ?? $splash_page->description, 2048);
        $_POST['secondary_button_name'] = input_clean($_POST['secondary_button_name'] ?? $splash_page->settings->secondary_button_name, 256);
        $_POST['secondary_button_url'] = input_clean($_POST['secondary_button_url'] ?? $splash_page->settings->secondary_button_url, 1024);
        $_POST['custom_css'] = mb_substr(trim($splash_page->settings->custom_css ?? ''), 0, 8192);
        $_POST['custom_js'] = mb_substr(trim($splash_page->settings->custom_js ?? ''), 0, 8192);
        $_POST['ads_header'] = mb_substr(trim($splash_page->settings->ads_header ?? ''), 0, 8192);
        $_POST['ads_footer'] = mb_substr(trim($splash_page->settings->ads_footer ?? ''), 0, 8192);
        $_POST['link_unlock_seconds'] = isset($_POST['link_unlock_seconds']) ? (int) $_POST['link_unlock_seconds'] : $splash_page->link_unlock_seconds;
        $_POST['auto_redirect'] = isset($_POST['auto_redirect']) ? (int) (bool) $_POST['auto_redirect'] : $splash_page->auto_redirect;

        $logo = \Altum\Uploads::process_upload($splash_page->settings->logo, 'splash_pages', 'logo', 'logo_remove', settings()->links->avatar_size_limit);
        $favicon = \Altum\Uploads::process_upload($splash_page->settings->favicon, 'splash_pages', 'favicon', 'favicon_remove', settings()->links->favicon_size_limit);
        $opengraph = \Altum\Uploads::process_upload($splash_page->settings->opengraph, 'splash_pages', 'opengraph', 'opengraph_remove', settings()->links->seo_image_size_limit);

        $settings = json_encode([
            'logo' => $logo,
            'favicon' => $favicon,
            'opengraph' => $opengraph,
            'secondary_button_name' => $_POST['secondary_button_name'],
            'secondary_button_url' => $_POST['secondary_button_url'],
            'custom_css' => $_POST['custom_css'],
            'custom_js' => $_POST['custom_js'],
            'ads_header' => $_POST['ads_header'],
            'ads_footer' => $_POST['ads_footer'],
        ]);

        /* Database query */
        db()->where('splash_page_id', $splash_page->splash_page_id)->update('splash_pages', [
            'name' => $_POST['name'],
            'description' => $_POST['description'],
            'link_unlock_seconds' => $_POST['link_unlock_seconds'],
            'auto_redirect' => $_POST['auto_redirect'],
            'settings' => $settings,
            'last_datetime' => get_date(),
        ]);

        /* Clear the cache */
        cache()->deleteItem('splash_pages?user_id=' . $this->api_user->user_id);

        /* Prepare the data */
        $data = [
            'id' => $splash_page->splash_page_id
        ];

        Response::jsonapi_success($data, null, 200);

    }

    private function delete() {

        $splash_page_id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Try to get details about the resource id */
        $splash_page = db()->where('splash_page_id', $splash_page_id)->where('user_id', $this->api_user->user_id)->getOne('splash_pages');

        /* We haven't found the resource */
        if(!$splash_page) {
            $this->return_404();
        }

        (new \Altum\Models\SplashPages())->delete($splash_page->splash_page_id);

        http_response_code(200);
        die();

    }

}
