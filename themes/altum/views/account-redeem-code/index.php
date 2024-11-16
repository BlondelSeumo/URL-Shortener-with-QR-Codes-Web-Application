<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['account_header_menu'] ?>

    <div class="d-flex align-items-center mb-3">
        <h1 class="h4 m-0"><?= l('account_redeem_code.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('account_redeem_code.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="plan_id"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i>  <?= l('account_redeem_code.plan_id') ?></label>
                    <select id="plan_id" name="plan_id" class="custom-select" required="required">
                        <?php foreach($data->plans as $plan): ?>
                            <?php if($plan->status != 1) continue ?>
                            <option value="<?= $plan->plan_id ?>" <?= $data->values['plan_id'] == $plan->plan_id ? 'selected="selected"' : null ?>><?= $plan->translations->{\Altum\Language::$name}->name ?: $plan->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="code"><i class="fas fa-fw fa-sm fa-tag text-muted mr-1"></i>  <?= l('account_redeem_code.code') ?></label>
                    <input type="text" id="code" name="code" class="form-control <?= \Altum\Alerts::has_field_errors('code') ? 'is-invalid' : null ?>" value="<?= $data->values['code'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('code') ?>
                </div>

                <div class="alert alert-info">
                    <?= l('account_redeem_code.info_message') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>
</div>
