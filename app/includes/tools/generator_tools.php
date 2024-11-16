<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    'paypal_link_generator' => [
        'icon' => 'fab fa-paypal'
    ],

    'signature_generator' => [
        'icon' => 'fas fa-signature',
    ],

    'mailto_link_generator' => [
        'icon' => 'fas fa-envelope-open'
    ],

    'utm_link_generator' => [
        'icon' => 'fas fa-external-link-alt'
    ],

    'whatsapp_link_generator' => [
        'icon' => 'fab fa-whatsapp'
    ],

    'youtube_timestamp_link_generator' => [
        'icon' => 'fab fa-youtube'
    ],

    'slug_generator' => [
        'icon' => 'fas fa-grip-lines'
    ],

    'lorem_ipsum_generator' => [
        'icon' => 'fas fa-paragraph'
    ],

    'password_generator' => [
        'icon' => 'fas fa-lock',
        'similar' => [
            'password_strength_checker',
        ]
    ],

    'random_number_generator' => [
        'icon' => 'fas fa-random'
    ],

    'uuid_v4_generator' => [
        'icon' => 'fas fa-compress'
    ],

    'bcrypt_generator' => [
        'icon' => 'fas fa-passport'
    ],

    'md2_generator' => [
        'icon' => 'fas fa-hand-sparkles',
        'similar' => [
            'md4_generator',
            'md5_generator',
        ]
    ],

    'md4_generator' => [
        'icon' => 'fas fa-columns',
        'similar' => [
            'md2_generator',
            'md5_generator',
        ]
    ],

    'md5_generator' => [
        'icon' => 'fas fa-hashtag',
        'similar' => [
            'md2_generator',
            'md4_generator',
        ]
    ],

    'whirlpool_generator' => [
        'icon' => 'fas fa-spinner'
    ],

    'sha1_generator' => [
        'icon' => 'fas fa-asterisk'
    ],

    'sha224_generator' => [
        'icon' => 'fas fa-atom'
    ],

    'sha256_generator' => [
        'icon' => 'fas fa-compact-disc'
    ],

    'sha384_generator' => [
        'icon' => 'fas fa-certificate'
    ],

    'sha512_generator' => [
        'icon' => 'fas fa-bahai'
    ],

    'sha512_224_generator' => [
        'icon' => 'fas fa-crosshairs'
    ],

    'sha512_256_generator' => [
        'icon' => 'fas fa-sun'
    ],

    'sha3_224_generator' => [
        'icon' => 'fas fa-compass'
    ],

    'sha3_256_generator' => [
        'icon' => 'fas fa-ring'
    ],

    'sha3_384_generator' => [
        'icon' => 'fas fa-life-ring'
    ],

    'sha3_512_generator' => [
        'icon' => 'fas fa-circle-notch'
    ],
];
