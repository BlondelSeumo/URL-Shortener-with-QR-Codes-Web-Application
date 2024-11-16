<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li><a href="<?= url('api-documentation') ?>"><?= l('api_documentation.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
                <li class="active" aria-current="page"><?= l('api_documentation.qr_codes') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <h1 class="h4 mb-4"><?= l('api_documentation.qr_codes') ?></h1>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#qr_codes_read_all" aria-expanded="true" aria-controls="qr_codes_read_all">
                        <?= l('api_documentation.read_all') ?>
                    </a>
                </h3>
            </div>

            <div id="qr_codes_read_all" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/qr-codes/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/qr-codes/' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>page</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td><?= l('api_documentation.filters.page') ?></td>
                            </tr>
                            <tr>
                                <td>results_per_page</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.filters.results_per_page'), '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250, 500, 1000]) . '</code>', 25) ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": [
        {
            "id": 1,
            "type": "url",
            "name": "Example name",
            "qr_code": "<?= SITE_URL ?>uploads/qr_code/example.svg",
            "qr_code_logo": null,
            "qr_code_background": null,
            "settings": {
                "foreground_type": "color",
                "foreground_color": "#000000",
                "background_color": "#ffffff",
                "custom_eyes_color": false,
                "qr_code_logo_size": 25,
                "size": 500,
                "margin": 0,
                "ecc": "L",
                "url": "https://example.com"
                ...
            },
            "embedded_data": "https://example.com",
            "last_datetime": "2021-10-31 09:47:25",
            "datetime": "2021-10-29 16:32:25"
        },
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/qr-codes?&page=1",
        "last": "<?= SITE_URL ?>api/qr-codes?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/qr-codes?&page=1"
    }
}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#qr_codes_read" aria-expanded="true" aria-controls="qr_codes_read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="qr_codes_read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/qr-codes/</span><span class="text-primary">{qr_code_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/qr-codes/<span class="text-primary">{qr_code_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 1,
        "type": "url",
        "name": "Example name",
        "qr_code": "<?= SITE_URL ?>uploads/qr_code/example.svg",
        "qr_code_logo": null,
        "qr_code_background": null,
        "settings": {
            "foreground_type": "color",
            "foreground_color": "#000000",
            "background_color": "#ffffff",
            "custom_eyes_color": false,
            "qr_code_logo_size": 25,
            "size": 500,
            "margin": 0,
            "ecc": "L",
            "url": "https://example.com"
            ...
        },
        "embedded_data": "https://example.com",
        "last_datetime": "2021-10-31 09:47:25",
        "datetime": "2021-10-29 16:32:25"
    }
}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#qr_codes_create" aria-expanded="true" aria-controls="qr_codes_create">
                        <?= l('api_documentation.create') ?>
                    </a>
                </h3>
            </div>

            <div id="qr_codes_create" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/qr-codes</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>project_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>name</td>
                                <td>
                                    <span class="badge badge-danger"><?= l('api_documentation.required') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>type</td>
                                <td>
                                    <span class="badge badge-danger"><?= l('api_documentation.required') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys((require APP_PATH . 'includes/enabled_qr_codes.php')))) ?></td>
                            </tr>
                            <tr>
                                <td>is_bulk</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = text</span>') ?></td>
                            </tr>
                            <tr>
                                <td>style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['square', 'dot', 'round', 'diamond', 'heart'])) ?></td>
                            </tr>
                            <tr>
                                <td>inner_eye_style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['square', 'dot', 'rounded', 'diamond', 'flower', 'leaf',])) ?></td>
                            </tr>
                            <tr>
                                <td>outer_eye_style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['square', 'circle', 'rounded', 'flower', 'leaf',])) ?></td>
                            </tr>
                            <tr>
                                <td>foreground_type</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['color', 'gradient'])) ?></td>
                            </tr>
                            <tr>
                                <td>foreground_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">foreground_type = color</span>') ?></td>
                            </tr>
                            <tr>
                                <td>foreground_gradient_style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial'])) ?></td>
                            </tr>
                            <tr>
                                <td>foreground_gradient_one</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">foreground_type = gradient</span>') ?></td>
                            </tr>
                            <tr>
                                <td>foreground_gradient_two</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">foreground_type = gradient</span>') ?></td>
                            </tr>
                            <tr>
                                <td>background_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>background_color_transparency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>custom_eyes_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>eyes_inner_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">custom_eyes_color = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>eyes_outer_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">custom_eyes_color = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>qr_code_logo</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.file') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>qr_code_logo_size</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>5-35</td>
                            </tr>
                            <tr>
                                <td>qr_code_background</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.file') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>qr_code_background_transparency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>0-100</td>
                            </tr>
                            <tr>
                                <td>qr_code_foreground</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.file') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>qr_code_foreground_transparency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>0-100</td>
                            </tr>
                            <tr>
                                <td>frame</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys(require APP_PATH . 'includes/qr_codes_frames.php'))) ?></td>
                            </tr>
                            <tr>
                                <td>frame_text</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>frame_text_size</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>-5 - 5</td>
                            </tr>
                            <tr>
                                <td>frame_text_font</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys(require APP_PATH . 'includes/qr_codes_frames_text_fonts.php'))) ?></td>
                            </tr>
                            <tr>
                                <td>frame_custom_colors</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>frame_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">frame_custom_colors = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>frame_text_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">frame_custom_colors = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>size</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>50-2000</td>
                            </tr>
                            <tr>
                                <td>margin</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>0-25</td>
                            </tr>
                            <tr>
                                <td>ecc</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['L', 'M', 'Q', 'H'])) ?></td>
                            </tr>
                            <tr>
                                <td>text</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = text</span>') ?></td>
                            </tr>
                            <tr>
                                <td>url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = url</span>') ?></td>
                            </tr>
                            <tr>
                                <td>phone</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = phone</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".sms_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    sms
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = sms</span>') ?></td>
                            </tr>
                            <tr class="collapse sms_collapse">
                                <td>sms_body</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = sms</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".email_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    email
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = email</span>') ?></td>
                            </tr>
                            <tr class="collapse email_collapse">
                                <td>email_subject</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = email</span>') ?></td>
                            </tr>
                            <tr class="collapse email_collapse">
                                <td>email_body</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = email</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".whatsapp_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    whatsapp
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = whatsapp</span>') ?></td>
                            </tr>
                            <tr class="collapse whatsapp_collapse">
                                <td>whatsapp_body</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = whatsapp</span>') ?></td>
                            </tr>
                            <tr>
                                <td>facetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = facetime</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".location_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    location
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse location_collapse">
                                <td>location_latitude</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span></td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = location</span>') ?></td>
                            </tr>
                            <tr class="collapse location_collapse">
                                <td>location_longitude</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span></td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = location</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".wifi_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    wifi
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_ssid</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = wifi</span>') ?></td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_encryption</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['nopass', 'WEP', 'WPA/WPA2', 'H'])) ?> (type=wifi)</td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_password</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = wifi</span>') ?></td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_is_hidden</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span></td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = wifi</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".event_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    event
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_location</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_note</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_timezone</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_start_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_end_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_first_alert_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_second_alert_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".crypto_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    crypto
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse crypto_collapse">
                                <td>crypto_coin</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = crypto</span>') ?></td>
                            </tr>
                            <tr class="collapse crypto_collapse">
                                <td>crypto_address</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = crypto</span>') ?></td>
                            </tr>
                            <tr class="collapse crypto_collapse">
                                <td>crypto_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = crypto</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".vcard_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    vcard
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_first_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_last_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_company</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_phone_number_label[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_phone_number_value[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_email</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_job_title</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_birthday</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_street</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_city</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_zip</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_region</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_country</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_note</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_social_label[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_social_value[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".paypal_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    paypal
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_type</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['buy_now', 'add_to_cart', 'donation'])) ?> (type=paypal)</td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_email</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_title</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_price</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_thank_you_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_cancel_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".upi_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    upi
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_payee_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_payee_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_transaction_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_transaction_note</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_transaction_reference</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_thank_you_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".epc_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    epc
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_iban</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_payee_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_bic</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_remittance_reference</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_remittance_text</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>information</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".pix_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    pix
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_payee_key</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_payee_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_city</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_transaction_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_description</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>

                    <div class="collapse" id="text_create"></div>
                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>api/qr-codes' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'name=<span class="text-primary">New York</span>' \<br />
                                --form 'type=<span class="text-primary">text</span>' \<br />
                                --form 'text=<span class="text-primary">Hello!</span>' \<br />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 1
    }
}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#qr_codes_update" aria-expanded="true" aria-controls="qr_codes_update">
                        <?= l('api_documentation.update') ?>
                    </a>
                </h3>
            </div>

            <div id="qr_codes_update" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>api/qr-codes/</span><span class="text-primary">{qr_code_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive table-custom-container mb-4">
                        <table class="table table-custom">
                            <thead>
                            <tr>
                                <th><?= l('api_documentation.parameters') ?></th>
                                <th><?= l('global.details') ?></th>
                                <th><?= l('global.description') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>project_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>type</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys((require APP_PATH . 'includes/enabled_qr_codes.php')))) ?></td>
                            </tr>
                            <tr>
                                <td>style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['square', 'dot', 'round', 'diamond', 'heart'])) ?></td>
                            </tr>
                            <tr>
                                <td>inner_eye_style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['square', 'dot', 'rounded', 'diamond', 'flower', 'leaf',])) ?></td>
                            </tr>
                            <tr>
                                <td>outer_eye_style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['square', 'circle', 'rounded', 'flower', 'leaf',])) ?></td>
                            </tr>
                            <tr>
                                <td>foreground_type</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['color', 'gradient'])) ?></td>
                            </tr>
                            <tr>
                                <td>foreground_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">foreground_type = color</span>') ?></td>
                            </tr>
                            <tr>
                                <td>foreground_gradient_style</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial'])) ?></td>
                            </tr>
                            <tr>
                                <td>foreground_gradient_one</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">foreground_type = gradient</span>') ?></td>
                            </tr>
                            <tr>
                                <td>foreground_gradient_two</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">foreground_type = gradient</span>') ?></td>
                            </tr>
                            <tr>
                                <td>background_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>background_color_transparency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>0 - 100</td>
                            </tr>
                            <tr>
                                <td>custom_eyes_color</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>eyes_inner_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">custom_eyes_color = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>eyes_outer_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">custom_eyes_color = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>qr_code_logo</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.file') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>qr_code_logo_size</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span></td>
                                <td>5-35</td>
                            </tr>
                            <tr>
                                <td>qr_code_background</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.file') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>qr_code_background_transparency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>0-100</td>
                            </tr>
                            <tr>
                                <td>qr_code_foreground</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.file') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>qr_code_foreground_transparency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>0-100</td>
                            </tr>
                            <tr>
                                <td>frame</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys(require APP_PATH . 'includes/qr_codes_frames.php'))) ?></td>
                            </tr>
                            <tr>
                                <td>frame_text</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>frame_text_size</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span>
                                </td>
                                <td>-5 -> 5</td>
                            </tr>
                            <tr>
                                <td>frame_text_font</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', array_keys(require APP_PATH . 'includes/qr_codes_frames_text_fonts.php'))) ?></td>
                            </tr>
                            <tr>
                                <td>frame_custom_colors</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>frame_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">frame_custom_colors = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>frame_text_color</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">frame_custom_colors = 1</span>') ?></td>
                            </tr>
                            <tr>
                                <td>size</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span></td>
                                <td>50-2000</td>
                            </tr>
                            <tr>
                                <td>margin</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.int') ?></span></td>
                                <td>0-25</td>
                            </tr>
                            <tr>
                                <td>ecc</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['L', 'M', 'Q', 'H'])) ?></td>
                            </tr>
                            <tr>
                                <td>text</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = text</span>') ?></td>
                            </tr>
                            <tr>
                                <td>url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = url</span>') ?></td>
                            </tr>
                            <tr>
                                <td>phone</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = phone</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".sms_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    sms
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = sms</span>') ?></td>
                            </tr>
                            <tr class="collapse sms_collapse">
                                <td>sms_body</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = sms</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".email_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    email
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = email</span>') ?></td>
                            </tr>
                            <tr class="collapse email_collapse">
                                <td>email_subject</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = email</span>') ?></td>
                            </tr>
                            <tr class="collapse email_collapse">
                                <td>email_body</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = email</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".whatsapp_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    whatsapp
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = whatsapp</span>') ?></td>
                            </tr>
                            <tr class="collapse whatsapp_collapse">
                                <td>whatsapp_body</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = whatsapp</span>') ?></td>
                            </tr>
                            <tr>
                                <td>facetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = facetime</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".location_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    location
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse location_collapse">
                                <td>location_latitude</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span></td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = location</span>') ?></td>
                            </tr>
                            <tr class="collapse location_collapse">
                                <td>location_longitude</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span></td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = location</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".wifi_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    wifi
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_ssid</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = wifi</span>') ?></td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_encryption</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['nopass', 'WEP', 'WPA/WPA2', 'H'])) ?> (type=wifi)</td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_password</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = wifi</span>') ?></td>
                            </tr>
                            <tr class="collapse wifi_collapse">
                                <td>wifi_is_hidden</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span> <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span></td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = wifi</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".event_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    event
                                </td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_location</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_note</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_timezone</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_start_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_end_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_first_alert_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr class="collapse event_collapse">
                                <td>event_second_alert_datetime</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = event</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".crypto_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    crypto
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse crypto_collapse">
                                <td>crypto_coin</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = crypto</span>') ?></td>
                            </tr>
                            <tr class="collapse crypto_collapse">
                                <td>crypto_address</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = crypto</span>') ?></td>
                            </tr>
                            <tr class="collapse crypto_collapse">
                                <td>crypto_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = crypto</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".vcard_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    vcard
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_first_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_last_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_company</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_phone_number_label[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_phone_number_value[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_email</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_job_title</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_birthday</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_street</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_city</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_zip</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_region</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_country</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_note</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_social_label[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr class="collapse vcard_collapse">
                                <td>vcard_social_value[index]</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = vcard</span>') ?></td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".paypal_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    paypal
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_type</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['buy_now', 'add_to_cart', 'donation'])) ?> (type=paypal)</td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_email</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_title</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_price</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_thank_you_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>
                            <tr class="collapse paypal_collapse">
                                <td>paypal_cancel_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = paypal</span>') ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".upi_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    upi
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_payee_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_payee_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_transaction_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_transaction_note</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_transaction_reference</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>
                            <tr class="collapse upi_collapse">
                                <td>upi_thank_you_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = upi</span>') ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".epc_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    epc
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_iban</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_payee_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_bic</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_remittance_reference</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>epc_remittance_text</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>
                            <tr class="collapse epc_collapse">
                                <td>information</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = epc</span>') ?></td>
                            </tr>

                            <tr>
                                <td>
                                    <a href="#" class="badge badge-light mr-1" data-toggle="collapse" data-target=".pix_collapse" data-tooltip title="<?= l('global.view') ?>" data-tooltip-hide-on-click>
                                        <i class="fas fa-fw fa-plus"></i>
                                    </a>
                                    pix
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_payee_key</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_payee_name</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_currency</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_amount</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.float') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_city</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_transaction_id</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            <tr class="collapse pix_collapse">
                                <td>pix_description</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.available_when'), '<span class="badge badge-light">type = pix</span>') ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>api/qr-codes/<span class="text-primary">{qr_code_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'name=<span class="text-primary">Las Vegas</span>' \<br />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 1
    }
}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#qr_codes_delete" aria-expanded="true" aria-controls="qr_codes_delete">
                        <?= l('api_documentation.delete') ?>
                    </a>
                </h3>
            </div>

            <div id="qr_codes_delete" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>api/qr-codes/</span><span class="text-primary">{qr_code_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request DELETE \<br />
                                --url '<?= SITE_URL ?>api/qr-codes/<span class="text-primary">{qr_code_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require THEME_PATH . 'views/partials/shiki_highlighter.php' ?>
