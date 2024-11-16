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

class PaymentProcessors extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['payment_processor_id', 'processor', 'is_enabled'], ['name'], ['last_datetime', 'datetime']));
        $filters->set_default_order_by('payment_processor_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `payment_processors` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('payment-processors?' . $filters->get_get() . '&page=%d')));

        /* Get the data list for the user */
        $payment_processors = [];
        $payment_processors_result = database()->query("SELECT * FROM `payment_processors` WHERE `user_id` = {$this->user->user_id} {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $payment_processors_result->fetch_object()) {
            $payment_processors[] = $row;
        }

        /* Export handler */
        process_export_csv($payment_processors, 'include', ['payment_processor_id', 'user_id', 'name', 'processor', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('payment_processors.title')));
        process_export_json($payment_processors, 'include', ['payment_processor_id', 'user_id', 'name', 'processor', 'settings', 'is_enabled', 'last_datetime', 'datetime'], sprintf(l('payment_processors.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'payment_processors' => $payment_processors,
            'total_payment_processors' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('payment-processors/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.payment_processors')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('payment-processors');
        }

        if(empty($_POST)) {
            redirect('payment-processors');
        }

        $payment_processor_id = (int) query_clean($_POST['payment_processor_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$payment_processor = db()->where('payment_processor_id', $payment_processor_id)->where('user_id', $this->user->user_id)->getOne('payment_processors', ['name', 'payment_processor_id'])) {
            redirect('payment-processors');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the payment processor */
            db()->where('payment_processor_id', $payment_processor_id)->delete('payment_processors');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $payment_processor->name . '</strong>'));

            /* Clear the cache */
            cache()->deleteItemsByTag('payment_processors?user_id=' . $this->user->user_id);

            redirect('payment-processors');
        }

        redirect('payment-processors');
    }
}
