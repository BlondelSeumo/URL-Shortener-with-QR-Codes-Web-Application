<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="embed-responsive embed-responsive-16by9 link-iframe-round">
        <iframe class="embed-responsive-item" scrolling="no" frameborder="no" src="https://www.youtube-nocookie.com/embed/<?= $data->embed ?>?controls=<?= (int) $data->link->settings->video_controls ?>&autoplay=<?= (int) $data->link->settings->video_autoplay ?>&loop=<?= (int) $data->link->settings->video_loop ?>&mute=<?= (int) $data->link->settings->video_muted ?>&playlist=<?= $data->embed ?>" allow="<?= $data->link->settings->video_controls ? 'controls;' : null ?> <?= $data->link->settings->video_autoplay ? 'autoplay;' : null ?>accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>
