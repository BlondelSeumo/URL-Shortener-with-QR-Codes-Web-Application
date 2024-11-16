<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    'youtube_thumbnail_downloader' => [
        'icon' => 'fab fa-youtube'
    ],

    'qr_code_reader' => [
        'icon' => 'fas fa-qrcode',
        'similar' => [
            'barcode_reader',
        ]
    ],

    'barcode_reader' => [
        'icon' => 'fas fa-barcode',
        'similar' => [
            'qr_code_reader',
        ]
    ],

    'exif_reader' => [
        'icon' => 'fas fa-camera',
        'similar' => [
            'qr_code_reader',
        ]
    ],

    'color_picker' => [
        'icon' => 'fas fa-palette'
    ],
];
