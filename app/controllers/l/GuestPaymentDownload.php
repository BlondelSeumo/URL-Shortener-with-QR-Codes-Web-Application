<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

class GuestPaymentDownload extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('payment-blocks')) {
            redirect();
        }

        $_GET['guest_payment_id'] = (int) $_GET['guest_payment_id'];
        $_GET['key'] = input_clean($_GET['key']);

        /* Make sure the transaction exists */
        if(!$guest_payment = db()->where('guest_payment_id', $_GET['guest_payment_id'])->getOne('guests_payments')) {
            redirect();
        }

        /* Make sure the key is correct */
        if(md5($guest_payment->payment_id) != $_GET['key']) {
            redirect();
        }

        /* Get the biolink block */
        if(!$biolink_block = db()->where('biolink_block_id', $guest_payment->biolink_block_id)->getOne('biolinks_blocks')) {
            redirect();
        }
        $biolink_block->settings = json_decode($biolink_block->settings ?? '');

        if(!$biolink_block->settings->file) {
            redirect();
        }

        /* Download the file */
        $file_url = \Altum\Uploads::get_full_url('products_files') . $biolink_block->settings->file;
        header('Content-Type: application/octet-stream');
        header('Content-Transfer-Encoding: Binary');
        header('Content-disposition: attachment; filename="' . basename($file_url) . '"');
        readfile($file_url);
    }

}
