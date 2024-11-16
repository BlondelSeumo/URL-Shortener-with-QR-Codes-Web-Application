<?php //defined('ALTUMCODE') || die() ?>

<!doctype html>
<html lang="<?= \Altum\Language::$active_languages[$data->language] ?>" dir="<?= l('direction', $data->language) ?>">
<head>
    <meta name="viewport" content="width=device-width" />
    <meta charset="UTF-8">
    <title></title>
    <style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
            margin-bottom: 15px;
        }

        body {
            background-color: #fff;
            font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,"Noto Sans","Liberation Sans",sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";
            -webkit-font-smoothing: antialiased;
            font-size: 16px;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }

        table td {

            font-size: 16px;
            vertical-align: top;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */

        .body {
            background-color: #fff;
            width: 100%;
            direction: <?= l('direction', $data->language) ?>;
        }

        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */
        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 620px;
            padding: 10px;
            width: 620px;
        }

        /* This should also be a block element, so that it will fill 100% of the .container */
        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 620px;
            padding: 10px;
        }

        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */

        .header a {
            text-decoration: none;
        }

        .logo {
            height: 40px;
            max-height: 40px;
            width: auto;
            margin-bottom: 20px;
        }

        .main {
            background: #f7f7f7;
            border-radius: 10px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 30px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #6f6f6f;
            font-size: 12px;
            text-align: center;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1,
        h2,
        h3,
        h4 {
            color: #000000;

            font-weight: 500;
            line-height: 1.3;
            margin: 0;
            margin-bottom: 17.5px;
        }

        h1 {
            font-size: 30px;
        }

        p,
        ul,
        ol {

            font-size: 16px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }
        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }

        /* -------------------------------------
            BUTTONS
        ------------------------------------- */
        .btn {
            box-sizing: border-box;
            width: 100%;
        }

        .btn > tbody > tr > td {
            padding-bottom: 15px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: 0;
            border-radius: 10px;
            box-sizing: border-box;
            color: #3437db;
            cursor: pointer;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary a {
            background-color: #000000;
            color: #ffffff;
        }

        /* -------------------------------------
            RESPONSIVE
        ------------------------------------- */
        @media only screen and (max-width: 640px) {
            .wrapper {
                padding: 20px !important;
            }
            .content {
                padding: 7.5px !important;
            }
            .container {
                padding: 0 !important;
                width: 100% !important;
            }
            .btn table {
                max-width: 100% !important;
                width: 100% !important;
            }
            .btn a {
                font-size: 16px !important;
                max-width: 100% !important;
                width: 100% !important;
            }
        }

        /* -------------------------------------
            OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */
        .note {
            font-size: 14px;
            color: #6f6f6f;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .powered-by a {
            font-weight: bold;
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid transparent;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">

                <div class="header align-center">
                    <a href="<?= url() ?>">
                        <?php if(!empty(settings()->main->logo_email)): ?>
                            <img src="<?= \Altum\Uploads::get_full_url('logo_email') . settings()->main->logo_email ?>" class="logo" alt="<?= settings()->main->title ?>" />
                        <?php else: ?>
                            <h1><?= settings()->main->title ?></h1>
                        <?php endif ?>
                    </a>
                </div>

                <!-- START CENTERED WHITE CONTAINER -->
                <table role="presentation" class="main">

                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td>
                                        <?= $data->content ?>
                                    </td>
                                </tr>
                            </table>

                            <?php if($data->is_broadcast && !$data->is_system_email): ?>
                                <hr />
                                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="note align-center">
                                            <small><?= sprintf(l('global.emails.is_broadcast', $data->language), '<a href="' . url('account') . '">', '</a>') ?></small>
                                        </td>
                                    </tr>
                                </table>
                            <?php endif ?>

                            <?php if($data->anti_phishing_code): ?>
                                <hr />
                                <table width="100%" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="note align-center">
                                            <small><?= sprintf(l('global.emails.anti_phishing_code', $data->language), '<strong>' . $data->anti_phishing_code . '</strong>') ?></small>
                                        </td>
                                    </tr>
                                </table>
                            <?php endif ?>
                        </td>
                    </tr>

                    <!-- END MAIN CONTENT AREA -->
                </table>

                <!-- START FOOTER -->
                <div class="footer">
                    <p class="content-block powered-by mb0">
                        <?= sprintf(l('global.emails.copyright', $data->language), date('Y'), '<a href="' . url() . '">' . settings()->main->title . '</a>') ?>
                    </p>
                </div>
                <!-- END FOOTER -->

                <!-- SOCIALS -->
                <?php if(settings()->smtp->display_socials): ?>
                    <div class="content-block" style="text-align: center">
                        <?php foreach(require APP_PATH . 'includes/admin_socials.php' as $key => $value): ?>
                            <?php if(isset(settings()->socials->{$key}) && !empty(settings()->socials->{$key})): ?>
                                <a href="<?= sprintf($value['format'], settings()->socials->{$key}) ?>" style="padding-right: 5px; padding-left: 5px; line-height: 30px;text-decoration: none !important;" target="_blank" title="<?= $value['name'] ?>">
                                    <img src="<?= ASSETS_FULL_URL . 'images/email/' . $key . '.png' ?>" style="width: 20px; height: auto;" width="20" height="auto" alt="<?= $value['name'] ?>" />
                                </a>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                <?php endif ?>
                <!-- END SOCIALS -->

                <!-- COMPANY -->
                <?php if(settings()->smtp->company_details): ?>
                    <div class="footer">
                        <p class="content-block" style="text-align: center"><?= nl2br(settings()->smtp->company_details) ?></p>
                    </div>
                <?php endif ?>
                <!-- END COMPANY -->

                <!-- END CENTERED WHITE CONTAINER -->
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
