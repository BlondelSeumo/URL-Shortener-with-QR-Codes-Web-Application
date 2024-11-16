<?php defined('ALTUMCODE') || die() ?>

<div class="container">
<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('pages') ?>"><?= l('pages.index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <?php if($data->pages_category): ?>
                <li><a href="<?= url('pages/' . $data->pages_category->url) ?>"><?= $data->pages_category->title ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <?php endif ?>
            <li class="active" aria-current="page"><?= l('page.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="card">
        <div class="card-body">
            <h1 class="h4 mb-1"><?= $data->page->title ?></h1>

            <p class="small text-muted">
                <span data-toggle="tooltip" title="<?= sprintf(l('global.last_datetime_tooltip'), \Altum\Date::get($data->page->last_datetime, 2)) ?>"><?= sprintf(l('global.datetime_tooltip'), \Altum\Date::get($data->page->datetime, 2)) ?></span>

                <?php if($data->pages_category): ?>
                    &bull; <a href="<?= SITE_URL . ($data->pages_category->language ? \Altum\Language::$active_languages[$data->pages_category->language] . '/' : null) . 'pages/' . $data->pages_category->url ?>" class="text-muted"><?= $data->pages_category->title ?></a>
                <?php endif ?>

                <?php if(settings()->content->pages_views_is_enabled): ?>
                    <span> &bull; <?= sprintf(l('page.total_views'), nr($data->page->total_views)) ?></span>
                <?php endif ?>

                <?php $estimated_reading_time = string_estimate_reading_time($data->page->content) ?>
                <?php if($estimated_reading_time->minutes > 0 || $estimated_reading_time->seconds > 0): ?>
                    <span>•
                        <?= $estimated_reading_time->minutes ? sprintf(l('page.estimated_reading_time'), $estimated_reading_time->minutes . ' ' . l('global.date.minutes')) : null ?>
                        <?= $estimated_reading_time->minutes == 0 && $estimated_reading_time->seconds ? sprintf(l('page.estimated_reading_time'), $estimated_reading_time->seconds . ' ' . l('global.date.seconds')) : null ?>
                    </span>
                <?php endif ?>
            </p>

            <p><?= $data->page->description ?></p>

            <?= $data->page->content ?>
        </div>
    </div>

    <?php if(settings()->content->pages_share_is_enabled): ?>
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <?= include_view(THEME_PATH . 'views/partials/share_buttons.php', ['url' => url(\Altum\Router::$original_request), 'class' => 'btn btn-gray-100 mb-2 mb-md-0 mr-md-3']) ?>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
