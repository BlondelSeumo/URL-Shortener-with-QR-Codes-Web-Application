<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Title;

class AdminLogDownload extends Controller {

    public function index() {

        /* Clear files caches */
        clearstatcache();

        $log_id = isset($this->params[0]) ? input_clean($this->params[0]) : null;

        if(!$log_id) {
            redirect('admin/logs');
        }

        $log_id = preg_replace('/[^a-zA-Z0-9-]/', '', $log_id);

        if(!file_exists(UPLOADS_PATH . 'logs/' . $log_id . '.log')) {
            redirect('admin/logs');
        }

        /* Set a custom title */
        Title::set(sprintf(l('admin_log.title'), $log_id));

        /* Prepare headers */
        header('Content-Description: File Transfer');
        header('Content-Type: text/plain');
        header('Content-Disposition: attachment; filename="' . $log_id . '.log"');
        header('Content-Length: ' . filesize(UPLOADS_PATH . 'logs/' . $log_id . '.log'));

        /* Output data */
        ob_clean();
        flush();
        readfile(UPLOADS_PATH . 'logs/' . $log_id . '.log');

    }

}
