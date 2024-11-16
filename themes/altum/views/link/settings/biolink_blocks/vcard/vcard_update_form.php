<?php defined('ALTUMCODE') || die() ?>

<form name="update_biolink_" method="post" role="form">
    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
    <input type="hidden" name="request_type" value="update" />
    <input type="hidden" name="block_type" value="vcard" />
    <input type="hidden" name="biolink_block_id" value="<?= $row->biolink_block_id ?>" />

    <div class="notification-container"></div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'vcard_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'vcard_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-id-card fa-sm mr-1"></i>  <?= l('create_biolink_vcard_modal.vcard_header') ?>
    </button>

    <div class="collapse" id="<?= 'vcard_settings_container_' . $row->biolink_block_id ?>">
        <div class="form-group">
            <label for="<?= 'vcard_avatar_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_avatar') ?></label>
            <div data-image-container="vcard_avatar" class="<?= !empty($row->settings->vcard_avatar) ? null : 'd-none' ?>">
                <div class="row">
                    <div class="my-2 col-6 col-xl-4">
                        <img src="<?= $row->settings->vcard_avatar ? \Altum\Uploads::get_full_url('avatars') . $row->settings->vcard_avatar : null ?>" class="img-fluid rounded <?= !empty($row->settings->vcard_avatar) ? null : 'd-none' ?>" loading="lazy" />
                    </div>
                </div>
                <div class="custom-control custom-checkbox my-2">
                    <input id="<?= $row->biolink_block_id . '_vcard_avatar_remove' ?>" name="vcard_avatar_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#<?= 'vcard_avatar_' . $row->biolink_block_id ?>').classList.add('d-none') : document.querySelector('#<?= 'vcard_avatar_' . $row->biolink_block_id ?>').classList.remove('d-none')">
                    <label class="custom-control-label" for="<?= $row->biolink_block_id . '_vcard_avatar_remove' ?>">
                        <span class="text-muted"><?= l('global.delete_file') ?></span>
                    </label>
                </div>
            </div>
            <input id="<?= 'vcard_avatar_' . $row->biolink_block_id ?>" type="file" name="vcard_avatar" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards_avatars') ?>" class="form-control-file altum-file-input" />
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards_avatars')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), 0.75) ?></small>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="<?= 'vcard_first_name_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_first_name') ?></label>
                    <input type="text" id="<?= 'vcard_first_name_' . $row->biolink_block_id ?>" name="vcard_first_name" class="form-control" value="<?= $row->settings->vcard_first_name ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['first_name']['max_length'] ?>" />
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="<?= 'vcard_last_name_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_last_name') ?></label>
                    <input type="text" id="<?= 'vcard_last_name_' . $row->biolink_block_id ?>" name="vcard_last_name" class="form-control" value="<?= $row->settings->vcard_last_name ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['last_name']['max_length'] ?>" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_email_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('global.email') ?></label>
            <input type="email" id="<?= 'vcard_email_' . $row->biolink_block_id ?>" name="vcard_email" class="form-control" value="<?= $row->settings->vcard_email ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['email']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_url_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_url') ?></label>
            <input type="url" id="<?= 'vcard_url_' . $row->biolink_block_id ?>" name="vcard_url" class="form-control" value="<?= $row->settings->vcard_url ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['url']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_company_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-building fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_company') ?></label>
            <input type="text" id="<?= 'vcard_company_' . $row->biolink_block_id ?>" name="vcard_company" class="form-control" value="<?= $row->settings->vcard_company ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['company']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_job_title_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_job_title') ?></label>
            <input type="text" id="<?= 'vcard_job_title_' . $row->biolink_block_id ?>" name="vcard_job_title" class="form-control" value="<?= $row->settings->vcard_job_title ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['job_title']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_birthday_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-birthday-cake fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_birthday') ?></label>
            <input type="date" id="<?= 'vcard_birthday_' . $row->biolink_block_id ?>" name="vcard_birthday" class="form-control" value="<?= $row->settings->vcard_birthday ?? null ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_street_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-road fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_street') ?></label>
            <input type="text" id="<?= 'vcard_street_' . $row->biolink_block_id ?>" name="vcard_street" class="form-control" value="<?= $row->settings->vcard_street ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['street']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_city_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-city fa-sm text-muted mr-1"></i> <?= l('global.city') ?></label>
            <input type="text" id="<?= 'vcard_city_' . $row->biolink_block_id ?>" name="vcard_city" class="form-control" value="<?= $row->settings->vcard_city ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['city']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_zip_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-mail-bulk fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_zip') ?></label>
            <input type="text" id="<?= 'vcard_zip_' . $row->biolink_block_id ?>" name="vcard_zip" class="form-control" value="<?= $row->settings->vcard_zip ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['zip']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_region_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-flag fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_region') ?></label>
            <input type="text" id="<?= 'vcard_region_' . $row->biolink_block_id ?>" name="vcard_region" class="form-control" value="<?= $row->settings->vcard_region ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['region']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_country_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.country') ?></label>
            <input type="text" id="<?= 'vcard_country_' . $row->biolink_block_id ?>" name="vcard_country" class="form-control" value="<?= $row->settings->vcard_country ?? null ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['country']['max_length'] ?>" />
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_note_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_note') ?></label>
            <textarea id="<?= 'vcard_note_' . $row->biolink_block_id ?>" name="vcard_note" class="form-control" maxlength="<?= $data->biolink_blocks['vcard']['fields']['note']['max_length'] ?>"><?= $row->settings->vcard_note ?? null ?></textarea>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'vcard_phone_numbers_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'vcard_phone_numbers_container_' . $row->biolink_block_id ?>">
            <i class="fas fa-fw fa-phone-square-alt fa-sm mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_numbers') ?>
        </button>

        <div class="collapse" id="<?= 'vcard_phone_numbers_container_' . $row->biolink_block_id ?>">
            <div id="<?= 'vcard_phone_numbers_' . $row->biolink_block_id ?>" data-biolink-block-id="<?= $row->biolink_block_id ?>">
                <?php foreach($row->settings->vcard_phone_numbers ?? [] as $key => $phone_number): ?>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="<?= 'vcard_phone_number_label_' . $key . '_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_label') ?></label>
                            <input id="<?= 'vcard_phone_number_label_' . $key . '_' . $row->biolink_block_id ?>" type="text" name="vcard_phone_number_label[<?= $key ?>]" class="form-control" value="<?= $phone_number->label ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['phone_number_label']['max_length'] ?>" />
                        </div>

                        <div class="form-group">
                            <label for="<?= 'vcard_phone_number_value_' . $key . '_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_value') ?></label>
                            <input id="<?= 'vcard_phone_number_value_' . $key . '_' . $row->biolink_block_id ?>" type="text" name="vcard_phone_number_value[<?= $key ?>]" value="<?= $phone_number->value ?>" class="form-control" maxlength="<?= $data->biolink_blocks['vcard']['fields']['phone_number_value']['max_length'] ?>" required="required" />
                        </div>

                        <button type="button" data-remove="vcard_phone_numbers" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="mb-3">
                <button data-add="vcard_phone_numbers" data-biolink-block-id="<?= $row->biolink_block_id ?>" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
            </div>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'vcard_socials_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'vcard_socials_container_' . $row->biolink_block_id ?>">
            <i class="fas fa-fw fa-share-alt fa-sm mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_socials') ?>
        </button>

        <div class="collapse" id="<?= 'vcard_socials_container_' . $row->biolink_block_id ?>">
            <div id="<?= 'vcard_socials_' . $row->biolink_block_id ?>" data-biolink-block-id="<?= $row->biolink_block_id ?>">
                <?php foreach($row->settings->vcard_socials ?? [] as $key => $social): ?>
                    <div class="mb-4">
                        <div class="form-group">
                            <label for="<?= 'vcard_social_label_' . $key . '_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_label') ?></label>
                            <input id="<?= 'vcard_social_label_' . $key . '_' . $row->biolink_block_id ?>" type="text" name="vcard_social_label[<?= $key ?>]" class="form-control" value="<?= $social->label ?>" maxlength="<?= $data->biolink_blocks['vcard']['fields']['social_label']['max_length'] ?>" required="required" />
                        </div>

                        <div class="form-group">
                            <label for="<?= 'vcard_social_value_' . $key . '_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_value') ?></label>
                            <input id="<?= 'vcard_social_value_' . $key . '_' . $row->biolink_block_id ?>" type="url" name="vcard_social_value[<?= $key ?>]" value="<?= $social->value ?>" class="form-control" maxlength="<?= $data->biolink_blocks['vcard']['fields']['social_value']['max_length'] ?>" required="required" />
                        </div>

                        <button type="button" data-remove="vcard_social" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                    </div>
                <?php endforeach ?>
            </div>

            <div class="mb-3">
                <button data-add="vcard_social" data-biolink-block-id="<?= $row->biolink_block_id ?>" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'button_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'button_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-square-check fa-sm mr-1"></i> <?= l('create_biolink_link_modal.button_header') ?>
    </button>

    <div class="collapse" id="<?= 'button_settings_container_' . $row->biolink_block_id ?>">
        <div class="form-group">
            <label for="<?= 'vcard_name_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.name') ?></label>
            <input id="<?= 'vcard_name_' . $row->biolink_block_id ?>" type="text" name="name" class="form-control" value="<?= $row->settings->name ?>" maxlength="128" required="required" />
        </div>

        <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= settings()->links->thumbnail_image_size_limit ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?>">
            <label for="<?= 'vcard_image_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.image') ?></label>
            <?= include_view(THEME_PATH . 'views/partials/custom_file_image_input.php', [
                'id'=> 'vcard_image_' . $row->biolink_block_id,
                'uploads_file_key' => 'block_thumbnail_images',
                'file_key' => 'image',
                'already_existing_image' => $row->settings->image,
                'image_container' => 'image',
                'accept' => \Altum\Uploads::array_to_list_format($data->biolink_blocks['vcard']['whitelisted_thumbnail_image_extensions']),
            ]) ?>
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::array_to_list_format($data->biolink_blocks['vcard']['whitelisted_thumbnail_image_extensions'])) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->thumbnail_image_size_limit) ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_icon_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-icons fa-sm text-muted mr-1"></i> <?= l('global.icon') ?></label>
            <input id="<?= 'vcard_icon_' . $row->biolink_block_id ?>" type="text" name="icon" class="form-control" value="<?= $row->settings->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_text_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.text_color') ?></label>
            <input id="<?= 'vcard_text_color_' . $row->biolink_block_id ?>" type="hidden" name="text_color" class="form-control" value="<?= $row->settings->text_color ?>" required="required" />
            <div class="text_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'block_text_alignment_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-align-center fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.text_alignment') ?></label>
            <div class="row btn-group-toggle" data-toggle="buttons">
                <?php foreach(['center', 'justify', 'left', 'right'] as $text_alignment): ?>
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'active"' : null?>">
                            <input type="radio" name="text_alignment" value="<?= $text_alignment ?>" class="custom-control-input" <?= ($row->settings->text_alignment  ?? null) == $text_alignment ? 'checked="checked"' : null ?> />
                            <i class="fas fa-fw fa-align-<?= $text_alignment ?> fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.text_alignment.' . $text_alignment) ?>
                        </label>
                    </div>
                <?php endforeach ?>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_background_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.background_color') ?></label>
            <input id="<?= 'vcard_background_color_' . $row->biolink_block_id ?>" type="hidden" name="background_color" class="form-control" value="<?= $row->settings->background_color ?>" required="required" />
            <div class="background_color_pickr"></div>
        </div>

        <div class="form-group">
            <label for="<?= 'vcard_animation_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-film fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.animation') ?></label>
            <select id="<?= 'vcard_animation_' . $row->biolink_block_id ?>" name="animation" class="custom-select">
                <option value="false" <?= !$row->settings->animation ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                <?php foreach(require APP_PATH . 'includes/biolink_animations.php' as $animation): ?>
                    <option value="<?= $animation ?>" <?= $row->settings->animation == $animation ? 'selected="selected"' : null ?>><?= $animation ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="form-group" data-animation="<?= implode(',', require APP_PATH . 'includes/biolink_animations.php') ?>">
            <label for="<?= 'vcard_animation_runs_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-play-circle fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.animation_runs') ?></label>
            <select id="<?= 'vcard_animation_runs_' . $row->biolink_block_id ?>" name="animation_runs" class="custom-select">
                <option value="repeat-1" <?= $row->settings->animation_runs == 'repeat-1' ? 'selected="selected"' : null ?>>1</option>
                <option value="repeat-2" <?= $row->settings->animation_runs == 'repeat-2' ? 'selected="selected"' : null ?>>2</option>
                <option value="repeat-3" <?= $row->settings->animation_runs == 'repeat-3' ? 'selected="selected"' : null ?>>3</option>
                <option value="infinite" <?= $row->settings->animation_runs == 'repeat-infinite' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.animation_runs_infinite') ?></option>
            </select>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_container_' . $row->biolink_block_id ?>">
            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('create_biolink_link_modal.border_header') ?>
        </button>

        <div class="collapse" id="<?= 'border_container_' . $row->biolink_block_id ?>">
            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'block_border_width_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_width') ?></label>
                <input id="<?= 'block_border_width_' . $row->biolink_block_id ?>" type="range" min="0" max="5" class="form-control-range" name="border_width" value="<?= $row->settings->border_width ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_color') ?></label>
                <input id="<?= 'block_border_color_' . $row->biolink_block_id ?>" type="hidden" name="border_color" class="form-control" value="<?= $row->settings->border_color ?>" required="required" />
                <div class="border_color_pickr"></div>
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_radius_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'active"' : null?>">
                            <input type="radio" name="border_radius" value="straight" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'straight' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-square-full fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius_straight') ?>
                        </label>
                    </div>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'round' ? 'active' : null?>">
                            <input type="radio" name="border_radius" value="round" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'round' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-circle fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius_round') ?>
                        </label>
                    </div>
                    <div class="col-4">
                        <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'active' : null?>">
                            <input type="radio" name="border_radius" value="rounded" class="custom-control-input" <?= ($row->settings->border_radius  ?? null) == 'rounded' ? 'checked="checked"' : null?> />
                            <i class="fas fa-fw fa-square fa-sm mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius_rounded') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_style_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_style') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <?php foreach(['solid', 'dashed', 'double', 'outset', 'inset'] as $border_style): ?>
                        <div class="col-4">
                            <label class="btn btn-light btn-block text-truncate <?= ($row->settings->border_style  ?? null) == $border_style ? 'active"' : null?>">
                                <input type="radio" name="border_style" value="<?= $border_style ?>" class="custom-control-input" <?= ($row->settings->border_style  ?? null) == $border_style ? 'checked="checked"' : null?> />
                                <?= l('create_biolink_link_modal.input.border_style_' . $border_style) ?>
                            </label>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'border_shadow_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'border_shadow_container_' . $row->biolink_block_id ?>">
            <i class="fas fa-fw fa-cloud fa-sm mr-1"></i> <?= l('create_biolink_link_modal.border_shadow_header') ?>
        </button>

        <div class="collapse" id="<?= 'border_shadow_container_' . $row->biolink_block_id ?>">
            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'block_border_shadow_offset_x_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_offset_x') ?></label>
                <input id="<?= 'block_border_shadow_offset_x_' . $row->biolink_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_x" value="<?= $row->settings->border_shadow_offset_x ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'block_border_shadow_offset_y_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_offset_y') ?></label>
                <input id="<?= 'block_border_shadow_offset_y_' . $row->biolink_block_id ?>" type="range" min="-20" max="20" class="form-control-range" name="border_shadow_offset_y" value="<?= $row->settings->border_shadow_offset_y ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'block_border_shadow_blur_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_blur') ?></label>
                <input id="<?= 'block_border_shadow_blur_' . $row->biolink_block_id ?>" type="range" min="0" max="20" class="form-control-range" name="border_shadow_blur" value="<?= $row->settings->border_shadow_blur ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="<?= 'block_border_shadow_spread_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_spread') ?></label>
                <input id="<?= 'block_border_shadow_spread_' . $row->biolink_block_id ?>" type="range" min="0" max="10" class="form-control-range" name="border_shadow_spread" value="<?= $row->settings->border_shadow_spread ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'block_border_shadow_color_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_color') ?></label>
                <input id="<?= 'block_border_shadow_color_' . $row->biolink_block_id ?>" type="hidden" name="border_shadow_color" class="form-control" value="<?= $row->settings->border_shadow_color ?>" required="required" />
                <div class="border_shadow_color_pickr"></div>
            </div>
        </div>
    </div>

    <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'display_settings_container_' . $row->biolink_block_id ?>" aria-expanded="false" aria-controls="<?= 'display_settings_container_' . $row->biolink_block_id ?>">
        <i class="fas fa-fw fa-display fa-sm mr-1"></i> <?= l('create_biolink_link_modal.display_settings_header') ?>
    </button>

    <div class="collapse" id="<?= 'display_settings_container_' . $row->biolink_block_id ?>">
        <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
            <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                <div class="form-group custom-control custom-switch">
                    <input
                            id="<?= 'link_schedule_' . $row->biolink_block_id ?>"
                            name="schedule" type="checkbox"
                            class="custom-control-input"
                        <?= !empty($row->start_date) && !empty($row->end_date) ? 'checked="checked"' : null ?>
                        <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                    >
                    <label class="custom-control-label" for="<?= 'link_schedule_' . $row->biolink_block_id ?>"><?= l('link.settings.schedule') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.schedule_help') ?></small>
                </div>
            </div>
        </div>

        <div class="mt-3 schedule_container" style="display: none;">
            <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_start_date_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                                <input
                                        id="<?= 'link_start_date_' . $row->biolink_block_id ?>"
                                        type="text"
                                        class="form-control"
                                        name="start_date"
                                        value="<?= \Altum\Date::get($row->start_date, 1) ?>"
                                        placeholder="<?= l('link.settings.start_date') ?>"
                                        autocomplete="off"
                                        data-daterangepicker
                                >
                            </div>
                        </div>

                        <div class="col">
                            <div class="form-group">
                                <label for="<?= 'link_end_date_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                <input
                                        id="<?= 'link_end_date_' . $row->biolink_block_id ?>"
                                        type="text"
                                        class="form-control"
                                        name="end_date"
                                        value="<?= \Altum\Date::get($row->end_date, 1) ?>"
                                        placeholder="<?= l('link.settings.end_date') ?>"
                                        autocomplete="off"
                                        data-daterangepicker
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_continents_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-earth-europe fa-sm text-muted mr-1"></i> <?= l('global.continents') ?></label>
            <select id="<?= 'link_display_continents_' . $row->biolink_block_id ?>" name="display_continents[]" class="custom-select" multiple="multiple">
                <?php foreach(get_continents_array() as $continent_code => $continent_name): ?>
                    <option value="<?= $continent_code ?>" <?= in_array($continent_code, $row->settings->display_continents ?? []) ? 'selected="selected"' : null ?>><?= $continent_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_countries_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.countries') ?></label>
            <select id="<?= 'link_display_countries_' . $row->biolink_block_id ?>" name="display_countries[]" class="custom-select" multiple="multiple">
                <?php foreach(get_countries_array() as $country => $country_name): ?>
                    <option value="<?= $country ?>" <?= in_array($country, $row->settings->display_countries ?? []) ? 'selected="selected"' : null ?>><?= $country_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_cities_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-sm fa-city text-muted mr-1"></i> <?= l('global.cities') ?></label>
            <input type="text" id="<?= 'link_display_cities_' . $row->biolink_block_id ?>" name="display_cities" value="<?= implode(',', $row->settings->display_cities ?? []) ?>" class="form-control" placeholder="<?= l('create_biolink_link_modal.input.display_cities_placeholder') ?>" />
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.input.display_cities_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_devices_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-laptop fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_devices') ?></label>
            <select id="<?= 'link_display_devices_' . $row->biolink_block_id ?>" name="display_devices[]" class="custom-select" multiple="multiple">
                <?php foreach(['desktop', 'tablet', 'mobile'] as $device_type): ?>
                    <option value="<?= $device_type ?>" <?= in_array($device_type, $row->settings->display_devices ?? []) ? 'selected="selected"' : null ?>><?= l('global.device.' . $device_type) ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_operating_systems_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-server fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_operating_systems') ?></label>
            <select id="<?= 'link_display_operating_systems_' . $row->biolink_block_id ?>" name="display_operating_systems[]" class="custom-select" multiple="multiple">
                <?php foreach(['iOS', 'Android', 'Windows', 'OS X', 'Linux', 'Ubuntu', 'Chrome OS'] as $os_name): ?>
                    <option value="<?= $os_name ?>" <?= in_array($os_name, $row->settings->display_operating_systems ?? []) ? 'selected="selected"' : null ?>><?= $os_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_browsers_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-window-restore fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_browsers') ?></label>
            <select id="<?= 'link_display_browsers_' . $row->biolink_block_id ?>" name="display_browsers[]" class="custom-select" multiple="multiple">
                <?php foreach(['Chrome', 'Firefox', 'Safari', 'Edge', 'Opera', 'Samsung Internet'] as $browser_name): ?>
                    <option value="<?= $browser_name ?>" <?= in_array($browser_name, $row->settings->display_browsers ?? []) ? 'selected="selected"' : null ?>><?= $browser_name ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.settings.display_help') ?></small>
        </div>

        <div class="form-group">
            <label for="<?= 'link_display_languages_' . $row->biolink_block_id ?>"><i class="fas fa-fw fa-language fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.display_languages') ?></label>
            <select id="<?= 'link_display_languages_' . $row->biolink_block_id ?>" name="display_languages[]" class="custom-select" multiple="multiple">
                <?php foreach(get_locale_languages_array() as $locale => $language): ?>
                    <option value="<?= $locale ?>" <?= in_array($locale, $row->settings->display_languages ?? []) ? 'selected="selected"' : null ?>><?= $language ?></option>
                <?php endforeach ?>
            </select>
            <small class="form-text text-muted"><?= l('create_biolink_link_modal.settings.display_help') ?></small>
        </div>
    </div>


    <div class="mt-4">
        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
    </div>
</form>
