<?php
defined('ALTUMCODE') || die();

return (object) [
    'plugin_id' => 'offload',
    'name' => 'Offload assets & user content',
    'description' => 'The offload plugin is meant to help offload the assets (images, js, css) files, including user upload content and retrieve them via an external storage, such as Amazon S3.',
    'version' => '1.0.0',
    'url' => 'https://altumco.de/offload-plugin',
    'author' => 'AltumCode',
    'author_url' => 'https://altumcode.com/',
    'status' => 'inexistent',
    'actions'=> true,
    'avatar_style' => 'background: #9F91B9;background: -webkit-linear-gradient(top right, #9F91B9, #406BA8);background: -moz-linear-gradient(top right, #9F91B9, #406BA8);background: linear-gradient(to bottom left, #9F91B9, #406BA8);',
    'icon' => '💻',
];
