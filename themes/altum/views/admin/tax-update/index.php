<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/taxes') ?>"><?= l('admin_taxes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_tax_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-paperclip text-primary-900 mr-2"></i> <?= l('admin_tax_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/taxes/admin_tax_dropdown_button.php', ['id' => $data->tax->tax_id]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= $data->tax->name ?>" disabled="disabled" />
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                <input type="text" id="description" name="description" class="form-control" value="<?= $data->tax->description ?>" disabled="disabled" />
            </div>

            <div class="row">
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="value"><i class="fas fa-fw fa-sm fa-percentage text-muted mr-1"></i> <?= l('admin_taxes.main.value') ?></label>
                        <input type="number" min="0" step=".01" id="value" name="value" class="form-control" value="<?= $data->tax->value ?>" disabled="disabled" />
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <label for="value_type"><i class="fas fa-fw fa-sm fa-list-ol text-muted mr-1"></i> <?= l('admin_taxes.main.value_type') ?></label>
                        <select id="value_type" name="value_type" class="custom-select" disabled="disabled">
                            <option value="percentage" <?= $data->tax->value_type == 'percentage' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.value_type_percentage') ?></option>
                            <option value="fixed" <?= $data->tax->value_type == 'fixed' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.value_type_fixed') ?></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                <select id="type" name="type" class="custom-select" disabled="disabled">
                    <option value="inclusive" <?= $data->tax->type == 'inclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.type_inclusive') ?></option>
                    <option value="exclusive" <?= $data->tax->type == 'exclusive' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.type_exclusive') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="billing_type"><i class="fas fa-fw fa-sm fa-receipt text-muted mr-1"></i> <?= l('admin_taxes.main.billing_type') ?></label>
                <select id="billing_type" name="billing_type" class="custom-select" disabled="disabled">
                    <option value="personal" <?= $data->tax->billing_type == 'personal' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.billing_type_personal') ?></option>
                    <option value="business" <?= $data->tax->billing_type == 'business' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.billing_type_business') ?></option>
                    <option value="both" <?= $data->tax->billing_type == 'both' ? 'selected="selected"' : null ?>><?= l('admin_taxes.main.billing_type_both') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="countries"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('global.countries') ?></label>
                <select id="countries" name="countries[]" class="custom-select" multiple="multiple" disabled="disabled">
                    <?php foreach(get_countries_array() as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $data->tax->countries && in_array($key, $data->tax->countries)  ? 'selected="selected"' : null ?>><?= $value ?></option>
                    <?php endforeach ?>
                </select>
                <small class="form-text text-muted"><?= l('admin_taxes.main.countries_help') ?></small>
            </div>

        </form>
    </div>
</div>

