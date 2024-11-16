<?php
if(
    !empty(settings()->ads->header_biolink)
    && !$data->user->plan_settings->no_ads
): ?>
    <div class="container my-3 d-print-none"><?= settings()->ads->header_biolink ?></div>
<?php endif ?>
