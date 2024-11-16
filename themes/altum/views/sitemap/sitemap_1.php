<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc><?= SITE_URL ?></loc>
    </url>
    <url>
        <loc><?= SITE_URL . 'login' ?></loc>
    </url>
    <?php if(settings()->users->register_is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'register' ?></loc>
        </url>
    <?php endif ?>
    <url>
        <loc><?= SITE_URL . 'lost-password' ?></loc>
    </url>
    <url>
        <loc><?= SITE_URL . 'resend-activation' ?></loc>
    </url>
    <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'affiliate' ?></loc>
        </url>
    <?php endif ?>

    <?php if(settings()->main->api_is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'api-documentation' ?></loc>
        </url>
    <?php endif ?>

    <?php if(settings()->email_notifications->contact && !empty(settings()->email_notifications->emails)): ?>
        <url>
            <loc><?= SITE_URL . 'contact' ?></loc>
        </url>
    <?php endif ?>

    <?php if(settings()->content->pages_is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'pages' ?></loc>
        </url>
        <?php foreach($data->pages as $page): ?>
            <url>
                <loc><?= SITE_URL . ($page->language ? \Altum\Language::$active_languages[$page->language] . '/' : null) . 'page/' . $page->url ?></loc>
            </url>
        <?php endforeach ?>
        <?php foreach($data->pages_categories as $pages_category): ?>
            <url>
                <loc><?= SITE_URL . ($pages_category->language ? \Altum\Language::$active_languages[$pages_category->language] . '/' : null) . 'pages/' . $pages_category->url ?></loc>
            </url>
        <?php endforeach ?>
    <?php endif ?>

    <?php if(settings()->content->blog_is_enabled): ?>
        <url>
            <loc><?= SITE_URL . 'blog' ?></loc>
        </url>
        <?php foreach($data->blog_posts as $blog_post): ?>
            <url>
                <loc><?= SITE_URL . ($blog_post->language ? \Altum\Language::$active_languages[$blog_post->language] . '/' : null) . 'blog/' . $blog_post->url ?></loc>
            </url>
        <?php endforeach ?>

        <?php foreach($data->blog_posts_categories as $blog_posts_category): ?>
            <url>
                <loc><?= SITE_URL . ($blog_posts_category->language ? \Altum\Language::$active_languages[$blog_posts_category->language] . '/' : null) . 'blog/category/' . $blog_posts_category->url ?></loc>
            </url>
        <?php endforeach ?>
    <?php endif ?>

    <?php if(settings()->tools->is_enabled && settings()->tools->access == 'everyone'): ?>
        <?php foreach((require APP_PATH . 'includes/tools/tools.php') as $key => $value): ?>
            <?php if(settings()->tools->available_tools->{$key}): ?>
                <url>
                    <loc><?= url('tools/' . str_replace('_', '-', $key)) ?></loc>
                </url>
            <?php endif ?>
        <?php endforeach ?>
    <?php endif ?>
</urlset>
