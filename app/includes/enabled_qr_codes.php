<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$enabled_qr_codes = [];

foreach(require APP_PATH . 'includes/qr_codes.php' as $type => $value) {
    if(settings()->codes->available_qr_codes->{$type}) {
        $enabled_qr_codes[$type] = $value;
    }
}

return $enabled_qr_codes;
