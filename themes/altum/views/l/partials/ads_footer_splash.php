<?php if(!empty(settings()->ads->footer_splash) && !$data->user->plan_settings->no_ads): ?>
    <div class="container my-3 d-print-none"><?= settings()->ads->footer_splash ?></div>
<?php endif ?>

<?php if($data->splash_page->settings->ads_footer && $data->user->plan_settings->no_ads): ?>
    <div class="container my-3"><?= $data->splash_page->settings->ads_footer ?></div>
<?php endif ?>

