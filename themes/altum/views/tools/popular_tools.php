<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->tools->popular_widget_is_enabled): ?>
    <div class="mt-5">
        <h2 class="h4 mb-4"><?= l('tools.popular_tools') ?></h2>

        <div class="row" id="popular_tools">
            <?php foreach($data->tools_usage as $key => $value): ?>
                <?php if(settings()->tools->available_tools->{$key}): ?>
                    <div class="col-12 col-lg-6 p-3 position-relative" data-tool-id="<?= $key ?>" data-tool-name="<?= l('tools.' . $key . '.name') ?>">
                        <div class="card d-flex flex-row h-100 overflow-hidden">
                            <div class="px-3 d-flex flex-column justify-content-center">
                                <div class="bg-primary-100 d-flex align-items-center justify-content-center rounded tool-icon">
                                    <i class="<?= $data->tools[$key]['icon'] ?> fa-fw text-primary-600"></i>
                                </div>
                            </div>

                            <div class="card-body text-truncate">
                                <a href="<?= url('tools/' . str_replace('_', '-', $key)) ?>" class="stretched-link text-decoration-none">
                                    <strong><?= l('tools.' . $key . '.name') ?></strong>
                                </a>
                                <p class="text-truncate small m-0"><?= l('tools.' . $key . '.description') ?></p>
                            </div>

                            <?php if(settings()->tools->views_is_enabled): ?>
                                <div class="p-3">
                                    <div class="badge badge-gray-100" data-toggle="tooltip" title="<?= l('tools.total_views') ?>">
                                        <i class="fas fa-fw fa-sm fa-eye mr-1"></i> <?= nr($data->tools_usage[$key]->total_views ?? 0) ?>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>
                <?php endif ?>
            <?php endforeach ?>
        </div>
    </div>
<?php endif ?>
