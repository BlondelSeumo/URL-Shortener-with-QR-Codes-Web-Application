<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.ip_lookup.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.ip_lookup.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.ip_lookup.description') ?>">
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
                    <label for="ip"><i class="fas fa-fw fa-search-location fa-sm text-muted mr-1"></i> <?= l('global.ip') ?></label>
                    <input type="text" id="ip" name="ip" class="form-control <?= \Altum\Alerts::has_field_errors('ip') ? 'is-invalid' : null ?>" value="<?= $data->values['ip'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('ip') ?>
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
                    <?php if(isset($data->result['continent'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.continent') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['continent']['names']['en'] ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['country'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.country') ?>
                            </td>
                            <td class="text-nowrap">
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($data->result['country']['iso_code']) . '.svg' ?>" class="img-fluid icon-favicon mr-1" /> <?= get_country_from_country_code($data->result['country']['iso_code']) ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['city'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('global.city') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['city']['names']['en'] ?>
                            </td>
                        </tr>
                    <?php endif ?>

                    <?php if(isset($data->result['location'])): ?>
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ip_lookup.result.latitude') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['location']['latitude'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ip_lookup.result.longitude') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['location']['longitude'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.ip_lookup.result.timezone') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['location']['time_zone'] ?>
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

