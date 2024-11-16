<?php defined('ALTUMCODE') || die() ?>

<input type="hidden" name="link_base" value="<?= $this->link->domain ? $this->link->domain->url : SITE_URL ?>" />

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('links') ?>"><?= l('links.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page">
                <?= l('link.breadcrumb.' . $data->link->type) . ' ' . l('link.' . $data->method . '.breadcrumb') ?>
            </li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row">
        <div class="col text-truncate">
            <h1 id="link_url" class="h3 text-truncate"><?= sprintf(l('link.header.header'), $data->link->url) ?></h1>
        </div>

        <div class="col-auto">
            <div class="d-flex align-items-center">
                <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= l('links.is_enabled_tooltip') ?>">
                    <input
                            type="checkbox"
                            class="custom-control-input"
                            id="link_is_enabled_<?= $data->link->link_id ?>"
                            data-row-id="<?= $data->link->link_id ?>"
                            onchange="ajax_call_helper(event, 'link-ajax', 'is_enabled_toggle')"
                        <?= $data->link->is_enabled ? 'checked="checked"' : null ?>
                    >
                    <label class="custom-control-label" for="link_is_enabled_<?= $data->link->link_id ?>"></label>
                </div>

                <button
                        id="link_full_url_copy"
                        type="button"
                        class="btn btn-link text-secondary"
                        data-toggle="tooltip"
                        title="<?= l('global.clipboard_copy') ?>"
                        aria-label="<?= l('global.clipboard_copy') ?>"
                        data-copy="<?= l('global.clipboard_copy') ?>"
                        data-copied="<?= l('global.clipboard_copied') ?>"
                        data-clipboard-text="<?= $data->link->full_url ?>"
                >
                    <i class="fas fa-fw fa-sm fa-copy"></i>
                </button>

                <?php if($data->method != 'statistics'): ?>
                    <a href="<?= url('link/' . $data->link->link_id . '/statistics') ?>" class="btn btn-link text-secondary" data-toggle="tooltip" title="<?= l('link.statistics.link') ?>"><i class="fas fa-fw fa-sm fa-chart-bar"></i></a>
                <?php endif ?>

                <?php if($data->method != 'settings'): ?>
                    <a href="<?= url('link/' . $data->link->link_id . '/settings') ?>" class="btn btn-link text-secondary" data-toggle="tooltip" title="<?= l('global.edit') ?>"><i class="fas fa-fw fa-pencil-alt"></i></a>
                <?php endif ?>

                <div class="dropdown">
                    <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                        <i class="fas fa-fw fa-ellipsis-v"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right">
                        <a href="<?= url('link/' . $data->link->link_id) ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-pencil-alt mr-2"></i> <?= l('global.edit') ?></a>
                        <a href="<?= url('link/' . $data->link->link_id . '/statistics') ?>" class="dropdown-item"><i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('link.statistics.link') ?></a>
                        <?php if(settings()->codes->qr_codes_is_enabled): ?>
                        <a href="<?= url('qr-code-create?name=' . $data->link->url . '&project_id=' . $data->link->project_id . '&type=url&url=' . $data->link->full_url . '&link_id=' . $data->link->link_id . '&url_dynamic=1') ?>" class="dropdown-item" rel="noreferrer noopener"><i class="fas fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('qr_codes.create') ?></a>
                        <?php endif ?>
                        <a href="#" data-toggle="modal" data-target="#link_duplicate_modal" class="dropdown-item" data-link-id="<?= $data->link->link_id ?>"><i class="fas fa-fw fa-sm fa-clone mr-2"></i> <?= l('global.duplicate') ?></a>
                        <a href="#" data-toggle="modal" data-target="#link_delete_modal" class="dropdown-item" data-link-id="<?= $data->link->link_id ?>"><i class="fas fa-fw fa-sm fa-trash-alt mr-2"></i> <?= l('global.delete') ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-baseline mb-4">
        <span class="mr-1" data-toggle="tooltip" title="<?= l('link.' . $data->link->type . '.name') ?>">
            <i class="fas fa-fw fa-circle fa-sm" style="color: <?= $data->links_types[$data->link->type]['color'] ?>"></i>
        </span>

        <div class="text-muted text-truncate">
            <?= sprintf(l('link.header.subheader'), '<a id="link_full_url" href="' . $data->link->full_url . '" target="_blank" rel="noreferrer noopener">' . remove_url_protocol_from_url($data->link->full_url) . '</a>') ?>
        </div>
    </div>

    <?= $this->views['method'] ?>
</div>


<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<link href="<?= ASSETS_FULL_URL . 'css/libraries/fontawesome-iconpicker.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php', ['exclude_js' => true]) ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'link_duplicate_modal', 'resource_id' => 'link_id', 'path' => 'link-ajax/duplicate']), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/duplicate_modal.php', ['modal_id' => 'biolink_block_duplicate_modal', 'resource_id' => 'biolink_block_id', 'path' => 'biolink-block-ajax/duplicate']), 'modals'); ?>
