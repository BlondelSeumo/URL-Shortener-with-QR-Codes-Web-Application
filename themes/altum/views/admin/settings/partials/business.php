<?php defined('ALTUMCODE') || die() ?>

<div>
    <p class="text-muted"><?= l('admin_settings.business.subheader') ?></p>

    <div class="form-group">
        <label for="brand_name"><i class="fas fa-building text-muted mr-1"></i> <?= l('admin_settings.business.brand_name') ?></label>
        <input id="brand_name" type="text" name="brand_name" class="form-control" value="<?= settings()->business->brand_name ?>" />
    </div>

    <div class="form-group">
        <label for="invoice_nr_prefix"><i class="fas fa-receipt text-muted mr-1"></i> <?= l('admin_settings.business.invoice_nr_prefix') ?></label>
        <input id="invoice_nr_prefix" type="text" name="invoice_nr_prefix" class="form-control" value="<?= settings()->business->invoice_nr_prefix ?>" />
        <small class="form-text text-muted"><?= l('admin_settings.business.invoice_nr_prefix_help') ?></small>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="form-group">
                <label for="name"><i class="fas fa-signature text-muted mr-1"></i> <?= l('admin_settings.business.name') ?></label>
                <input id="name" type="text" name="name" class="form-control" value="<?= settings()->business->name ?>" />
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <label for="address"><i class="fas fa-map-marker-alt text-muted mr-1"></i> <?= l('admin_settings.business.address') ?></label>
                <input id="address" type="text" name="address" class="form-control" value="<?= settings()->business->address ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="city"><i class="fas fa-city text-muted mr-1"></i> <?= l('global.city') ?></label>
                <input id="city" type="text" name="city" class="form-control" value="<?= settings()->business->city ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-4">
            <div class="form-group">
                <label for="county"><i class="fas fa-building text-muted mr-1"></i> <?= l('admin_settings.business.county') ?></label>
                <input id="county" type="text" name="county" class="form-control" value="<?= settings()->business->county ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-2">
            <div class="form-group">
                <label for="zip"><i class="fas fa-arrow-up-9-1 text-muted mr-1"></i> <?= l('admin_settings.business.zip') ?></label>
                <input id="zip" type="text" name="zip" class="form-control" value="<?= settings()->business->zip ?>" />
            </div>
        </div>

        <div class="col-12">
            <div class="form-group">
                <label for="country"><i class="fas fa-flag text-muted mr-1"></i> <?= l('global.country') ?></label>
                <select id="country" name="country" class="custom-select">
                    <?php foreach(get_countries_array() as $key => $value): ?>
                        <option value="<?= $key ?>" <?= settings()->business->country == $key ? 'selected="selected"' : null ?>><?= $value ?></option>
                    <?php endforeach ?>
                </select>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="email"><i class="fas fa-envelope text-muted mr-1"></i> <?= l('global.email') ?></label>
                <input id="email" type="text" name="email" class="form-control" value="<?= settings()->business->email ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="phone"><i class="fas fa-phone-square-alt text-muted mr-1"></i> <?= l('admin_settings.business.phone') ?></label>
                <input id="phone" type="text" name="phone" class="form-control" value="<?= settings()->business->phone ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="tax_type"><i class="fas fa-file-invoice-dollar text-muted mr-1"></i> <?= l('admin_settings.business.tax_type') ?></label>
                <input id="tax_type" type="text" name="tax_type" class="form-control" value="<?= settings()->business->tax_type ?>" placeholder="<?= l('admin_settings.business.tax_type_placeholder') ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="tax_id"><i class="fas fa-id-card text-muted mr-1"></i> <?= l('admin_settings.business.tax_id') ?></label>
                <input id="tax_id" type="text" name="tax_id" class="form-control" value="<?= settings()->business->tax_id ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="custom_key_one"><i class="fas fa-key text-muted mr-1"></i> <?= l('admin_settings.business.custom_key') ?></label>
                <input id="custom_key_one" type="text" name="custom_key_one" class="form-control" value="<?= settings()->business->custom_key_one ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="custom_value_one"><i class="fas fa-pencil-alt text-muted mr-1"></i> <?= l('admin_settings.business.custom_value') ?></label>
                <input id="custom_value_one" type="text" name="custom_value_one" class="form-control" value="<?= settings()->business->custom_value_one ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="custom_key_two"><i class="fas fa-key text-muted mr-1"></i> <?= l('admin_settings.business.custom_key') ?></label>
                <input id="custom_key_two" type="text" name="custom_key_two" class="form-control" value="<?= settings()->business->custom_key_two ?>" />
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="form-group">
                <label for="custom_value_two"><i class="fas fa-pencil-alt text-muted mr-1"></i> <?= l('admin_settings.business.custom_value') ?></label>
                <input id="custom_value_two" type="text" name="custom_value_two" class="form-control" value="<?= settings()->business->custom_value_two ?>" />
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
