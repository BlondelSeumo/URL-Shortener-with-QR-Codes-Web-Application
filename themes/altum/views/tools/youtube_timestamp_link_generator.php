<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.youtube_timestamp_link_generator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.youtube_timestamp_link_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.youtube_timestamp_link_generator.description') ?>">
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
                    <label for="url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('tools.youtube_timestamp_link_generator.url') ?></label>
                    <input type="url" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('url') ?>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="start_minute"><?= l('global.date.minute') ?></label>
                            <input type="number" min="0" id="start_minute" name="start_minute" class="form-control <?= \Altum\Alerts::has_field_errors('start_minute') ? 'is-invalid' : null ?>" max="15" />
                            <?= \Altum\Alerts::output_field_error('start_minute') ?>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-group">
                            <label for="start_second"><?= l('global.date.second') ?></label>
                            <input type="number" min="0" id="start_second" name="start_second" class="form-control <?= \Altum\Alerts::has_field_errors('start_second') ? 'is-invalid' : null ?>" max="15" />
                            <?= \Altum\Alerts::output_field_error('start_second') ?>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>

    <div class="mt-4">
        <div class="card">
            <div class="card-body">

                <div class="form-group">
                    <div class="d-flex justify-content-between align-items-center">
                        <label for="result"><?= l('tools.result') ?></label>
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
                    <textarea id="result" class="form-control"></textarea>
                </div>

            </div>
        </div>
    </div>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php ob_start() ?>
<script>
    'use strict';

    let generate = () => {
        let link = null;
        let url = document.querySelector('#url').value;
        let start_minute = parseInt(document.querySelector('#start_minute').value) ?? 0;
        let start_second = parseInt(document.querySelector('#start_second').value) ?? 0;

        if(url && (start_minute || start_second)) {
            link = new URL(url);
            let seconds = start_minute * 60 + start_second;
            link.searchParams.set('t', seconds);
            link = link.toString();
        }

        document.querySelector('#result').value = link;
    }

    ['#url', '#start_minute', '#start_second'].forEach(selector => document.querySelector(selector).addEventListener('change', generate));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
