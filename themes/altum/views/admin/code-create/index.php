<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/codes') ?>"><?= l('admin_codes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_code_create.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-tags text-primary-900 mr-2"></i> <?= l('admin_code_create.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input type="text" id="name" name="name" class="form-control" maxlength="64" required="required" />
            </div>

            <div class="form-group">
                <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-12 col-lg-6">
                        <label class="btn btn-light btn-block text-truncate <?= $data->values['type'] == 'discount' ? 'active"' : null?>">
                            <input type="radio" name="type" value="discount" class="custom-control-input" <?= $data->values['type'] == 'discount' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-percent fa-fw fa-sm mr-1"></i> <?= l('admin_codes.main.type_discount') ?>
                        </label>
                    </div>

                    <div class="col-12 col-lg-6">
                        <label class="btn btn-light btn-block text-truncate <?= $data->values['type'] == 'redeemable' ? 'active"' : null?>">
                            <input type="radio" name="type" value="redeemable" class="custom-control-input" <?= $data->values['type'] == 'redeemable' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-parachute-box fa-fw fa-sm mr-1"></i> <?= l('admin_codes.main.type_redeemable') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="code"><i class="fas fa-fw fa-sm fa-tags text-muted mr-1"></i> <?= l('admin_codes.main.code') ?></label>
                <input type="text" id="code" name="code" class="form-control" maxlength="32" required="required" />
            </div>

            <div class="form-group" data-type="discount">
                <label for="discount"><i class="fas fa-fw fa-sm fa-percentage text-muted mr-1"></i> <?= l('admin_codes.main.discount') ?></label>
                <input id="discount" type="number" min="1" max="99" name="discount" class="form-control" value="1" required="required" />
                <small class="form-text text-muted"><?= l('admin_codes.main.discount_help') ?></small>
            </div>

            <div class="form-group" data-type="redeemable">
                <label for="days"><i class="fas fa-fw fa-sm fa-calendar-day text-muted mr-1"></i> <?= l('admin_codes.main.days') ?></label>
                <input id="days" type="number" min="1" max="999999" name="days" class="form-control" value="1" required="required" />
                <small class="form-text text-muted"><?= l('admin_codes.main.days_help') ?></small>
            </div>

            <div class="form-group">
                <label for="quantity"><i class="fas fa-fw fa-sm fa-sort-numeric-up-alt text-muted mr-1"></i> <?= l('admin_codes.main.quantity') ?></label>
                <input type="number" min="1" id="quantity" name="quantity" class="form-control" value="1" required="required" />
                <small class="form-text text-muted"><?= l('admin_codes.main.quantity_help') ?></small>
            </div>

            <div class="form-group">
                <label for="plans_ids"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i> <?= l('admin_codes.main.plans_ids') ?></label>
                <div class="row">
                    <?php foreach($data->plans as $plan_id => $plan): ?>
                        <div class="col-12 col-lg-4">
                            <div class="custom-control custom-checkbox my-2">
                                <input id="<?= 'plan_id_' . $plan_id ?>" name="plans_ids[]" value="<?= $plan_id ?>" type="checkbox" class="custom-control-input" <?= in_array($plan_id, $data->values['plans_ids'] ?? []) ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label d-flex align-items-center" for="<?= 'plan_id_' . $plan_id ?>">
                                    <span><?= $plan->name ?></span>
                                </label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_bulk" name="is_bulk" type="checkbox" class="custom-control-input">
                <label class="custom-control-label" for="is_bulk"><?= l('admin_codes.main.is_bulk') ?></label>
            </div>

            <div id="bulk_container">
                <div class="form-group">
                    <label for="amount"><i class="fas fa-fw fa-sm fa-sort-amount-up-alt text-muted mr-1"></i> <?= l('admin_codes.main.amount') ?></label>
                    <input id="amount" type="number" min="1" max="50000" name="amount" class="form-control" value="1" />
                </div>

                <div class="form-group">
                    <label for="prefix"><i class="fas fa-fw fa-sm fa-quote-right text-muted mr-1"></i> <?= l('admin_codes.main.prefix') ?></label>
                    <input type="text" id="prefix" name="prefix" class="form-control" />
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>
        </form>

    </div>
</div>

<?php ob_start() ?>
<script>
    /* Bulk */
    let bulk_checker = () => {
        let is_bulk = document.querySelector('#is_bulk').checked;

        if(is_bulk) {
            document.querySelector('#code').setAttribute('disabled', 'disabled');
            document.querySelector('#name').setAttribute('disabled', 'disabled');
            document.querySelector('#bulk_container').classList.remove('d-none');
        } else {
            document.querySelector('#code').removeAttribute('disabled');
            document.querySelector('#name').removeAttribute('disabled');
            document.querySelector('#bulk_container').classList.add('d-none');
        }
    }

    bulk_checker();

    document.querySelector('#is_bulk').addEventListener('change', bulk_checker);

    type_handler('input[name="type"]', 'data-type');
    document.querySelector('input[name="type"]') && document.querySelectorAll('input[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('input[name="type"]', 'data-type'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
