<?php
define('ROOT', realpath(__DIR__ . '/..') . '/');
define('DEBUG', 0);
define('CACHE', 1);
define('LOGGING', 1);
require_once ROOT . 'app/init.php';
require_once ROOT . 'update/info.php';

mysqli_report(MYSQLI_REPORT_OFF);

$database = new \mysqli(
    DATABASE_SERVER,
    DATABASE_USERNAME,
    DATABASE_PASSWORD,
    DATABASE_NAME
);

if($database->connect_error) {
    die(json_encode([
        'status' => 'error',
        'message' => 'The database connection has failed!'
    ]));
}

$database->set_charset('utf8mb4');

$product_info = $database->query("SELECT `value` FROM `settings` WHERE `key` = 'product_info'")->fetch_object() ?? null;
$license = $database->query("SELECT `value` FROM `settings` WHERE `key` = 'license'")->fetch_object() ?? null;
$license = json_decode($license->value);

if($product_info) {
    $product_info = json_decode($product_info->value);
}

/* Start the pre updating process */
$update_key = array_search($product_info ? $product_info->code : (defined('PRODUCT_CODE') ? PRODUCT_CODE : '800'), $updates);

if($update_key !== false) {
    $update_key++;
}

$updates_to_run = array_slice($updates, $update_key);

/* Go over each updates that we need to run */
foreach($updates_to_run as $value) {

    /* Run SQL */
    $dump_content = file_get_contents(ROOT . 'update/sql/' . $value . '.sql');

    /* Get the Regular & Extended queries */
    $exploded_dump_content = array_filter(explode('-- EXTENDED SEPARATOR --', $dump_content));

    $dump = array_filter(explode('-- SEPARATOR --', $exploded_dump_content[0]));

    /* Run all the SQL from the specific version */
    foreach($dump as $query) {
        $throw_error = true;
        $query = trim($query);

        if(empty($query)) {
            continue;
        }

        if(string_starts_with('-- X --', $query)) {
            $throw_error = false;
            $query = trim(str_replace('-- X --', '', $query));
        }

        $database->query($query);

        if($database->error && $throw_error) {
            die(json_encode([
                'status' => 'error',
                'message' => 'Error when running the database queries: ' . $database->error
            ]));
        }
    }

    /* Run Extended SQL queries if existing */
    if(isset($exploded_dump_content[1]) && in_array($license->type, ['SPECIAL', 'Extended License', 'extended'])) {
        $dump = array_filter(explode('-- SEPARATOR --', $exploded_dump_content[1]));

        /* Run all the SQL from the specific version */
        foreach($dump as $query) {
        $throw_error = true;
        $query = trim($query);

        if(empty($query)) {
            continue;
        }

        if(string_starts_with('-- X --', $query)) {
            $throw_error = false;
            $query = trim(str_replace('-- X --', '', $query));
        }

        $database->query($query);

        if($database->error && $throw_error) {
                die(json_encode([
                    'status' => 'error',
                    'message' => 'Error when running the database queries: ' . $database->error
                ]));
            }
        }
    }

}

/* Delete the cache store for the settings */
\Altum\Cache::initialize();
cache()->clear();

/* Output */
die(json_encode([
    'status' => 'success',
    'message' => ''
]));
