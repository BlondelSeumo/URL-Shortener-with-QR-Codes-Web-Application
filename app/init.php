<?php
/*
 * @copyright Copyright (c) 2024 AltumCode (https://altumcode.com/)
 *
 * This software is exclusively sold through https://altumcode.com/ by the AltumCode author.
 * Downloading this product from any other sources and running it without a proper license is illegal,
 *  except the official ones linked from https://altumcode.com/.
 */

const ALTUMCODE = 1;
define('ROOT_PATH', realpath(__DIR__ . '/..') . '/');
const APP_PATH = ROOT_PATH . 'app/';
const PLUGINS_PATH = ROOT_PATH . 'plugins/';
const THEME_PATH = ROOT_PATH . 'themes/altum/';
const THEME_URL_PATH = 'themes/altum/';
const ASSETS_PATH = THEME_PATH . 'assets/';
const ASSETS_URL_PATH = THEME_URL_PATH . 'assets/';
const UPLOADS_PATH = ROOT_PATH . 'uploads/';
const UPLOADS_URL_PATH = 'uploads/';
const CACHE_DEFAULT_SECONDS = 2592000;

/* Starting to include the required files */
require_once APP_PATH . 'includes/debug.php';
if(!DEBUG) require_once APP_PATH . 'includes/500.php';
require_once APP_PATH . 'includes/product.php';

/* Config file */
require_once ROOT_PATH . 'config.php';

/* Establish cookie / session on this path specifically */
define('COOKIE_PATH', preg_replace('|https?://[^/]+|i', '', SITE_URL));

/* Determine if we should set the samesite=strict */
session_set_cookie_params([
    'lifetime' => null,
    'path' => COOKIE_PATH,
    'samesite' => 'Lax'
]);

/* Only start a session handler if we need to */
$should_start_session = !isset($_GET['altum'])
    || (
        isset($_GET['altum'])
        && !(mb_strpos($_GET['altum'], 'cron') === 0)
        && !(mb_strpos($_GET['altum'], 'sitemap') === 0)
    );

if($should_start_session) {
    session_start();
}

/* Autoloader */
spl_autoload_register (function ($class) {
    $namespace_prefix = 'Altum';
    $split = explode('\\', $class);

    if($split[0] !== $namespace_prefix) {
        return;
    }

    /* Altum core */
    if(isset($split[1]) && !isset($split[2])) {
        require_once APP_PATH . 'core/' . $split[1] . '.php';
    }

    /* Traits, Models, Helpers */
    if(isset($split[1], $split[2]) && in_array($split[1], ['Traits', 'Models', 'Helpers'])) {
        $folder = mb_strtolower($split[1]);
        require_once APP_PATH . $folder . '/' . $split[2] . '.php';
    }

    /* Payment Gateways helpers */
    if(isset($split[1], $split[2]) && $split[1] == 'PaymentGateways') {
        require_once APP_PATH . 'helpers/payment-gateways/' . $split[2] . '.php';
    }

    /* Qr codes helpers */
    if(isset($split[1], $split[2]) && $split[1] == 'QrCodes') {
        require_once APP_PATH . 'helpers/qr-codes/' . $split[2] . '.php';
    }
});

/* Require files */
require_once APP_PATH . 'core/Controller.php';
require_once APP_PATH . 'core/Model.php';

/* Load some helpers */
require_once APP_PATH . 'helpers/Link.php';
require_once APP_PATH . 'helpers/core.php';
require_once APP_PATH . 'helpers/notifications.php';
require_once APP_PATH . 'helpers/others.php';
require_once APP_PATH . 'helpers/links.php';
require_once APP_PATH . 'helpers/strings.php';
require_once APP_PATH . 'helpers/email.php';
require_once APP_PATH . 'helpers/66uptime.php';

/* Autoload for vendor */
require_once ROOT_PATH . 'vendor/autoload.php';

