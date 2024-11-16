<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <<?= $data->link->settings->heading_type ?> class="<?= $data->link->settings->heading_type ?> m-0 text-break" style="color: <?= $data->link->settings->text_color ?>; text-align: <?= ($data->link->settings->text_alignment ?? 'center') ?>;" data-text data-text-color data-text-alignment>
    <?php if($data->biolink->is_verified && $data->link->settings->verified_location == 'left'): ?>
        <small class="link-verified-small" data-toggle="tooltip" title="<?= sprintf(l('link.biolink.verified_help'), settings()->main->title) ?>">
            <span class="fa-stack">
                <i class="fa-solid fa-certificate fa-stack-2x"></i>
                <i class="fas fa-check fa-stack-1x fa-inverse"></i>
            </span>
        </small>
    <?php endif ?>
        <?= $data->link->settings->text ?>
    <?php if($data->biolink->is_verified && $data->link->settings->verified_location == 'right'): ?>
        <small class="link-verified-small" data-toggle="tooltip" title="<?= sprintf(l('link.biolink.verified_help'), settings()->main->title) ?>">
            <span class="fa-stack">
                <i class="fa-solid fa-certificate fa-stack-2x"></i>
                <i class="fas fa-check fa-stack-1x fa-inverse"></i>
            </span>
        </small>
    <?php endif ?>
    </<?= $data->link->settings->heading_type ?>>
</div>

