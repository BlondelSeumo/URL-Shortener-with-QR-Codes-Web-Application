<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.ssl_lookup.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.ssl_lookup.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.ssl_lookup.description') ?>">
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
                    <label for="host"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('tools.ssl_lookup.host') ?></label>
                    <input type="text" id="host" name="host" class="form-control <?= \Altum\Alerts::has_field_errors('host') ? 'is-invalid' : null ?>" value="<?= $data->values['host'] ?>" placeholder="<?= l('global.host_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('host') ?>
                </div>

                <div class="form-group">
                    <label for="port"><i class="fas fa-fw fa-dna fa-sm text-muted mr-1"></i> <?= l('tools.ssl_lookup.port') ?></label>
                    <input type="number" min="0" max="100000" id="port" name="port" class="form-control <?= \Altum\Alerts::has_field_errors('port') ? 'is-invalid' : null ?>" value="<?= $data->values['port'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('port') ?>
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

                    <?php if(isset($data->result['is_valid'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.status') ?>
                            </td>
                            <td class="text-nowrap">
                                <?php if($data->result['is_valid']): ?>
                                    <i class="fas fa-fw fa-sm fa-check-circle text-success"></i>
                                <?php else: ?>
                                    <i class="fas fa-fw fa-sm fa-times-circle text-danger"></i>
                                <?php endif ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['start_datetime'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ssl_lookup.result.start_datetime') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= \Altum\Date::get($data->result['start_datetime'], 2)  . ' (' . \Altum\Date::get($data->result['start_datetime'], 1) . ')' ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['end_datetime'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ssl_lookup.result.end_datetime') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= \Altum\Date::get($data->result['end_datetime'], 2)  . ' (' . \Altum\Date::get($data->result['end_datetime'], 1) . ')' ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['organization'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ssl_lookup.result.organization') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['organization'] ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['common_name'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ssl_lookup.result.common_name') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['common_name'] ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['issuer_country'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.country') ?>
                            </td>
                            <td class="text-nowrap">
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->result['issuer_country']) . '.svg' ?>" class="img-fluid icon-favicon mr-1" /> <?= get_country_from_country_code($data->result['issuer_country']) ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['signature_type'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ssl_lookup.result.signature_type') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['signature_type'] ?>
                            </td>
                        </tr>
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

