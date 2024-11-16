<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.color_picker.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.color_picker.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.color_picker.description') ?>">
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
                    <label for="color"><i class="fas fa-fw fa-palette fa-sm text-muted mr-1"></i> <?= l('tools.color_picker.color') ?></label>
                    <input type="text" id="color" name="color" class="form-control" value="<?= $data->values['color'] ?>" required="required" />
                </div>
            </form>

        </div>
    </div>

    <div id="result_wrapper" class="mt-4 d-none">
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <tbody>
                <tr>
                    <td class="font-weight-bold">
                        HEXA
                    </td>
                    <td class="text-nowrap" id="hexa"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="hexa_button"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        CMYK
                    </td>
                    <td class="text-nowrap" id="cmyk"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="cmyk_button"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        HSLA
                    </td>
                    <td class="text-nowrap" id="hsla"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="hsla_button"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        HSVA
                    </td>
                    <td class="text-nowrap" id="hsva"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="hsva_button"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="font-weight-bold">
                        RGBA
                    </td>
                    <td class="text-nowrap" id="rgba"></td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <button
                                    type="button"
                                    class="btn btn-link text-secondary"
                                    data-toggle="tooltip"
                                    title="<?= l('global.clipboard_copy') ?>"
                                    aria-label="<?= l('global.clipboard_copy') ?>"
                                    data-copy="<?= l('global.clipboard_copy') ?>"
                                    data-copied="<?= l('global.clipboard_copied') ?>"
                                    data-clipboard-text=""
                                    id="rgba_button"
                            >
                                <i class="fas fa-fw fa-sm fa-copy"></i>
                            </button>
                        </div>
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

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/pickr.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/pickr.min.js?v=' . PRODUCT_CODE ?>"></script>
<script>
    /* Initiate the color picker */
    let pickr_options = {
        comparison: false,
        components: {
            preview: true,
            opacity: true,
            hue: true,
            comparison: false,
            interaction: {
                hex: true,
                rgba: true,
                hsla: true,
                hsva: true,
                cmyk: true,
                input: true,
                clear: false,
                save: false,
            }
        }
    };

    let pickr = Pickr.create({
        el: '#color',
        default: document.querySelector('#color').value,
        ...pickr_options
    });

    pickr.on('change', hsva => {
        document.querySelector('#result_wrapper').classList.remove('d-none');
        document.querySelector('#hexa').innerHTML = hsva.toHEXA().toString(0);
        document.querySelector('#hexa_button').setAttribute('data-clipboard-text', hsva.toHEXA().toString(0));
        document.querySelector('#cmyk').innerHTML = hsva.toCMYK().toString(0);
        document.querySelector('#cmyk_button').setAttribute('data-clipboard-text', hsva.toCMYK().toString(0));
        document.querySelector('#hsla').innerHTML = hsva.toHSLA().toString(0);
        document.querySelector('#hsla_button').setAttribute('data-clipboard-text', hsva.toHSLA().toString(0));
        document.querySelector('#hsva').innerHTML = hsva.toHSVA().toString(0);
        document.querySelector('#hsva_button').setAttribute('data-clipboard-text', hsva.toHSVA().toString(0));
        document.querySelector('#rgba').innerHTML = hsva.toRGBA().toString(0);
        document.querySelector('#rgba_button').setAttribute('data-clipboard-text', hsva.toRGBA().toString(0));
    });

    document.querySelector('form').addEventListener('submit', event => {
        event.preventDefault();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
