<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
    <nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('admin/biolinks-themes') ?>"><?= l('admin_biolinks_themes.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('admin_biolink_theme_update.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <div><h1 class="h3 mb-0 mr-1"><i class="fas fa-fw fa-xs fa-palette text-primary-900 mr-2"></i> <?= l('admin_biolink_theme_update.header') ?></h1></div>

    <?= include_view(THEME_PATH . 'views/admin/biolinks-themes/admin_biolink_theme_dropdown_button.php', ['id' => $data->biolink_theme->biolink_theme_id, 'resource_name' => $data->biolink_theme->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input type="text" id="name" name="name" class="form-control" value="<?= $data->biolink_theme->name ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= $data->biolink_theme->order ?>" class="form-control" />
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->biolink_theme->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <h2 class="h4"><?= l('admin_biolinks_themes.biolink') ?></h2>

            <div class="form-group">
                <label for="biolink_background_type"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('link.settings.background_type') ?></label>
                <select id="biolink_background_type" name="biolink_background_type" class="custom-select">
                    <?php foreach($data->biolink_backgrounds as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $data->biolink_theme->settings->biolink->background_type == $key ? 'selected="selected"' : null?>><?= l('link.settings.background_type_' . $key) ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div id="biolink_background_type_preset" class="row">
                <?php foreach($data->biolink_backgrounds['preset'] as $key => $value): ?>
                    <label for="biolink_background_type_preset_<?= $key ?>" class="m-0 col-3 p-3">
                        <input type="radio" name="biolink_background" value="<?= $key ?>" id="biolink_background_type_preset_<?= $key ?>" class="d-none" <?= $data->biolink_theme->settings->biolink->background_type == 'preset' && $data->biolink_theme->settings->biolink->background == $key ? 'checked="checked"' : null ?>/>
                        <div class="link-background-type-preset" style="<?= $value ?>"></div>
                    </label>
                <?php endforeach ?>
            </div>

            <div id="biolink_background_type_preset_abstract" class="row">
                <?php foreach($data->biolink_backgrounds['preset_abstract'] as $key => $value): ?>
                    <label for="biolink_background_type_preset_abstract_<?= $key ?>" class="m-0 col-3 p-3">
                        <input type="radio" name="biolink_background" value="<?= $key ?>" id="biolink_background_type_preset_abstract_<?= $key ?>" class="d-none" <?= $data->biolink_theme->settings->biolink->background_type == 'preset_abstract' && $data->biolink_theme->settings->biolink->background == $key ? 'checked="checked"' : null ?>/>
                        <div class="link-background-type-preset" style="<?= $value ?>"></div>
                    </label>
                <?php endforeach ?>
            </div>

            <div id="biolink_background_type_gradient">
                <div class="form-group">
                    <label for="biolink_background_type_gradient_color_one"><?= l('link.settings.background_type_gradient_color_one') ?></label>
                    <input type="hidden" id="biolink_background_type_gradient_color_one" name="biolink_background_color_one" class="form-control" value="<?= $data->biolink_theme->settings->biolink->background_color_one ?? '#000000' ?>" data-color-picker />
                </div>

                <div class="form-group">
                    <label for="biolink_background_type_gradient_color_two"><?= l('link.settings.background_type_gradient_color_two') ?></label>
                    <input type="hidden" id="biolink_background_type_gradient_color_two" name="biolink_background_color_two" class="form-control" value="<?= $data->biolink_theme->settings->biolink->background_color_two ?? '#000000' ?>" data-color-picker />
                </div>
            </div>

            <div id="biolink_background_type_color">
                <div class="form-group">
                    <label for="biolink_background_type_color"><?= l('link.settings.background_type_color') ?></label>
                    <input type="hidden" id="biolink_background_type_color" name="biolink_background" class="form-control" value="<?= is_string($data->biolink_theme->settings->biolink->background) ? $data->biolink_theme->settings->biolink->background : '#000000' ?>" data-color-picker />
                </div>
            </div>

            <div id="biolink_background_type_image">
                <div class="form-group">
                    <label for="biolink_background_type_image"><?= l('link.settings.background_type_image') ?></label>
                    <?php if(!empty($data->biolink_theme->settings->biolink->background) && is_string($data->biolink_theme->settings->biolink->background)): ?>
                        <?php if(!string_ends_with('.mp4', $data->biolink_theme->settings->biolink->background)): ?>
                            <div class="m-1">
                                <img src="<?= \Altum\Uploads::get_full_url('backgrounds') . $data->biolink_theme->settings->biolink->background ?>" class="img-fluid" style="max-height: 6rem;height: 6rem;" />
                            </div>
                        <?php endif ?>
                        <div class="custom-control custom-checkbox my-2">
                            <input id="biolink_background_image_remove" name="biolink_background_image_remove" type="checkbox" class="custom-control-input" onchange="this.checked ? document.querySelector('#biolink_background_image').classList.add('d-none') : document.querySelector('#biolink_background_image').classList.remove('d-none')">
                            <label class="custom-control-label" for="biolink_background_image_remove">
                                <span class="text-muted"><?= l('global.delete_file') ?></span>
                            </label>
                        </div>
                    <?php endif ?>
                    <input id="biolink_background_image" type="file" name="biolink_background_image" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('biolink_background') ?>" class="form-control-file altum-file-input" />
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('biolink_background')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), settings()->links->background_size_limit) ?></small>
                </div>
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="biolink_background_blur"><i class="fas fa-fw fa-low-vision fa-sm text-muted mr-1"></i> <?= l('link.settings.background_blur') ?></label>
                <input id="biolink_background_blur" type="range"  min="0" max="30" class="form-control-range" name="biolink_background_blur" value="<?= $data->biolink_theme->settings->biolink->background_blur ?>" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="%">
                <label for="biolink_background_brightness"><i class="fas fa-fw fa-sun fa-sm text-muted mr-1"></i> <?= l('link.settings.background_brightness') ?></label>
                <input id="biolink_background_brightness" type="range"  min="0" max="150" class="form-control-range" name="biolink_background_brightness" value="<?= $data->biolink_theme->settings->biolink->background_brightness ?>" />
            </div>

            <div class="form-group">
                <label for="biolink_font"><i class="fas fa-fw fa-pen-nib fa-sm text-muted mr-1"></i> <?= l('link.settings.font') ?></label>
                <select id="biolink_font" name="biolink_font" class="custom-select">
                    <?php foreach($data->biolink_fonts as $key => $value): ?>
                        <option value="<?= $key ?>" <?= $data->biolink_theme->settings->biolink->font == $key ? 'selected="selected"' : null?>><?= $value['name'] ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="biolink_font_size"><i class="fas fa-fw fa-font fa-sm text-muted mr-1"></i> <?= l('link.settings.font_size') ?></label>
                <div class="input-group">
                    <input id="biolink_font_size" type="number" min="14" max="22" name="biolink_font_size" class="form-control" value="<?= $data->biolink_theme->settings->biolink->font_size ?>" />
                    <div class="input-group-append">
                        <span class="input-group-text">px</span>
                    </div>
                </div>
            </div>

            <h2 class="h4"><?= l('admin_biolinks_themes.biolink_block') ?></h2>

            <div class="form-group">
                <label for="biolink_block_text_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.text_color') ?></label>
                <input id="biolink_block_text_color" type="hidden" name="biolink_block_text_color" class="form-control" value="<?= $data->biolink_theme->settings->biolink_block->text_color ?>" required="required" data-color-picker />
            </div>

            <div class="form-group">
                <label for="biolink_block_description_color"><i class="fas fa-fw fa-paint-brush fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.description_color') ?></label>
                <input id="biolink_block_description_color" type="hidden" name="biolink_block_description_color" class="form-control" value="<?= $data->biolink_theme->settings->biolink_block->description_color ?>" required="required" data-color-picker />
            </div>

            <div class="form-group">
                <label for="biolink_block_background_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.background_color') ?></label>
                <input id="biolink_block_background_color" type="hidden" name="biolink_block_background_color" class="form-control" value="<?= $data->biolink_theme->settings->biolink_block->background_color ?>" required="required" data-color-picker />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="biolink_block_border_width"><i class="fas fa-fw fa-border-style fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_width') ?></label>
                <input id="biolink_block_border_width" type="range" min="0" max="5" class="form-control-range" name="biolink_block_border_width" value="<?= $data->biolink_theme->settings->biolink_block->border_width ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="biolink_block_border_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_color') ?></label>
                <input id="biolink_block_border_color" type="hidden" name="biolink_block_border_color" class="form-control" value="<?= $data->biolink_theme->settings->biolink_block->border_color ?>" required="required" data-color-picker />
            </div>

            <div class="form-group">
                <label for="biolink_block_border_radius"><i class="fas fa-fw fa-border-none fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_radius') ?></label>
                <select id="biolink_block_border_radius" name="biolink_block_border_radius" class="custom-select">
                    <option value="straight" <?= $data->biolink_theme->settings->biolink_block->border_radius == 'straight' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_radius_straight') ?></option>
                    <option value="round" <?= $data->biolink_theme->settings->biolink_block->border_radius == 'round' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_radius_round') ?></option>
                    <option value="rounded" <?= $data->biolink_theme->settings->biolink_block->border_radius == 'rounded' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_radius_rounded') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="biolink_block_border_style"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_style') ?></label>
                <select id="biolink_block_border_style" name="biolink_block_border_style" class="custom-select">
                    <option value="solid" <?= $data->biolink_theme->settings->biolink_block->border_style == 'solid' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_style_solid') ?></option>
                    <option value="dashed" <?= $data->biolink_theme->settings->biolink_block->border_style == 'dashed' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_style_dashed') ?></option>
                    <option value="double" <?= $data->biolink_theme->settings->biolink_block->border_style == 'double' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_style_double') ?></option>
                    <option value="outset" <?= $data->biolink_theme->settings->biolink_block->border_style == 'outset' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_style_outset') ?></option>
                    <option value="inset" <?= $data->biolink_theme->settings->biolink_block->border_style == 'inset' ? 'selected="selected"' : null ?>><?= l('create_biolink_link_modal.input.border_style_inset') ?></option>
                </select>
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="biolink_block_border_shadow_offset_x"><i class="fas fa-fw fa-arrows-alt-h fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_offset_x') ?></label>
                <input id="biolink_block_border_shadow_offset_x" type="range" min="-20" max="20" class="form-control-range" name="biolink_block_border_shadow_offset_x" value="<?= $data->biolink_theme->settings->biolink_block->border_shadow_offset_x ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="biolink_block_border_shadow_offset_y"><i class="fas fa-fw fa-arrows-alt-v fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_offset_y') ?></label>
                <input id="biolink_block_border_shadow_offset_y" type="range" min="-20" max="20" class="form-control-range" name="biolink_block_border_shadow_offset_y" value="<?= $data->biolink_theme->settings->biolink_block->border_shadow_offset_y ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="biolink_block_border_shadow_blur"><i class="fas fa-fw fa-arrows-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_blur') ?></label>
                <input id="biolink_block_border_shadow_blur" type="range" min="0" max="20" class="form-control-range" name="biolink_block_border_shadow_blur" value="<?= $data->biolink_theme->settings->biolink_block->border_shadow_blur ?>" required="required" />
            </div>

            <div class="form-group" data-range-counter data-range-counter-suffix="px">
                <label for="biolink_block_border_shadow_spread"><i class="fas fa-fw fa-border-all fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_spread') ?></label>
                <input id="biolink_block_border_shadow_spread" type="range" min="0" max="10" class="form-control-range" name="biolink_block_border_shadow_spread" value="<?= $data->biolink_theme->settings->biolink_block->border_shadow_spread ?>" required="required" />
            </div>

            <div class="form-group">
                <label for="biolink_block_border_shadow_color"><i class="fas fa-fw fa-fill fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.border_shadow_color') ?></label>
                <input id="biolink_block_border_shadow_color" type="hidden" name="biolink_block_border_shadow_color" class="form-control" value="<?= $data->biolink_theme->settings->biolink_block->border_shadow_color ?>" required="required" data-color-picker />
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>

    </div>
</div>

<?php ob_start() ?>
<style>
    .link-background-type-preset {
        width: 100%;
        height: 5rem;
        border-radius: var(--border-radius);
        transition: .3s transform, opacity;
    }

    @media (min-width: 992px) {
        .link-background-type-preset {
            height: 8rem;
        }
    }

    .link-background-type-preset:hover {
        cursor: pointer;
        transform: scale(1.025);
    }

    input[type="radio"]:checked ~ .link-background-type-preset {
        transform: scale(1.05);
        opacity: .25;
    }
</style>

<script>
    /* Background Type Handler */
    let biolink_background_type_handler = () => {
        let type = document.querySelector('#biolink_background_type').value;

        /* Show only the active background type */
        $(`div[id="biolink_background_type_${type}"]`).show();
        $(`div[id="biolink_background_type_${type}"]`).find('[name^="biolink_background"]').removeAttr('disabled');

        /* Disable the other possible types so they dont get submitted */
        let biolink_background_type_containers = $(`div[id^="biolink_background_type_"]:not(div[id$="_${type}"])`);

        biolink_background_type_containers.hide();
        biolink_background_type_containers.find('[name^="biolink_background"]').attr('disabled', 'disabled');
    };

    biolink_background_type_handler();
    document.querySelector('#biolink_background_type').addEventListener('change', biolink_background_type_handler);
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>


<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_url.php', [
    'name' => 'biolink_theme',
    'resource_id' => 'biolink_theme_id',
    'has_dynamic_resource_name' => true,
    'path' => 'admin/biolinks-themes/delete/'
]), 'modals'); ?>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php', ['opacity' => true]) ?>
