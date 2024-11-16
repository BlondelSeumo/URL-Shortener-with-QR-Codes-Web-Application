<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.paypal_link_generator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.paypal_link_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.paypal_link_generator.description') ?>">
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
                        <?php foreach(['buy_now', 'add_to_cart', 'donation'] as $key): ?>
                            <option value="<?= $key ?>"><?= l('tools.paypal_link_generator.type.' . $key) ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('tools.paypal_link_generator.email') ?></label>
                    <input type="text" id="email" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" maxlength="320" />
                    <?= \Altum\Alerts::output_field_error('email') ?>
                </div>

                <div class="form-group">
                    <label for="title"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('tools.paypal_link_generator.title') ?></label>
                    <input type="text" id="title" name="title" class="form-control <?= \Altum\Alerts::has_field_errors('title') ? 'is-invalid' : null ?>" maxlength="256" />
                    <?= \Altum\Alerts::output_field_error('title') ?>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="price"><i class="fas fa-fw fa-dollar-sign fa-sm text-muted mr-1"></i> <?= l('tools.paypal_link_generator.price') ?></label>
                            <input id="price" type="number" name="price" min="1" step="0.01" class="form-control <?= \Altum\Alerts::has_field_errors('price') ? 'is-invalid' : null ?>" placeholder="5" />
                            <?= \Altum\Alerts::output_field_error('price') ?>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="currency"><i class="fas fa-fw fa-euro-sign fa-sm text-muted mr-1"></i> <?= l('tools.paypal_link_generator.currency') ?></label>
                            <input type="text" id="currency" name="currency" class="form-control <?= \Altum\Alerts::has_field_errors('currency') ? 'is-invalid' : null ?>" placeholder="USD" maxlength="8" />
                            <?= \Altum\Alerts::output_field_error('currency') ?>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="thank_you_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('tools.paypal_link_generator.thank_you_url') ?></label>
                    <input type="url" id="thank_you_url" name="thank_you_url" class="form-control <?= \Altum\Alerts::has_field_errors('thank_you_url') ? 'is-invalid' : null ?>" maxlength="2048" />
                    <?= \Altum\Alerts::output_field_error('thank_you_url') ?>
                </div>

                <div class="form-group">
                    <label for="cancel_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('tools.paypal_link_generator.cancel_url') ?></label>
                    <input type="url" id="cancel_url" name="cancel_url" class="form-control <?= \Altum\Alerts::has_field_errors('cancel_url') ? 'is-invalid' : null ?>" maxlength="2048" />
                    <?= \Altum\Alerts::output_field_error('cancel_url') ?>
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
        let type = document.querySelector('#type').value;
        let email = document.querySelector('#email').value;
        let title = document.querySelector('#title').value;
        let price = document.querySelector('#price').value;
        let currency = document.querySelector('#currency').value;
        let thank_you_url = document.querySelector('#thank_you_url').value;
        let cancel_url = document.querySelector('#cancel_url').value;

        let paypal_type = {
            'buy_now': '_xclick',
            'add_to_cart': '_cart',
            'donation': '_donations'
        };

        let link = null;

        if(type == 'add_to_cart') {
            link = `https://www.paypal.com/cgi-bin/webscr?business=${email}&cmd=${paypal_type[type]}&currency_code=${currency}&amount=${price}&item_name=${title}&button_subtype=products&add=1&return=${thank_you_url}&cancel_return=${cancel_url}`
        } else {
            link = `https://www.paypal.com/cgi-bin/webscr?business=${email}&cmd=${paypal_type[type]}&currency_code=${currency}&amount=${price}&item_name=${title}&return=${thank_you_url}&cancel_return=${cancel_url}`
        }

        document.querySelector('#result').value = link;
    }

    ['#type', '#email', '#title', '#price', '#currency', '#thank_you_url', '#cancel_url'].forEach(selector => document.querySelector(selector).addEventListener('change', generate));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
