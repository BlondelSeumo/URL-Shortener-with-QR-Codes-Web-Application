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

class Affiliate extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('affiliate') || (\Altum\Plugin::is_active('affiliate') && !settings()->affiliate->is_enabled)) {
            redirect('not-found');
        }

        /* Get the min & max of commission for affiliates */
        $plans = (new \Altum\Models\Plan())->get_plans();
        $plans['free'] = (new Plan())->get_plan_by_id('free');
        $plans['custom'] = (new Plan())->get_plan_by_id('custom');
        unset($plans['guest']);

        $minimum_commission = 100;
        $maximum_commission = 0;

        foreach($plans as $plan) {
            if($plan->settings->affiliate_commission_percentage > $maximum_commission) $maximum_commission = $plan->settings->affiliate_commission_percentage;
            if($plan->settings->affiliate_commission_percentage < $minimum_commission) $minimum_commission = $plan->settings->affiliate_commission_percentage;
        }

        /* Meta */
        \Altum\Meta::set_canonical_url();

        /* Prepare the view */
        $data = [
            'minimum_commission' => $minimum_commission,
            'maximum_commission' => $maximum_commission,
        ];

        $view = new \Altum\View('affiliate/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}


