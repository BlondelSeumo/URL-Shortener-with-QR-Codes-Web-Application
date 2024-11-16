<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('pages') ?>"><?= l('pages.index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('pages.pages_category.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <h1 class="h4"><?= $data->pages_category->title ?></h1>

    <?php if(count($data->pages)): ?>
        <div class="mt-4">
            <div class="row">
                <?php foreach($data->pages as $row): ?>

                    <div class="col-12 col-md-6 mb-4">
                        <a href="<?= $row->type == 'internal' ? SITE_URL . ($row->language ? \Altum\Language::$active_languages[$row->language] . '/' : null) . 'page/' . $row->url : $row->url ?>" target="<?= $row->type == 'internal' ? '_self' : '_blank' ?>" class="text-decoration-none">
                            <div class="card bg-gray-50 border-0 h-100 p-3">
                                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                    <div class="h5"><?= $row->title ?></div>

                                    <span class="text-muted text-center"><?= $row->description ?></span>
                                </div>
                            </div>
                        </a>
                    </div>

                <?php endforeach ?>
            </div>
        </div>

    <?php else: ?>
    <div class="mt-4">
            <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
                'filters_get' => $data->filters->get ?? [],
                'name' => 'pages',
                'has_secondary_text' => true,
            ]); ?>
        </div>
    <?php endif ?>
</div>
