<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('affiliate.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="d-flex align-items-center">
        <h1 class="h4 m-0"><?= l('affiliate.header') ?></h1>

        <div class="ml-2">
            <span data-toggle="tooltip" title="<?= l('affiliate.subheader') ?>">
                <i class="fas fa-fw fa-info-circle text-muted"></i>
            </span>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="card border-0 h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="h5"><?= sprintf(l('affiliate.commission_percentage.header'), '<span class="text-primary">' . ($data->minimum_commission == $data->maximum_commission ? $data->minimum_commission : $data->minimum_commission . '% - ' . $data->maximum_commission) . '%</span>') ?></span>

                                <div class="ml-3">
                                    <i class="fas fa-fw fa-lg fa-money-check-dollar text-primary"></i>
                                </div>
                            </div>
                            <span class="text-muted"><?= l('affiliate.commission_percentage.subheader_' . settings()->affiliate->commission_type) ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-12 mb-4">
                    <div class="card border-0 h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="h5"><?= sprintf(l('affiliate.minimum_withdrawal_amount.header'), '<span class="text-primary">' . settings()->affiliate->minimum_withdrawal_amount . ' ' . settings()->payment->default_currency . '</span>') ?></span>

                                <div class="ml-3">
                                    <i class="fas fa-fw fa-lg fa-money-bill-transfer text-primary"></i>
                                </div>
                            </div>

                            <span class="text-muted"><?= l('affiliate.minimum_withdrawal_amount.subheader') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg">
            <div class="card border-0 h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <?= sprintf(file_get_contents(ROOT_PATH . ASSETS_URL_PATH . 'images/affiliate.svg'), 'var(--primary)', 'img-fluid col-9') ?>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h2 class="h4 mb-4"><?= l('affiliate.how.header') ?></h2>

        <div class="row justify-content-between">
            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 h-100 icon-zoom-animation">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-primary-100 text-primary mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fas fa-fw fa-user-plus fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">1. <?= l('affiliate.how.one') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.one_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 h-100 icon-zoom-animation">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-primary-100 text-primary mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fas fa-fw fa-link fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">2. <?= l('affiliate.how.two') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.two_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 h-100 icon-zoom-animation">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-primary-100 text-primary mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fas fa-fw fa-wallet fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">3. <?= l('affiliate.how.three') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.three_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-6 mb-4">
                <div class="card border-0 h-100 icon-zoom-animation">
                    <div class="card-body d-flex">
                        <div>
                            <div class="card border-0 bg-primary-100 text-primary mr-3">
                                <div class="p-3 d-flex align-items-center justify-content-between">
                                    <i class="fas fa-fw fa-money-bill fa-lg"></i>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column">
                            <span class="h5">4. <?= l('affiliate.how.four') ?></span>
                            <small class="text-muted"><?= l('affiliate.how.four_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(settings()->users->register_is_enabled): ?>
        <div class="mt-5">
            <div class="card">
                <div class="card-body py-5 py-lg-6">
                    <div class="row align-items-center justify-content-center">
                        <div class="col-12 col-lg-5">
                            <div class="text-center text-lg-left mb-4 mb-lg-0">
                                <h1 class="h2 text-gray-900"><?= l('affiliate.cta.header') ?></h1>
                                <p class="h6 text-gray-800"><?= l('affiliate.cta.subheader') ?></p>
                            </div>
                        </div>

                        <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                            <div class="text-center text-lg-right">
                                <?php if(\Altum\Authentication::check()): ?>
                                    <a href="<?= url('referrals') ?>" class="btn btn-outline-primary index-button">
                                        <?= l('referrals.menu') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                    </a>
                                <?php else: ?>
                                    <a href="<?= url('register') ?>" class="btn btn-outline-primary index-button">
                                        <?= l('index.cta.register') ?> <i class="fas fa-fw fa-arrow-right"></i>
                                    </a>
                                <?php endif ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>
</div>
