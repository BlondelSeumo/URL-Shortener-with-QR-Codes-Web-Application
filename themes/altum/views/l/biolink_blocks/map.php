<?php defined('ALTUMCODE') || die() ?>

<?php
$markers = null;
$markers_string = '';
if($data->link->settings->markers) {
    $markers = explode("\r\n",$data->link->settings->markers);
    foreach($markers as $marker) {
        $markers_string .= '&markers=' . urlencode($marker);
    }
}

?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <?php if($data->link->location_url): ?>
    <a href="<?= $data->link->location_url . $data->link->utm_query ?>" data-track-biolink-block-id="<?= $data->link->biolink_block_id ?>" target="_blank">
        <img src="https://maps.googleapis.com/maps/api/staticmap?scale=2&center=<?= urlencode($data->link->settings->address) ?>&zoom=<?= $data->link->settings->zoom ?>&size=800x400&maptype=<?= $data->link->settings->type ?><?= $markers_string ?>&key=<?= settings()->links->google_static_maps_api_key ?>" class="img-fluid rounded" alt="<?= $data->link->settings->address ?>" loading="lazy" />
    </a>
    <?php else: ?>
        <img src="https://maps.googleapis.com/maps/api/staticmap?scale=2&center=<?= urlencode($data->link->settings->address) ?>&zoom=<?= $data->link->settings->zoom ?>&size=800x400&maptype=<?= $data->link->settings->type ?><?= $markers_string ?>&key=<?= settings()->links->google_static_maps_api_key ?>" class="img-fluid rounded" alt="<?= $data->link->settings->address ?>" loading="lazy" />
    <?php endif ?>
</div>

