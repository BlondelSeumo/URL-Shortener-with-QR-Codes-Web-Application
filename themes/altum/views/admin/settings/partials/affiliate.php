<?php defined('ALTUMCODE') || die() ?>

<div>
    <?php if(!in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
        <div class="alert alert-primary" role="alert">
            You need to own the Extended License in order to activate the affiliate plugin system.
        </div>
    <?php endif ?>

    <div <?= !\Altum\Plugin::is_active('affiliate') ? 'data-toggle="tooltip" title="' . sprintf(l('admin_plugins.no_access'), \Altum\Plugin::get('affiliate')->name ?? 'affiliate') . '"' : null ?>>
        <div class="<?= !in_array(settings()->license->type, ['Extended License', 'extended']) || !\Altum\Plugin::is_active('affiliate') ? 'container-disabled' : null ?>">
            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-wallet text-muted mr-1"></i> <?= l('admin_settings.affiliate.is_enabled') ?></label>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.is_enabled_help') ?></small>
            </div>

            <div class="form-group">
                <label for="commission_type"><i class="fas fa-fw fa-sm fa-hand-holding-usd text-muted mr-1"></i> <?= l('admin_settings.affiliate.commission_type') ?></label>
                <select id="commission_type" name="commission_type" class="custom-select">
                    <option value="once" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->commission_type == 'once' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.commission_type_once') ?></option>
                    <option value="forever" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->commission_type == 'forever' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.commission_type_forever') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="tracking_type"><i class="fas fa-fw fa-sm fa-spider text-muted mr-1"></i> <?= l('admin_settings.affiliate.tracking_type') ?></label>
                <select id="tracking_type" name="tracking_type" class="custom-select">
                    <option value="first" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->tracking_type == 'first' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.tracking_type_first') ?></option>
                    <option value="last" <?= \Altum\Plugin::is_active('affiliate') && settings()->affiliate->tracking_type == 'last' ? 'selected="selected"' : null ?>><?= l('admin_settings.affiliate.tracking_type_last') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="tracking_duration"><i class="fas fa-fw fa-sm fa-cookie text-muted mr-1"></i> <?= l('admin_settings.affiliate.tracking_duration') ?></label>
                <div class="input-group">
                    <input id="tracking_duration" type="number" min="1" name="tracking_duration" class="form-control" value="<?= \Altum\Plugin::is_active('affiliate') ? settings()->affiliate->tracking_duration : 30 ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= l('global.date.days') ?></span>
                    </div>
                </div>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.tracking_duration_help') ?></small>
            </div>

            <div class="form-group">
                <label for="minimum_withdrawal_amount"><i class="fas fa-fw fa-sm fa-piggy-bank text-muted mr-1"></i> <?= l('admin_settings.affiliate.minimum_withdrawal_amount') ?></label>
                <div class="input-group">
                    <input id="minimum_withdrawal_amount" type="number" min="1" name="minimum_withdrawal_amount" class="form-control" value="<?= \Altum\Plugin::is_active('affiliate') ? settings()->affiliate->minimum_withdrawal_amount : 1 ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text"><?= settings()->payment->default_currency ?></span>
                    </div>
                </div>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.minimum_withdrawal_amount_help') ?></small>
            </div>

            <div class="form-group">
                <label for="withdrawal_notes" class="d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-fw fa-sm fa-sticky-note text-muted mr-1"></i> <?= l('admin_settings.affiliate.withdrawal_notes') ?></span>
                    <button class="btn btn-sm btn-dark" type="button" data-toggle="collapse" data-target="#withdrawal_notes_translate_container" aria-expanded="false" aria-controls="withdrawal_notes_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                </label>
                <textarea id="withdrawal_notes" name="withdrawal_notes" class="form-control"><?= \Altum\Plugin::is_active('affiliate') ? settings()->affiliate->withdrawal_notes : null ?></textarea>
                <small class="form-text text-muted"><?= l('admin_settings.affiliate.withdrawal_notes_help') ?></small>
            </div>

            <div class="collapse" id="withdrawal_notes_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_withdrawal_notes' ?>"><i class="fas fa-fw fa-sm fa-sticky-note text-muted mr-1"></i> <?= l('admin_settings.announcements.content') ?> - <?= $language_name ?></label>
                            <textarea id="<?= 'translation_' . $language_name . '_withdrawal_notes' ?>" name="<?= 'translations[' . $language_name . '][withdrawal_notes]' ?>" class="form-control"><?= settings()->affiliate->translations->{$language_name}->withdrawal_notes ?? null ?></textarea>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if(\Altum\Plugin::is_active('affiliate')): ?>
    <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
<?php endif ?>
