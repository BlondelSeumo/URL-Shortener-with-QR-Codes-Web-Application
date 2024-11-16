<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.file_mime_type_checker.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.file_mime_type_checker.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.file_mime_type_checker.description') ?>">
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
                    <label for="file"><i class="fas fa-fw fa-sm fa-file text-muted mr-1"></i> <?= l('tools.file_mime_type_checker.file') ?></label>
                    <input id="file" type="file" name="file" class="form-control-file altum-file-input <?= \Altum\Alerts::has_field_errors('file') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('file') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <div id="result_wrapper" class="mt-4 d-none">
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <tbody>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.file_mime_type_checker.file_name') ?>
                    </td>
                    <td class="text-nowrap" id="file_name"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.file_mime_type_checker.file_size') ?>
                    </td>
                    <td class="text-nowrap" id="file_size"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.file_mime_type_checker.file_type') ?>
                    </td>
                    <td class="text-nowrap" id="file_type"></td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.file_mime_type_checker.file_last_modified_date') ?>
                    </td>
                    <td class="text-nowrap" id="file_last_modified_date"></td>
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

    let check = () => {
        pause_submit_button(document.querySelector('[type="submit"][name="submit"]'));

        const file = document.getElementById('file').files[0];

        if(!file) {
            /* Hide result wrapper */
            document.querySelector('#result_wrapper').classList.add('d-none');
            return;
        }

        /* Display result wrapper */
        document.querySelector('#result_wrapper').classList.remove('d-none');

        /* Display file details */
        document.querySelector('#file_type').innerHTML = file.type;
        document.querySelector('#file_name').innerHTML = file.name;
        document.querySelector('#file_size').innerHTML = get_formatted_bytes(file.size);
        document.querySelector('#file_last_modified_date').innerHTML = file.lastModifiedDate;

        enable_submit_button(document.querySelector('[type="submit"][name="submit"]'));
    }

    let get_formatted_bytes = bytes => {
        let selected_size = 0;
        let selected_unit = 'B';

        if(bytes > 0) {
            let units = ['TB', 'GB', 'MB', 'KB', 'B'];

            for (let i = 0; i < units.length; i++) {
                let unit = units[i];
                let cutoff = Math.pow(1000, 4 - i) / 10;

                if(bytes >= cutoff) {
                    selected_size = bytes / Math.pow(1000, 4 - i);
                    selected_unit = unit;
                    break;
                }
            }

            selected_size = Math.round(10 * selected_size) / 10;
        }

        return `${selected_size} ${selected_unit}`;
    }

    document.getElementById('file').addEventListener('change', check);
    document.querySelector('form').addEventListener('submit', event => {
        event.preventDefault();
        check();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
