<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="d-flex flex-column align-items-center">
        <?php if($data->link->location_url): ?>
        <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-biolink-block-id="<?= $data->link->biolink_block_id ?>" target="<?= $data->link->settings->open_in_new_tab ? '_blank' : '_self' ?>">
        <?php endif ?>

            <img src="<?= $data->link->settings->image ? \Altum\Uploads::get_full_url('avatars') . $data->link->settings->image : null ?>" class="link-image <?= 'link-avatar-' . $data->link->settings->border_radius ?> <?= $data->link->location_url ? ($data->biolink->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->biolink->settings->hover_animation ?? 'smooth') : null : null ?>" style="<?= $data->link->settings->image ? null : 'display: none;' ?>width: <?= $data->link->settings->size ?>px; height: <?= $data->link->settings->size ?>px; border-width: <?= $data->link->settings->border_width ?>px; border-color: <?= $data->link->settings->border_color ?>; border-style: <?= $data->link->settings->border_style ?>; object-fit: <?= $data->link->settings->object_fit ?>; <?= 'box-shadow: ' . ($data->link->settings->border_shadow_offset_x ?? '0') . 'px ' . ($data->link->settings->border_shadow_offset_y ?? '0') . 'px ' . ($data->link->settings->border_shadow_blur ?? '0') . 'px ' . ($data->link->settings->border_shadow_spread ?? '0') . 'px ' . ($data->link->settings->border_shadow_color ?? '#00000010') ?>" alt="<?= $data->link->settings->image_alt ?>" loading="lazy" data-border-width data-border-avatar-radius data-border-style data-border-color data-border-shadow data-avatar />

        <?php if($data->link->location_url): ?>
        </a>
        <?php endif ?>
    </div>
</div>
