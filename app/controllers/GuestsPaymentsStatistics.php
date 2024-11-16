<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;


class GuestsPaymentsStatistics extends Controller {

    public function index() {
        \Altum\Authentication::guard();

        $_GET['biolink_block_id'] = (int) $_GET['biolink_block_id'];

        if(!$biolink_block = db()->where('biolink_block_id', $_GET['biolink_block_id'])->where('user_id', $this->user->user_id)->getOne('biolinks_blocks')) {
            redirect('guests-payments');
        }
        $biolink_block->settings = json_decode($biolink_block->settings ?? '');

        /* Statistics related variables */
        $datetime = \Altum\Date::get_start_end_dates_new();

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters(['biolink_block_id', 'link_id', 'payment_processor_id', 'project_id', 'processor'], [], []));

        /* Get the data list for the user */
        $guests_payments = [];
        $guests_payments_chart = [];

        $convert_tz_sql = get_convert_tz_sql('`datetime`', $this->user->timezone);

        $guests_payments_result = database()->query("
            SELECT
                COUNT(`guest_payment_id`) AS `payments`,
                SUM(`total_amount`) AS `total_amount`,
                DATE_FORMAT({$convert_tz_sql}, '{$datetime['query_date_format']}') AS `formatted_date`
            FROM
                 `guests_payments`
            WHERE
                  `user_id` = {$this->user->user_id}
                  AND `status` = 1
                  AND ({$convert_tz_sql} BETWEEN '{$datetime['query_start_date']}' AND '{$datetime['query_end_date']}')
                  {$filters->get_sql_where()} 
            GROUP BY
                `formatted_date`
            ORDER BY
                `formatted_date`
        ");
        while($row = $guests_payments_result->fetch_object()) {
            $guests_payments[] = $row;

            $row->formatted_date = $datetime['process']($row->formatted_date, true);

            $guests_payments_chart[$row->formatted_date] = [
                'payments' => $row->payments,
                'total_amount' => $row->total_amount
            ];
        }

        $guests_payments_chart = get_chart_data($guests_payments_chart);

        /* Prepare the view */
        $data = [
            'biolink_block' => $biolink_block,
            'guests_payments' => $guests_payments,
            'guests_payments_chart' => $guests_payments_chart,
            'datetime' => $datetime,
            'filters' => $filters,
        ];

        $view = new \Altum\View('guests-payments-statistics/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }
}
