<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$pro_blocks = \Altum\Plugin::is_active('pro-blocks') && file_exists(\Altum\Plugin::get('pro-blocks')->path . 'pro_blocks.php') ? include \Altum\Plugin::get('pro-blocks')->path . 'pro_blocks.php' : [];
$ultimate_blocks = \Altum\Plugin::is_active('ultimate-blocks') && file_exists(\Altum\Plugin::get('ultimate-blocks')->path . 'ultimate_blocks.php') ? include \Altum\Plugin::get('ultimate-blocks')->path . 'ultimate_blocks.php' : [];
$payment_blocks = \Altum\Plugin::is_active('payment-blocks') && file_exists(\Altum\Plugin::get('payment-blocks')->path . 'payment_blocks.php') ? include \Altum\Plugin::get('payment-blocks')->path . 'payment_blocks.php' : [];

$default_blocks = [
    'link' => [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-link',
        'color' => '#004ecc',
        'has_statistics' => true,
        'themable' => true,
        'display_dynamic_name' => 'name',
        'whitelisted_thumbnail_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'standard',
    ],
    'heading' => [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-heading',
        'color' => '#000000',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => 'text',
        'category' => 'standard',
    ],
    'paragraph' => [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-paragraph',
        'color' => '#494949',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => 'text',
        'category' => 'standard',
    ],
    'avatar' => [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-user',
        'color' => '#8b2abf',
        'has_statistics' => true,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'standard',
    ],
    'image' => [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-image',
        'color' => '#0682FF',
        'has_statistics' => true,
        'themable' => false,
        'display_dynamic_name' => 'image_alt',
        'whitelisted_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'standard',
    ],
    'socials' => [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-users',
        'color' => '#63d2ff',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'category' => 'standard',
    ],
    'email_collector' => [
        'type' => 'default',
        'icon' => 'fas fa-envelope',
        'color' => '#c91685',
        'has_statistics' => false,
        'themable' => true,
        'display_dynamic_name' => 'name',
        'whitelisted_thumbnail_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'advanced',
    ],
    'threads' => [
        'type' => 'default',
        'icon' => 'fab fa-threads',
        'color' => '#f54640',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['threads.net', 'www.threads.net'],
        'category' => 'embeds',
    ],
    'soundcloud' => [
        'type' => 'default',
        'icon' => 'fab fa-soundcloud',
        'color' => '#ff8800',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['soundcloud.com'],
        'category' => 'embeds',
    ],
    'spotify' => [
        'type' => 'default',
        'icon' => 'fab fa-spotify',
        'color' => '#1db954',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['open.spotify.com'],
        'category' => 'embeds',
    ],
    'youtube' => [
        'type' => 'default',
        'icon' => 'fab fa-youtube',
        'color' => '#ff0000',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['www.youtube.com', 'youtu.be'],
        'category' => 'embeds',
    ],
    'twitch' => [
        'type' => 'default',
        'icon' => 'fab fa-twitch',
        'color' => '#6441a5',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['www.twitch.tv'],
        'category' => 'embeds',
    ],
    'vimeo' => [
        'type' => 'default',
        'icon' => 'fab fa-vimeo',
        'color' => '#1ab7ea',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['vimeo.com'],
        'category' => 'embeds',
    ],
    'tiktok_video' => [
        'type' => 'default',
        'icon' => 'fab fa-tiktok',
        'color' => '#FD3E3E',
        'has_statistics' => false,
        'themable' => false,
        'display_dynamic_name' => false,
        'whitelisted_hosts' => ['www.tiktok.com'],
        'category' => 'embeds',
    ],
    'paypal' => [
        'type' => 'default',
        'icon' => 'fab fa-fw fa-paypal',
        'color' => '#00457C',
        'has_statistics' => true,
        'themable' => true,
        'display_dynamic_name' => 'name',
        'whitelisted_thumbnail_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'payments',
    ],
    'phone_collector' => [
        'type' => 'default',
        'icon' => 'fas fa-phone-square-alt',
        'color' => '#39c640',
        'has_statistics' => false,
        'themable' => true,
        'display_dynamic_name' => 'name',
        'whitelisted_thumbnail_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'advanced',
    ],
    'contact_collector' => [
        'type' => 'default',
        'icon' => 'fas fa-address-book',
        'color' => '#7136c0',
        'has_statistics' => false,
        'themable' => true,
        'display_dynamic_name' => 'name',
        'whitelisted_thumbnail_image_extensions' => ['jpg', 'jpeg', 'png', 'svg', 'gif', 'webp'],
        'category' => 'advanced',
    ],
];

if(settings()->links->google_static_maps_is_enabled) {
    $default_blocks['map'] = [
        'type' => 'default',
        'icon' => 'fas fa-fw fa-map',
        'color' => '#31A952',
        'has_statistics' => true,
        'themable' => false,
        'display_dynamic_name' => 'address',
        'category' => 'advanced',
    ];
}

return array_merge(
    $default_blocks,
    $pro_blocks,
    $ultimate_blocks,
    $payment_blocks,
);

