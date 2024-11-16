<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$enabled_biolink_blocks = [];

foreach(require APP_PATH . 'includes/biolink_blocks.php' as $type => $value) {
    if(settings()->links->available_biolink_blocks->{$type}) {
        $enabled_biolink_blocks[$type] = $value;
    }
}

return $enabled_biolink_blocks;
