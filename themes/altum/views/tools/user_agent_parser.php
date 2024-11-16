<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.user_agent_parser.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.user_agent_parser.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.user_agent_parser.description') ?>">
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
                    <label for="user_agent"><i class="fas fa-fw fa-columns fa-sm text-muted mr-1"></i> <?= l('tools.user_agent_parser.user_agent') ?></label>
                    <input type="text" id="user_agent" name="user_agent" class="form-control <?= \Altum\Alerts::has_field_errors('user_agent') ? 'is-invalid' : null ?>" value="<?= $data->values['user_agent'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('user_agent') ?>
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
                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.user_agent_parser.result.browser') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['browser_name'] . ' ' . $data->result['browser_version'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.user_agent_parser.result.os') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['os_name'] . ' ' . $data->result['os_version'] ?>
                            </td>
                        </tr>

                        <tr>
                            <td class="font-weight-bold">
                                <?= l('tools.user_agent_parser.result.device_type') ?>
                            </td>
                            <td class="text-nowrap">
                                <?= $data->result['device_type'] ?>
                            </td>
                        </tr>
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

