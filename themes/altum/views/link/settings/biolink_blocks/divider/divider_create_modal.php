<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_divider" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('create_biolink_divider_modal.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_divider" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="divider" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="divider_margin_top"><?= l('create_biolink_divider_modal.margin_top') ?></label>
                        <input id="divider_margin_top" type="range" name="margin_top" min="0" max="7" step="1" class="form-control-range" />
                    </div>

                    <div class="form-group">
                        <label for="divider_margin_bottom"><?= l('create_biolink_divider_modal.margin_bottom') ?></label>
                        <input id="divider_margin_bottom" type="range" name="margin_bottom" min="0" max="7" step="1" class="form-control-range" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
