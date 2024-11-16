<?php defined('ALTUMCODE') || die() ?>
<?= '<?xml version="1.0" encoding="UTF-8"?>' ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <?php for($i = 1; $i <= $data->total_sitemaps; $i++): ?>
    <sitemap>
        <loc><?= SITE_URL . 'sitemap/' .  $i  ?></loc>
        <lastmod><?= (new \DateTime())->format('Y-m-d\TH:i:sP') ?></lastmod>
    </sitemap>
    <?php endfor ?>
</sitemapindex>
