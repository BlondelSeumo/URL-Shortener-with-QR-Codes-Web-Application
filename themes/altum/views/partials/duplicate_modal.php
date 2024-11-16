<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="<?= $data->modal_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-clone text-dark mr-2"></i>
                        <?= l('duplicate_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <p class="text-muted"><?= l('duplicate_modal.subheader') ?></p>

                <form name="<?= $data->modal_id ?>" method="post" action="<?= url($data->path) ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="<?= $data->resource_id ?>" value="" />

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('<?= '#' . $data->modal_id ?>').on('show.bs.modal', event => {
        let id = $(event.relatedTarget).data('<?= str_replace('_', '-', $data->resource_id) ?>');

        $(event.currentTarget).find('input[name="<?= $data->resource_id ?>"]').val(id);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
