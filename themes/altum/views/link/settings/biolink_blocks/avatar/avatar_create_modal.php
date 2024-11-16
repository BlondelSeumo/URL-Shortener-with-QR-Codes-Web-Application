<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_avatar" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('create_biolink_avatar_modal.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_avatar" method="post" role="form" enctype="multipart/form-data">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="avatar" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="avatar_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('global.image') ?></label>
                        <input id="avatar_image" type="file" name="image" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['avatar']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" required="required" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['avatar']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->avatar_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="avatar_size"><i class="fas fa-fw fa-expand fa-sm text-muted mr-1"></i> <?= l('create_biolink_avatar_modal.size') ?></label>
                        <select id="avatar_size" name="size" class="custom-select">
                            <option value="75">75x75px</option>
                            <option value="100">100x100px</option>
                            <option value="125">125x125px</option>
                            <option value="150">150x150px</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="avatar_border_radius"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_avatar_modal.border_radius') ?></label>
                        <select id="avatar_border_radius" name="border_radius" class="custom-select">
                            <option value="straight"><?= l('create_biolink_avatar_modal.border_radius_straight') ?></option>
                            <option value="round"><?= l('create_biolink_avatar_modal.border_radius_round') ?></option>
                            <option value="rounded"><?= l('create_biolink_avatar_modal.border_radius_rounded') ?></option>
                        </select>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
