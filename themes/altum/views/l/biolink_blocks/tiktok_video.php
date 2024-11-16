<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <div class="link-iframe-round">
        <blockquote class="link-round tiktok-embed" data-video-id="<?= $data->embed ?>">
            <section></section>
        </blockquote>
    </div>
</div>

<?php if(!\Altum\Event::exists_content_type_key('javascript', 'tiktok')): ?>
    <?php ob_start() ?>
    <script defer src="https://www.tiktok.com/embed.js"></script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'tiktok') ?>
<?php endif ?>
