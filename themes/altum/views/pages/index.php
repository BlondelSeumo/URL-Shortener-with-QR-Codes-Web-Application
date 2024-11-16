<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('pages.index.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="d-flex align-items-center">
        <h1 class="h4 m-0"><?= l('pages.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('pages.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <?php if(count($data->pages_categories) || count($data->popular_pages)): ?>

        <?php if(count($data->pages_categories)): ?>
            <div class="mt-4">
                <div class="row">
                    <?php foreach($data->pages_categories as $row): ?>

                        <div class="col-12 col-md-4 mb-4">
                            <a href="<?= SITE_URL . ($row->language ? \Altum\Language::$active_languages[$row->language] . '/' : null) . 'pages/' . $row->url ?>" class="text-decoration-none">
                                <div class="card bg-gray-50 border-0 h-100 p-3">
                                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                                        <?php if(!empty($row->icon)): ?>
                                            <span class="round-circle-lg bg-primary-100 text-primary p-3 mb-4"><i class="<?= $row->icon ?> fa-2x"></i></span>
                                        <?php endif ?>

                                        <div class="h5"><?= $row->title ?></div>
                                    </div>
                                </div>
                            </a>
                        </div>

                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>

        <?php if(count($data->popular_pages)): ?>
            <div class="mt-4">
                <h2 class="h5 mb-4"><?= l('pages.index.popular_pages') ?></h2>

                <div class="row">
                    <?php foreach($data->popular_pages as $row): ?>

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
        <?php endif ?>

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



