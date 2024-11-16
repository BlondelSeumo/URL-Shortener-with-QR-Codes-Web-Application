<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div>
        <div class="row mb-3">
            <div class="col-12 col-xl mb-3 mb-xl-0">
                <h1 class="h4"><?= $this->user->plan->translations->{\Altum\Language::$name}->name ?? $this->user->plan->name ?></h1>
                <?php if($this->user->plan_id != 'free' && (new \DateTime($this->user->plan_expiration_date)) < (new \DateTime())->modify('+5 years')): ?>
                    <p class="text-muted m-0">
                        <?=
                        $this->user->payment_subscription_id ?
                            sprintf(l('account_plan.plan.renews'), '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>', l('pay.custom_plan.' . $this->user->payment_processor), nr($this->user->payment_total_amount), $this->user->payment_currency)
                            : sprintf(l('account_plan.plan.expires'), '<strong>' . \Altum\Date::get($this->user->plan_expiration_date, 2) . '</strong>')
                        ?>
                    </p>
                <?php endif ?>
            </div>

            <?php if(settings()->payment->is_enabled): ?>
                <div class="col-12 col-xl-auto">
                    <?php if($this->user->plan_id == 'free'): ?>
                        <a href="<?= url('plan/upgrade') ?>" class="btn btn-outline-primary"><i class="fas fa-fw fa-sm fa-arrow-up"></i> <?= l('account.plan.upgrade_plan') ?></a>
                    <?php else: ?>
                        <a href="<?= url('plan/renew') ?>" class="btn btn-outline-primary"><i class="fas fa-fw fa-sm fa-sync-alt"></i> <?= l('account.plan.renew_plan') ?></a>
                    <?php endif ?>
                </div>
            <?php endif ?>
        </div>

        <div class="card">
            <div class="card-body">

                <?= (new \Altum\View('partials/plan_features'))->run(['plan_settings' => $this->user->plan_settings]) ?>

            </div>
        </div>
    </div>

    <?php if($this->user->plan_id != 'free' && $this->user->payment_subscription_id): ?>
        <hr class="border-gray-50 my-4" />

        <h1 class="h4"><?= l('account_plan.cancel.header') ?></h1>
        <p class="text-muted"><?= l('account_plan.cancel.subheader') ?></p>

        <div class="card">
            <div class="card-body">
                <a href="<?= url('account-plan/cancel_subscription' . \Altum\Csrf::get_url_query()) ?>" class="btn btn-block btn-outline-secondary" onclick='return confirm(<?= json_encode(l('account_plan.cancel.confirm_message')) ?>)'><?= l('account_plan.cancel.cancel') ?></a>
            </div>
        </div>
    <?php endif ?>
</div>

