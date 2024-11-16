<?php if(!empty(settings()->ads->header_splash) && !$data->user->plan_settings->no_ads): ?>
    <div class="container my-3 d-print-none"><?= settings()->ads->header_splash ?></div>
<?php endif ?>

<?php if($data->splash_page->settings->ads_header && $data->user->plan_settings->no_ads): ?>
    <div class="container my-3"><?= $data->splash_page->settings->ads_header ?></div>
<?php endif ?>

