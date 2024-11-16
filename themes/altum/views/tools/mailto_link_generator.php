<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.mailto_link_generator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.mailto_link_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.mailto_link_generator.description') ?>">
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
                    <label for="to"><?= l('tools.mailto_link_generator.to') ?></label>
                    <input type="email" id="to" name="to" class="form-control <?= \Altum\Alerts::has_field_errors('to') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('to') ?>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="cc"><?= l('tools.mailto_link_generator.cc') ?></label>
                            <input type="text" id="cc" name="cc" class="form-control <?= \Altum\Alerts::has_field_errors('cc') ? 'is-invalid' : null ?>" />
                            <?= \Altum\Alerts::output_field_error('cc') ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="bcc"><?= l('tools.mailto_link_generator.bcc') ?></label>
                            <input type="text" id="bcc" name="bcc" class="form-control <?= \Altum\Alerts::has_field_errors('bcc') ? 'is-invalid' : null ?>" />
                            <?= \Altum\Alerts::output_field_error('bcc') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="subject"><?= l('tools.mailto_link_generator.subject') ?></label>
                    <input type="text" id="subject" name="subject" class="form-control <?= \Altum\Alerts::has_field_errors('subject') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('subject') ?>
                </div>

                <div class="form-group">
                    <label for="body"><?= l('tools.mailto_link_generator.body') ?></label>
                    <textarea type="text" id="body" name="body" class="form-control <?= \Altum\Alerts::has_field_errors('body') ? 'is-invalid' : null ?>"></textarea>
                    <?= \Altum\Alerts::output_field_error('body') ?>
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
        let to = document.querySelector('#to').value;
        let cc = document.querySelector('#cc').value;
        let bcc = document.querySelector('#bcc').value;
        let subject = document.querySelector('#subject').value;
        let body = document.querySelector('#body').value;

        let link = `mailto:${to}`;

        if(cc || bcc || subject || body) {
            link += '?';
            let parameters = [];

            if(cc) parameters.push(`cc=${cc.trim()}`);
            if(bcc) parameters.push(`bcc=${bcc.trim()}`);
            if(subject) parameters.push(`subject=${encodeURIComponent(subject.trim())}`);
            if(body) parameters.push(`body=${encodeURIComponent(body.trim())}`);

            link += parameters.join('&');
        }

        document.querySelector('#result').value = link;
    }

    ['#to', '#cc', '#bcc', '#subject', '#body'].forEach(selector => document.querySelector(selector).addEventListener('change', generate));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
