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

class GuestsPayments extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['biolink_block_id', 'link_id', 'payment_processor_id', 'project_id', 'type', 'processor'], ['email', 'name'], ['total_amount', 'datetime']));
        $filters->set_default_order_by('guest_payment_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `guests_payments` WHERE `user_id` = {$this->user->user_id} AND `status` = 1 {$filters->get_sql_where()}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('guests-payments?' . $filters->get_get() . '&page=%d')));

        /* Get the data list for the user */
        $guests_payments = [];
        $guests_payments_result = database()->query("SELECT * FROM `guests_payments` WHERE `user_id` = {$this->user->user_id} AND `status` = 1 {$filters->get_sql_where()} {$filters->get_sql_order_by()} {$paginator->get_sql_limit()}");
        while($row = $guests_payments_result->fetch_object()) {
            $guests_payments[] = $row;
        }

        /* Export handler */
        process_export_csv($guests_payments, 'include', ['guest_payment_id', 'biolink_block_id', 'link_id', 'payment_processor_id', 'project_id', 'user_id', 'processor', 'payment_id', 'email', 'name', 'total_amount', 'currency', 'status', 'datetime'], sprintf(l('guests_payments.title')));
        process_export_json($guests_payments, 'include', ['guest_payment_id', 'biolink_block_id', 'link_id', 'payment_processor_id', 'project_id', 'user_id', 'processor', 'payment_id', 'email', 'name', 'total_amount', 'currency', 'data', 'settings', 'status', 'datetime'], sprintf(l('guests_payments.title')));

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        /* Prepare the view */
        $data = [
            'guests_payments' => $guests_payments,
            'total_guests_payments' => $total_rows,
            'pagination' => $pagination,
            'filters' => $filters,
        ];

        $view = new \Altum\View('guests-payments/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

    public function delete() {

        \Altum\Authentication::guard();

        /* Team checks */
        if(\Altum\Teams::is_delegated() && !\Altum\Teams::has_access('delete.guests_payments')) {
            Alerts::add_info(l('global.info_message.team_no_access'));
            redirect('guests-payments');
        }

        if(empty($_POST)) {
            redirect('guests-payments');
        }

        $guest_payment_id = (int) query_clean($_POST['guest_payment_id']);

        //ALTUMCODE:DEMO if(DEMO) if($this->user->user_id == 1) Alerts::add_error('Please create an account on the demo to test out this function.');

        if(!\Altum\Csrf::check()) {
            Alerts::add_error(l('global.error_message.invalid_csrf_token'));
        }

        if(!$payment_processor = db()->where('guest_payment_id', $guest_payment_id)->where('user_id', $this->user->user_id)->getOne('guests_payments', ['name', 'guest_payment_id'])) {
            redirect('guests-payments');
        }

        if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

            /* Delete the payment processor */
            db()->where('guest_payment_id', $guest_payment_id)->delete('guests_payments');

            /* Set a nice success message */
            Alerts::add_success(sprintf(l('global.success_message.delete1'), '<strong>' . $payment_processor->name . '</strong>'));

            /* Clear the cache */
            cache()->deleteItemsByTag('guests_payments?user_id=' . $this->user->user_id);

            redirect('guests-payments');
        }

        redirect('guests-payments');
    }
}
