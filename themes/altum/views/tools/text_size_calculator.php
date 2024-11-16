<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.text_size_calculator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.text_size_calculator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.text_size_calculator.description') ?>">
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
                    <label for="text"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('tools.text') ?></label>
                    <textarea id="text" name="text" class="form-control <?= \Altum\Alerts::has_field_errors('text') ? 'is-invalid' : null ?>" required="required"><?= $data->values['text'] ?></textarea>
                    <?= \Altum\Alerts::output_field_error('text') ?>
                </div>
            </form>

        </div>
    </div>

    <div class="mt-4">
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <tbody>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.text_size_calculator.result') ?>
                    </td>
                    <td class="text-nowrap" id="result"></td>
                </tr>
                </tbody>
            </table>
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

    let size_format = size => {
        let level = 0;

        while (size > 1024) {
            size /= 1024;
            level++;
        }

        // Round to 2 decimals
        size = Math.round(size*100)/100;

        level = ['', 'K', 'M', 'G', 'T'][level];

        return ('<strong>' + size + '</strong>') + ' ' + level + 'B';
    }

    ['change', 'paste', 'keyup'].forEach(event_type => document.querySelector('#text').addEventListener(event_type, () => {
        let text = document.querySelector('#text').value;
        let size = new Blob([text]).size;


        document.querySelector('#result').innerHTML = size_format(size);
    }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

