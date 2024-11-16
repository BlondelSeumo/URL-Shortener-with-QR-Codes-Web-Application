<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    'text' => [
        'icon' => 'fas fa-paragraph',
        'emoji' => '📝',

        'max_length' => 3000
    ],
    'url' => [
        'icon' => 'fas fa-link',
        'emoji' => '🔗',

        'max_length' => 2048
    ],
    'phone' => [
        'icon' => 'fas fa-phone-square-alt',
        'emoji' => '📞',

        'max_length' => 32
    ],
    'sms' => [
        'icon' => 'fas fa-sms',
        'emoji' => '💬',

        'max_length' => 32,
        'body' => [
            'max_length' => 160,
        ]
    ],
    'email' => [
        'icon' => 'fas fa-envelope',
        'emoji' => '📧',

        'max_length' => 360,
        'subject' => [
            'max_length' => 256,
        ],
        'body' => [
            'max_length' => 2048,
        ]
    ],
    'whatsapp' => [
        'icon' => 'fab fa-whatsapp',
        'emoji' => '💚',

        'max_length' => 32,
        'body' => [
            'max_length' => 2048,
        ]
    ],
    'facetime' => [
        'icon' => 'fas fa-headset',
        'emoji' => '🎥',

        'max_length' => 32
    ],
    'location' => [
        'icon' => 'fas fa-map-pin',
        'emoji' => '📍',

        'latitude' => [
            'max_length' => 32,
        ],
        'longitude' => [
            'max_length' => 32,
        ]
    ],
    'wifi' => [
        'icon' => 'fas fa-wifi',
        'emoji' => '📶',

        'ssid' => [
            'max_length' => 128,
        ],
        'password' => [
            'max_length' => 128,
        ]
    ],
    'event' => [
        'icon' => 'fas fa-calendar-alt',
        'emoji' => '📅',

        'max_length' => 128,
        'location' => [
            'max_length' => 128,
        ],
        'url' => [
            'max_length' => 1024,
        ],
        'note' => [
            'max_length' => 256,
        ]
    ],
    'vcard' => [
        'icon' => 'fas fa-id-card',
        'emoji' => '👤',

        'first_name' => [
            'max_length' => 64,
        ],
        'last_name' => [
            'max_length' => 64,
        ],
        'email' => [
            'max_length' => 320,
        ],
        'url' => [
            'max_length' => 1024,
        ],
        'company' => [
            'max_length' => 64,
        ],
        'job_title' => [
            'max_length' => 64,
        ],
        'birthday' => [
            'max_length' => 16,
        ],
        'street' => [
            'max_length' => 128,
        ],
        'city' => [
            'max_length' => 64,
        ],
        'zip' => [
            'max_length' => 32,
        ],
        'region' => [
            'max_length' => 32,
        ],
        'country' => [
            'max_length' => 32,
        ],
        'note' => [
            'max_length' => 256,
        ],
        'phone_number_label' => [
            'max_length' => 32,
        ],
        'phone_number_value' => [
            'max_length' => 32,
        ],
        'social_label' => [
            'max_length' => 32
        ],
        'social_value' => [
            'max_length' => 1024
        ]
    ],
    'crypto' => [
        'icon' => 'fab fa-bitcoin',
        'emoji' => '💰',

        'coins' => [
            'bitcoin' => 'Bitcoin BTC',
            'ethereum' => 'Ethereum ETH',
            'elrond' => 'Elrond EGLD',
        ],
        'address' => [
            'max_length' => 128,
        ],
        'amount' => []
    ],
    'paypal' => [
        'icon' => 'fab fa-paypal',
        'emoji' => '💳',

        'type' => [
            'buy_now' => '_xclick',
            'add_to_cart' => '_cart',
            'donation' => '_donations'
        ],
        'email' => [
            'max_length' => 320,
        ],
        'title' => [
            'max_length' => 256,
        ],
        'currency' => [
            'max_length' => 3,
        ],
        'price' => [],
        'thank_you_url' => [
            'max_length' => 1024,
        ],
        'cancel_url' => [
            'max_length' => 1024,
        ],
    ],
    'upi' => [
        'icon' => 'fas fa-rupee-sign',
        'emoji' => '💸',

        'payee_id' => [
            'max_length' => 64,
        ],
        'payee_name' => [
            'max_length' => 128,
        ],
        'amount' => [],
        'currency' => [
            'max_length' => 3,
        ],
        'transaction_reference' => [
            'max_length' => 35,
        ],
        'transaction_note' => [
            'max_length' => 80,
        ],
        'transaction_id' => [
            'max_length' => 35,
        ],
        'thank_you_url' => [
            'max_length' => 256,
        ],
    ],
    'epc' => [
        'icon' => 'fas fa-euro-sign',
        'emoji' => '💶',

        'iban' => [
            'max_length' => 34,
        ],
        'payee_name' => [
            'max_length' => 70,
        ],
        'amount' => [],
        'currency' => [
            'max_length' => 3,
        ],
        'bic' => [
            'max_length' => 11,
        ],
        'remittance_reference' => [
            'max_length' => 35,
        ],
        'remittance_text' => [
            'max_length' => 140,
        ],
        'information' => [
            'max_length' => 70,
        ],
    ],

    'pix' => [
        'icon' => 'fas fa-credit-card',
        'emoji' => '💳',

        'payee_key' => [
            'max_length' => 64,
        ],
        'payee_name' => [
            'max_length' => 99,
        ],
        'amount' => [],
        'currency' => [
            'max_length' => 3,
        ],
        'city' => [
            'max_length' => 99,
        ],
        'transaction_id' => [
            'max_length' => 99,
        ],
        'description' => [
            'max_length' => 128,
        ],
    ],
];
