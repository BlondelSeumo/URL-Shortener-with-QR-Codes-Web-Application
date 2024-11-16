<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.png_to_webp.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.png_to_webp.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.png_to_webp.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="form-group">
                    <label for="image"><i class="fas fa-fw fa-sm fa-image text-muted mr-1"></i> <?= l('global.image') ?></label>
                    <input id="image" type="file" name="image" accept=".png" class="form-control-file altum-file-input <?= \Altum\Alerts::has_field_errors('image') ? 'is-invalid' : null ?>" />
                    <?= \Altum\Alerts::output_field_error('image') ?>
                    <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), '.png') ?></small>
                </div>

                <div class="form-group">
                    <label for="quality"><i class="fas fa-fw fa-sort-numeric-up fa-sm text-muted mr-1"></i> <?= l('tools.quality') ?></label>
                    <input type="number" min="1" max="100" id="quality" name="quality" class="form-control <?= \Altum\Alerts::has_field_errors('quality') ? 'is-invalid' : null ?>" value="<?= $data->values['quality'] ?>" required="required" />
                    <?= \Altum\Alerts::output_field_error('quality') ?>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <div id="result_wrapper" class="mt-4 d-none">
        <div class="card">
            <div class="card-body">
                <div class="text-center">
                    <img id="preview" src="" class="img-fluid mb-3" style="max-height: 20rem;" />
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <label for="result"><?= l('tools.result') ?></label>
                    <div>
                        <a
                                href=""
                                target="_blank"
                                class="btn btn-link text-secondary"
                                data-toggle="tooltip"
                                title="<?= l('global.download') ?>"
                                download="<?= l('tools.result') . '.webp' ?>"
                                id="download"
                        >
                            <i class="fas fa-fw fa-sm fa-download"></i>
                        </a>
                    </div>
                </div>
            </div>
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

    let convert = () => {
        pause_submit_button(document.querySelector('[type="submit"][name="submit"]'));

        const file = document.getElementById('image').files[0];
        const quality = parseInt(document.getElementById('quality').value) / 100;

        if(!file) {
            /* Hide result wrapper */
            document.querySelector('#result_wrapper').classList.add('d-none');
            return;
        }

        /* Display result wrapper */
        document.querySelector('#result_wrapper').classList.remove('d-none');

        /* Initiate file reader */
        let file_reader = new FileReader();

        /* Input the content of the uploaded file */
        file_reader.readAsDataURL(file);

        /* Onload */
        file_reader.onload = function(event) {

            /* Create the image object */
            let image = new Image;
            image.crossOrigin = 'anonymous';

            /* Onload */
            image.onload = function() {
                /* Get original width & height */
                let width  = image.naturalWidth  || image.width;
                let height = image.naturalHeight || image.height;

                /* Create canvas */
                let canvas = document.createElement('canvas');

                canvas.width = width;
                canvas.height = height;

                /* Draw image */
                let context = canvas.getContext('2d');
                context.drawImage(image, 0, 0, width, height);

                /* Generate new image data */
                let new_image_data = canvas.toDataURL(`image/webp`, quality);

                /* Display it */
                document.querySelector('#preview').setAttribute('src', new_image_data);

                /* Prepare download button */
                document.querySelector('#download').href = new_image_data;

                enable_submit_button(document.querySelector('[type="submit"][name="submit"]'));
            }

            /* Trigger the onload function */
            image.src = event.target.result;

        }

    }

    document.getElementById('image').addEventListener('change', convert);
    document.getElementById('quality').addEventListener('change', convert);
    document.querySelector('form').addEventListener('submit', event => {
       event.preventDefault();
       convert();
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
