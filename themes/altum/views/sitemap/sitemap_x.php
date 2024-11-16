<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php while($row = $data->links_result->fetch_object()): ?>
    <url>
        <loc><?= SITE_URL . $row->url ?></loc>

        <lastmod><?= (new \DateTime($row->datetime))->format('Y-m-d\TH:i:sP') ?></lastmod>

        <changefreq>daily</changefreq>

        <priority>0.9</priority>
    </url>
<?php endwhile ?>
</urlset>
