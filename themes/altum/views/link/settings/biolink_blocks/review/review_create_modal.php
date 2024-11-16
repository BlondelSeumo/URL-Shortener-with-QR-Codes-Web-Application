<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_review" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('create_biolink_review_modal.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_review" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="review" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="review_title"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_review_modal.title') ?></label>
                        <input id="review_title" type="text" name="title" class="form-control" maxlength="128" />
                    </div>

                    <div class="form-group">
                        <label for="review_description"><i class="fas fa-fw fa-pen fa-sm text-muted mr-1"></i> <?= l('create_biolink_review_modal.description') ?></label>
                        <textarea id="review_description" name="description" class="form-control" maxlength="1024"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="review_image"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('create_biolink_review_modal.image') ?></label>
                        <input id="review_image" type="file" name="image" accept="<?= \Altum\Uploads::array_to_list_format($data->biolink_blocks['review']['whitelisted_image_extensions']) ?>" class="form-control-file altum-file-input" />
                        <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['review']['whitelisted_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->image_size_limit) ?></small>
                    </div>

                    <div class="form-group">
                        <label for="review_author_name"><i class="fas fa-fw fa-user fa-sm text-muted mr-1"></i> <?= l('create_biolink_review_modal.author_name') ?></label>
                        <input id="review_author_name" type="text" name="author_name" class="form-control" maxlength="128" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="review_author_description"><i class="fas fa-fw fa-user-tag fa-sm text-muted mr-1"></i> <?= l('create_biolink_review_modal.author_description') ?></label>
                        <input id="review_author_description" type="text" name="author_description" class="form-control" maxlength="128" />
                    </div>

                    <div class="form-group">
                        <label for="review_stars"><i class="fas fa-fw fa-star fa-sm text-muted mr-1"></i> <?= l('create_biolink_review_modal.stars') ?></label>
                        <input id="review_stars" type="number" min="1" max="5" name="stars" class="form-control" value="5" required="required" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
