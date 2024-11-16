<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('tools.image_optimizer.name') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.image_optimizer.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.image_optimizer.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('global.image') ?></label>
                    <input id="image" type="file" name="image" accept=".gif, .png, .jpg, .jpeg, .webp" class="form-control-file altum-file-input <?= \Altum\Alerts::has_field_errors('image') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('image') ?>
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), '.gif, .png, .jpg, .jpeg, .webp') . ' ' . sprintf(l('global.accessibility.file_size_limit'), '5') ?></small>
                </div>

                <div class="form-group">
                    <label for="quality"><i class="fas fa-fw fa-sort-numeric-up fa-sm text-muted mr-1"></i> <?= l('tools.quality') ?></label>
                    <input type="number" min="1" max="100" id="quality" name="quality" class="form-control <?= \Altum\Alerts::has_field_errors('quality') ? 'is-invalid' : null ?>" value="<?= $data->values['quality'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('quality') ?>
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
                            <?= l('tools.image_optimizer.result.original_size') ?>
                        </td>
                        <td class="text-nowrap <?= $data->result['original_size'] > $data->result['new_size'] ? 'text-danger' : 'text-success'; ?>">
                            <?= nr($data->result['original_size']) . ' KB' ?>
                        </td>
                    </tr>

                    <tr>
                        <td class="font-weight-bold">
                            <?= l('tools.image_optimizer.result.new_size') ?>
                        </td>
                        <td class="text-nowrap <?= $data->result['original_size'] >= $data->result['new_size'] ? 'text-success' : 'text-danger'; ?>">
                            <?= nr($data->result['new_size']) . ' KB' ?> <?= '(' . nr(get_percentage_change($data->result['original_size'], $data->result['new_size']), 2) . '%)' ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4">
            <div class="card">
                <div class="card-body">
                    <div class="text-center">
                        <img src="<?= $data->result['file_url'] ?>" class="img-fluid mb-3" style="max-height: 20rem;" />
                    </div>

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="result"><?= l('tools.result') ?></label>
                            <div>
                                <a
                                        href="<?= url('tools/download?url=' . urlencode($data->result['original_file_url']) . '&name=' . urlencode($data->result['name']) . '&global_token=' . \Altum\Csrf::get('global_token')) ?>"
                                        target="_blank"
                                        class="btn btn-link text-secondary"
                                        data-toggle="tooltip"
                                        title="<?= l('global.download') ?>"
                                        download="<?= $data->result['name'] ?>"
                                >
                                    <i class="fas fa-fw fa-sm fa-download"></i>
                                </a>

                                <a
                                        href="<?= $data->result['file_url'] ?>"
                                        target="_blank"
                                        class="btn btn-link text-secondary"
                                        data-toggle="tooltip"
                                        title="<?= l('tools.image_optimizer.open') ?>"
                                >
                                    <i class="fas fa-fw fa-sm fa-external-link-alt"></i>
                                </a>

                                <button
                                        type="button"
                                        class="btn btn-link text-secondary"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-target="#result"
                                        data-clipboard-text
                                >
                                    <i class="fas fa-fw fa-sm fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <textarea id="result" class="form-control"><?= $data->result['file_url'] ?></textarea>
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


<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
