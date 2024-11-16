<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/templates-categories') ?>"><?= l('admin_templates_categories.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_template_category_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-folder text-primary-900 mr-2"></i> <?= l('admin_template_category_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/templates-categories/admin_template_category_dropdown_button.php', ['id' => $data->template_category->template_category_id, 'resource_name' => $data->template_category->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">
        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <div class="input-group">
                    <input type="text" id="name" name="name" value="<?= $data->template_category->name ?>" class="form-control" maxlength="64" required="required" />
                    <div class="input-group-append">
                        <button class="btn btn-dark" type="button" data-toggle="collapse" data-target="#name_translate_container" aria-expanded="false" aria-controls="name_translate_container" data-tooltip title="<?= l('global.translate') ?>"><i class="fas fa-fw fa-sm fa-language"></i></button>
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
                                <input type="text" id="<?= 'translation_' . $language_name . '_name' ?>" name="<?= 'translations[' . $language_name . '][name]' ?>" value="<?= $data->template_category->settings->translations->{$language_name}->name ?>" class="form-control" maxlength="64" required="required" />
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="icon"><i class="fas fa-fw fa-sm fa-icons text-muted mr-1"></i> <?= l('global.icon') ?></label>
                        <input type="text" id="icon" name="icon" value="<?= $data->template_category->icon ?>" class="form-control" maxlength="64" placeholder="<?= l('global.icon_placeholder') ?>" required="required" />
                        <small class="form-text text-muted"><?= l('global.icon_help') ?></small>
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="emoji"><i class="fas fa-fw fa-sm fa-smile text-muted mr-1"></i> <?= l('admin_templates_categories.main.emoji') ?></label>
                        <input type="text" id="emoji" name="emoji" value="<?= $data->template_category->emoji ?>" class="form-control" maxlength="64" placeholder="<?= l('admin_templates_categories.main.emoji_placeholder') ?>" required="required" />
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="color"><i class="fas fa-fw fa-sm fa-paint-brush text-muted mr-1"></i> <?= l('admin_templates_categories.main.color') ?></label>
                        <input id="color" type="hidden" name="color" value="<?= $data->template_category->color ?>" class="form-control" data-color-picker />
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <div class="form-group">
                        <label for="background"><i class="fas fa-fw fa-sm fa-fill text-muted mr-1"></i> <?= l('admin_templates_categories.main.background') ?></label>
                        <input id="background" type="hidden" name="background" value="<?= $data->template_category->background ?>" class="form-control" data-color-picker />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="order"><i class="fas fa-fw fa-sm fa-sort text-muted mr-1"></i> <?= l('global.order') ?></label>
                <input id="order" type="number" name="order" value="<?= $data->template_category->order ?>" class="form-control" />
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="is_enabled" name="is_enabled" type="checkbox" class="custom-control-input" <?= $data->template_category->is_enabled ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="is_enabled"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
