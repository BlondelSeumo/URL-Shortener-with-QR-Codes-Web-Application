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

class AdminCodeCreate extends Controller {

    public function index() {

        /* Requested plan details */
        $plans = (new \Altum\Models\Plan())->get_plans();

        if(!empty($_POST)) {
            /* Filter some the variables */
            $_POST['name'] = input_clean($_POST['name'], 64);
            $_POST['type'] = in_array($_POST['type'], ['discount', 'redeemable']) ? input_clean($_POST['type']) : 'discount';
            $_POST['days'] = $_POST['type'] == 'redeemable' ? (int) $_POST['days'] : null;
            $_POST['discount'] = $_POST['type'] == 'redeemable' ? 100 : (int) $_POST['discount'];
            $_POST['quantity'] = (int) $_POST['quantity'];
            $_POST['code'] = input_clean(get_slug($_POST['code'], '-', false), 32);
            $_POST['is_bulk'] = (int) isset($_POST['is_bulk']);
            $_POST['amount'] = (int) $_POST['amount'];
            $_POST['prefix'] = mb_strtoupper(input_clean($_POST['prefix']));

            //ALTUMCODE:DEMO if(DEMO) Alerts::add_error('This command is blocked on the demo.');

            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Bulk generator */
                if($_POST['is_bulk']) {
                    $codes = [];

                    while(count($codes) < $_POST['amount']) {
                        $code = $_POST['prefix'] . mb_strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
                        $codes[$code] = null;
                    }

                    $codes = array_keys($codes);

                    foreach($codes as $code) {
                        /* Database query */
                        db()->insert('codes', [
                            'name' => $code,
                            'type' => $_POST['type'],
                            'days' => $_POST['days'],
                            'code' => $code,
                            'discount' => $_POST['discount'],
                            'quantity' => $_POST['quantity'],
                            'plans_ids' => json_encode($_POST['plans_ids']),
                            'datetime' => get_date(),
                        ]);
                    }
                }

                /* Normal database insertion */
                else {
                    /* Database query */
                    db()->insert('codes', [
                        'name' => $_POST['name'],
                        'type' => $_POST['type'],
                        'days' => $_POST['days'],
                        'code' => $_POST['code'],
                        'discount' => $_POST['discount'],
                        'quantity' => $_POST['quantity'],
                        'plans_ids' => json_encode($_POST['plans_ids']),
                        'datetime' => get_date(),
                    ]);
                }

                /* Set a nice success message */
                Alerts::add_success(l('global.success_message.create2'));

                redirect('admin/codes');
            }
        }

        $values = [
            'type' => $_POST['type'] ?? 'discount',
        ];

        /* Main View */
        $data = [
            'values' => $values,
            'plans' => $plans,
        ];

        $view = new \Altum\View('admin/code-create/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
