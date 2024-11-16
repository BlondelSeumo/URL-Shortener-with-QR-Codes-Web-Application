<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/templates') ?>"><?= l('admin_templates.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_template_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-moon text-primary-900 mr-2"></i> <?= l('admin_template_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/templates/admin_template_dropdown_button.php', ['id' => $data->template->template_id, 'resource_name' => $data->template->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <div class="input-group">
                    <input type="text" id="name" name="name" value="<?= $data->template->name ?>" class="form-control" maxlength="64" required="required" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#name_translate_container" aria-expanded="false" aria-controls="name_translate_container" data-tooltip title="<?= l('global.translate') ?>" data-tooltip-hide-on-click><i class="fas fa-fw fa-sm fa-language"></i></button>
                    </div>
                </div>
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
                                <input type="text" id="<?= 'translation_' . $language_name . '_name' ?>" name="<?= 'translations[' . $language_name . '][name]' ?>" value="<?= $data->template->settings->translations->{$language_name}->name ?? null ?>" class="form-control" maxlength="64" required="required" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="<?= 'translation_' . $language_name . '_description' ?>"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><?= $language_name ?></span>
                                </div>
                                <input type="text" id="<?= 'translation_' . $language_name . '_description' ?>" name="<?= 'translations[' . $language_name . '][description]' ?>" value="<?= $data->template->settings->translations->{$language_name}->description ?? null ?>" class="form-control" maxlength="256" required="required" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="form-group">
                <label for="template_category_id"><i class="fas fa-fw fa-sm fa-folder text-muted mr-1"></i> <?= l('admin_templates_categories.main.template_category_id') ?></label>
                <select id="template_category_id" name="template_category_id" class="custom-select">
                    <?php foreach($data->templates_categories as $template_id => $template): ?>
                        <option value="<?= $template_id ?>" <?= $data->template->template_category_id == $template->template_category_id ? 'selected="selected"' : null ?>><?= $template->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="prompt"><i class="fas fa-fw fa-sm fa-terminal text-muted mr-1"></i> <?= l('admin_templates.main.prompt') ?></label>
                <textarea id="prompt" name="prompt" class="form-control" placeholder="<?= l('admin_templates.main.prompt_placeholder') ?>" maxlength="5000" required="required"><?= $data->template->prompt ?></textarea>
                <small class="form-text text-muted"><?= l('admin_templates.main.prompt_help') ?></small>
            </div>

            <div class="form-group">
                <h2 class="h6"><i class="fas fa-fw fa-sm fa-keyboard text-muted mr-1"></i> <?= l('admin_templates.main.inputs') ?></h2>
                <div id="inputs">
                    <?php foreach($data->template->settings->inputs as $input_key => $input): ?>
                        <div class="p-3 bg-gray-50 rounded mb-4" data-input>
                            <div class="form-row">
                                <div class="form-group col-lg">
                                    <label for="<?= 'input_key_' . $input_key ?>"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_templates.main.input.key') ?></label>
                                    <input id="<?= 'input_key_' . $input_key ?>" type="text" name="inputs[<?= $input_key ?>][key]" value="<?= $input_key ?>" class="form-control" placeholder="<?= l('admin_templates.main.input.key_placeholder') ?>" />
                                </div>

                                <div class="form-group col-lg">
                                    <label for="<?= 'input_type_' . $input_key ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_templates.main.input.type') ?></label>
                                    <select id="<?= 'input_type_' . $input_key ?>" name="inputs[<?= $input_key ?>][type]" class="custom-select">
                                        <option value="input" <?= $input->type == 'input' ? 'selected="selected"' : null ?>>input</option>
                                        <option value="textarea" <?= $input->type == 'textarea' ? 'selected="selected"' : null ?>>textarea</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg">
                                    <label for="<?= 'input_icon_' . $input_key ?>"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                                    <input id="<?= 'input_icon_' . $input_key ?>" type="text" name="inputs[<?= $input_key ?>][icon]" value="<?= $input->icon ?>" class="form-control" placeholder="<?= l('global.icon_placeholder') ?>" />
                                </div>
                            </div>

                            <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                                <div class="form-group">
                                    <label for="<?= 'input_translation_' . $language_name . '_name_' . $input_key ?>"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?= $language_name ?></span>
                                        </div>
                                        <input type="text" id="<?= 'input_translation_' . $language_name . '_name_' . $input_key ?>" name="<?= 'inputs[' . $input_key . '][translations][' . $language_name . '][name]' ?>" value="<?= $input->translations->{$language_name}->name ?? null ?>" class="form-control" maxlength="128" required="required" />
                                    </div>
                                </div>
                            <?php endforeach ?>

                            <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                                <div class="form-group">
                                    <label for="<?= 'input_translation_' . $language_name . '_placeholder_' . $input_key ?>"><i class="fas fa-fw fa-sm fa-highlighter text-muted mr-1"></i> <?= l('admin_templates.main.input.placeholder') ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?= $language_name ?></span>
                                        </div>
                                        <input type="text" id="<?= 'input_translation_' . $language_name . '_placeholder_' . $input_key ?>" name="<?= 'inputs[' . $input_key . '][translations][' . $language_name . '][placeholder]' ?>" value="<?= $input->translations->{$language_name}->placeholder ?? null ?>" class="form-control" maxlength="128" />
                                    </div>
                                </div>
                            <?php endforeach ?>

                            <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
                                <div class="form-group">
                                    <label for="<?= 'input_translation_' . $language_name . '_help_' . $input_key ?>"><i class="fas fa-fw fa-sm fa-info-circle text-muted mr-1"></i> <?= l('admin_templates.main.input.help') ?></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><?= $language_name ?></span>
                                        </div>
                                        <input type="text" id="<?= 'input_translation_' . $language_name . '_help_' . $input_key ?>" name="<?= 'inputs[' . $input_key . '][translations][' . $language_name . '][help]' ?>" value="<?= $input->translations->{$language_name}->help ?? null ?>" class="form-control" maxlength="128" />
                                    </div>
                                </div>
                            <?php endforeach ?>

                            <button type="button" data-remove="input" class="btn btn-block btn-outline-danger"><i class="fas fa-sm fa-fw fa-times mr-1"></i> <?= l('global.delete') ?></button>
                        </div>
                    <?php endforeach ?>
                </div>

                <div class="mb-3">
                    <button data-add="input" type="button" class="btn btn-block btn-outline-success"><i class="fas fa-sm fa-fw fa-plus-circle mr-1"></i> <?= l('global.create') ?></button>
                </div>
            </div>

            <div class="form-group">
                <label for="icon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                <input type="text" id="icon" name="icon" value="<?= $data->template->icon ?>" class="form-control" maxlength="64" placeholder="<?= l('global.icon_placeholder') ?>" required="required" />
                <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= $data->template->order ?>" class="form-control" />
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->template->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>

<template id="template_input">
    <div class="p-3 bg-gray-50 rounded mb-4" data-input>
        <div class="form-row">
            <div class="form-group col-lg">
                <label for="input_key_INCREMENT"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_templates.main.input.key') ?></label>
                <input id="input_key_INCREMENT" type="text" name="inputs[INCREMENT][key]" class="form-control" placeholder="<?= l('admin_templates.main.input.key_placeholder') ?>" />
            </div>

            <div class="form-group col-lg">
                <label for="input_type_INCREMENT"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_templates.main.input.type') ?></label>
                <select id="input_type_INCREMENT" name="inputs[INCREMENT][type]" class="custom-select">
                    <option value="input">input</option>
                    <option value="textarea">textarea</option>
                </select>
            </div>

            <div class="form-group col-lg">
                <label for="input_icon_INCREMENT"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                <input id="input_icon_INCREMENT" type="text" name="inputs[INCREMENT][icon]" class="form-control" placeholder="<?= l('global.icon_placeholder') ?>" />
            </div>
        </div>

        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
            <div class="form-group">
                <label for="<?= 'input_translation_' . $language_name . '_name_INCREMENT' ?>"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= $language_name ?></span>
                    </div>
                    <input type="text" id="<?= 'input_translation_' . $language_name . '_name_INCREMENT' ?>" name="<?= 'inputs[INCREMENT][translations][' . $language_name . '][name]' ?>" class="form-control" maxlength="128" required="required" />
                </div>
            </div>
        <?php endforeach ?>

        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
            <div class="form-group">
                <label for="<?= 'input_translation_' . $language_name . '_placeholder_INCREMENT' ?>"><i class="fas fa-fw fa-sm fa-highlighter text-muted mr-1"></i> <?= l('admin_templates.main.input.placeholder') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= $language_name ?></span>
                    </div>
                    <input type="text" id="<?= 'input_translation_' . $language_name . '_placeholder_INCREMENT' ?>" name="<?= 'inputs[INCREMENT][translations][' . $language_name . '][placeholder]' ?>" class="form-control" maxlength="128" />
                </div>
            </div>
        <?php endforeach ?>

        <?php foreach(\Altum\Language::$active_languages as $language_name => $language_code): ?>
            <div class="form-group">
                <label for="<?= 'input_translation_' . $language_name . '_help_INCREMENT' ?>"><i class="fas fa-fw fa-sm fa-info-circle text-muted mr-1"></i> <?= l('admin_templates.main.input.help') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><?= $language_name ?></span>
                    </div>
                    <input type="text" id="<?= 'input_translation_' . $language_name . '_help_INCREMENT' ?>" name="<?= 'inputs[INCREMENT][translations][' . $language_name . '][help]' ?>" class="form-control" maxlength="128" />
                </div>
            </div>
        <?php endforeach ?>

        <button type="button" data-remove="input" class="btn btn-block btn-outline-danger"><i class="fas fa-sm fa-fw fa-times mr-1"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
<script>
    'use strict';

    /* add new */
    let input_add = event => {
        let clone = document.querySelector(`#template_input`).content.cloneNode(true);

        let inputs_count = document.querySelectorAll(`#inputs [data-input]`).length;

        if(inputs_count > 20) {
            return;
        }

        clone.querySelectorAll(`[name*="[INCREMENT]"]`).forEach(element => {
            element.setAttribute('name', `${element.getAttribute('name').replace('INCREMENT', inputs_count)}`);
        });

        clone.querySelectorAll(`label[for*="INCREMENT"]`).forEach(element => {
            element.setAttribute('for', `${element.getAttribute('for').replace('INCREMENT', inputs_count)}`);
        });

        clone.querySelectorAll(`input[id*="INCREMENT"],select[id*="INCREMENT"]`).forEach(element => {
            element.setAttribute('id', `${element.getAttribute('id').replace('INCREMENT', inputs_count)}`);
        });

        document.querySelector(`#inputs`).appendChild(clone);

        input_remove_initiator();
    };

    document.querySelectorAll('[data-add]').forEach(element => {
        element.addEventListener('click', input_add);
    })

    /* remove input */
    let input_remove = event => {
        event.currentTarget.closest('[data-input]').remove();
    };

    let input_remove_initiator = () => {
        document.querySelectorAll('#inputs [data-remove]').forEach(element => {
            element.removeEventListener('click', input_remove);
            element.addEventListener('click', input_remove)
        })
    };

    input_remove_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
