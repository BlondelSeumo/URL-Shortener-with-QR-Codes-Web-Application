<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/languages') ?>"><?= l('admin_languages.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_language_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-language text-primary-900 mr-2"></i> <?= l('admin_language_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/languages/admin_language_dropdown_button.php', ['id' => $data->language['name'], 'resource_name' => $data->language['name']]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<?php if($data->type): ?>
    <?php if($data->language['name'] == \Altum\Language::$main_name): ?>
        <div class="alert alert-warning" role="alert">
            <?= l('admin_languages.info_message.main') ?>
        </div>
    <?php endif ?>

    <?php
    $total_translated = 0;
    $total = 0;
    foreach(\Altum\Language::$languages[\Altum\Language::$main_name]['content'] as $key => $value) {
        if(!empty(\Altum\Language::$languages[$data->language['name']]['content'][$key])) $total_translated++;
        $total++;
    }
    ?>

    <div class="alert <?= $total > (int) ini_get('max_input_vars') ? 'alert-danger' : 'alert-info' ?>" role="alert">
        <?= sprintf(l('admin_languages.info_message.max_input_vars'), nr((int) ini_get('max_input_vars'))) ?>
    </div>
<?php endif ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <div class="row mb-4">
            <div class="col-4">
                <a href="<?= url('admin/language-update/' . replace_space_with_plus($data->language['name'])) ?>" class="btn btn-block <?= !$data->type ? 'btn-primary' : 'btn-outline-primary' ?>">
                    <i class="fas fa-fw fa-wrench fa-sm mr-1"></i> <?= l('admin_languages.main_settings') ?>
                </a>
            </div>

            <div class="col-4">
                <a href="<?= url('admin/language-update/' . replace_space_with_plus($data->language['name']) . '/app') ?>" class="btn btn-block <?= $data->type == 'app' ? 'btn-primary' : 'btn-outline-primary' ?>">
                    <i class="fas fa-fw fa-desktop fa-sm mr-1"></i> <?= l('admin_languages.translate_app') ?>
                </a>
            </div>

            <div class="col-4">
                <a href="<?= url('admin/language-update/' . replace_space_with_plus($data->language['name']) . '/admin') ?>" class="btn btn-block <?= $data->type == 'admin' ? 'btn-primary' : 'btn-outline-primary' ?>">
                    <i class="fas fa-fw fa-fingerprint fa-sm mr-1"></i> <?= l('admin_languages.translate_admin') ?>
                </a>
            </div>
        </div>

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="language_name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_languages.main.language_name') ?></label>
                <input id="language_name" type="text" name="language_name" class="form-control <?= \Altum\Alerts::has_field_errors('language_name') ? 'is-invalid' : null ?>" value="<?= $data->language['name'] ?>" <?= ($data->language['name'] == \Altum\Language::$main_name || $data->type) ? 'readonly="readonly"' : null ?> required="required" />
                <?= \Altum\Alerts::output_field_error('language_name') ?>
                <small class="form-text text-muted"><?= l('admin_languages.main.language_name_help') ?></small>
            </div>

            <div class="form-group">
                <label for="language_code"><i class="fas fa-fw fa-sm fa-language text-muted mr-1"></i> <?= l('admin_languages.main.language_code') ?></label>
                <input id="language_code" type="text" name="language_code" class="form-control <?= \Altum\Alerts::has_field_errors('language_code') ? 'is-invalid' : null ?>" value="<?= $data->language['code'] ?>" <?= ($data->language['name'] == \Altum\Language::$main_name || $data->type) ? 'readonly="readonly"' : null ?> required="required" />
                <?= \Altum\Alerts::output_field_error('language_code') ?>
                <small class="form-text text-muted"><?= l('admin_languages.main.language_code_help') ?></small>
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
                <select id="status" name="status" class="custom-select" <?= $data->type ? 'disabled="disabled"' : null ?>>
                    <option value="active" <?= (settings()->languages->{$data->language['name']}->status ?? $data->language['status']) == 'active' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                    <option value="disabled" <?= (settings()->languages->{$data->language['name']}->status ?? $data->language['status']) == 'disabled' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= settings()->languages->{$data->language['name']}->order ?? 1 ?>" class="form-control" />
            </div>

            <div class="form-group">
                <label for="language_flag"><i class="fas fa-fw fa-sm fa-flag text-muted mr-1"></i> <?= l('admin_languages.main.language_flag') ?></label>
                <input id="language_flag" type="text" name="language_flag" value="<?= settings()->languages->{$data->language['name']}->language_flag ?? '' ?>" class="form-control" placeholder="<?= l('admin_languages.main.language_flag_placeholder') ?>" />
            </div>

            <?php if($data->type): ?>
                <div class="d-flex align-items-center my-5">
                    <?php if(\Altum\Language::$main_name != $data->language['name']): ?>
                        <div class="mr-3">
                            <button type="button" class="btn btn-dark" data-translate-all data-toggle="tooltip" title="<?= l('admin_languages.main.auto_translate_all_help') ?>" data-is-ajax><?= l('admin_languages.main.auto_translate') ?></button>
                        </div>
                    <?php endif ?>

                    <div class="flex-fill">
                        <hr class="border-gray-200">
                    </div>

                    <div class="ml-3">
                        <select id="display" name="display" class="custom-select" aria-label="<?= l('admin_languages.main.display') ?>" data-is-not-custom-select>
                            <option value="all"><?= l('global.all') ?></option>
                            <option value="translated"><?= l('admin_languages.main.display_translated') ?></option>
                            <option value="not_translated"><?= l('admin_languages.main.display_not_translated') ?></option>
                        </select>
                    </div>
                </div>

                <div class="alert alert-info" role="alert">
                    <?= sprintf(l('admin_languages.info_message.total'), nr($total_translated), nr($total), nr($total - $total_translated)) ?>
                </div>

                <div id="translations">
                    <?php $index = 1; ?>
                    <?php foreach(\Altum\Language::$languages[\Altum\Language::$main_name]['content'] as $key => $value): ?>
                        <?php if(string_starts_with('admin_', $key) && $data->type != 'admin') continue ?>
                        <?php if(!string_starts_with('admin_', $key) && $data->type != 'app') continue ?>

                        <?php $form_key = str_replace('.', 'ALTUM', $key) ?>

                        <?php if($key == 'direction'): ?>
                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="<?= \Altum\Language::$main_name . '_' . $form_key ?>"><?= $key ?></label>
                                        <input id="<?= \Altum\Language::$main_name . '_' . $form_key ?>" value="<?= $value ?>" class="form-control" readonly="readonly" />
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="<?= $form_key ?>">&nbsp;</label>
                                        <select id="<?= $form_key ?>" name="<?= $form_key ?>" class="custom-select <?= \Altum\Alerts::has_field_errors($form_key) ? 'is-invalid' : null ?> <?= !isset(\Altum\Language::get($data->language['name'])[$key]) || (isset(\Altum\Language::get($data->language['name'])[$key]) && empty(\Altum\Language::get($data->language['name'])[$key])) ? 'border-info' : null ?>" <?= $index++ >= (int) ini_get('max_input_vars') ? 'readonly="readonly"' : null ?>>
                                            <option value="ltr" <?= (\Altum\Language::get($data->language['name'])[$key] ?? null) == 'ltr' ? 'selected="selected"' : null ?>>ltr</option>
                                            <option value="rtl" <?= (\Altum\Language::get($data->language['name'])[$key] ?? null) == 'rtl' ? 'selected="selected"' : null ?>>rtl</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row" data-display-container>
                                <div class="col-6">
                                    <div class="form-group">
                                        <div class="d-flex align-items-center justify-content-between flex-wrap">
                                            <label for="<?= \Altum\Language::$main_name . '_' . $form_key ?>"><?= $key ?></label>

                                            <?php if($value && \Altum\Language::$main_name != $data->language['name']): ?>
                                                <button type="button" class="btn btn-sm btn-light" data-translate="<?= '#' . \Altum\Language::$main_name . '_' . $form_key ?>" data-translate-target="<?= '#' . $form_key ?>" data-toggle="tooltip" title="<?= l('admin_languages.main.auto_translate_help') ?>" data-is-ajax><?= l('admin_languages.main.auto_translate') ?></button>
                                            <?php endif ?>
                                        </div>
                                        <textarea id="<?= \Altum\Language::$main_name . '_' . $form_key ?>" class="form-control" readonly="readonly"><?= $value ?></textarea>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="<?= $form_key ?>">&nbsp;</label>
                                        <textarea data-display-input id="<?= $form_key ?>" name="<?= $form_key ?>" class="form-control <?= \Altum\Alerts::has_field_errors($form_key) ? 'is-invalid' : null ?> <?= !isset(\Altum\Language::get($data->language['name'])[$key]) || (isset(\Altum\Language::get($data->language['name'])[$key]) && empty(\Altum\Language::get($data->language['name'])[$key])) ? 'border-info' : null ?>" <?= $index++ >= (int) ini_get('max_input_vars') ? 'readonly="readonly" data-toggle="tooltip" data-html="true" title="' . (str_replace('"', '\'', sprintf(l('admin_languages.info_message.max_input_vars'), (int) ini_get('max_input_vars')))) . '"' : null ?>><?= \Altum\Language::get($data->language['name'])[$key] ?? null ?></textarea>
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>

    </div>
</div>

<?php if($data->type): ?>
<div style="position: fixed; right: 1rem; bottom: 1rem; z-index: 1;">
    <div class="mb-2">
        <button type="button" class="btn btn-light" onclick="document.querySelector('.admin-content').scrollTo({ top: 0, behavior: 'smooth' });" data-toggle="tooltip" data-placement="left" title="<?= l('global.scroll_top') ?>" data-tooltip-hide-on-click>
            <i class="fas fa-fw fa-arrow-up"></i>
        </button>
    </div>
    <div>
        <button type="button" class="btn btn-light" onclick="document.querySelector('footer').scrollIntoView({ behavior: 'smooth', block: 'center' });" data-toggle="tooltip" data-placement="left" title="<?= l('global.scroll_bottom') ?>" data-tooltip-hide-on-click>
            <i class="fas fa-fw fa-arrow-down"></i>
        </button>
    </div>
</div>
<?php endif ?>

<?php ob_start() ?>
<script>
    let display_handler = () => {
        let display_element = document.querySelector('#display');
        let display = display_element.value;

        switch(display) {
            case 'all':

                document.querySelectorAll('#translations [data-display-container]').forEach(element => {
                    element.classList.remove('d-none');
                });

                break;

            case 'translated':

                document.querySelectorAll('#translations [data-display-input]').forEach(element => {
                    if(element.value.trim() != '') {
                        element.closest('[data-display-container]').classList.remove('d-none');
                    } else {
                        element.closest('[data-display-container]').classList.add('d-none');
                    }
                });

                break;

            case 'not_translated':

                document.querySelectorAll('#translations [data-display-input]').forEach(element => {
                    if(element.value.trim() != '') {
                        element.closest('[data-display-container]').classList.add('d-none');
                    } else {
                        element.closest('[data-display-container]').classList.remove('d-none');
                    }
                });

                break;
        }
    }

    document.querySelector('#display') && document.querySelector('#display').addEventListener('change', display_handler);

    /* Typer function */
    let type_in_field = (field, text) => {
        /* clear the field first */
        field.value = '';

        const delay = 35;

        let i = 0;
        const type_character = () => {
            if(i < text.length) {
                field.value += text.charAt(i);
                i++;
                setTimeout(type_character, delay);

                /* Emit change event after */
                field.dispatchEvent(new Event('change'));
            }
        };

        type_character();
    }

    let translate = async button => {
        const openai_api_key = <?= json_encode(settings()->main->openai_api_key) ?>;
        const openai_api_endpoint = 'https://api.openai.com/v1/chat/completions';

        if(!openai_api_key) {
            alert('<?= l('admin_languages.main.auto_translate_info') ?>');
            return false;
        }

        $(button).tooltip('hide');

        pause_submit_button(button);

        let string_to_translate = document.querySelector(button.getAttribute('data-translate')).value;
        let target_field = document.querySelector(button.getAttribute('data-translate-target'));
        let language_to_translate_to = document.querySelector('#language_name').value;

        /* Send API request */
        try {
            const response = await fetch(openai_api_endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${openai_api_key}`
                },
                body: JSON.stringify({
                    'model': '<?= settings()->main->openai_model ?? 'gpt-4o' ?>',
                    'messages': [
                        {
                            'role': 'system',
                            'content': `You are a professional translator that will translate strings from English to ${language_to_translate_to}. You should exclude PHP sprintf type of variables and leave them as they are. You will only return the translated strings back with no additional text. You will also only use ending punctuation marks when used in the original string.`
                        },
                        {
                            'role': 'user',
                            'content' : `${string_to_translate}`
                        }
                    ],
                    'user': 'Admin panel - auto translation',
                    'temperature': 0
                })
            });

            const data = await response.json();

            if(data.error) {
                alert(`${data.error.code} - ${data.error.message}`);
                enable_submit_button(button);
                return false;
            } else {
                let translated_string = data.choices[0].message.content;
                button.value = '';
                type_in_field(target_field, translated_string);
            }

        } catch (error) {
            alert(error);
            enable_submit_button(button);
            return false;
        }

        enable_submit_button(button);
        return true;
    }

    /* Translate all */
    let should_continue_translating = null;

    document.querySelector('[data-translate-all]') && document.querySelector('[data-translate-all]').addEventListener('click', async event => {
        should_continue_translating = true;
        let elements = document.querySelectorAll('[data-translate]');

        for(let i = 0; i < elements.length; i++) {
            if(should_continue_translating && !elements[i].closest('[data-display-container]').classList.contains('d-none')) {
                elements[i].scrollIntoView({ behavior: 'smooth', block: 'center' });

                should_continue_translating = await translate(elements[i]);

                await new Promise(r => setTimeout(r, 1000));
            }
        }
    });

    /* Escape key stop translating */
    document.addEventListener('keydown', event => {
        if(event.keyCode == 27) {
            should_continue_translating = false;
        }
    });

    /* AI single field translation handler */
    document.querySelectorAll('[data-translate]').forEach(element => {
        element.addEventListener('click', async event => {
            await translate(event.currentTarget);
        })
    })

    /* Error checker for variable presence in the fields */
    let language_main_name = <?= json_encode(\Altum\Language::$main_name) ?>;
    let language_missing_variables = <?= json_encode(l('admin_languages.error_message.missing_variables')) ?>;

    document.querySelectorAll('[data-display-input]').forEach(element => {
        ['change', 'paste', 'keyup'].forEach(event_type => {
            element.addEventListener(event_type, event => {
                let translated_string = event.currentTarget.value.trim();
                let translated_string_id = event.currentTarget.id;
                let original_translation_string = document.querySelector(`#${language_main_name}_${translated_string_id}`).value.trim();

                if(translated_string != '') {
                    let original_translation_string_variables = count_matched_translation_variables(original_translation_string);
                    let translated_string_variables = count_matched_translation_variables(translated_string);

                    if(original_translation_string_variables != translated_string_variables) {
                        /* Display a friendly error */
                        event.currentTarget.classList.add('is-invalid');
                        event.currentTarget.nextElementSibling.innerHTML = language_missing_variables.replace('%1$s', original_translation_string_variables).replace('%2$s', translated_string_variables);
                    } else {
                        /* Remove error */
                        event.currentTarget.classList.remove('is-invalid');
                        event.currentTarget.nextElementSibling.innerHTML = '';
                    }
                } else {
                    /* Remove error */
                    event.currentTarget.classList.remove('is-invalid');
                    event.currentTarget.nextElementSibling.innerHTML = '';
                }
            })
        });
    });

    let count_matched_translation_variables = string => {
        const re = /(%\d+\$s|%s)+/g
        return ((string || '').match(re) || []).length
    }
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'language',
    'resource_id' => 'language_name',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/languages/delete/'
]), 'modals'); ?>
