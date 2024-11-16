<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.whatsapp_link_generator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.whatsapp_link_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.whatsapp_link_generator.description') ?>">
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
                    <label for="phone"><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('tools.whatsapp_link_generator.phone') ?></label>
                    <input type="number" id="phone" name="phone" class="form-control <?= \Altum\Alerts::has_field_errors('phone') ? 'is-invalid' : null ?>" max="15" />
                    <?= \Altum\Alerts::output_field_error('phone') ?>
                </div>

                <div class="form-group">
                    <label for="prefilled_message"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('tools.whatsapp_link_generator.prefilled_message') ?></label>
                    <textarea id="prefilled_message" name="prefilled_message" class="form-control <?= \Altum\Alerts::has_field_errors('prefilled_message') ? 'is-invalid' : null ?>"></textarea>
                    <?= \Altum\Alerts::output_field_error('prefilled_message') ?>
                    <small class="form-text text-muted"><?= l('tools.whatsapp_link_generator.prefilled_message_help') ?></small>
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
        let phone = parseInt(document.querySelector('#phone').value);
        let prefilled_message = document.querySelector('#prefilled_message').value;

        let link = `https://wa.me/${phone}`;

        if(prefilled_message) {
            link += `?text=${encodeURIComponent(prefilled_message.trim())}`;
        }

        document.querySelector('#result').value = link;
    }

    ['#phone', '#prefilled_message'].forEach(selector => document.querySelector(selector).addEventListener('change', generate));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
