<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/domains') ?>"><?= l('admin_domains.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_domain_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<?php $url = parse_url(SITE_URL); $host = $url['host'] . (mb_strlen($url['path']) > 1 ? $url['path'] : null); ?>

<div class="mb-4">
    <div class="d-flex justify-content-between">
        <div class="d-flex align-items-center">
            <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-globe text-primary-900 mr-2"></i> <?= l('admin_domain_update.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" data-html="true" title="<?= sprintf(l('admin_domains.main.helper'), '<strong>' . (settings()->links->domains_custom_main_ip ?: $_SERVER['SERVER_ADDR']) . '</strong>', '<strong>' . $host . '</strong>') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

        <?= include_view(THEME_PATH . 'views/admin/domains/admin_domain_dropdown_button.php', ['id' => $data->domain->domain_id, 'resource_name' => $data->domain->host]) ?>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <div class="d-flex">
                    <img src="<?= get_gravatar($data->user->email) ?>" class="user-avatar rounded-circle mr-3" alt="" />

                    <div class="d-flex flex-column">
                        <div>
                            <a href="<?= url('admin/user-view/' . $data->user->user_id) ?>"><?= $data->user->name ?></a>
                        </div>

                        <span class="text-muted"><?= $data->user->email ?></span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="host"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.host') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select name="scheme" class="appearance-none custom-select form-control input-group-text">
                            <option value="https://" <?= $data->domain->scheme == 'https://' ? 'selected="selected"' : null ?>>https://</option>
                            <option value="http://" <?= $data->domain->scheme == 'http://' ? 'selected="selected"' : null ?>>http://</option>
                        </select>
                    </div>
                    <input id="host" type="text" name="host" class="form-control <?= \Altum\Alerts::has_field_errors('host') ? 'is-invalid' : null ?>" placeholder="<?= l('admin_domains.main.host_placeholder') ?>" value="<?= $data->domain->host ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('host') ?>
                </div>
                <small class="form-text text-muted"><?= l('admin_domains.main.host_help') ?></small>
            </div>

            <div class="form-group">
                <label for="dns"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.dns') ?></label>
                <div class="card bg-gray-50">
                    <div class="card-body">
                        <?php
                        $dns_record_a = @dns_get_record($data->domain->host . '.', DNS_A);
                        $dns_record_cname = @dns_get_record($data->domain->host . '.', DNS_CNAME);
                        $dns_records = array_merge($dns_record_a ?: [], $dns_record_cname ?: []);
                        ?>

                        <?php if(count($dns_records)): ?>
                            <?php foreach($dns_records as $dns_record): ?>
                                <div class="row">
                                    <div class="col"><?= $dns_record['host'] ?></div>
                                    <div class="col"><?= $dns_record['type'] ?></div>
                                    <div class="col"><?= $dns_record['ip'] ?></div>
                                </div>
                            <?php endforeach ?>
                        <?php else: ?>
                            <span class="text-muted"><?= l('global.none') ?></span>
                        <?php endif ?>
                    </div>
                </div>
                <small class="form-text text-muted"><?= l('admin_domains.main.dns_help') ?></small>
            </div>

            <div class="form-group">
                <label for="custom_index_url"><i class="fas fa-fw fa-sitemap fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.custom_index_url') ?></label>
                <input id="custom_index_url" type="text" name="custom_index_url" class="form-control" value="<?= $data->domain->custom_index_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
                <small class="form-text text-muted"><?= l('admin_domains.main.custom_index_url_help') ?></small>
            </div>

            <div class="form-group">
                <label for="custom_not_found_url"><i class="fas fa-fw fa-location-arrow fa-sm text-muted mr-1"></i> <?= l('admin_domains.main.custom_not_found_url') ?></label>
                <input id="custom_not_found_url" type="text" name="custom_not_found_url" class="form-control" value="<?= $data->domain->custom_not_found_url ?>" placeholder="<?= l('global.url_placeholder') ?>" />
                <small class="form-text text-muted"><?= l('admin_domains.main.custom_not_found_url_help') ?></small>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->domain->is_enabled ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>

    </div>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'domain',
    'resource_id' => 'domain_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/domains/delete/'
]), 'modals'); ?>
