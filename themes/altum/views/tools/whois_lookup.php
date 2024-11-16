<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.whois_lookup.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.whois_lookup.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.whois_lookup.description') ?>">
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
                    <label for="domain_name"><i class="fas fa-fw fa-network-wired fa-sm text-muted mr-1"></i> <?= l('tools.whois_lookup.domain_name') ?></label>
                    <input type="text" id="domain_name" name="domain_name" class="form-control <?= \Altum\Alerts::has_field_errors('domain_name') ? 'is-invalid' : null ?>" value="<?= $data->values['domain_name'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('domain_name') ?>
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

                    <?php if(isset($data->result['registrar'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.whois_lookup.result.registrar') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['registrar'] ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['start_datetime'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.whois_lookup.result.start_datetime') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= \Altum\Date::get($data->result['start_datetime'], 2)  . '(' . \Altum\Date::get($data->result['start_datetime'], 1) . ')' ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['updated_datetime'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.whois_lookup.result.updated_datetime') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= \Altum\Date::get($data->result['updated_datetime'], 2)  . '(' . \Altum\Date::get($data->result['updated_datetime'], 1) . ')' ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['end_datetime'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.whois_lookup.result.end_datetime') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= \Altum\Date::get($data->result['end_datetime'], 2)  . '(' . \Altum\Date::get($data->result['end_datetime'], 1) . ')' ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['nameservers'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.whois_lookup.result.nameservers') ?>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex flex-column">
                                    <?php foreach($data->result['nameservers'] as $nameserver): ?>
                                        <div><?= $nameserver ?></div>
                                    <?php endforeach ?>
                                </div>
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

