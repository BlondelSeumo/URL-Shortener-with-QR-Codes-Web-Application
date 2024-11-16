<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(!in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <div class="alert alert-primary" role="alert">
            You need to own the Extended License in order to activate the payment system.
        </div>
    <?php endif ?>

    <div class="<?= !in_array(settings()->license->type, ['Extended License', 'extended']) ? 'container-disabled' : null ?>">
        <div class="form-group custom-control custom-switch">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="type"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.type') ?></label>
            <select id="type" name="type" class="custom-select">
                <option value="one_time" <?= settings()->payment->type == 'one_time' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_one_time') ?></option>
                <option value="recurring" <?= settings()->payment->type == 'recurring' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_recurring') ?></option>
                <option value="both" <?= settings()->payment->type == 'both' ? 'selected="selected"' : null ?>><?= l('admin_settings.payment.type_both') ?></option>
            </select>
        </div>

        <div class="form-group">
            <label for="default_payment_frequency"><i class="fas fa-fw fa-sm fa-shopping-bag text-muted mr-1"></i> <?= l('admin_settings.payment.default_payment_frequency') ?></label>
            <select id="default_payment_frequency" name="default_payment_frequency" class="custom-select">
                <option value="monthly" <?= settings()->payment->default_payment_frequency == 'monthly' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.monthly') ?></option>
                <option value="annual" <?= settings()->payment->default_payment_frequency == 'annual' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.annual') ?></option>
                <option value="lifetime" <?= settings()->payment->default_payment_frequency == 'lifetime' ? 'selected="selected"' : null ?>><?= l('plan.custom_plan.lifetime') ?></option>
            </select>
        </div>

        <label for="currencies"><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_settings.payment.currencies') ?></label>
        <div id="currencies">
            <?php foreach((array) settings()->payment->currencies ?? [] as $currency): ?>
                <div class="currency p-3 bg-gray-50 rounded mb-4">
                    <div class="form-group">
                        <label for="<?= 'code[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.code') ?></label>
                        <input id="<?= 'code[' . $currency->code . ']' ?>" type="text" name="code[<?= $currency->code ?>]" minlength="3" maxlength="3" class="form-control" value="<?= $currency->code ?>" placeholder="USD" required="required" />
                        <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.code_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="<?= 'symbol[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-euro-sign text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.symbol') ?></label>
                        <input id="<?= 'symbol[' . $currency->code . ']' ?>" type="text" name="symbol[<?= $currency->code ?>]" maxlength="3" class="form-control" value="<?= $currency->symbol ?>" placeholder="$" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="<?= 'default_payment_processor[' . $currency->code . ']' ?>"><i class="fas fa-fw fa-sm fa-piggy-bank text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.default_payment_processor') ?></label>
                        <select id="<?= 'default_payment_processor[' . $currency->code . ']' ?>" name="default_payment_processor[<?= $currency->code ?>]" class="custom-select" data-is-not-custom-select>
                            <?php foreach(require APP_PATH . 'includes/payment_processors.php' as $key => $value): ?>
                            <option value="<?= $key ?>" <?= $currency->default_payment_processor == $key ? 'selected="selected"' : null ?>><?= l('pay.custom_plan.' . $key) ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.default_payment_processor_help') ?></small>
                    </div>

                    <button type="button" data-remove="currencies" class="mb-3 btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times fa-sm mr-1"></i> <?= l('global.delete') ?></button>
                </div>
            <?php endforeach ?>
        </div>

        <div class="mb-4">
            <button data-add="currencies" type="button" class="btn btn-block btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
        </div>

        <div class="form-group">
            <label for="default_currency"><i class="fas fa-fw fa-sm fa-euro-sign text-muted mr-1"></i> <?= l('admin_settings.payment.default_currency') ?></label>
            <input id="default_currency" type="text" name="default_currency" class="form-control" value="<?= settings()->payment->default_currency ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.payment.default_currency_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="codes_is_enabled" name="codes_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->codes_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="codes_is_enabled"><i class="fas fa-fw fa-sm fa-tags text-muted mr-1"></i> <?= l('admin_settings.payment.codes_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.codes_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="taxes_and_billing_is_enabled" name="taxes_and_billing_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->taxes_and_billing_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="taxes_and_billing_is_enabled"><i class="fas fa-fw fa-sm fa-receipt text-muted mr-1"></i> <?= l('admin_settings.payment.taxes_and_billing_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.taxes_and_billing_is_enabled_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="invoice_is_enabled" name="invoice_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->invoice_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="invoice_is_enabled"><i class="fas fa-fw fa-sm fa-file-invoice text-muted mr-1"></i> <?= l('admin_settings.payment.invoice_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.invoice_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="user_plan_expiry_reminder"><i class="fas fa-fw fa-sm fa-envelope-open-text text-muted mr-1"></i> <?= l('admin_settings.payment.user_plan_expiry_reminder') ?></label>
            <div class="input-group">
                <input id="user_plan_expiry_reminder" type="number" min="0" name="user_plan_expiry_reminder" class="form-control" value="<?= settings()->payment->user_plan_expiry_reminder ?>" />
                <div class="input-group-append">
                    <span class="input-group-text"><?= l('global.date.days') ?></span>
                </div>
            </div>
            <small class="form-text text-muted"><?= l('admin_settings.payment.user_plan_expiry_reminder_help') ?></small>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="user_plan_expiry_checker_is_enabled" name="user_plan_expiry_checker_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payment->user_plan_expiry_checker_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="user_plan_expiry_checker_is_enabled"><i class="fas fa-fw fa-sm fa-credit-card text-muted mr-1"></i> <?= l('admin_settings.payment.user_plan_expiry_checker_is_enabled') ?></label>
            <small class="form-text text-muted"><?= l('admin_settings.payment.user_plan_expiry_checker_is_enabled_help') ?></small>
        </div>

        <div class="form-group">
            <label for="currency_exchange_api_key"><i class="fas fa-fw fa-sm fa-terminal text-muted mr-1"></i> <?= l('admin_settings.payment.currency_exchange_api_key') ?></label>
            <input id="currency_exchange_api_key" type="text" name="currency_exchange_api_key" class="form-control" value="<?= settings()->payment->currency_exchange_api_key ?>" />
            <small class="form-text text-muted"><?= l('admin_settings.payment.currency_exchange_api_key_help') ?></small>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<template id="template_currencies">
    <div class="currency p-3 bg-gray-50 rounded mb-4">
        <div class="form-group">
            <label for="<?= 'code[]' ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.code') ?></label>
            <input id="<?= 'code[]' ?>" type="text" name="code[]" class="form-control" value="" placeholder="USD" required="required" />
            <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.code_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'symbol[]' ?>"><i class="fas fa-fw fa-sm fa-euro-sign text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.symbol') ?></label>
            <input id="<?= 'symbol[]' ?>" type="text" name="symbol[]" class="form-control" value="" placeholder="$" required="required" />
        </div>

        <div class="form-group">
            <label for="<?= 'default_payment_processor[]' ?>"><i class="fas fa-fw fa-sm fa-piggy-bank text-muted mr-1"></i> <?= l('admin_settings.payment.currencies.default_payment_processor') ?></label>
            <select id="<?= 'default_payment_processor[]' ?>" name="default_payment_processor[]" class="custom-select" data-is-not-custom-select>
                <?php foreach(require APP_PATH . 'includes/payment_processors.php' as $key => $value): ?>
                    <option value="<?= $key ?>"><?= l('pay.custom_plan.' . $key) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('admin_settings.payment.currencies.default_payment_processor_help') ?></small>
        </div>

        <button type="button" data-remove="request" class="mb-3 btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
<script>
    'use strict';

    /* add new request header */
    let add = event => {
        let type = event.currentTarget.getAttribute('data-add');
        let clone = document.querySelector(`#template_${type}`).content.cloneNode(true);

        document.querySelector(`#${type}`).appendChild(clone);

        remove_initiator();
        code_initiator();
    };

    document.querySelectorAll('[data-add]').forEach(element => {
        element.addEventListener('click', add);
    })

    /* remove request header */
    let remove = event => {
        event.currentTarget.closest('.currency').remove();

        code_initiator();
    };

    let remove_initiator = () => {
        document.querySelectorAll('#currencies [data-remove]').forEach(element => {
            element.removeEventListener('click', remove);
            element.addEventListener('click', remove)
        })
    };

    remove_initiator();

    let code = event => {
        let currency = event.currentTarget.closest('.currency');
        let code = event.currentTarget.value;

        currency.querySelectorAll(`input,select`).forEach(element => {
            let cleaned_code = element.id.split('[')[0];
            element.name = `${cleaned_code}[${code}]`;
            element.id = `${cleaned_code}[${code}]`;
            element.closest('.form-group').querySelector('label').setAttribute('for', `${cleaned_code}[${code}]`);
        });
    }

    let code_initiator = () => {
        document.querySelectorAll('#currencies [name^="code"]').forEach(element => {
            element.removeEventListener('change', code);
            element.addEventListener('change', code)
        })
    }

    code_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
