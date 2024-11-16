<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.unix_timestamp_to_date.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.unix_timestamp_to_date.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.unix_timestamp_to_date.description') ?>">
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
                    <label for="unix_timestamp"><i class="fas fa-fw fa-sort-numeric-up fa-sm text-muted mr-1"></i> <?= l('tools.unix_timestamp_to_date.unix_timestamp') ?></label>
                    <input type="number" min="0" id="unix_timestamp" name="unix_timestamp" class="form-control" value="<?= time() ?>" required="required" />
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <div class="mt-4">
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <tbody>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.unix_timestamp_to_date.utc') ?>
                    </td>
                    <td class="text-nowrap" id="result_utc"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="copy_utc"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.unix_timestamp_to_date.local') ?>
                    </td>
                    <td class="text-nowrap" id="result_local"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="copy_local"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </td>
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

    let convert = () => {
        pause_submit_button(document.querySelector('[type="submit"][name="submit"]'));

        const unix_timestamp = parseInt(document.getElementById('unix_timestamp').value);
        const milliseconds = unix_timestamp * 1000;
        const date = new Date(milliseconds);
        document.querySelector('#result_local').innerHTML = date.toString();
        document.querySelector('#copy_local').setAttribute('data-clipboard-text', date.toString());
        document.querySelector('#result_utc').innerHTML = date.toUTCString();
        document.querySelector('#copy_utc').setAttribute('data-clipboard-text', date.toUTCString());

        enable_submit_button(document.querySelector('[type="submit"][name="submit"]'));
    }

    document.getElementById('unix_timestamp').addEventListener('change', convert);
    document.querySelector('form').addEventListener('submit', event => {
        event.preventDefault();
        convert();
    });
    convert();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
