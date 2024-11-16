<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/domains') ?>"><?= l('admin_domains.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_domain_create.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<?php $url = parse_url(SITE_URL); $host = $url['host'] . (mb_strlen($url['path']) > 1 ? $url['path'] : null); ?>

<div class="d-flex align-items-center mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-globe text-primary-900 mr-2"></i> <?= l('admin_domain_create.header') ?></h1>
    <div class="ml-2">
        <span data-toggle="tooltip" data-html="true" title="<?= sprintf(l('admin_domains.main.helper'), '<strong>' . (settings()->links->domains_custom_main_ip ?: $_SERVER['SERVER_ADDR']) . '</strong>', '<strong>' . $host . '</strong>') ?>">
            <i class="fas fa-fw fa-info-circle text-muted"></i>
        </span>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="host"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.host') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select name="scheme" class="appearance-none custom-select form-control input-group-text">
                            <option value="https://">https://</option>
                            <option value="http://">http://</option>
                        </select>
                    </div>
                    <input id="host" type="text" name="host" class="form-control <?= \Altum\Alerts::has_field_errors('host') ? 'is-invalid' : null ?>" placeholder="<?= l('admin_domains.main.host_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('host') ?>
                </div>
                <small class="form-text text-muted"><?= l('admin_domains.main.host_help') ?></small>
            </div>

            <div class="form-group">
                <label for="custom_index_url"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.custom_index_url') ?></label>
                <input id="custom_index_url" type="text" name="custom_index_url" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" />
                <small class="form-text text-muted"><?= l('admin_domains.main.custom_index_url_help') ?></small>
            </div>

            <div class="form-group">
                <label for="custom_not_found_url"><i class="fas fa-fw fa-location-arrow fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.custom_not_found_url') ?></label>
                <input id="custom_not_found_url" type="text" name="custom_not_found_url" class="form-control" placeholder="<?= l('global.url_placeholder') ?>" />
                <small class="form-text text-muted"><?= l('admin_domains.main.custom_not_found_url_help') ?></small>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input">
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.create') ?></button>
        </form>

    </div>
</div>

