<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.password_strength_checker.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.password_strength_checker.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.password_strength_checker.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="password"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('global.password') ?></label>
                    <input type="text" id="password" name="password" class="form-control <?= \Altum\Alerts::has_field_errors('password') ? 'is-invalid' : null ?>" value="<?= $data->values['password'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('password') ?>
                </div>
            </form>

        </div>
    </div>

    <div class="mt-4">
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <tbody>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.password_strength_checker.characters') ?>
                    </td>
                    <td class="text-nowrap">
                        <span id="characters"></span>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        <?= l('tools.password_strength_checker.strength') ?>
                    </td>
                    <td class="text-nowrap">
                        <span id="strength"></span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>

<?php ob_start() ?>
<script>
    'use strict';

    let password_strength_check = () => {
        let password = document.querySelector('#password').value;

        /* Generate the password score */
        let password_score = 0
        let password_minimum_length = 8;

        let regexLower = new RegExp('(?=.*[a-z])');
        let regexUpper = new RegExp('(?=.*[A-Z])');
        let regexDigits = new RegExp('(?=.*[0-9])');
        let regexLength = new RegExp('(?=.{' + password_minimum_length + ',})');

        if(password.match(regexLower)) { ++password_score }
        if(password.match(regexUpper)) { ++password_score }
        if(password.match(regexDigits)) { ++password_score }
        if(password.match(regexLength)) { ++password_score }

        if(password_score === 0 && password.length > 0) { ++password_score }

        switch(password_score) {
            case 1:
                document.querySelector('#strength').textContent = <?= json_encode(l('tools.password_strength_checker.strength.very_low')) ?>;
                document.querySelector('#strength').setAttribute('class', 'text-danger');
                break
            case 2:
                document.querySelector('#strength').textContent = <?= json_encode(l('tools.password_strength_checker.strength.low')) ?>;
                document.querySelector('#strength').setAttribute('class', 'text-warning');
                break
            case 3:
                document.querySelector('#strength').textContent = <?= json_encode(l('tools.password_strength_checker.strength.moderate')) ?>;
                document.querySelector('#strength').setAttribute('class', 'text-info');
                break
            case 4:
                document.querySelector('#strength').textContent = <?= json_encode(l('tools.password_strength_checker.strength.strong')) ?>;
                document.querySelector('#strength').setAttribute('class', 'text-success');
                break
            default:
                document.querySelector('#strength').textContent = <?= json_encode(l('tools.password_strength_checker.strength.no_data')) ?>;
                document.querySelector('#strength').removeAttribute('class');
        }

        document.querySelector('#characters').textContent = password.length;
    }

    ['change', 'paste', 'keyup'].forEach(event_type => document.querySelector('#password').addEventListener(event_type, password_strength_check));

    password_strength_check();
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
