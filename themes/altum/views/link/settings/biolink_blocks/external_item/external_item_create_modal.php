<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_external_item" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('create_biolink_external_item_modal.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_external_item" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="external_item" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="external_item_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.name') ?></label>
                        <input id="external_item_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="external_item_description"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('create_biolink_external_item_modal.description') ?></label>
                        <input id="external_item_description" type="text" name="description" maxlength="256" class="form-control" value="" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="external_item_price"><i class="fas fa-fw fa-dollar-sign fa-sm text-muted mr-1"></i> <?= l('create_biolink_external_item_modal.price') ?></label>
                        <input id="external_item_price" type="text" name="price" maxlength="32" class="form-control" value="" placeholder="<?= l('create_biolink_external_item_modal.price_placeholder') ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="external_item_location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_external_item_modal.location_url') ?></label>
                        <input id="external_item_location_url" type="url" class="form-control" name="location_url" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
