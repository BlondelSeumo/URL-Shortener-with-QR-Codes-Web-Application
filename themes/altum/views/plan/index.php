<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('plan.breadcrumb') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <?php if(\Altum\Authentication::check() && $this->user->plan_is_expired && $this->user->plan_id != 'free'): ?>
        <div class="alert alert-info" role="alert">
            <?= l('global.info_message.user_plan_is_expired') ?>
        </div>
    <?php endif ?>

    <?php if($data->type == 'new'): ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= l('plan.header_new') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('plan.subheader_new') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

    <?php elseif($data->type == 'renew'): ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= l('plan.header_renew') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('plan.subheader_renew') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

    <?php elseif($data->type == 'upgrade'): ?>

        <div class="d-flex align-items-center mb-4">
            <h1 class="h4 m-0"><?= l('plan.header_upgrade') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('plan.subheader_upgrade') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>

    <?php endif ?>

    <div class="mt-4">
        <?= $this->views['plans'] ?>
    </div>

    <div class="mt-5">
        <h1 class="h4"><?= l('plan.why.header') ?></h1>

        <div class="mt-4 row">
            <div class="col-12 col-lg-4 mb-4 mb-lg-0 icon-zoom-animation">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="h5"><?= l('plan.why.one.header') ?></span>

                                <div class="ml-3">
                                    <i class="fas fa-fw fa-lg fa-headset text-primary"></i>
                                </div>
                            </div>

                            <span class="text-muted"><?= l('plan.why.one.subheader') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-4 mb-lg-0 icon-zoom-animation">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="h5"><?= l('plan.why.two.header') ?></span>

                                <div class="ml-3">
                                    <i class="fas fa-fw fa-lg fa-eye text-primary"></i>
                                </div>
                            </div>

                            <span class="text-muted"><?= l('plan.why.two.subheader') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4 mb-4 mb-lg-0 icon-zoom-animation">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-between">
                                <span class="h5"><?= l('plan.why.three.header') ?></span>

                                <div class="ml-3">
                                    <i class="fas fa-fw fa-lg fa-bolt text-primary"></i>
                                </div>
                            </div>

                            <span class="text-muted"><?= l('plan.why.three.subheader') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-5">
        <h1 class="h4"><?= l('plan.faq.header') ?></h1>

        <div class="accordion index-faq mt-4" id="faq_accordion">
            <?php foreach(['one', 'two', 'three', 'four'] as $key): ?>
                <div class="card">
                    <div class="card-body">
                        <div class="" id="<?= 'faq_accordion_' . $key ?>">
                            <h3 class="mb-0">
                                <button class="btn btn-lg font-weight-bold btn-block d-flex justify-content-between text-gray-800 px-0 icon-zoom-animation" type="button" data-toggle="collapse" data-target="<?= '#faq_accordion_answer_' . $key ?>" aria-expanded="true" aria-controls="<?= 'faq_accordion_answer_' . $key ?>">
                                    <span><?= l('plan.faq.' . $key . '.question') ?></span>

                                    <span data-icon>
                                        <i class="fas fa-fw fa-circle-chevron-down"></i>
                                    </span>
                                </button>
                            </h3>
                        </div>

                        <div id="<?= 'faq_accordion_answer_' . $key ?>" class="collapse text-muted mt-3" aria-labelledby="<?= 'faq_accordion_' . $key ?>" data-parent="#faq_accordion">
                            <?= l('plan.faq.' . $key . '.answer') ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        $('#faq_accordion').on('show.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.transform = 'rotate(180deg)';
            svg.style.color = 'var(--primary)';
        })

        $('#faq_accordion').on('hide.bs.collapse', event => {
            let svg = event.target.parentElement.querySelector('[data-icon] svg')
            svg.style.color = 'var(--primary-800)';
            svg.style.removeProperty('transform');
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
</div>
