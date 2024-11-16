<?php defined('ALTUMCODE') || die() ?>

<div class="row">
    <div class="col">
        <input id="<?= ($data->id ?? $data->file_key) ?>" type="file" name="<?= $data->file_key ?>" accept="<?= $data->accept ?? \Altum\Uploads::get_whitelisted_file_extensions_accept($data->uploads_file_key) ?>" class="form-control-file altum-file-input" <?= $data->input_data ?? null ?> />
    </div>

    <div data-image-container="<?= $data->image_container ?>" id="<?= ($data->id ?? $data->file_key) . '_preview' ?>" class="col-3 <?= !empty($data->already_existing_image) ? null : 'd-none' ?>">
        <div class="d-flex justify-content-center align-items-center">
            <a href="<?= $data->already_existing_image ? \Altum\Uploads::get_full_url($data->uploads_file_key) . $data->already_existing_image : '#' ?>" target="_blank" data-toggle="tooltip" title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                <img src="<?= $data->already_existing_image ? \Altum\Uploads::get_full_url($data->uploads_file_key) . $data->already_existing_image : null ?>" class="altum-file-input-preview rounded <?= !empty($data->already_existing_image) ? null : 'd-none' ?>" loading="lazy" />
            </a>
        </div>
    </div>

    <div data-image-container="<?= $data->image_container ?>" id="<?= ($data->id ?? $data->file_key) . '_remove_wrapper' ?>" class="col-12 <?= !empty($data->already_existing_image) ? null : 'd-none' ?>">
        <div class="custom-control custom-checkbox my-2">
            <input id="<?= ($data->id ?? $data->file_key) . '_remove' ?>" name="<?= $data->file_key . '_remove' ?>" type="checkbox" class="custom-control-input">
            <label class="custom-control-label" for="<?= ($data->id ?? $data->file_key) . '_remove' ?>">
                <span class="text-muted"><?= l('global.delete_file') ?></span>
            </label>
        </div>
    </div>

    <div id="<?= ($data->id ?? $data->file_key) . '_remove_selected_file_wrapper' ?>" class="col-12 d-none">
        <label href="#" role="button" id="<?= $data->file_key . '_remove_selected_file' ?>" type="button" class="my-2 text-muted text-decoration-none">
            <i class="fas fa-fw fa-sm fa-trash-alt mr-1"></i> <?= l('global.remove_selected_file') ?>
        </label>
    </div>
</div>
