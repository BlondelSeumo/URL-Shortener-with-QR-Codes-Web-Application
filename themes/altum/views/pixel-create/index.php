<?php defined('ALTUMCODE') || die() ?>

<?php \Altum\Alerts::clear(); ?>
<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li>
                <a href="<?= url('pixels') ?>"><?= l('pixels.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
            </li>
            <li class="active" aria-current="page"><?= l('pixel_create.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <h1 class="h4 text-truncate mb-4"><i class="fas fa-fw fa-xs fa-adjust mr-1"></i> <?= l('pixel_create.header') ?></h1>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('global.name') ?></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?= $data->values['name'] ?>" required="required" />
                </div>

                <div class="form-group">
                    <label for="type"><i class="fas fa-fw fa-adjust fa-sm text-muted mr-1"></i> <?= l('global.type') ?></label>
                    <div class="row btn-group-toggle" data-toggle="buttons">
                        <?php foreach(require APP_PATH . 'includes/pixels.php' as $pixel_key => $pixel): ?>
                        <div class="col-12 col-lg-4">
                            <label class="btn btn-light btn-block text-truncate <?= $data->values['type'] == $pixel_key ? 'active"' : null?>">
                                <input type="radio" name="type" value="<?= $pixel_key ?>" class="custom-control-input" <?= $data->values['type'] == $pixel_key ? 'checked="checked"' : null?> required="required" />
                                <i class="<?= $pixel['icon'] ?> fa-fw fa-sm mr-1" style="color: <?= $pixel['color'] ?>"></i> <?= $pixel['name'] ?>
                            </label>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="pixel"><i class="fas fa-fw fa-code fa-sm text-muted mr-1"></i> <?= l('pixels.input.pixel') ?></label>
                    <input type="text" id="pixel" name="pixel" class="form-control" value="<?= $data->values['pixel'] ?>" required="required" />
                    <small class="text-muted form-text"><?= l('pixels.input.pixel_help') ?></small>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.create') ?></button>
            </form>

        </div>
    </div>
</div>
