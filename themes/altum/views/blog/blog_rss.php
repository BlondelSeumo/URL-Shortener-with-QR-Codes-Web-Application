<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<rss version="2.0">
    <channel>

        <title><?= settings()->main->title ?></title>
        <link><?= SITE_URL ?></link>
        <description><?= l('index.meta_description') ?></description>
        <language><?=  \Altum\Language::$code  ?></language>

        <?php foreach($data->blog_posts as $blog_post): ?>
            <?php
            $blog_post->title = htmlspecialchars(html_entity_decode($blog_post->title, ENT_QUOTES, 'UTF-8'), ENT_XML1, 'UTF-8');
            $blog_post->description = htmlspecialchars(html_entity_decode($blog_post->description, ENT_QUOTES, 'UTF-8'), ENT_XML1, 'UTF-8');
            ?>

        <item>
            <title><?= $blog_post->title ?></title>
            <link><?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?></link>
            <description><?= $blog_post->description ?></description>
            <pubDate><?= \Altum\Date::get($blog_post->datetime, 'D, d M Y H:i:s O') ?></pubDate>
            <guid><?= $blog_post->blog_post_id ?></guid>
        </item>
        <?php endforeach ?>

    </channel>
</rss>
