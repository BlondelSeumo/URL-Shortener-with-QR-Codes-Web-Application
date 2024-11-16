<?php

define('ROOT', realpath(__DIR__ . '/..') . '/');
require_once ROOT . 'vendor/autoload.php';
require_once ROOT . 'app/includes/product.php';
require_once ROOT . 'config.php';
require_once ROOT . 'update/info.php';

$database = new \mysqli(
    DATABASE_SERVER,
    DATABASE_USERNAME,
    DATABASE_PASSWORD,
    DATABASE_NAME
);

if($database->connect_error) {
    die('The database connection has failed!');
}

$product_info = $database->query("SELECT `value` FROM `settings` WHERE `key` = 'product_info'")->fetch_object() ?? null;

if($product_info) {
    $product_info = json_decode($product_info->value);
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">

    <link rel="icon" href="./assets/favicons/favicon.ico">

    <title><?= PRODUCT_NAME ?> Update</title>
</head>
<body>

<div class="container">
    <header class="card header mt-4">
        <div class="card-body d-flex">
            <div class="mr-3">
                <img src="./assets/images/logo.png" class="img-fluid logo" alt="AltumCode logo" />
            </div>

            <div class="d-flex flex-column justify-content-center">
                <h1>Update</h1>
                <p class="subheader d-flex flex-row">
                    <span class="text-muted">
                        <a href="<?= PRODUCT_URL ?>" target="_blank" class="text-gray-500"><?= PRODUCT_NAME ?></a> by <a href="https://altumco.de/site" target="_blank" class="text-gray-500">AltumCode</a>
                    </span>
                </p>
            </div>
        </div>
    </header>
</div>

<main class="main mb-4">
    <div class="container">
        <div class="row">

            <div class="col col-md-3 d-none d-md-block">
                <div class="card">
                    <div class="card-body">
                        <nav class="nav sidebar-nav">
                            <ul class="sidebar mb-0" id="sidebar-ul">
                                <li class="nav-item">
                                    <a href="#welcome" class="navigator nav-link">Welcome</a>
                                </li>

                                <li class="nav-item">
                                    <a href="#update" class="navigator nav-link" style="display: none">Update</a>
                                </li>

                                <li class="nav-item">
                                    <a href="#finish" class="navigator nav-link" style="display: none">Finish</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>

            <div class="col" id="content">
                <div class="card">
                    <div class="card-body">
                        <section id="welcome" style="display: none">
                            <h2 class="mb-4">Welcome 👋</h2>
                            <p>Thank you for choosing the <a href="https://altumco.de/site" target="_blank">AltumCode</a> brand 🤗.</p>

                            <p>Please note, by proceeding with the update, you consent to the privacy policy and terms of service of <?= PRODUCT_NAME ?>, which are mentioned in their respective pages on <a href="<?= PRODUCT_URL ?>" target="_blank"><?= PRODUCT_URL ?></a> 📜.</p>

                            <a href="#update" id="welcome_start" class="navigator btn btn-block btn-primary mt-4">Next</a>
                        </section>

                        <section id="update" style="display: none">
                            <h2 class="mb-4">Update</h2>

                            <form id="setup_form" method="post" action="" role="form">
                                <div class="form-group">
                                    <label for="product_version">Current version</label>
                                    <input type="text" class="form-control" id="product_version" name="product_version" value="<?= $product_info ? $product_info->version : (defined('PRODUCT_VERSION') ? PRODUCT_VERSION : '8.0.0') ?>" aria-describedby="license_help" readonly="readonly">
                                </div>

                                <div class="form-group">
                                    <label for="new_product_version">Final version</label>
                                    <input type="text" class="form-control" id="new_product_version" name="new_product_version" value="<?= NEW_PRODUCT_VERSION ?>" aria-describedby="license_help" readonly="readonly">
                                </div>

                                <?php if(($product_info ? $product_info->version : PRODUCT_VERSION) == NEW_PRODUCT_VERSION): ?>
                                    <div class="alert alert-success">Your database is already on the latest version.</div>
                                <?php else: ?>
                                    <button type="submit" name="submit" class="btn btn-block btn-primary mt-4">Finish update</button>
                                <?php endif ?>
                            </form>
                        </section>

                        <section id="finish" style="display: none">
                            <h2 class="mb-4">Update Completed</h2>

                            <div class="alert alert-success"><strong>Success!</strong> The database update is finished!</div>

                            <div class="alert alert-info">It is now recommended to <strong>delete the /update folder</strong>.</div>
                        </section>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/popper.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tsparticles-confetti@2.12.0/tsparticles.confetti.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

</body>
</html>
