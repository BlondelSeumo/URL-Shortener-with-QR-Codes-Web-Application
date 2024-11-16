<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/pages') ?>"><?= l('admin_pages.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_page_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-copy text-primary-900 mr-2"></i> <?= l('admin_page_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/pages/admin_page_dropdown_button.php', ['id' => $data->page->page_id, 'resource_name' => $data->page->title, 'url' => $data->page->url, 'language' => $data->page->language, 'type' => $data->page->type]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="type"><i class="fas fa-fw fa-fingerprint fa-sm text-muted mr-1"></i> <?= l('global.type') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->type == 'internal' ? 'active"' : null?>">
                            <input type="radio" name="type" value="internal" class="custom-control-input" <?= $data->page->type == 'internal' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-file fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.type_internal') ?>
                        </label>
                    </div>
                    <div class="col-6">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->type == 'external' ? 'active"' : null?>">
                            <input type="radio" name="type" value="external" class="custom-control-input" <?= $data->page->type == 'external' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-link fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.type_external') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" data-type="internal">
                <label for="url"><i class="fas fa-fw fa-sm fa-bolt text-muted mr-1"></i> <?= l('global.url') ?></label>
                <div class="input-group">
                    <div id="url_prepend" class="input-group-prepend">
                        <span class="input-group-text"><?= remove_url_protocol_from_url(SITE_URL) . 'page/' ?></span>
                    </div>

                    <input id="url" type="text" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" placeholder="<?= l('global.url_slug_placeholder') ?>" value="<?= $data->page->url ?>" onchange="update_this_value(this, get_slug)" onkeyup="update_this_value(this, get_slug)" maxlength="256" required="required" />
                    <?= \Altum\Alerts::output_field_error('url') ?>
                </div>
            </div>

            <div class="form-group" data-type="external">
                <label for="url"><i class="fas fa-fw fa-sm fa-bolt text-muted mr-1"></i> <?= l('global.url') ?></label>
                <input id="url" type="url" name="url" class="form-control <?= \Altum\Alerts::has_field_errors('url') ? 'is-invalid' : null ?>" value="<?= $data->page->url ?>" placeholder="<?= l('global.url_placeholder') ?>" maxlength="256" required="required" />
                <?= \Altum\Alerts::output_field_error('url') ?>
            </div>

            <div class="form-group custom-control custom-switch" data-type="external">
                <input id="open_in_new_tab" name="open_in_new_tab" type="checkbox" class="custom-control-input" <?= $data->page->open_in_new_tab ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="open_in_new_tab"><i class="fas fa-fw fa-sm fa-external-link-alt text-muted mr-1"></i> <?= l('admin_resources.main.open_in_new_tab') ?></label>
            </div>

            <div class="form-group">
                <label for="title"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('admin_resources.main.title') ?></label>
                <input id="title" type="text" name="title" class="form-control <?= \Altum\Alerts::has_field_errors('title') ? 'is-invalid' : null ?>" value="<?= $data->page->title ?>" maxlength="256" required="required" />
                <?= \Altum\Alerts::output_field_error('title') ?>
            </div>

            <div class="form-group" data-type="internal">
                <label for="description"><i class="fas fa-fw fa-sm fa-pen text-muted mr-1"></i> <?= l('global.description') ?></label>
                <input id="description" type="text" name="description" class="form-control" value="<?= $data->page->description ?>" maxlength="256" />
            </div>

            <div class="form-group">
                <label for="icon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                <input id="icon" type="text" name="icon" class="form-control" value="<?= $data->page->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" maxlength="32" />
                <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
            </div>

            <div class="form-group" data-type="internal">
                <label for="editor"><i class="fas fa-fw fa-sm fa-newspaper text-muted mr-1"></i> <?= l('admin_resources.main.editor') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-12 col-lg-4">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->editor == 'wysiwyg' ? 'active"' : null?>">
                            <input type="radio" name="editor" value="wysiwyg" class="custom-control-input" <?= $data->page->editor == 'wysiwyg' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-eye fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.editor_wysiwyg') ?>
                        </label>
                    </div>

                    <div class="col-12 col-lg-4">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->editor == 'blocks' ? 'active"' : null?>">
                            <input type="radio" name="editor" value="blocks" class="custom-control-input" <?= $data->page->editor == 'blocks' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-th-large fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.editor_blocks') ?>
                        </label>
                    </div>

                    <div class="col-12 col-lg-4">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->editor == 'raw' ? 'active"' : null?>">
                            <input type="radio" name="editor" value="raw" class="custom-control-input" <?= $data->page->editor == 'raw' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-pen-nib fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.editor_raw') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group" data-type="internal">
                <label for="content"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('admin_resources.main.content') ?></label>
                <div id="quill_container">
                    <div id="quill"></div>
                </div>
                <div class="bg-gray-100 rounded p-3" id="editorjs"></div>
                <textarea name="content" id="content" class="form-control d-none" style="height: 15rem;"><?= e($data->page->content) ?></textarea>
            </div>

            <div class="form-group">
                <label for="pages_category_id"><i class="fas fa-fw fa-sm fa-book text-muted mr-1"></i> <?= l('admin_resources.main.pages_category_id') ?></label>
                <select id="pages_category_id" name="pages_category_id" class="custom-select">
                    <?php foreach($data->pages_categories as $row): ?>
                        <option value="<?= $row->pages_category_id ?>" <?= $data->page->pages_category_id == $row->pages_category_id ? 'selected="selected"' : null ?>><?= $row->title ?></option>
                    <?php endforeach ?>
                    <option value="" <?= !$data->page->pages_category_id ? 'selected="selected"' : null ?>><?= l('global.none') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="position"><i class="fas fa-fw fa-sm fa-thumbtack text-muted mr-1"></i> <?= l('admin_resources.main.position') ?></label>
                <div class="row btn-group-toggle" data-toggle="buttons">
                    <div class="col-12 col-lg-4">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->position == 'bottom' ? 'active"' : null?>">
                            <input type="radio" name="position" value="bottom" class="custom-control-input" <?= $data->page->position == 'bottom' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-arrow-down fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.position_bottom') ?>
                        </label>
                    </div>

                    <div class="col-12 col-lg-4">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->position == 'top' ? 'active"' : null?>">
                            <input type="radio" name="position" value="top" class="custom-control-input" <?= $data->page->position == 'top' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-arrow-up fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.position_top') ?>
                        </label>
                    </div>

                    <div class="col-12 col-lg-4">
                        <label class="btn btn-light btn-block text-truncate <?= $data->page->position == 'hidden' ? 'active"' : null?>">
                            <input type="radio" name="position" value="hidden" class="custom-control-input" <?= $data->page->position == 'hidden' ? 'checked="checked"' : null?> required="required" />
                            <i class="fas fa-eye-slash fa-fw fa-sm mr-1"></i> <?= l('admin_resources.main.position_hidden') ?>
                        </label>
                    </div>
                </div>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_published" name="is_published" type="checkbox" class="custom-control-input" <?= $data->page->is_published ? 'checked="checked"' : null ?>>
                <label class="custom-control-label" for="is_published"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('admin_resources.main.is_published') ?></label>
            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('admin_resources.main.advanced') ?>
            </button>

            <div class="collapse" id="advanced_container">
                <div class="form-group" data-type="internal">
                    <label for="keywords"><i class="fas fa-fw fa-sm fa-file-word text-muted mr-1"></i> <?= l('admin_resources.main.keywords') ?></label>
                    <input id="keywords" type="text" name="keywords" class="form-control" value="<?= $data->page->keywords ?>" maxlength="256" />
                    <small class="form-text text-muted"><?= l('admin_resources.main.keywords_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="language"><i class="fas fa-fw fa-sm fa-language text-muted mr-1"></i> <?= l('global.language') ?></label>
                    <select id="language" name="language" class="custom-select">
                        <option value="" <?= !$data->page->language ? 'selected="selected"' : null ?>><?= l('global.all') ?></option>
                        <?php foreach(\Altum\Language::$languages as $language): ?>
                            <option value="<?= $language['name'] ?>" <?= $data->page->language == $language['name'] ? 'selected="selected"' : null ?>><?= $language['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                    <input id="order" type="number" name="order" class="form-control" value="<?= $data->page->order ?>" />
                    <small class="form-text text-muted"><?= l('admin_resources.main.order_help') ?></small>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>

<?php include_view(THEME_PATH . 'views/partials/fontawesome_iconpicker_js.php') ?>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/quill.snow.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">

<style>
    .codex-editor__redactor {
        padding-bottom: 0 !important;
    }
</style>
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/quill.min.js?v=' . PRODUCT_CODE ?>"></script>

<script src="https://cdn.jsdelivr.net/npm/@editorjs/header@latest"></script><!-- Header -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/simple-image@latest"></script><!-- Image -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/list@latest"></script><!-- List -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/link@latest"></script><!-- Link -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/code@latest"></script><!-- Code -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/raw@latest"></script><!-- Raw HTML -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/embed@latest"></script><!-- Embed -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/delimiter@latest"></script><!-- Delimiter -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/marker@latest"></script><!-- Text Marker -->

<!-- Load Editor.js's Core -->
<script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

<script>
    'use strict';

    const is_valid_json = (str) => {
        try {
            JSON.parse(str);
            return true;
        } catch {
            return false;
        }
    };

    /* EditorJS initiatilization */
    let editorjs = new EditorJS({
        readOnly: false,
        holder: 'editorjs',

        /* Data */
        data: is_valid_json(document.querySelector('#content').innerText) ? JSON.parse(document.querySelector('#content').innerText) : {},

        /* Tolls */
        tools: {
            header: {
                class: Header,
                inlineToolbar: true,
            },

            list: {
                class: List,
                inlineToolbar: true,
            },

            delimiter: Delimiter,

            marker: Marker,

            code: CodeTool,

            image: SimpleImage,

            embed: Embed,

            raw: RawTool,
        },
    });

    (async () => {
        try {
            await editorjs.isReady;
        } catch (reason) {
            console.log(`Editor.js initialization failed because of ${reason}`)
        }
    })();

    /* Initiate QuillJs */
    let quill = new Quill('#quill', {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ "font": [] }, { "size": ["small", false, "large", "huge"] }],
                ["bold", "italic", "underline", "strike"],
                [{ "color": [] }, { "background": [] }],
                [{ "script": "sub" }, { "script": "super" }],
                [{ "header": 1 }, { "header": 2 }, "blockquote", "code-block"],
                [{ "list": "ordered" }, { "list": "bullet" }, { "indent": "-1" }, { "indent": "+1" }],
                [{ "direction": "rtl" }, { "align": [] }],
                ["link", "image", "video", "formula"],
                ["clean"]
            ]
        },
    });
    quill.root.innerHTML = document.querySelector('#content').value;

    /* Handle form submission with the editor */
    document.querySelector('form').addEventListener('submit', async event => {
        let editor = document.querySelector('input[name="editor"]:checked')?.value ?? 'blocks';

        if(editor == 'wysiwyg') {
            document.querySelector('#content').value = quill.root.innerHTML;
        }

        if(editor == 'blocks') {
            let data = await editorjs.save();
            document.querySelector('#content').value = JSON.stringify(data);
        }
    });

    /* Editor change handlers */
    let current_editor = document.querySelector('input[name="editor"]:checked')?.value ?? 'blocks';

    let editor_handler = async (event = null) => {
        if(event && !confirm(<?= json_encode(l('admin_resources.main.editor_confirm')) ?>)) {
            document.querySelector('input[name="editor"]:checked').value = current_editor;
            return;
        }

        let editor = document.querySelector('input[name="editor"]:checked')?.value ?? 'blocks';

        switch(editor) {
            case 'wysiwyg':
                document.querySelector('#quill_container').classList.remove('d-none');
                quill.enable(true);
                document.querySelector('#editorjs').classList.add('d-none');
                document.querySelector('#content').classList.add('d-none');
                break;

            case 'blocks':
                document.querySelector('#quill_container').classList.add('d-none');
                quill.enable(false);
                document.querySelector('#editorjs').classList.remove('d-none');
                document.querySelector('#content').classList.add('d-none');
                break;

            case 'raw':
                document.querySelector('#quill_container').classList.add('d-none');
                quill.enable(false);
                document.querySelector('#editorjs').classList.add('d-none');
                document.querySelector('#content').classList.remove('d-none');
                break;
        }

        current_editor = document.querySelector('input[name="editor"]:checked')?.value ?? 'blocks';
    };

    document.querySelectorAll('input[name="editor"]').forEach(element => element.addEventListener('change', editor_handler));
    editor_handler();

    type_handler('input[name="type"]', 'data-type');
    document.querySelector('input[name="type"]') && document.querySelectorAll('input[name="type"]').forEach(element => element.addEventListener('change', () => { type_handler('input[name="type"]', 'data-type'); }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'page',
    'resource_id' => 'page_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/pages/delete/'
]), 'modals'); ?>
