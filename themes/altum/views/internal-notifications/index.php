<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-bell mr-1"></i> <?= l('internal_notifications.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('internal_notifications.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <?php if(count($data->internal_notifications)): ?>
        <div class="card mb-5">
            <div class="card-body py-2">
                <div>
                    <?php foreach($data->internal_notifications as $notification): ?>
                        <div class="bg-gray-100 p-3 my-3 rounded <?= $notification->is_read ? null : 'border border-info' ?> position-relative icon-zoom-animation">
                            <div class="d-flex align-items-center">
                                <div class="p-3 bg-gray-50 mr-3 rounded">
                                    <i class="<?= $notification->icon ?> fa-fw fa-lg text-primary-900"></i>
                                </div>

                                <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between flex-fill">
                                    <div class="d-flex flex-column">
                                        <div class="font-weight-bold mb-1">
                                            <?php if($notification->url): ?>
                                                <a href="<?= $notification->url ?>" class="stretched-link text-decoration-none text-body"><?= $notification->title ?></a>
                                            <?php else: ?>
                                                <?= $notification->title ?>
                                            <?php endif ?>
                                        </div>

                                        <small class="text-muted"><?= $notification->description ?></small>
                                    </div>

                                    <div>
                                        <small class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($notification->datetime, 1) ?>"><?= \Altum\Date::get_timeago($notification->datetime) ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'internal_notifications',
            'has_secondary_text' => false,
        ]); ?>
    <?php endif ?>

</div>


