<?php
defined('ALTUMCODE') || die();

return (object) [
    'plugin_id' => 'email-signatures',
    'name' => 'Email signatures generator',
    'description' => 'This plugin adds a fully fledged email signatures generator.',
    'version' => '1.0.0',
    'url' => 'https://altumco.de/email-signatures-plugin',
    'author' => 'AltumCode',
    'author_url' => 'https://altumcode.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'settings_url' => url('admin/settings/signatures'),
    'avatar_style' => 'background: #A770EF;background: -webkit-linear-gradient(to right, #FDB99B, #CF8BF3, #A770EF);background: linear-gradient(to right, #FDB99B, #CF8BF3, #A770EF);',
    'icon' => '✉️',
];
