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

class Referrals extends Controller {

    public function index() {

        if(!\Altum\Plugin::is_active('affiliate') || (\Altum\Plugin::is_active('affiliate') && !settings()->affiliate->is_enabled)) {
            redirect('not-found');
        }

        \Altum\Authentication::guard();

        /* Get details for statistics */
        $referrals_statistics = database()->query("SELECT COUNT(`user_id`) AS `referrals`, SUM(`referred_by_has_converted`) AS `converted_referrals` FROM `users` WHERE `referred_by` = {$this->user->user_id}")->fetch_object() ?? null;

        $pending_affiliate_commissions_date = (new \DateTime())->modify('-30 days')->format('Y-m-d H:i:s');
        $pending_affiliate_commissions = database()->query("SELECT SUM(`amount`) AS `total` FROM `affiliates_commissions` WHERE `user_id` = {$this->user->user_id} AND `datetime` > '{$pending_affiliate_commissions_date}' AND `is_withdrawn` = 0")->fetch_object()->total ?? 0;
        $approved_affiliate_commissions = database()->query("SELECT SUM(`amount`) AS `total` FROM `affiliates_commissions` WHERE `user_id` = {$this->user->user_id} AND `datetime` < '{$pending_affiliate_commissions_date}' AND `is_withdrawn` = 0")->fetch_object()->total ?? 0;
        $approved_affiliate_commissions = number_format($approved_affiliate_commissions, 2, '.', '');

        /* Prepare the filtering system */
        $filters = (new \Altum\Filters());
        $filters->set_default_order_by('affiliate_withdrawal_id', $this->user->preferences->default_order_type ?? settings()->main->default_order_type);
        $filters->set_default_results_per_page($this->user->preferences->default_results_per_page ?? settings()->main->default_results_per_page);

        /* Prepare the paginator */
        $total_rows = database()->query("SELECT COUNT(*) AS `total` FROM `affiliates_withdrawals` WHERE `user_id` = {$this->user->user_id}")->fetch_object()->total ?? 0;
        $paginator = (new \Altum\Paginator($total_rows, $filters->get_results_per_page(), $_GET['page'] ?? 1, url('referrals?' . $filters->get_get() . '&page=%d')));

        /* Get withdrawals */
        $affiliate_commission_is_pending = false;
        $affiliate_withdrawals = [];
        $affiliate_withdrawals_result = database()->query("SELECT * FROM `affiliates_withdrawals` WHERE `user_id` = {$this->user->user_id} {$paginator->get_sql_limit()}");
        while($row = $affiliate_withdrawals_result->fetch_object()) {
            $affiliate_withdrawals[] = $row;

            if(!$row->is_paid) {
                $affiliate_commission_is_pending = true;
            }
        }

        /* Prepare the pagination view */
        $pagination = (new \Altum\View('partials/pagination', (array) $this))->run(['paginator' => $paginator]);

        if(!empty($_POST)) {
            $_POST['amount'] = number_format((float) $_POST['amount'], 2, '.', '');
            $_POST['note'] = trim(query_clean($_POST['note']));

            /* Check for any errors */
            if(!\Altum\Csrf::check()) {
                Alerts::add_error(l('global.error_message.invalid_csrf_token'));
            }

            if($_POST['amount'] < settings()->affiliate->minimum_withdrawal_amount) {
                redirect('referrals');
            }

            if($approved_affiliate_commissions < settings()->affiliate->minimum_withdrawal_amount) {
                redirect('referrals');
            }

            if($_POST['amount'] > $approved_affiliate_commissions) {
                redirect('referrals');
            }

            if(!Alerts::has_field_errors() && !Alerts::has_errors()) {

                /* Get approved affiliate commissions ids */
                $affiliate_commissions_ids = [];
                $amount = 0;
                $result = database()->query("SELECT `affiliate_commission_id`, `amount` FROM `affiliates_commissions` WHERE `user_id` = {$this->user->user_id} AND `datetime` < '{$pending_affiliate_commissions_date}' AND `is_withdrawn` = 0");
                while($row = $result->fetch_object()) {
                    $affiliate_commissions_ids[] = $row->affiliate_commission_id;
                    $amount += $row->amount;
                }
                $affiliate_commissions_ids = json_encode($affiliate_commissions_ids);
                $amount = number_format($amount, 2, '.', '');

                /* Database query */
                db()->insert('affiliates_withdrawals', [
                    'user_id' => $this->user->user_id,
                    'amount' => $amount,
                    'currency' => settings()->payment->default_currency,
                    'note' => $_POST['note'],
                    'affiliate_commissions_ids' => $affiliate_commissions_ids,
                    'datetime' => get_date(),
                ]);

                /* Send notification to admin if needed */
                if(settings()->email_notifications->new_affiliate_withdrawal && !empty(settings()->email_notifications->emails)) {
                    /* Prepare the email */
                    $email_template = get_email_template(
                        [
                            '{{TOTAL_AMOUNT}}' => $amount,
                            '{{CURRENCY}}' => settings()->payment->default_currency,
                        ],
                        l('global.emails.admin_new_affiliate_withdrawal_notification.subject'),
                        [
                            '{{NAME}}' => $this->user->name,
                            '{{EMAIL}}' => $this->user->email,
                            '{{TOTAL_AMOUNT}}' => $amount,
                            '{{CURRENCY}}' => settings()->payment->default_currency,
                            '{{AFFILIATE_WITHDRAWAL_NOTE}}' => $_POST['note'],
                            '{{ADMIN_AFFILIATE_WITHDRAWAL_LINK}}' => url('admin/affiliates-withdrawals'),
                        ],
                        l('global.emails.admin_new_affiliate_withdrawal_notification.body')
                    );

                    send_mail(explode(',', settings()->email_notifications->emails), $email_template->subject, $email_template->body);
                }

                if(settings()->internal_notifications->admins_is_enabled && settings()->internal_notifications->new_affiliate_withdrawal) {
                    db()->insert('internal_notifications', [
                        'for_who' => 'admin',
                        'from_who' => 'system',
                        'icon' => 'fas fa-wallet',
                        'title' => l('global.notifications.new_affiliate_withdrawal.title'),
                        'description' => sprintf(l('global.notifications.new_affiliate_withdrawal.description'), $this->user->name, $this->user->email, $amount, settings()->payment->default_currency),
                        'url' => 'admin/affiliates-withdrawals',
                        'datetime' => get_date(),
                    ]);
                }

                /* Set a nice success message */
                Alerts::add_success(l('referrals.withdraw.success_message'));

                redirect('referrals');
            }

        }

        /* Get the account header menu */
        $menu = new \Altum\View('partials/account_header_menu', (array) $this);
        $this->add_view_content('account_header_menu', $menu->run());

        /* Prepare the view */
        $data = [
            'referrals_statistics' => $referrals_statistics,
            'pending_affiliate_commissions' => $pending_affiliate_commissions,
            'approved_affiliate_commissions' => $approved_affiliate_commissions,

            'affiliate_commission_is_pending' => $affiliate_commission_is_pending,
            'affiliate_withdrawals' => $affiliate_withdrawals,
            'pagination' => $pagination
        ];

        $view = new \Altum\View('referrals/index', (array) $this);

        $this->add_view_content('content', $view->run($data));

    }

}
