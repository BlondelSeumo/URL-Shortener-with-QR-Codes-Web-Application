<?php
defined('ALTUMCODE') || die();

return (object) [
    'plugin_id' => 'push-notifications',
    'name' => 'Push Notifications system',
    'description' => 'The plugin gives you the ability to send push subscribers notifications with ease.',
    'version' => '1.0.0',
    'url' => 'https://altumco.de/push-notifications-plugin',
    'author' => 'AltumCode',
    'author_url' => 'https://altumcode.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'settings_url' => url('admin/settings/push-notifications'),
    'avatar_style' => 'background: #ad5389; background: -webkit-linear-gradient(to right, #3c1053, #ad5389); background: linear-gradient(to right, #3c1053, #ad5389);',
    'icon' => '🔔',
];
