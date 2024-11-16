<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->extra_content_is_enabled && l('tools.' . \Altum\Router::$method . '.extra_content')): ?>
    <div class="card mt-5">
        <div class="card-body">
            <?= l('tools.' . \Altum\Router::$method . '.extra_content') ?>
        </div>
    </div>
<?php endif ?>

<?php if(settings()->tools->share_is_enabled): ?>
<div class="mt-5">
    <h2 class="h4 mb-4"><?= l('tools.share') ?></h2>
    <div class="card">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => url(\Altum\Router::$original_request), 'class' => 'btn btn-gray-100 mb-2 mb-md-0 mr-md-3']) ?>
            </div>
        </div>
    </div>
</div>
<?php endif ?>
