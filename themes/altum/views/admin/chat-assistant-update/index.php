<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/chats-assistants') ?>"><?= l('admin_chats_assistants.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_chat_assistant_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-id-card-alt text-primary-900 mr-2"></i> <?= l('admin_chat_assistant_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/chats-assistants/admin_chat_assistant_dropdown_button.php', ['id' => $data->chat_assistant->chat_assistant_id, 'resource_name' => $data->chat_assistant->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <div class="input-group">
                    <input type="text" id="name" name="name" value="<?= $data->chat_assistant->name ?>" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" maxlength="64" required="required" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#name_translate_container" aria-expanded="false" aria-controls="name_translate_container" data-tooltip title="<?= l('global.translate') ?>"><i class="fas fa-fw fa-sm fa-language"></i></button>
                    </div>
                </div>
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="collapse show" id="name_translate_container">
                <div class="p-3 bg-gray-50 rounded mb-4">
                    <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_name' ?>"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_name' ?>" name="<?= 'translations[' . $language_name . '][name]' ?>" value="<?= $data->chat_assistant->settings->translations->{$language_name}->name ?? null ?>" class="form-control <?= \Altum\Alerts::has_field_errors('translations_' . $language_name . '_name') ? 'is-invalid' : null ?>" maxlength="64" required="required" />
                            </div>
                            <?= \Altum\Alerts::output_field_error('translations_' . $language_name . '_name') ?>
                        </div>

                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_description' ?>"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_description' ?>" name="<?= 'translations[' . $language_name . '][description]' ?>" value="<?= $data->chat_assistant->settings->translations->{$language_name}->description ?? null ?>" class="form-control <?= \Altum\Alerts::has_field_errors('translations_' . $language_name . '_description') ? 'is-invalid' : null ?>" maxlength="256" required="required" />
                            </div>
                            <?= \Altum\Alerts::output_field_error('translations_' . $language_name . '_description') ?>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="prompt"><i class="fas fa-fw fa-sm fa-terminal text-muted mr-1"></i> <?= l('admin_chats_assistants.main.prompt') ?></label>
                <textarea id="prompt" name="prompt" class="form-control <?= \Altum\Alerts::has_field_errors('prompt') ? 'is-invalid' : null ?>" placeholder="<?= l('admin_chats_assistants.main.prompt_placeholder') ?>" maxlength="5000" required="required"><?= $data->chat_assistant->prompt ?></textarea>
                <?= \Altum\Alerts::output_field_error('prompt') ?>
                <small class="form-text text-muted"><?= l('admin_chats_assistants.main.prompt_help') ?></small>
            </div>

            <div class="form-group" data-file-image-input-wrapper data-file-input-wrapper-size-limit="<?= get_max_upload() ?>" data-file-input-wrapper-size-limit-error="<?= sprintf(l('global.error_message.file_size_limit'), get_max_upload()) ?>">
                <label for="image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('admin_chats_assistants.main.image') ?></label>
                <?= include_view(THEME_PATH . 'views/partials/file_image_input.php', ['uploads_file_key' => 'chats_assistants', 'file_key' => 'image', 'already_existing_image' => $data->chat_assistant->image]) ?>
                <?= \Altum\Alerts::output_field_error('image') ?>
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('chats_assistants')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), get_max_upload()) ?></small>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= $data->chat_assistant->order ?>" class="form-control <?= \Altum\Alerts::has_field_errors('order') ? 'is-invalid' : null ?>" />
                <?= \Altum\Alerts::output_field_error('order') ?>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->chat_assistant->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>
