<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="<?= $data->name . '_delete_modal' ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-trash-alt text-dark mr-2"></i>
                        <?= l('delete_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted" id="<?= $data->name . '_delete_modal_subheader' ?>"></p>

                <span class="d-none" id="<?= $data->name . '_delete_modal_subheader_hidden' ?>">
                    <?= $data->has_dynamic_resource_name ? l('delete_modal.subheader1') : l('delete_modal.subheader2') ?>
                </span>

                <div class="mt-4">
                    <a href="" id="<?= $data->name . '_delete_modal_url' ?>" class="btn btn-lg btn-block btn-danger"><?= l('global.delete') ?></a>
                </div>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('<?= '#' . $data->name . '_delete_modal' ?>').on('show.bs.modal', event => {
        let related_target = event.relatedTarget;
        let current_target = event.currentTarget;

        let <?= $data->resource_id ?> = related_target.getAttribute('data-<?= str_replace('_', '-', $data->resource_id) ?>');
        current_target.querySelector('<?= '#' . $data->name . '_delete_modal_url' ?>').setAttribute('href', `${url}<?= $data->path ?>${<?= $data->resource_id ?>}&global_token=${global_token}&original_request=<?= base64_encode(\Altum\Router::$original_request) ?>&original_request_query=<?= base64_encode(\Altum\Router::$original_request_query) ?>`);

        <?php if($data->has_dynamic_resource_name): ?>
        current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader' ?>').innerHTML = current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader_hidden' ?>').innerHTML.replace('%s', related_target.getAttribute('data-resource-name'));
        <?php else: ?>
        current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader' ?>').innerHTML = current_target.querySelector('<?= '#' . $data->name . '_delete_modal_subheader_hidden' ?>').innerHTML;
        <?php endif ?>
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
