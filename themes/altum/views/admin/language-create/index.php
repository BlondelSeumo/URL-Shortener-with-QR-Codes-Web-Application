<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/languages') ?>"><?= l('admin_languages.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_language_create.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-language text-primary-900 mr-2"></i> <?= l('admin_language_create.header') ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="language_name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_languages.main.language_name') ?></label>
                <input id="language_name" type="text" name="language_name" class="form-control <?= \Altum\Alerts::has_field_errors('language_name') ? 'is-invalid' : null ?>" value="<?= $data->values['language_name'] ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('language_name') ?>
                <small class="form-text text-muted"><?= l('admin_languages.main.language_name_help') ?></small>
            </div>

            <div class="form-group">
                <label for="language_code"><i class="fas fa-fw fa-sm fa-language text-muted mr-1"></i> <?= l('admin_languages.main.language_code') ?></label>
                <input id="language_code" type="text" name="language_code" class="form-control <?= \Altum\Alerts::has_field_errors('language_code') ? 'is-invalid' : null ?>" value="<?= $data->values['language_code'] ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('language_code') ?>
                <small class="form-text text-muted"><?= l('admin_languages.main.language_code_help') ?></small>
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
                <select id="status" name="status" class="custom-select">
                    <option value="active" <?= $data->values['status'] == 'active' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                    <option value="disabled" <?= $data->values['status'] == 'disabled' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= $data->values['order'] ?>" class="form-control" />
            </div>

            <div class="form-group">
                <label for="language_flag"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('admin_languages.main.language_flag') ?></label>
                <input id="language_flag" type="text" name="language_flag" value="<?= $data->values['language_flag'] ?>" class="form-control" placeholder="<?= l('admin_languages.main.language_flag_placeholder') ?>" />
            </div>

            <div class="alert alert-info" role="alert">
                <?= l('admin_language_create.subheader') ?>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>
        </form>

    </div>
</div>
