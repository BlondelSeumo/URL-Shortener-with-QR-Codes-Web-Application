<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->sso->is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('admin_settings.sso.is_enabled') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.sso.is_enabled_help') ?></small>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="display_menu_items" name="display_menu_items" type="checkbox" class="custom-control-input" <?= settings()->sso->display_menu_items ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="display_menu_items"><?= l('admin_settings.sso.display_menu_items') ?></label>
        <small class="form-text text-muted"><?= l('admin_settings.sso.display_menu_items_help') ?></small>
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.sso.display_menu_items_help2'), '<a href="' . url('admin/pages') . '">', '</a>', '<code>' . SITE_URL . 'sso/switch?to={website_id}&redirect={page}' . '</code>') ?></small>
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.sso.display_menu_items_help3')) ?></small>
        <small class="form-text text-muted"><?= sprintf(l('admin_settings.sso.display_menu_items_help4')) ?></small>
    </div>

    <label><?= l('admin_settings.sso.websites') ?></label>
    <div id="websites">
        <?php foreach(settings()->sso->websites ?? [] as $website): ?>
        <div class="website p-3 bg-gray-50 rounded mb-4">
            <div class="form-group">
                <label for="<?= 'id[' . $website->id . ']' ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_settings.sso.id') ?></label>
                <input id="<?= 'id[' . $website->id . ']' ?>" type="text" name="id[<?= $website->id ?>]" class="form-control" value="<?= $website->id ?>" required="required" />
                <small class="form-text text-muted"><?= l('admin_settings.sso.id_help') ?></small>
            </div>

            <div class="form-group">
                <label for="<?= 'name[' . $website->id . ']' ?>"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input id="<?= 'name[' . $website->id . ']' ?>" type="text" name="name[<?= $website->id ?>]" class="form-control" value="<?= $website->name ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="<?= 'url[' . $website->id . ']' ?>"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('global.url') ?></label>
                <input id="<?= 'url[' . $website->id . ']' ?>" type="url" name="url[<?= $website->id ?>]" class="form-control" value="<?= $website->url ?>" placeholder="<?= SITE_URL ?>" required="required" />
                <small class="form-text text-muted"><?= l('admin_settings.sso.url_help') ?></small>
            </div>

            <div class="form-group">
                <label for="<?= 'api_key[' . $website->id . ']' ?>"><i class="fas fa-fw fa-sm fa-code text-muted mr-1"></i> <?= l('admin_settings.sso.api_key') ?></label>
                <input id="<?= 'api_key[' . $website->id . ']' ?>" type="text" name="api_key[<?= $website->id ?>]" class="form-control" value="<?= $website->api_key ?>" required="required" />
                <small class="form-text text-muted"><?= l('admin_settings.sso.api_key_help') ?></small>
            </div>

            <div class="form-group">
                <label for="<?= 'icon[' . $website->id . ']' ?>"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                <input id="<?= 'icon[' . $website->id . ']' ?>" type="text" name="icon[<?= $website->id ?>]" class="form-control" value="<?= $website->icon ?>" placeholder="<?= l('global.icon_placeholder') ?>" maxlength="32" required="required" />
                <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
            </div>

            <button type="button" data-remove="websites" class="btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times fa-sm mr-1"></i> <?= l('global.delete') ?></button>
        </div>
        <?php endforeach ?>
    </div>

    <div class="mb-4">
        <button data-add="websites" type="button" class="btn btn-block btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<template id="template_websites">
    <div class="website p-3 bg-gray-50 rounded mb-4">
        <div class="form-group">
            <label for="id"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_settings.sso.id') ?></label>
            <input id="id" type="text" name="id[]" class="form-control" value="" required="required" />
            <small class="form-text text-muted"><?= l('admin_settings.sso.id_help') ?></small>
        </div>

        <div class="form-group">
            <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
            <input id="name" type="text" name="name[]" class="form-control" required="required" />
        </div>

        <div class="form-group">
            <label for="url"><i class="fas fa-fw fa-sm fa-link text-muted mr-1"></i> <?= l('global.url') ?></label>
            <input id="url" type="url" name="url[]" class="form-control" placeholder="<?= SITE_URL ?>" required="required" />
            <small class="form-text text-muted"><?= l('admin_settings.sso.url_help') ?></small>
        </div>

        <div class="form-group">
            <label for="api_key"><i class="fas fa-fw fa-sm fa-code text-muted mr-1"></i> <?= l('admin_settings.sso.api_key') ?></label>
            <input id="api_key" type="text" name="api_key[]" class="form-control" required="required" />
            <small class="form-text text-muted"><?= l('admin_settings.sso.api_key_help') ?></small>
        </div>

        <div class="form-group">
            <label for="icon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
            <input id="icon" type="text" name="icon[]" class="form-control" placeholder="<?= l('global.icon_placeholder') ?>" maxlength="32" required="required" />
            <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
        </div>

        <button type="button" data-remove="request" class="btn btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<?php ob_start() ?>
<script>
    'use strict';

    /* add new request header */
    let add = event => {
        let type = event.currentTarget.getAttribute('data-add');
        let clone = document.querySelector(`#template_${type}`).content.cloneNode(true);

        document.querySelector(`#${type}`).appendChild(clone);

        remove_initiator();
        id_initiator();
    };

    document.querySelectorAll('[data-add]').forEach(element => {
        element.addEventListener('click', add);
    })

    /* remove request header */
    let remove = event => {
        event.currentTarget.closest('.website').remove();

        id_initiator();
    };

    let remove_initiator = () => {
        document.querySelectorAll('#websites [data-remove]').forEach(element => {
            element.removeEventListener('click', remove);
            element.addEventListener('click', remove)
        })
    };

    remove_initiator();

    let id = event => {
        let website = event.currentTarget.closest('.website');
        let id = event.currentTarget.value;

        website.querySelectorAll(`input`).forEach(element => {
            let cleaned_id = element.id.split('[')[0];
            element.name = `${cleaned_id}[${id}]`;
            element.id = `${cleaned_id}[${id}]`;
            element.closest('.form-group').querySelector('label').setAttribute('for', `${cleaned_id}[${id}]`);
        });
    }

    let id_initiator = () => {
        document.querySelectorAll('#websites [name^="id"]').forEach(element => {
            element.removeEventListener('change', id);
            element.addEventListener('change', id)
        })
    }

    id_initiator();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
