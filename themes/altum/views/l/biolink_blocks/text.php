<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <h2 class="h4 text-break" style="color: <?= $data->link->settings->title_text_color ?>"><?= $data->link->settings->title ?></h2>
    <p class="text-break" style="color: <?= $data->link->settings->description_color ?>"><?= nl2br($data->link->settings->description) ?></p>
</div>

