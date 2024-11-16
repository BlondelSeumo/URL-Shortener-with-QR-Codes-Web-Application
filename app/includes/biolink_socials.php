<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

return [
    'email' => [
        'format' => 'mailto:%s',
        'input_group' => null,
        'max_length' => 320,
        'icon' => 'fas fa-envelope'
    ],
    'tel'=> [
        'format' => 'tel: %s',
        'input_group' => null,
        'max_length' => 32,
        'icon' => 'fas fa-phone-square-alt'
    ],
    'telegram'=> [
        'format' => 'https://t.me/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-telegram'
    ],
    'whatsapp'=> [
        'format' => 'https://wa.me/%s',
        'input_group' => null,
        'max_length' => 32,
        'icon' => 'fab fa-whatsapp'
    ],
    'whatsapp_channel'=> [
        'format' => 'https://www.whatsapp.com/channel/%s',
        'input_group' => null,
        'max_length' => 64,
        'icon' => 'fab fa-whatsapp'
    ],
    'facebook'=> [
        'format' => 'https://facebook.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-facebook'
    ],
    'facebook-messenger'=> [
        'format' => 'https://m.me/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-facebook-messenger'
    ],
    'instagram'=> [
        'format' => 'https://instagram.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-instagram'
    ],
    'twitter'=> [
        'format' => 'https://x.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-twitter'
    ],
    'tiktok'=> [
        'format' => 'https://tiktok.com/@%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-tiktok'
    ],
    'youtube'=> [
        'format' => 'https://youtube.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-youtube'
    ],
    'soundcloud'=> [
        'format' => 'https://soundcloud.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-soundcloud'
    ],
    'linkedin'=> [
        'format' => 'https://linkedin.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-linkedin'
    ],
    'spotify' => [
        'format' => 'https://open.spotify.com/artist/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-spotify'
    ],
    'pinterest' => [
        'format' => 'https://pinterest.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-pinterest'
    ],
    'snapchat' => [
        'format' => 'https://snapchat.com/add/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-snapchat'
    ],
    'twitch' => [
        'format' => 'https://twitch.tv/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-twitch'
    ],
    'discord' => [
        'format' => 'https://discord.gg/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-discord'
    ],
    'address' => [
        'format' => 'https://maps.google.com/maps?q=%s',
        'input_group' => false,
        'max_length' => 256,
        'icon' => 'fas fa-map-marker-alt'
    ],
    'threads' => [
        'format' => 'https://threads.net/@%s',
        'input_group' => true,
        'max_length' => 64,
        'icon' => 'fab fa-threads',
    ],
    'reddit' => [
        'format' => 'https://reddit.com/%s',
        'input_group' => true,
        'max_length' => 64,
        'icon' => 'fab fa-reddit',
    ],
    'rumble' => [
        'format' => 'https://rumble.com/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fas fa-play-circle',
    ],
    'vk'=> [
        'format' => 'https://vk.me/%s',
        'input_group' => true,
        'max_length' => 128,
        'icon' => 'fab fa-vk'
    ],
    'signal'=> [
        'format' => 'https://signal.me/%s',
        'input_group' => true,
        'max_length' => 256,
        'icon' => 'fa fa-comment'
    ],
];
