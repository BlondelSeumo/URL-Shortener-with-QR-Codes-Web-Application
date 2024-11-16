<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="embed-responsive embed-responsive-16by9 link-iframe-round">
        <iframe
                class="embed-responsive-item"
                scrolling="no"
                frameborder="no"
                src="https://player.twitch.tv/?channel=<?= $data->embed ?>&autoplay=false&parent=<?= query_clean($_SERVER['HTTP_HOST']) ?>"
        ></iframe>
    </div>
</div>

