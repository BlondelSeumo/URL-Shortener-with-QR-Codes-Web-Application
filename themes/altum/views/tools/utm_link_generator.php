<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.utm_link_generator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.utm_link_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.utm_link_generator.description') ?>">
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
                    <label for="url"><?= l('global.url') ?></label>
                    <input type="url" id="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('url') ?>
                </div>

                <div class="form-group">
                    <label for="utm_source"><?= l('tools.utm_link_generator.utm_source') ?></label>
                    <input type="text" id="utm_source" name="utm_source" class="form-control <?= \Altum\Alerts::has_field_errors('utm_source') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('utm_source') ?>
                </div>

                <div class="form-group">
                    <label for="utm_medium"><?= l('tools.utm_link_generator.utm_medium') ?></label>
                    <input type="text" id="utm_medium" name="utm_medium" class="form-control <?= \Altum\Alerts::has_field_errors('utm_medium') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('utm_medium') ?>
                </div>

                <div class="form-group">
                    <label for="utm_campaign"><?= l('tools.utm_link_generator.utm_campaign') ?></label>
                    <input type="text" id="utm_campaign" name="utm_campaign" class="form-control <?= \Altum\Alerts::has_field_errors('utm_campaign') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('utm_campaign') ?>
                </div>

                <div class="form-group">
                    <label for="utm_content"><?= l('tools.utm_link_generator.utm_content') ?></label>
                    <input type="text" id="utm_content" name="utm_content" class="form-control <?= \Altum\Alerts::has_field_errors('utm_content') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('utm_content') ?>
                </div>

                <div class="form-group">
                    <label for="utm_term"><?= l('tools.utm_link_generator.utm_term') ?></label>
                    <input type="text" id="utm_term" name="utm_term" class="form-control <?= \Altum\Alerts::has_field_errors('utm_term') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('utm_term') ?>
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
        let utm_source = document.querySelector('#utm_source').value;
        let utm_medium = document.querySelector('#utm_medium').value;
        let utm_campaign = document.querySelector('#utm_campaign').value;
        let utm_content = document.querySelector('#utm_content').value;
        let utm_term = document.querySelector('#utm_term').value;

        if(url && (utm_source || utm_medium || utm_campaign)) {
            link = new URL(url);

            if(utm_source) link.searchParams.set('utm_source', utm_source.trim());
            if(utm_medium) link.searchParams.set('utm_medium', utm_medium.trim());
            if(utm_campaign) link.searchParams.set('utm_campaign', utm_campaign.trim());
            if(utm_content) link.searchParams.set('utm_content', utm_content.trim());
            if(utm_term) link.searchParams.set('utm_term', utm_term.trim());

            link = link.toString();
        }

        document.querySelector('#result').value = link;
    }

    ['#url', '#utm_source', '#utm_medium', '#utm_campaign', '#utm_content', '#utm_term'].forEach(selector => document.querySelector(selector).addEventListener('change', generate));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
