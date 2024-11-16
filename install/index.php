<?php
define('ROOT', realpath(__DIR__ . '/..') . '/');
require_once ROOT . 'app/includes/product.php';

if(file_exists(ROOT . 'install/installed')) {
    die();
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

    <title><?= PRODUCT_NAME ?> Installation</title>
</head>
<body>

<div class="container">
    <header class="card header mt-4">
        <div class="card-body d-flex">
            <div class="mr-3">
                <img src="./assets/images/logo.png" class="img-fluid logo" alt="AltumCode logo" />
            </div>

            <div class="d-flex flex-column justify-content-center">
                <h1>Installation</h1>
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
                                    <a href="#requirements" class="navigator nav-link" style="display: none">Requirements</a>
                                </li>

                                <li class="nav-item">
                                    <a href="#setup" class="navigator nav-link" style="display: none">Setup</a>
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
                            <h2 class="mb-4">Welcome</h2>

                            <p>Thank you for choosing the <a href="https://altumco.de/site" target="_blank">AltumCode</a> brand 🤗.</p>

                            <p>By continuing with the installation process, you are agreeing to the privacy policy and terms of service of <?= PRODUCT_NAME ?>, which are mentioned in their respective pages on <a href="<?= PRODUCT_URL ?>" target="_blank"><?= PRODUCT_URL ?></a> 📜.</p>

                            <a href="#requirements" id="welcome_start" class="navigator btn btn-block btn-primary mt-4">Start the installation</a>
                        </section>

                        <section id="requirements" style="display: none">
                            <?php $requirements = true ?>
                            <h2 class="mb-4">Requirements</h2>

                            <div class="table-responsive table-custom-container">
                                <table class="table table-custom">
                                    <thead>
                                    <tr>
                                        <th>Requirement</th>
                                        <th>Required</th>
                                        <th>Current</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>PHP Version</td>
                                        <td>8.1 - 8.3</td>
                                        <td><?= PHP_VERSION ?></td>
                                        <td class="text-right">
                                            <?php if(version_compare(PHP_VERSION, '8.1.0', '>=') && version_compare(PHP_VERSION, '8.4', '<')): ?>
                                                ✅
                                            <?php else: ?>
                                                ❌
                                                <?php $requirements = false; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>cURL</td>
                                        <td>Enabled</td>
                                        <td><?= function_exists('curl_version') ? 'Enabled' : 'Not Enabled' ?></td>
                                        <td class="text-right">
                                            <?php if(function_exists('curl_version')): ?>
                                                ✅
                                            <?php else: ?>
                                                ❌
                                                <?php $requirements = false; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>OpenSSL</td>
                                        <td>Enabled</td>
                                        <td><?= extension_loaded('openssl') ? 'Enabled' : 'Not Enabled' ?></td>
                                        <td class="text-right">
                                            <?php if(extension_loaded('openssl')): ?>
                                                ✅
                                            <?php else: ?>
                                                ❌
                                                <?php $requirements = false; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>mbstring</td>
                                        <td>Enabled</td>
                                        <td><?= extension_loaded('mbstring') && function_exists('mb_get_info') ? 'Enabled' : 'Not Enabled' ?></td>
                                        <td class="text-right">
                                            <?php if(extension_loaded('mbstring') && function_exists('mb_get_info')): ?>
                                                ✅
                                            <?php else: ?>
                                                ❌
                                                <?php $requirements = false; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>MySQLi</td>
                                        <td>Enabled</td>
                                        <td><?= function_exists('mysqli_connect') ? 'Enabled' : 'Not Enabled' ?></td>
                                        <td class="text-right">
                                            <?php if(function_exists('mysqli_connect')): ?>
                                                ✅
                                            <?php else: ?>
                                                ❌
                                                <?php $requirements = false; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="table-responsive table-custom-container mt-5">
                                <table class="table table-custom">
                                    <thead>
                                    <tr>
                                        <th>Path / File</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach(['config.php', 'uploads/main/', 'uploads/cache/', 'uploads/offline_payment_proofs/', 'uploads/blog/', 'uploads/pwa/', 'uploads/products_files/', 'uploads/avatars/', 'uploads/backgrounds/', 'uploads/block_thumbnail_images/', 'uploads/block_images/', 'uploads/files/', 'uploads/favicons/', 'uploads/qr_code/', 'uploads/qr_code_logo/', 'uploads/qr_code_background', 'uploads/qr_code_foreground', 'uploads/chats_assistants/', 'uploads/chats_images', 'uploads/syntheses', 'uploads/static/', 'uploads/splash_pages/'] as $key): ?>
                                    <tr>
                                        <td>/<?= $key ?></td>
                                        <td><?= is_writable(ROOT . $key) ? 'Writable' : 'Not Writable' ?></td>
                                        <td class="text-right">
                                            <?php if(is_writable(ROOT . $key)): ?>
                                                ✅
                                            <?php else: ?>
                                                ❌
                                                <?php $requirements = false; ?>
                                            <?php endif ?>
                                        </td>
                                    </tr>
                                    <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4">
                                <?php if($requirements): ?>
                                    <a href="#setup" class="navigator btn btn-block btn-primary">Next</a>
                                <?php else: ?>
                                    <div class="alert alert-danger" role="alert">
                                        Please make sure all the requirements listed on the documentation and on this page are met before continuing!
                                    </div>
                                <?php endif ?>
                            </div>
                        </section>

                        <section id="setup" style="display: none">
                            <?php
                            $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                            $installation_url = preg_replace('/install\/$/', '', $actual_link);
                            ?>
                            <h2 class="mb-4">Setup</h2>

                            <form id="setup_form" method="post" action="" role="form">
                                <div class="form-group">
                                    <label for="license_key">License key | <a style="color:red;" href="https://weadown.com" target="_blank">weadown.com</a></label>
                                    <input type="text" class="form-control" id="license_key" name="license_key" required="required">
                                    <small class="form-text text-muted">Enter random data!</small>
                                </div>

                                <div class="form-group">
                                    <label for="installation_url">Website URL</label>
                                    <input type="text" class="form-control" id="installation_url" name="installation_url" value="<?= $installation_url ?>" placeholder="https://example.com/" required="required">
                                    <small class="form-text text-muted">Make sure to specify the full url of the installation path of the product.<br /> Subdomain example: <code>https://subdomain.domain.com/</code> <br />Subfolder example: <code>https://domain.com/product/</code></small>
                                </div>

                                <h3 class="mt-5">Database Details</h3>
                                <p>Fill in the database details that you will use for the installation of this product.</p>

                                <div class="form-group">
                                    <label for="database_host">Host</label>
                                    <input type="text" class="form-control" id="database_host" name="database_host" value="localhost" required="required">
                                </div>

                                <div class="form-group">
                                    <label for="database_name">Name</label>
                                    <input type="text" class="form-control" id="database_name" name="database_name" required="required">
                                </div>

                                <div class="form-group">
                                    <label for="database_username">Username</label>
                                    <input type="text" class="form-control" id="database_username" name="database_username" required="required">
                                </div>

                                <div class="form-group">
                                    <label for="database_password">Password</label>
                                    <input type="password" class="form-control" id="database_password" name="database_password">
                                </div>


                                <h3 class="mt-5">Keep in touch</h3>
                                <p>Subscribe to the newsletter and you will receive email updates of <strong>new products</strong>, <strong>discounts</strong> and <strong>product updates</strong>.</p>

                                <div class="form-group row">
                                    <label for="newsletter_email" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="newsletter_email" name="newsletter_email" placeholder="Your valid email address">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="newsletter_name" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="newsletter_name" name="newsletter_name" placeholder="Your full name">
                                    </div>
                                </div>
                                <p class="text-muted"><small>Leave the fields empty if you do not wish to subscribe to the newsletter.</small></p>

                                <button type="submit" name="submit" class="btn btn-block btn-primary mt-4">Finish installation</button>
                            </form>
                        </section>

                        <section id="finish" style="display: none">
                            <h2 class="mb-4">Finish</h2>

                            <div class="alert alert-success">The installation process has been successfuly completed! <a style="color:red;" href="https://weadown.com" target="_blank">weadown.com</a></div>

                            <div class="table-responsive table-custom-container mt-4">
                                <table class="table table-custom">
                                    <tbody>
                                    <tr>
                                        <td class="font-weight-bold">URL</td>
                                        <td><a href="" id="final_url"></a></td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Username</td>
                                        <td>admin</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">Password</td>
                                        <td>admin</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
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
