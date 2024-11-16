<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

$access = [
    'read' => [
        'read.all' => l('global.all')
    ],

    'create' => [
        'create.links' => l('links.title'),
        'create.pixels' => l('pixels.title'),
        'create.projects' => l('projects.title'),
    ],

    'update' => [
        'update.links' => l('links.title'),
        'update.pixels' => l('pixels.title'),
        'update.projects' => l('projects.title'),
    ],

    'delete' => [
        'delete.links' => l('links.title'),
        'delete.pixels' => l('pixels.title'),
        'delete.projects' => l('projects.title'),
        'delete.data' => l('data.title'),
    ],
];

if(settings()->links->biolinks_is_enabled) {
    $access['create']['create.biolinks_blocks'] = l('biolinks_blocks.title');
    $access['update']['update.biolinks_blocks'] = l('biolinks_blocks.title');
    $access['delete']['delete.biolinks_blocks'] = l('biolinks_blocks.title');
}

if(settings()->codes->qr_codes_is_enabled) {
    $access['create']['create.qr_codes'] = l('qr_codes.title');
    $access['update']['update.qr_codes'] = l('qr_codes.title');
    $access['delete']['delete.qr_codes'] = l('qr_codes.title');
}

if(settings()->links->domains_is_enabled) {
    $access['create']['create.domains'] = l('domains.title');
    $access['update']['update.domains'] = l('domains.title');
    $access['delete']['delete.domains'] = l('domains.title');
}

if(\Altum\Plugin::is_active('payment-blocks')) {
    $access['delete']['delete.guests_payments'] = l('guests_payments.title');
    $access['create']['create.payment_processors'] = l('payment_processors.title');
    $access['update']['update.payment_processors'] = l('payment_processors.title');
    $access['delete']['delete.payment_processors'] = l('payment_processors.title');
}

if(\Altum\Plugin::is_active('email-signatures')) {
    $access['create']['create.signatures'] = l('signatures.title');
    $access['update']['update.signatures'] = l('signatures.title');
    $access['delete']['delete.signatures'] = l('signatures.title');
}

if(\Altum\Plugin::is_active('aix')) {
    if(settings()->aix->documents_is_enabled) {
        $access['create']['create.documents'] = l('documents.title');
        $access['update']['update.documents'] = l('documents.title');
        $access['delete']['delete.documents'] = l('documents.title');
    }

    if(settings()->aix->images_is_enabled) {
        $access['create']['create.images'] = l('images.title');
        $access['update']['update.images'] = l('images.title');
        $access['delete']['delete.images'] = l('images.title');
    }

    if(settings()->aix->transcriptions_is_enabled) {
        $access['create']['create.transcriptions'] = l('transcriptions.title');
        $access['update']['update.transcriptions'] = l('transcriptions.title');
        $access['delete']['delete.transcriptions'] = l('transcriptions.title');
    }

    if(settings()->aix->syntheses_is_enabled) {
        $access['create']['create.syntheses'] = l('syntheses.title');
        $access['update']['update.syntheses'] = l('syntheses.title');
        $access['delete']['delete.syntheses'] = l('syntheses.title');
    }
}

if(settings()->links->splash_page_is_enabled) {
    $access['create']['create.splash_pages'] = l('splash_pages.title');
    $access['update']['update.splash_pages'] = l('splash_pages.title');
    $access['delete']['delete.splash_pages'] = l('splash_pages.title');
}

return $access;
