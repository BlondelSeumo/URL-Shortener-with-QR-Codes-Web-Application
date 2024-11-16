<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.base64_image_converter.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.base64_image_converter.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.base64_image_converter.description') ?>">
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
                    <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                    <select id="type" name="type" class="custom-select" required="required">
                        <option value="image_to_base64" <?= $data->values['type'] == 'image_to_base64' ? 'selected="selected"' : null ?>><?= l('tools.base64_image_converter.image_to_base64') ?></option>
                        <option value="base64_to_image" <?= $data->values['type'] == 'base64_to_image' ? 'selected="selected"' : null ?>><?= l('tools.base64_image_converter.base64_to_image') ?></option>
                    </select>
                </div>

                <div class="form-group" data-type="base64_to_image">
                    <label for="text"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('tools.text') ?></label>
                    <textarea id="text" name="text" class="form-control <?= \Altum\Alerts::has_field_errors('text') ? 'is-invalid' : null ?>" required="required"><?= $data->values['text'] ?></textarea>
                    <?= \Altum\Alerts::output_field_error('text') ?>
                </div>

                <div class="form-group" data-type="image_to_base64">
                    <label for="image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('global.image') ?></label>
                    <input id="image" type="file" name="image" accept=".gif, .png, .jpg, .jpeg, .svg" class="form-control-file altum-file-input" />
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), '.gif, .png, .jpg, .jpeg, .svg') . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">

            <div class="card">
                <div class="card-body">

                    <?php if($data->values['type'] == 'image_to_base64'): ?>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="result"><?= l('tools.text') ?></label>
                                <div>
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
                            <textarea id="result" class="form-control"><?= $data->result['base64'] ?></textarea>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="result"><?= l('global.image') ?></label>
                                <div class="dropdown">
                                    <button type="button" class="btn btn-outline-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-fw fa-sm fa-download mr-1"></i> <?= l('global.download') ?>
                                    </button>

                                    <div class="dropdown-menu">
                                        <a href="data:image/png;base64,<?= $data->result['base64'] ?>" class="dropdown-item" download="<?= l('global.image') . '.png' ?>">PNG</a>
                                        <a href="data:image/jpg;base64,<?= $data->result['base64'] ?>" class="dropdown-item" download="<?= l('global.image') . '.jpg' ?>">JPG</a>
                                        <a href="data:image/webp;base64,<?= $data->result['base64'] ?>" class="dropdown-item" download="<?= l('global.image') . '.webp' ?>">WEBP</a>
                                        <a href="data:image/gif;base64,<?= $data->result['base64'] ?>" class="dropdown-item" download="<?= l('global.image') . '.gif' ?>">GIF</a>
                                    </div>
                                </div>
                            </div>

                            <img src="data:image/png;base64,<?= $data->result['base64'] ?>" class="img-fluid" />
                        </div>
                    <?php endif ?>
                </div>
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


<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
