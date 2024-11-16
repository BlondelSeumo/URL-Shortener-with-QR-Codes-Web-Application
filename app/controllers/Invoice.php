<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

namespace Altum\Controllers;

use Altum\Models\Plan;
use Altum\Title;

class Invoice extends Controller {

    public function index() {

        \Altum\Authentication::guard();

        $id = isset($this->params[0]) ? (int) $this->params[0] : null;

        /* Make sure the campaign exists and is accessible to the user */
        if(!$payment = db()->where('id', $id)->getOne('payments')) {
            redirect();
        }

        if($payment->user_id != $this->user->user_id && !\Altum\Authentication::is_admin()) {
            redirect();
        }

        /* Try to see if we get details from the billing */
        $payment->billing = json_decode($payment->billing ?? '');
        $payment->business = json_decode($payment->business ?? '');
        $payment->plan = json_decode($payment->plan ?? '');

        /* Get the plan details */
        $payment->plan_db = (new Plan())->get_plan_by_id($payment->plan_id);

        /* Check for potential taxes */
        $payment_taxes = (new \Altum\Models\Plan())->get_plan_taxes_by_taxes_ids($payment->taxes_ids);

        /* Calculate the price if a discount was used */
        $payment->price = $payment->discount_amount ? $payment->base_amount - $payment->discount_amount : $payment->base_amount;

        /* Calculate taxes */
        if(!empty($payment_taxes)) {

            /* Check for the inclusives */
            $inclusive_taxes_total_percentage = 0;

            foreach($payment_taxes as $key => $row) {
                if($row->type == 'exclusive') continue;

                $inclusive_taxes_total_percentage += $row->value;
            }

            $total_inclusive_tax = $payment->price - ($payment->price / (1 + $inclusive_taxes_total_percentage / 100));

            $price_without_inclusive_taxes = $payment->price - $total_inclusive_tax;

            foreach($payment_taxes as $key => $row) {
                if($row->type == 'exclusive') continue;

                $percentage_of_total_inclusive_tax = $row->value ? $row->value * 100 / $inclusive_taxes_total_percentage : 0;

                $inclusive_tax = number_format($total_inclusive_tax * $percentage_of_total_inclusive_tax / 100, 2);

                $payment_taxes[$key]->amount = $inclusive_tax;
            }

            /* Check for the exclusives */
            foreach($payment_taxes as $key => $row) {

                if($row->type == 'inclusive') {
                    continue;
                }

                $exclusive_tax = number_format($row->value_type == 'percentage' ? $price_without_inclusive_taxes * ($row->value / 100) : $row->value, 2);

                $payment_taxes[$key]->amount = $exclusive_tax;

            }

        }

        /* Set a custom title */
        Title::set(sprintf(l('invoice.title'), $payment->business->invoice_nr_prefix . $payment->id));

        /* Prepare the view */
        $data = [
            'payment' => $payment,
            'payment_taxes' => $payment_taxes
        ];

        $view = new \Altum\View('invoice/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
