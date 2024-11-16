<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(!in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <div class="alert alert-primary" role="alert">
            You need to own the Extended License in order to activate the payment system.
        </div>
    <?php endif ?>

    <div class="<?= !in_array(settings()->license->type, ['Extended License', 'extended']) ? 'container-disabled' : null ?>">
        <div class="form-group custom-control custom-switch">
            <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->payu->is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('admin_settings.payu.is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="mode"><?= l('admin_settings.payment.mode') ?></label>
            <select id="mode" name="mode" class="custom-select">
                <option value="secure" <?= settings()->payu->mode == 'secure' ? 'selected="selected"' : null ?>>secure</option>
                <option value="sandbox" <?= settings()->payu->mode == 'sandbox' ? 'selected="selected"' : null ?>>sandbox</option>
            </select>
        </div>

        <div class="form-group">
            <label for="merchant_pos_id"><?= l('admin_settings.payu.merchant_pos_id') ?></label>
            <input id="merchant_pos_id" type="text" name="merchant_pos_id" class="form-control" value="<?= settings()->payu->merchant_pos_id ?>" />
        </div>

        <div class="form-group">
            <label for="signature_key"><?= l('admin_settings.payu.signature_key') ?></label>
            <input id="signature_key" type="text" name="signature_key" class="form-control" value="<?= settings()->payu->signature_key ?>" />
        </div>

        <div class="form-group">
            <label for="oauth_client_id"><?= l('admin_settings.payu.oauth_client_id') ?></label>
            <input id="oauth_client_id" type="text" name="oauth_client_id" class="form-control" value="<?= settings()->payu->oauth_client_id ?>" />
        </div>

        <div class="form-group">
            <label for="oauth_client_secret"><?= l('admin_settings.payu.oauth_client_secret') ?></label>
            <input id="oauth_client_secret" type="text" name="oauth_client_secret" class="form-control" value="<?= settings()->payu->oauth_client_secret ?>" />
        </div>

        <div class="form-group">
            <label><i class="fas fa-fw fa-sm fa-coins text-muted mr-1"></i> <?= l('admin_settings.payment.currencies') ?></label>
            <div class="row">
                <?php foreach((array) settings()->payment->currencies as $currency => $currency_data): ?>
                    <div class="col-12 col-lg-4">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="<?= 'currency_' . $currency ?>" name="currencies[]" value="<?= $currency ?>" type="checkbox" class="custom-control-input" <?= in_array($currency, settings()->payu->currencies ?? []) ? 'checked="checked"' : null ?>>
                            <label class="custom-control-label d-flex align-items-center" for="<?= 'currency_' . $currency ?>">
                                <span><?= $currency ?></span>
                            </label>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
