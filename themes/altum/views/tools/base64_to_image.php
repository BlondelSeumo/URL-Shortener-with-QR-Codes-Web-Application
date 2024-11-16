<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.base64_to_image.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.base64_to_image.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.base64_to_image.description') ?>">
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
                    <label for="content"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('tools.content') ?></label>
                    <textarea id="content" name="content" class="form-control <?= \Altum\Alerts::has_field_errors('content') ? 'is-invalid' : null ?>" required="required"><?= $data->values['content'] ?></textarea>
                    <?= \Altum\Alerts::output_field_error('content') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">

            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="result"><?= l('tools.base64_to_image.result') ?></label>
                            <div class="dropdown">
                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-fw fa-sm fa-download mr-1"></i> <?= l('global.download') ?>
                                </button>

                                <div class="dropdown-menu">
                                    <a href="data:image/png;base64,<?= $data->result ?>" class="dropdown-item" download="download.png"><?= sprintf(l('global.download_as'), 'PNG') ?></a>
                                    <a href="data:image/jpg;base64,<?= $data->result ?>" class="dropdown-item" download="download.jpg"><?= sprintf(l('global.download_as'), 'JPG') ?></a>
                                    <a href="data:image/webp;base64,<?= $data->result ?>" class="dropdown-item" download="download.webp"><?= sprintf(l('global.download_as'), 'WEBP') ?></a>
                                    <a href="data:image/gif;base64,<?= $data->result ?>" class="dropdown-item" download="download.gif"><?= sprintf(l('global.download_as'), 'GIF') ?></a>
                                </div>
                            </div>
                        </div>

                        <img src="data:image/png;base64,<?= $data->result ?>" class="img-fluid" />
                    </div>

                </div>
            </div>

        </div>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
