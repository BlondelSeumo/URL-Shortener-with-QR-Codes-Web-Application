<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url() ?>"><?= l('index.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li><a href="<?= url('api-documentation') ?>"><?= l('api_documentation.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('api_documentation.signatures') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <h1 class="h4 mb-4"><?= l('api_documentation.signatures') ?></h1>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#signatures_read_all" aria-expanded="true" aria-controls="signatures_read_all">
                        <?= l('api_documentation.read_all') ?>
                    </a>
                </h3>
            </div>

            <div id="signatures_read_all" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/signatures/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/signatures/' \<br />
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
			"project_id": 0,
			"name": "Jane Doe",
			"template": "mars",
			"settings": {
				"direction": "ltr",
				"is_removed_branding": true,
				"image_url": "",
				"sign_off": "Regards,",
				"full_name": "Jane Doe",
				"job_title": "CEO",
				"department": "Head of department",
				"company": "Example Company LTD",
				"email": "hello@micu.com",
				"website_url": "https:\/\/micupharma.com\/",
				"address": "Example Street, nr. 18",
				"address_url": "",
				"phone_number": "",
				"whatsapp": "",
				"facebook_messenger": "",
				"telegram": "",
				"disclaimer": "",
				"font_family": "arial",
				"font_size": 14,
				"width": 500,
				"image_width": 50,
				"socials_width": 20,
				"socials_padding": 10,
				"separator_size": 0,
				"theme_color": "#000000",
				"full_name_color": "#000000",
				"text_color": "#000000",
				"link_color": "#000000",
				"facebook": "micu",
				"twitter": "micu",
				"instagram": "micu",
				"youtube": "micu",
				"tiktok": "",
				"spotify": "",
				"pinterest": "",
				"linkedin": "",
				"snapchat": "",
				"twitch": "",
				"discord": "",
				"github": "",
				"reddit": ""
			},
			"last_datetime": "2023-01-22 15:49:06",
			"datetime": "2023-01-17 20:38:51"
		}
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>api/signatures?&page=1",
        "last": "<?= SITE_URL ?>api/signatures?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>api/signatures?&page=1"
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#signatures_read" aria-expanded="true" aria-controls="signatures_read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="signatures_read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>api/signatures/</span><span class="text-primary">{signature_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>api/signatures/<span class="text-primary">{signature_id}</span>' \<br />
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
        "project_id": 0,
        "name": "Jane Doe",
        "template": "mars",
        "settings": {
            "direction": "ltr",
            "is_removed_branding": true,
            "image_url": "",
            "sign_off": "Regards,",
            "full_name": "Jane Doe",
            "job_title": "CEO",
            "department": "Head of department",
            "company": "Example Company LTD",
            "email": "hello@micu.com",
            "website_url": "https:\/\/micupharma.com\/",
            "address": "Example Street, nr. 18",
            "address_url": "",
            "phone_number": "",
            "whatsapp": "",
            "facebook_messenger": "",
            "telegram": "",
            "disclaimer": "",
            "font_family": "arial",
            "font_size": 14,
            "width": 500,
            "image_width": 50,
            "socials_width": 20,
            "socials_padding": 10,
            "separator_size": 0,
            "theme_color": "#000000",
            "full_name_color": "#000000",
            "text_color": "#000000",
            "link_color": "#000000",
            "facebook": "micu",
            "twitter": "micu",
            "instagram": "micu",
            "youtube": "micu",
            "tiktok": "",
            "spotify": "",
            "pinterest": "",
            "linkedin": "",
            "snapchat": "",
            "twitch": "",
            "discord": "",
            "github": "",
            "reddit": ""
        },
        "last_datetime": "2023-01-22 15:49:06",
        "datetime": "2023-01-17 20:38:51"
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#signatures_delete" aria-expanded="true" aria-controls="signatures_delete">
                        <?= l('api_documentation.delete') ?>
                    </a>
                </h3>
            </div>

            <div id="signatures_delete" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>api/signatures/</span><span class="text-primary">{signature_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request DELETE \<br />
                                --url '<?= SITE_URL ?>api/signatures/<span class="text-primary">{signature_id}</span>' \<br />
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
