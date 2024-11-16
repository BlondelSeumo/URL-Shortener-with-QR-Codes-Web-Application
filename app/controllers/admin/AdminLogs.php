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

class AdminLogs extends Controller {

    public function index() {
        /* Clear files caches */
        clearstatcache();

        /* Get the data */
        $logs = [];

        foreach(glob(UPLOADS_PATH . 'logs/' . '*.log') as $file_path) {
            $file_path_exploded = explode('/', $file_path);
            $file_name = str_replace('.log', '', trim(end($file_path_exploded)));
            $file_last_modified = filemtime($file_path);

            $logs[$file_last_modified] = (object) [
                'name' => $file_name,
                'full_name' => $file_name . '.log',
                'extension' => 'log',
                'size' => filesize($file_path),
                'last_modified' => date('Y-m-d H:i:s', $file_last_modified),
            ];
        }

        krsort($logs);

        /* Main View */
        $data = [
            'logs' => $logs,
        ];

        $view = new \Altum\View('admin/logs/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function bulk() {

        /* Check for any errors */
        if(empty($_POST)) {
            redirect('admin/logs');
        }

        if(empty($_POST['selected'])) {
            redirect('admin/logs');
        }

        if(!isset($_POST['type'])) {
            redirect('admin/logs');
        }

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            switch($_POST['type']) {
                case 'delete':

                    foreach($_POST['selected'] as $id) {
                        $id = preg_replace('/[^a-zA-Z0-9-]/', '', input_clean($id));
                        unlink(UPLOADS_PATH . 'logs/' . $id . '.log');
                    }
                    break;
            }

            /* Set a nice success message */
            Alerts::add_success(l('bulk_delete_modal.success_message'));

        }

        redirect('admin/logs');
    }

    public function delete() {

        $log_id = isset($this->params[0]) ? input_clean($this->params[0]) : null;

        if(!$log_id) {
            redirect('admin/logs');
        }

        $log_id = preg_replace('/[^a-zA-Z0-9-]/', '', $log_id);

        //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

        if(!\Altum\Csrf::check('global_token')) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!file_exists(UPLOADS_PATH . 'logs/' . $log_id . '.log')) {
            redirect('admin/logs');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the resource */
            unlink(UPLOADS_PATH . 'logs/' . $log_id . '.log');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $log_id . '</strong>'));

        }

        redirect('admin/logs');
    }

}
