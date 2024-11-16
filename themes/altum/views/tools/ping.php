<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('tools.ping.name') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.ping.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.ping.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('tools.ping.type') ?></label>
                    <select id="type" name="type" class="custom-select" required="required">
                        <option value="website" <?= $data->values['type'] == 'website' ? 'selected="selected"' : null ?>><?= l('tools.ping.type_website') ?></option>
                        <option value="ping" <?= $data->values['type'] == 'ping' ? 'selected="selected"' : null ?>><?= l('tools.ping.type_ping') ?></option>
                        <option value="port" <?= $data->values['type'] == 'port' ? 'selected="selected"' : null ?>><?= l('tools.ping.type_port') ?></option>
                    </select>
                    <small id="type_website_help" data-type="website" class="form-text text-muted"><?= l('tools.ping.type_website_help') ?></small id=type_help>
                    <small id="type_ping_help" data-type="ping" class="form-text text-muted"><?= l('tools.ping.type_ping_help') ?></small id=type_help>
                    <small id="type_port_help" data-type="port" class="form-text text-muted"><?= l('tools.ping.type_port_help') ?></small>
                </div>

                <div class="form-group" data-type="website">
                    <label for="target_website_url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('tools.ping.target_url') ?></label>
                    <input type="url" id="target_website_url" name="target" class="form-control <?= \Altum\Alerts::has_field_errors('target') ? 'is-invalid' : null ?>" value="<?= $data->values['target'] ?>" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('target') ?>
                </div>

                <div class="form-group" data-type="ping">
                    <label for="target_ping_host"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('tools.ping.target_host') ?></label>
                    <input type="text" id="target_ping_host" name="target" class="form-control" value="<?= $data->values['target'] ?>" required="required" />
                </div>

                <div class="row" data-type="port">
                    <div class="col-lg-3">
                        <div class="form-group" data-type="port">
                            <label for="target_port_host"><i class="fas fa-fw fa-sm fa-globe text-muted mr-1"></i> <?= l('tools.ping.target_host') ?></label>
                            <input type="text" id="target_port_host" name="target" class="form-control" value="<?= $data->values['target'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-9">
                        <div class="form-group" data-type="port">
                            <label for="target_port_port"><i class="fas fa-fw fa-sm fa-dna text-muted mr-1"></i> <?= l('tools.ping.target_port') ?></label>
                            <input type="text" id="target_port_port" name="port" class="form-control" value="<?= $data->values['port'] ?>" required="required" />
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">
            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <tbody>

                    <?php if($data->values['type'] == 'website'): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.url') ?>
                            </td>
                            <td class="text-nowrap">
                                <img src="<?= get_favicon_url_from_domain(parse_url($data->values['target'], PHP_URL_HOST)) ?>" class="img-fluid icon-favicon mr-1" /> <?= remove_url_protocol_from_url($data->values['target']) ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['ping_server_id'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ping.result.ping_server_id') ?>
                            </td>
                            <td class="text-nowrap">
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->ping_servers[$data->result['ping_server_id']]->country_code) . '.svg' ?>" class="img-fluid icon-favicon mr-1" /> <?= get_country_from_country_code($data->ping_servers[$data->result['ping_server_id']]->country_code). ', ' . $data->ping_servers[$data->result['ping_server_id']]->city_name ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['is_ok'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.status') ?>
                            </td>
                            <td class="text-nowrap">
                                <?php if($data->result['is_ok']): ?>
                                    <i class="fas fa-fw fa-sm fa-check-circle text-success"></i> <?= l('tools.ping.result.is_ok') ?>
                                <?php else: ?>
                                    <i class="fas fa-fw fa-sm fa-times-circle text-danger"></i> <?= l('tools.ping.result.is_not_ok') ?>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if($data->result['is_ok']): ?>
                        <?php if(isset($data->result['response_time'])): ?>
                            <tr>
                                <td class="font-weight-bold">
                                    <?= l('tools.ping.result.response_time') ?>
                                </td>
                                <td class="text-nowrap">
                                    <?= display_response_time($data->result['response_time']) ?>
                                </td>
                            </tr>
                        <?php endif ?>

                        <?php if(isset($data->result['response_status_code'])): ?>
                            <tr>
                                <td class="font-weight-bold">
                                    <?= l('tools.ping.result.response_status_code') ?>
                                </td>
                                <td class="text-nowrap">
                                    <?= $data->result['response_status_code'] ?>
                                </td>
                            </tr>
                        <?php endif ?>
                    <?php else: ?>
                        <?php if(isset($data->result['error'])): ?>
                            <tr>
                                <td class="font-weight-bold">
                                    <?= l('tools.ping.result.error') ?>
                                </td>
                                <td class="text-nowrap">
                                    <?= $data->result['error']['message'] ?>
                                </td>
                            </tr>
                        <?php endif ?>
                    <?php endif ?>

                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php ob_start() ?>
<script>
    'use strict';

    type_handler('select[name="type"]', 'data-type');
    document.querySelector('select[name="type"]') && document.querySelectorAll('select[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('select[name="type"]', 'data-type'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

