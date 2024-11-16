<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_image_grid" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('create_biolink_image_grid_modal.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_image_grid" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="image_grid" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="image_grid_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.name') ?></label>
                        <input id="image_grid_name" type="text" name="name" class="form-control" />
                    </div>

                    <div class="form-group">
                        <label for="image_grid_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                        <input id="image_grid_image" type="file" name="image" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['image_grid']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" required="required" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['image_grid']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="image_grid_location_url"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.location_url') ?></label>
                        <input id="image_grid_location_url" type="url" class="form-control" name="location_url" maxlength="2048" placeholder="<?= l('global.url_placeholder') ?>" />
                    </div>

                    <div class="form-group">
                        <label for="image_grid_columns"><i class="fas fa-fw fa-grip fa-sm text-muted mr-1"></i> <?= l('create_biolink_image_grid_modal.columns') ?></label>
                        <div class="row btn-group-toggle" data-toggle="buttons">
                            <div class="col-12 col-lg-6 h-100">
                                <label class="btn btn-light btn-block text-truncate active">
                                    <input type="radio" name="columns" value="2" class="custom-control-input" checked="checked" required="required" />
                                    2
                                </label>
                            </div>

                            <div class="col-12 col-lg-6 h-100">
                                <label class="btn btn-light btn-block text-truncate">
                                    <input type="radio" name="columns" value="3" class="custom-control-input" required="required" />
                                    3
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
