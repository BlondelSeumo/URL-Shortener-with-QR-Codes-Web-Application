<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <h1 class="h4"><?= l('referrals.invite.header') ?></h1>
    <p class="text-muted"><?= sprintf(l('referrals.invite.subheader_' . settings()->affiliate->commission_type), '<strong>' . settings()->main->title . '</strong>', '<strong>' . $this->user->plan_settings->affiliate_commission_percentage . '%</strong>') ?></p>

    <div <?= $this->user->plan_settings->affiliate_commission_percentage ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
        <div class="card <?= $this->user->plan_settings->affiliate_commission_percentage ? null : 'container-disabled' ?>">
            <div class="card-body">

                <div class="form-group">
                    <label for="referral_key"><?= l('referrals.invite.referral_key') ?></label>
                    <div class="input-group">
                        <input type="text" id="referral_key" name="referral_key" value="<?= SITE_URL . '?ref=' . $this->user->referral_key ?>" class="form-control" onclick="this.select();" readonly="readonly" />
                        <div class="input-group-append">
                            <button
                                    id="url_copy"
                                    type="button"
                                    class="btn btn-light"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text="<?= SITE_URL . '?ref=' . $this->user->referral_key ?>"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= sprintf(l('referrals.invite.referral_key_help'), '<code>ref=' . $this->user->referral_key . '</code>') ?></small>
                </div>

            </div>
        </div>
    </div>

    <hr class="border-gray-50 my-4" />

    <h1 class="h4 mb-3"><?= l('referrals.statistics.header') ?></h1>

    <div class="row justify-content-between">
        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body">

                    <div class="d-flex">
                        <span class="text-muted"><?= l('referrals.statistics.referrals') ?></span>

                        <span class="ml-1" data-toggle="tooltip" title="<?= l('referrals.statistics.referrals_help') ?>">
                            <i class="fas fa-fw fa-sm fa-info-circle text-muted"></i>
                        </span>
                    </div>

                    <div class="mt-3">
                        <span class="h3 m-0 text-blue-500"><?= nr($data->referrals_statistics->referrals) ?></span>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body">

                    <div class="d-flex">
                        <span class="text-muted"><?= l('referrals.statistics.converted_referrals') ?></span>

                        <span class="ml-1" data-toggle="tooltip" title="<?= l('referrals.statistics.converted_referrals_help') ?>">
                            <i class="fas fa-fw fa-sm fa-info-circle text-muted"></i>
                        </span>
                    </div>

                    <div class="mt-3">
                        <span class="h3 m-0 text-blue-500"><?= nr($data->referrals_statistics->converted_referrals) ?></span>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body">

                    <div class="d-flex">
                        <span class="text-muted"><?= l('referrals.statistics.pending_affiliate_commissions') ?></span>

                        <span class="ml-1" data-toggle="tooltip" title="<?= l('referrals.statistics.pending_affiliate_commissions_help') ?>">
                            <i class="fas fa-fw fa-sm fa-info-circle text-muted"></i>
                        </span>
                    </div>

                    <div class="mt-3">
                        <span class="h3 m-0 text-blue-500"><?= nr($data->pending_affiliate_commissions, 2) . ' ' . settings()->payment->default_currency ?></span>
                    </div>

                </div>
            </div>
        </div>

        <div class="col-12 col-xl mb-3 mb-xl-0">
            <div class="card h-100">
                <div class="card-body">

                    <div class="d-flex">
                        <span class="text-muted"><?= l('referrals.statistics.approved_affiliate_commissions') ?></span>

                        <span class="ml-1" data-toggle="tooltip" title="<?= l('referrals.statistics.approved_affiliate_commissions_help') ?>">
                            <i class="fas fa-fw fa-sm fa-info-circle text-muted"></i>
                        </span>
                    </div>

                    <div class="mt-3">
                        <span class="h3 m-0 text-blue-500"><?= nr($data->approved_affiliate_commissions, 2) . ' ' . settings()->payment->default_currency ?></span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <hr class="border-gray-50 my-4" />

    <h1 class="h4"><?= l('referrals.withdraw.header') ?></h1>
    <p class="text-muted"><?= sprintf(l('referrals.withdraw.subheader'), '<strong>' . settings()->affiliate->minimum_withdrawal_amount . ' ' . settings()->payment->default_currency . '</strong>') ?></p>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="amount"><?= l('referrals.withdraw.amount') ?></label>
                    <div class="input-group">
                        <input type="number" id="amount" name="amount" value="<?= $data->approved_affiliate_commissions ?>" class="form-control" readonly="readonly" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= settings()->payment->default_currency ?></span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="note"><?= settings()->affiliate->translations->{\Altum\Language::$name}->withdrawal_notes ?: settings()->affiliate->withdrawal_notes; ?></label>
                    <textarea id="note" name="note" class="form-control"></textarea>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary" <?= $data->affiliate_commission_is_pending || ($data->approved_affiliate_commissions < settings()->affiliate->minimum_withdrawal_amount) ? 'disabled="disabled"' : null ?>><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(count($data->affiliate_withdrawals)): ?>
        <div class="mt-4">
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th><?= l('referrals.withdraw.amount') ?></th>
                        <th><?= l('global.status') ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php foreach($data->affiliate_withdrawals as $row): ?>
                        <tr>
                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <span><?= nr($row->amount, 2) . ' ' . settings()->payment->default_currency ?></span>
                                    <small class="text-muted"><?= $row->note ?></small>
                                </div>
                            </td>
                            <td class="text-nowrap">
                                <?php if($row->is_paid): ?>
                                    <span class="badge badge-success"><i class="fas fa-fw fa-check"></i> <?= l('referrals.withdraw.is_paid_paid') ?></span>
                                <?php else: ?>
                                    <span class="badge badge-warning"><i class="fas fa-fw fa-eye-slash"></i> <?= l('referrals.withdraw.is_paid_pending') ?></span>
                                <?php endif ?>
                            </td>
                            <td class="text-nowrap text-muted">
                            <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>">
                                <?= \Altum\Date::get($row->datetime, 2) ?>
                            </span>
                            </td>
                        </tr>
                    <?php endforeach ?>

                    </tbody>
                </table>
            </div>

            <div class="mt-3"><?= $data->pagination ?></div>
        </div>
    <?php endif ?>

</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
