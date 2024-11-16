<?php defined('ALTUMCODE') || die() ?>

<div>
    <div class="form-group custom-control custom-switch">
        <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-screwdriver-wrench text-muted mr-1"></i> <?= l('admin_settings.tools.is_enabled') ?></label>
    </div>

    <div class="form-group">
        <label for="access"><?= l('admin_settings.tools.access') ?></label>
        <select id="access" name="access" class="custom-select">
            <option value="everyone" <?= settings()->tools->access == 'everyone' ? 'selected="selected"' : null ?>><?= l('admin_settings.tools.access_everyone') ?></option>
            <option value="users" <?= settings()->tools->access == 'users' ? 'selected="selected"' : null ?>><?= l('admin_settings.tools.access_users') ?></option>
        </select>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="extra_content_is_enabled" name="extra_content_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->extra_content_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="extra_content_is_enabled"><i class="fas fa-fw fa-sm fa-paragraph text-muted mr-1"></i> <?= l('admin_settings.tools.extra_content_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="share_is_enabled" name="share_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->share_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="share_is_enabled"><i class="fas fa-fw fa-sm fa-share-alt text-muted mr-1"></i> <?= l('admin_settings.tools.share_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="popular_widget_is_enabled" name="popular_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->popular_widget_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="popular_widget_is_enabled"><i class="fas fa-fw fa-sm fa-fire text-muted mr-1"></i> <?= l('admin_settings.tools.popular_widget_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="similar_widget_is_enabled" name="similar_widget_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->similar_widget_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="similar_widget_is_enabled"><i class="fas fa-fw fa-sm fa-clone text-muted mr-1"></i> <?= l('admin_settings.tools.similar_widget_is_enabled') ?></label>
    </div>

    <div class="form-group custom-control custom-switch">
        <input id="views_is_enabled" name="views_is_enabled" type="checkbox" class="custom-control-input" <?= settings()->tools->views_is_enabled ? 'checked="checked"' : null?>>
        <label class="custom-control-label" for="views_is_enabled"><i class="fas fa-fw fa-sm fa-eye text-muted mr-1"></i> <?= l('admin_settings.tools.views_is_enabled') ?></label>
    </div>

    <div class="form-group mt-5">
        <?php $tools = require APP_PATH . 'includes/tools/tools.php'; ?>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h5"><?= l('admin_settings.tools.available_tools') . ' (' . count($tools) . ')' ?></h3>

            <div>
                <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_tools[]']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[name='available_tools[]']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
            </div>
        </div>

        <?php foreach(require APP_PATH . 'includes/tools/categories.php' as $tool_category => $tool_category_properties): ?>
            <?php $tools_category = require APP_PATH . 'includes/tools/' . $tool_category . '.php'; ?>

            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="h6"><?= l('tools.' . $tool_category) . ' (' . count($tools_category) . ')' ?></h4>

                    <div>
                        <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.select_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[data-tool-category='<?= $tool_category ?>']`).forEach(element => element.checked ? null : element.checked = true)"><i class="fas fa-fw fa-check-square"></i></button>
                        <button type="button" class="btn btn-sm btn-light" data-toggle="tooltip" title="<?= l('global.deselect_all') ?>" data-tooltip-hide-on-click onclick="document.querySelectorAll(`[data-tool-category='<?= $tool_category ?>']`).forEach(element => element.checked ? element.checked = false : null)"><i class="fas fa-fw fa-minus-square"></i></button>
                    </div>
                </div>

                <div class="row">
                    <?php foreach($tools_category as $key => $value): ?>
                        <?php
                        /* Determine the tool name / description */
                        if(isset($value['category']) && $value['category'] == 'data_converter') {
                            /* Process the tool */
                            $exploded = explode('_to_', $key);
                            $from = $exploded[0];
                            $to = $exploded[1];

                            $name = sprintf(l('tools.data_converter.name'), l('tools.' . $from), l('tools.' . $to));
                        } else {
                            $name = l('tools.' . $key . '.name');
                        }
                        ?>

                        <div class="col-12 col-lg-6">
                            <div class="custom-control custom-checkbox my-2">
                                <input id="<?= 'tool_' . $key ?>" name="available_tools[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= settings()->tools->available_tools->{$key} ? 'checked="checked"' : null ?> data-tool-category="<?= $tool_category ?>">
                                <label class="custom-control-label d-flex align-items-center" for="<?= 'tool_' . $key ?>">
                                    <?= $name ?>
                                </label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
