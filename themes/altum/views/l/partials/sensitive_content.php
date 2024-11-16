<?php defined('ALTUMCODE') || die() ?>

<body class="link-body">
    <div class="container animate__animated animate__fadeIn">
        <div class="row justify-content-center mt-5 mt-lg-8">
            <div class="col-md-8 py-6">

                <div class="mb-4 text-center">
                    <h1 class="h3"><?= l('link.sensitive_content.header')  ?></h1>
                    <span class="text-muted">
                        <?= l('link.sensitive_content.subheader') ?>
                    </span>
                </div>

                <?= \Altum\Alerts::output_alerts() ?>

                <form action="" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
                    <input type="hidden" name="type" value="sensitive_content" />

                    <button type="submit" name="submit" class="btn btn-block btn-primary mt-4"><?= l('link.sensitive_content.button') ?></button>
                </form>

            </div>
        </div>
    </div>
</body>


