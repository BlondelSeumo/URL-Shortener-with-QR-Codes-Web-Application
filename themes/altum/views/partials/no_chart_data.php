<?php defined('ALTUMCODE') || die() ?>

<?php if($data->has_wrapper ?? true): ?>
<div class="card">
    <div class="card-body">
        <?php endif ?>

        <div class="d-flex flex-column align-items-center justify-content-center py-4">
            <?= sprintf(file_get_contents(ROOT_PATH . ASSETS_URL_PATH . 'images/no_chart_data.svg'), 'var(--primary)', 'w-100 mb-4', 'height: 175px') ?>

            <h2 class="h5 text-muted text-center"><?= l('global.no_chart_data') ?></h2>

            <?php if($data->has_secondary_text ?? true): ?>
                <p class="text-muted text-center small m-0"><?= l('global.no_chart_data_help') ?></p>
            <?php endif ?>
        </div>

        <?php if($data->has_wrapper ?? true): ?>
    </div>
</div>
<?php endif ?>
