<?php defined('ALTUMCODE') || die() ?>

<div>
    <h2 class="h5">OpenAI</h2>
    <p class="text-muted">Used for <code>NSFW moderation</code>, <code>AI Documents</code>, <code>AI Images</code>, <code>AI Transcriptions</code>, <code>AI Syntheses</code>, <code>AI Chats</code>.</p>
    <div class="form-group">
        <label for="openai_api_key"><?= l('admin_settings.aix.openai_api_key') ?></label>
        <textarea id="openai_api_key" name="openai_api_key" class="form-control"><?= settings()->aix->openai_api_key ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.aix.openai_api_key_help') ?></small>
        <small class="form-text text-muted"><?= l('admin_settings.aix.openai_api_key_help') ?></small>
    </div>

    <h2 class="h5">ClipDrop</h2>
    <p class="text-muted">Used for <code>AI Images</code>.</p>

    <div class="form-group">
        <label for="clipdrop_api_key"><?= l('admin_settings.aix.clipdrop_api_key') ?></label>
        <textarea id="clipdrop_api_key" name="clipdrop_api_key" class="form-control"><?= settings()->aix->clipdrop_api_key ?></textarea>
        <small class="form-text text-muted"><?= l('admin_settings.aix.clipdrop_api_key_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="input_moderation_is_enabled" name="input_moderation_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->input_moderation_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="input_moderation_is_enabled"><?= l('admin_settings.aix.input_moderation_is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.aix.input_moderation_is_enabled_help') ?></small>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#documents_container" aria-expanded="false" aria-controls="documents_container">
        <i class="fas fa-fw fa-robot fa-sm mr-1"></i> <?= l('admin_documents.menu') ?>
    </button>

    <div class="collapse" id="documents_container">
        <div class="form-group custom-control custom-switch">
            <input id="documents_is_enabled" name="documents_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->documents_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="documents_is_enabled"><?= l('admin_settings.aix.documents_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="documents_available_languages"><?= l('admin_settings.aix.documents_available_languages') ?></label>
            <textarea id="documents_available_languages" type="text" name="documents_available_languages" class="form-control" rows="5"><?= implode(',', settings()->aix->documents_available_languages ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.aix.documents_available_languages_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#images_container" aria-expanded="false" aria-controls="images_container">
        <i class="fas fa-fw fa-icons fa-sm mr-1"></i> <?= l('admin_images.menu') ?>
    </button>

    <div class="collapse" id="images_container">
        <div class="form-group custom-control custom-switch">
            <input id="images_is_enabled" name="images_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->images_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="images_is_enabled"><?= l('admin_settings.aix.images_is_enabled') ?></label>
        </div>

        <div class="form-group custom-control custom-switch">
            <input id="images_display_latest_on_index" name="images_display_latest_on_index" type="checkbox" class="custom-control-input" <?= settings()->aix->images_display_latest_on_index ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="images_display_latest_on_index"><?= l('admin_settings.aix.images_display_latest_on_index') ?></label>
        </div>

        <div class="form-group">
            <label for="images_available_artists"><?= l('admin_settings.aix.images_available_artists') ?></label>
            <textarea id="images_available_artists" type="text" name="images_available_artists" class="form-control" rows="5"><?= implode(',', settings()->aix->images_available_artists ?? []) ?></textarea>
            <small class="form-text text-muted"><?= l('admin_settings.aix.images_available_artists_help') ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#transcriptions_container" aria-expanded="false" aria-controls="transcriptions_container">
        <i class="fas fa-fw fa-microphone-alt fa-sm mr-1"></i> <?= l('admin_transcriptions.menu') ?>
    </button>

    <div class="collapse" id="transcriptions_container">
        <div class="form-group custom-control custom-switch">
            <input id="transcriptions_is_enabled" name="transcriptions_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->transcriptions_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="transcriptions_is_enabled"><?= l('admin_settings.aix.transcriptions_is_enabled') ?></label>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#chats_container" aria-expanded="false" aria-controls="chats_container">
        <i class="fas fa-fw fa-comments fa-sm mr-1"></i> <?= l('admin_chats.menu') ?>
    </button>

    <div class="collapse" id="chats_container">
        <div class="form-group custom-control custom-switch">
            <input id="chats_is_enabled" name="chats_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->chats_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="chats_is_enabled"><?= l('admin_settings.aix.chats_is_enabled') ?></label>
        </div>

        <div class="form-group">
            <label for="chats_assistant_name"><?= l('admin_settings.aix.chats_assistant_name') ?></label>
            <input id="chats_assistant_name" type="text" name="chats_assistant_name" value="<?= settings()->aix->chats_assistant_name ?>" class="form-control" />
        </div>

        <div class="form-group">
            <label for="chats_avatar"><i class="fas fa-fw fa-sm fa-user-circle text-muted mr-1"></i> <?= l('admin_settings.aix.chats_avatar') ?></label>
            <?php if(!empty(settings()->aix->chats_avatar)): ?>
                <div class="m-1">
                    <img src="<?= \Altum\Uploads::get_full_url('chats_assistants') . settings()->aix->chats_avatar ?>" class="img-fluid" style="max-height: 2.5rem;height: 2.5rem;" />
                </div>
                <div class="custom-control custom-checkbox my-2">
                    <input id="chats_avatar_remove" name="chats_avatar_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#chats_avatar').classList.add('d-none') : document.querySelector('#chats_avatar').classList.remove('d-none')">
                    <label class="custom-control-label" for="chats_avatar_remove">
                        <span class="text-muted"><?= l('global.delete_file') ?></span>
                    </label>
                </div>
            <?php endif ?>
            <input id="chats_avatar" type="file" name="chats_avatar" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('chats_assistants') ?>" class="form-control-file altum-file-input" />
            <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('chats_assistants')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
        </div>
    </div>

    <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#syntheses_container" aria-expanded="false" aria-controls="syntheses_container">
        <i class="fas fa-fw fa-voicemail fa-sm mr-1"></i> <?= l('admin_syntheses.menu') ?>
    </button>

    <div class="collapse" id="syntheses_container">
        <div class="form-group custom-control custom-switch">
            <input id="syntheses_is_enabled" name="syntheses_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->aix->syntheses_is_enabled ? 'checked="checked"' : null?>>
            <label class="custom-control-label" for="syntheses_is_enabled"><?= l('admin_settings.aix.syntheses_is_enabled') ?></label>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
