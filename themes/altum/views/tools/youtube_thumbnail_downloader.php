<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.youtube_thumbnail_downloader.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.youtube_thumbnail_downloader.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.youtube_thumbnail_downloader.description') ?>">
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
                    <label for="url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('tools.youtube_thumbnail_downloader.url') ?></label>
                    <input type="url" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->values['url'] ?>" placeholder="<?= l('global.url_placeholder') ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('url') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">
            <ul class="nav nav-pills mb-4" role="tablist">
                <?php foreach(['default', 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault'] as $key): ?>
                    <li class="nav-item" role="presentation">
                        <a class="nav-link <?= $key == 'default' ? 'active' : null ?>" id="<?= 'tab_link_' . $key ?>" data-toggle="tab" href="<?= '#tab_container_' . $key ?>" role="tab" aria-controls="home">
                            <?= l('tools.youtube_thumbnail_downloader.result.' . $key) ?>
                        </a>
                    </li>
                <?php endforeach ?>
            </ul>

            <div class="tab-content">
                <?php foreach(['default', 'mqdefault', 'hqdefault', 'sddefault', 'maxresdefault'] as $key): ?>
                    <div class="tab-pane fade <?= $key == 'default' ? 'show active' : null ?>" id="<?= 'tab_container_' . $key ?>" role="tabpanel" aria-labelledby="<?= 'tab_link_' . $key ?>">
                        <div class="card">
                            <div class="card-body">
                                <img src="<?= $data->result[$key] ?>" class="img-fluid mb-3" />

                                <div class="form-group">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <label for="<?= 'result_' . $key ?>"><?= l('tools.youtube_thumbnail_downloader.result.' . $key) ?></label>
                                        <div>
                                            <a
                                                    href="<?= url('tools/download?url=' . urlencode($data->result[$key]) . '&name=' . urlencode($key . '.jpg') . '&mime_content_type=' . urlencode('image/jpeg') . '&global_token=' . \Altum\Csrf::get('global_token')) ?>"
                                                    target="_blank"
                                                    class="btn btn-link text-secondary"
                                                    data-toggle="tooltip"
                                                    title="<?= l('global.download') ?>"
                                                    download="<?= $key . '.jpg' ?>"
                                            >
                                                <i class="fas fa-fw fa-sm fa-download"></i>
                                            </a>

                                            <a
                                                    href="<?= $data->result[$key] ?>"
                                                    target="_blank"
                                                    class="btn btn-link text-secondary"
                                                    data-toggle="tooltip"
                                                    title="<?= l('tools.youtube_thumbnail_downloader.open') ?>"
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
                                                    data-clipboard-target="#<?= 'result_' . $key ?>"
                                                    data-clipboard-text
                                            >
                                                <i class="fas fa-fw fa-sm fa-copy"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <textarea id="<?= 'result_' . $key ?>" class="form-control"><?= $data->result[$key] ?></textarea>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
