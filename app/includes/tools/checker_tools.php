<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    'dns_lookup' => [
        'icon' => 'fas fa-network-wired',
        'similar' => [
            'reverse_ip_lookup',
            'ip_lookup',
            'ssl_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'ip_lookup' => [
        'icon' => 'fas fa-search-location',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ssl_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'reverse_ip_lookup' => [
        'icon' => 'fas fa-book',
        'similar' => [
            'ip_lookup',
            'dns_lookup',
            'ssl_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'ssl_lookup' => [
        'icon' => 'fas fa-lock',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ip_lookup',
            'whois_lookup',
            'ping',
        ]
    ],

    'whois_lookup' => [
        'icon' => 'fas fa-fingerprint',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ip_lookup',
            'ssl_lookup',
            'ping',
        ]
    ],

    'ping' => [
        'icon' => 'fas fa-server',
        'similar' => [
            'reverse_ip_lookup',
            'dns_lookup',
            'ip_lookup',
            'ssl_lookup',
            'whois_lookup',
        ]
    ],

    'http_headers_lookup' => [
        'icon' => 'fas fa-asterisk'
    ],

    'http2_checker' => [
        'icon' => 'fas fa-satellite'
    ],

    'brotli_checker' => [
        'icon' => 'fas fa-compress-alt',
        'similar' => [
            'ssl_lookup',
            'http_headers_lookup',
            'http2_checker',
        ]
    ],

    'safe_url_checker' => [
        'icon' => 'fab fa-google'
    ],

    'google_cache_checker' => [
        'icon' => 'fas fa-history'
    ],

    'url_redirect_checker' => [
        'icon' => 'fas fa-directions'
    ],

    'password_strength_checker' => [
        'icon' => 'fas fa-key',
        'similar' => [
            'password_generator',
        ]
    ],

    'meta_tags_checker' => [
        'icon' => 'fas fa-external-link-alt'
    ],

    'website_hosting_checker' => [
        'icon' => 'fas fa-server'
    ],

    'file_mime_type_checker' => [
        'icon' => 'fas fa-file'
    ],

    'gravatar_checker' => [
        'icon' => 'fas fa-user-circle'
    ],
];
