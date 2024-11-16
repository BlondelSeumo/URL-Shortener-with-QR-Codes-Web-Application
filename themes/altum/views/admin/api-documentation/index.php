<?php defined('ALTUMCODE') || die() ?>

<div class="mb-4">
    <h1 class="h3 m-0"><i class="fas fa-fw fa-xs fa-code text-primary-900 mr-2"></i> <?= sprintf(l('admin_api_documentation.header')) ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card mb-5">
    <div class="card-body">
        <p class="text-muted"><?= l('admin_api_documentation.subheader') ?></p>

        <div class="form-group">
            <label for="api_key"><?= l('api_documentation.api_key') ?></label>
            <input type="text" id="api_key" value="<?= $this->user->api_key ?>" class="form-control" onclick="this.select();" readonly="readonly" />
        </div>

        <div class="form-group">
            <label for="base_url"><?= l('api_documentation.base_url') ?></label>
            <input type="text" id="base_url" value="<?= SITE_URL . 'admin-api' ?>" class="form-control" onclick="this.select();" readonly="readonly" />
        </div>
    </div>
</div>


<h2 class="h4 mb-4"><?= l('api_documentation.authentication.header') ?></h2>

<div class="card mb-5">
    <div class="card-body">
        <p class="text-muted"><?= l('api_documentation.authentication.subheader') ?></p>

        <div class="form-group">
            <label><?= l('api_documentation.example') ?></label>
            <div class="card bg-gray-200 border-0">
                <div class="card-body">
                    curl --request GET \<br />
                    --url '<?= SITE_URL . 'admin-api/' ?><span class="text-primary">{endpoint}</span>' \<br />
                    --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-5">
    <div class="mb-3">
        <h2 class="h4"><?= l('admin_api_documentation.users.header') ?></h2>
    </div>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#users_read_all" aria-expanded="true" aria-controls="users_read_all">
                        <?= l('api_documentation.read_all') ?>
                    </a>
                </h3>
            </div>

            <div id="users_read_all" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>admin-api/users/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>admin-api/users/' \<br />
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
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('api_documentation.filters.page') ?></td>
                            </tr>
                            <tr>
                                <td>results_per_page</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= sprintf(l('api_documentation.filters.results_per_page'), '<code>' . implode('</code> , <code>', [10, 25, 50, 100, 250, 500, 1000]) . '</code>', 25) ?></td>
                            </tr>
                            <tr>
                                <td>search</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('api_documentation.filters.search') ?></td>
                            </tr>
                            <tr>
                                <td>search_by</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= sprintf(l('api_documentation.filters.search_by'), '<code>' . implode('</code> , <code>', ['name', 'email']) . '</code>') ?></td>
                            </tr>
                            <tr>
                                <td>order_by</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= sprintf(l('api_documentation.filters.order_by'), '<code>' . implode('</code> , <code>', ['email', 'datetime', 'last_activity', 'name', 'total_logins']) . '</code>') ?></td>
                            </tr>
                            <tr>
                                <td>order_by_type</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('api_documentation.filters.order_by_type') ?></td>
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
            "type": "1",
            "name": "Example",
            "email": "hey@example.com",
            "language": "english",
            "timezone": "Europe\/Bucharest",
            "twofa": false,
            "anti_phishing_code": true,
            "is_newsletter_subscribed": true,
            "billing": {
                "type": "business",
                "name": "John",
                "address": "Lorem Ipsum",
                "city": "Dolor",
                "county": "Sit",
                "zip": "3000",
                "country": "US",
                "phone": "+40404040",
                "tax_id": "DD12345"
            },
            "status": true,
            "plan_id": "custom",
            "plan_expiration_date": "2023-03-08 00:00:00",
            "plan_settings": {
                ...
            },
            "plan_trial_done": false,
            "plan_expiry_reminder": false,
            "payment_processor": "paypal",
            "payment_total_amount": "9.95",
            "payment_currency": "USD",
            "payment_subscription_id": "sub_123123",
            "user_deletion_reminder": false,
            "source": "direct",
            "ip": "127.0.0.1",
            "continent_code": "NA",
            "country": "US",
            "city_name": "New-York",
            "api_key": "123456789",
            "referral_key": "987654321",
            "referred_by": null,
            "referred_by_has_converted": false,
            "last_activity": "2023-01-21 00:25:28",
            "total_logins": 1,
            "datetime": "2019-08-22 19:21:23"
        }
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>admin-api/users?&page=1",
        "last": "<?= SITE_URL ?>admin-api/users?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>admin-api/users?&page=1"
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#users_read" aria-expanded="true" aria-controls="users_read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="users_read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>admin-api/users/</span><span class="text-primary">{user_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>admin-api/users/<span class="text-primary">{user_id}</span>' \<br />
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
        "type": "1",
        "name": "Example",
        "email": "hey@example.com",
        "language": "english",
        "timezone": "Europe\/Bucharest",
        "twofa": false,
        "anti_phishing_code": true,
        "is_newsletter_subscribed": true,
        "billing": {
            "type": "business",
            "name": "John",
            "address": "Lorem Ipsum",
            "city": "Dolor",
            "county": "Sit",
            "zip": "3000",
            "country": "US",
            "phone": "+40404040",
            "tax_id": "DD12345"
        },
        "status": true,
        "plan_id": "custom",
        "plan_expiration_date": "2023-03-08 00:00:00",
        "plan_settings": {
            ...
        },
        "plan_trial_done": false,
        "plan_expiry_reminder": false,
        "payment_processor": "paypal",
        "payment_total_amount": "9.95",
        "payment_currency": "USD",
        "payment_subscription_id": "sub_123123",
        "user_deletion_reminder": false,
        "source": "direct",
        "ip": "127.0.0.1",
        "continent_code": "NA",
        "country": "US",
        "city_name": "New-York",
        "api_key": "123456789",
        "referral_key": "987654321",
        "referred_by": null,
        "referred_by_has_converted": false,
        "last_activity": "2023-01-21 00:25:28",
        "total_logins": 1,
        "datetime": "2019-08-22 19:21:23"
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#users_create" aria-expanded="true" aria-controls="users_create">
                        <?= l('api_documentation.create') ?>
                    </a>
                </h3>
            </div>

            <div id="users_create" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>admin-api/users</span>
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
                                <td>name</td>
                                <td><span class="badge badge-danger"><?= l('api_documentation.required') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>email</td>
                                <td><span class="badge badge-danger"><?= l('api_documentation.required') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>password</td>
                                <td><span class="badge badge-danger"><?= l('api_documentation.required') ?></span></td>
                                <td>-</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>admin-api/users' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'name=<span class="text-primary">John Doe</span>' \<br />
                                --form 'email=<span class="text-primary">john@example.com</span>' \<br />
                                --form 'password=<span class="text-primary">MyStrongPassword123</span>'
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 2
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#users_update" aria-expanded="true" aria-controls="users_update">
                        <?= l('api_documentation.update') ?>
                    </a>
                </h3>
            </div>

            <div id="users_update" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>admin-api/users/</span><span class="text-primary">{user_id}</span>
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
                                <td>name</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>email</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>password</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>status</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('admin_api_documentation.users.update.status') ?></td>
                            </tr>
                            <tr>
                                <td>type</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('admin_api_documentation.users.update.type') ?></td>
                            </tr>
                            <tr>
                                <td>plan_id</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('admin_api_documentation.users.update.plan_id') ?></td>
                            </tr>
                            <tr>
                                <td>plan_expiration_date</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('admin_api_documentation.users.update.plan_expiration_date') ?></td>
                            </tr>
                            <tr>
                                <td>plan_trial_done</td>
                                <td><span class="badge badge-info"><?= l('api_documentation.optional') ?></span></td>
                                <td><?= l('admin_api_documentation.users.update.plan_trial_done') ?></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>admin-api/users/<span class="text-primary">{user_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'name=<span class="text-primary">Jane Doe</span>' \<br />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "id": 2
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#users_delete" aria-expanded="true" aria-controls="users_delete">
                        <?= l('api_documentation.delete') ?>
                    </a>
                </h3>
            </div>

            <div id="users_delete" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>admin-api/users/</span><span class="text-primary">{user_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request DELETE \<br />
                                --url '<?= SITE_URL ?>admin-api/users/<span class="text-primary">{user_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#users_one_time_login_code" aria-expanded="true" aria-controls="users_one_time_login_code">
                        <?= l('admin_api_documentation.users.one_time_login_code_header') ?>
                    </a>
                </h3>
            </div>

            <div id="users_one_time_login_code" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>admin-api/users/</span><span class="text-primary">{user_id}</span><span class="text-muted">/one-time-login-code</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>admin-api/users/<span class="text-primary">{user_id}</span>/one-time-login-code' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": {
        "one_time_login_code": "7be875f9f1e3e73e1c7a09f186f6b69c",
        "url": "<?= SITE_URL ?>login/one-time-login-code/7be875f9f1e3e73e1c7a09f186f6b69c",
        "id": "1"
    }
}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>


<div class="mb-5">
    <div class="mb-3">
        <h2 class="h4"><?= l('admin_api_documentation.plans.header') ?></h2>
    </div>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#plans_read_all" aria-expanded="true" aria-controls="plans_read_all">
                        <?= l('api_documentation.read_all') ?>
                    </a>
                </h3>
            </div>

            <div id="plans_read_all" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>admin-api/plans/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>admin-api/plans/' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.response') ?></label>
                        <div data-shiki="json">
{
    "data": [
        {
            "id": 1,
            "name": "Golden",
            "description": "The best plan.",
            "prices": {
                "monthly": {
                    "USD": 5
                },
                "annual": {
                    "USD": 50
                },
                "lifetime": {
                    "USD": 500
                },
            },
            "trial_days": 7,
            "settings": {
                ...
            },
            "taxes_ids": [],
            "color": "",
            "status": 1,
            "order": 1,
            "datetime": "2023-01-01 12:00:00"
        }
    ]
}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#plans_read" aria-expanded="true" aria-controls="plans_read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="plans_read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>admin-api/plans/</span><span class="text-primary">{plan_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-200 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>admin-api/plans/<span class="text-primary">{plan_id}</span>' \<br />
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
        "name": "Golden",
        "description": "The best plan.",
        "prices": {
            "monthly": {
                "USD": 5
            },
            "annual": {
                "USD": 50
            },
            "lifetime": {
                "USD": 500
            },
        },
        "trial_days": 7,
        "settings": {
            ...
        },
        "taxes_ids": [],
        "color": "",
        "status": 1,
        "order": 1,
        "datetime": "2023-01-01 12:00:00"
    }
}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="mb-5">
    <div class="mb-3">
        <h2 class="h4"><?= l('admin_api_documentation.domains.header') ?></h2>
    </div>

    <div class="accordion">
        <div class="card">
            <div class="card-header bg-white p-3 position-relative">
                <h3 class="h6 m-0">
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_read_all" aria-expanded="true" aria-controls="domains_read_all">
                        <?= l('api_documentation.read_all') ?>
                    </a>
                </h3>
            </div>

            <div id="domains_read_all" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>admin-api/domains/</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>admin-api/domains/' \<br />
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
            "user_id": 1,
            "scheme": "https://",
            "host": "example.com",
            "custom_index_url": "",
            "is_enabled": true,
            "last_datetime": null,
            "datetime": "2021-02-04 23:29:18"
        },
    ],
    "meta": {
        "page": 1,
        "results_per_page": 25,
        "total": 1,
        "total_pages": 1
    },
    "links": {
        "first": "<?= SITE_URL ?>admin-api/domains?&page=1",
        "last": "<?= SITE_URL ?>admin-api/domains?&page=1",
        "next": null,
        "prev": null,
        "self": "<?= SITE_URL ?>admin-api/domains?&page=1"
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_read" aria-expanded="true" aria-controls="domains_read">
                        <?= l('api_documentation.read') ?>
                    </a>
                </h3>
            </div>

            <div id="domains_read" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-success mr-3">GET</span> <span class="text-muted"><?= SITE_URL ?>admin-api/domains/</span><span class="text-primary">{domain_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request GET \<br />
                                --url '<?= SITE_URL ?>admin-api/domains/<span class="text-primary">{domain_id}</span>' \<br />
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
        "user_id": 1,
        "scheme": "https://",
        "host": "example.com",
        "custom_index_url": "",
        "is_enabled": true,
        "last_datetime": null,
        "datetime": "2021-02-04 23:29:18"
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_create" aria-expanded="true" aria-controls="domains_create">
                        <?= l('api_documentation.create') ?>
                    </a>
                </h3>
            </div>

            <div id="domains_create" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>admin-api/domains</span>
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
                                <td>scheme</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['http://', 'https://'])) ?></td>
                            </tr>
                            <tr>
                                <td>host</td>
                                <td>
                                    <span class="badge badge-danger"><?= l('api_documentation.required') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>custom_index_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>custom_not_found_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>is_enabled</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>admin-api/domains' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'host=<span class="text-primary">domain.com</span>' \<br />
                                --form 'custom_index_url=<span class="text-primary">https://mywebsite.com/</span>' \<br />
                                --form 'custom_not_found_url=<span class="text-primary">https://mywebsite.com/404-page</span>'
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_update" aria-expanded="true" aria-controls="domains_update">
                        <?= l('api_documentation.update') ?>
                    </a>
                </h3>
            </div>

            <div id="domains_update" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-info mr-3">POST</span> <span class="text-muted"><?= SITE_URL ?>admin-api/domains/</span><span class="text-primary">{domain_id}</span>
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
                                <td>scheme</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td><?= sprintf(l('api_documentation.allowed_values'), '<code>' . implode('</code>, <code>', ['http://', 'https://'])) ?></td>
                            </tr>
                            <tr>
                                <td>host</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>custom_index_url</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.string') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>is_enabled</td>
                                <td>
                                    <span class="badge badge-info"><?= l('api_documentation.optional') ?></span>
                                    <span class="badge badge-secondary"><?= l('api_documentation.boolean') ?></span>
                                </td>
                                <td>-</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request POST \<br />
                                --url '<?= SITE_URL ?>admin-api/domains/<span class="text-primary">{domain_id}</span>' \<br />
                                --header 'Authorization: Bearer <span class="text-primary">{api_key}</span>' \<br />
                                --header 'Content-Type: multipart/form-data' \<br />
                                --form 'host=<span class="text-primary">domain.com</span>' \<br />
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
                    <a href="#" class="stretched-link" data-toggle="collapse" data-target="#domains_delete" aria-expanded="true" aria-controls="domains_delete">
                        <?= l('api_documentation.delete') ?>
                    </a>
                </h3>
            </div>

            <div id="domains_delete" class="collapse">
                <div class="card-body">

                    <div class="form-group mb-4">
                        <label><?= l('api_documentation.endpoint') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                <span class="badge badge-danger mr-3">DELETE</span> <span class="text-muted"><?= SITE_URL ?>admin-api/domains/</span><span class="text-primary">{domain_id}</span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><?= l('api_documentation.example') ?></label>
                        <div class="card bg-gray-100 border-0">
                            <div class="card-body">
                                curl --request DELETE \<br />
                                --url '<?= SITE_URL ?>admin-api/domains/<span class="text-primary">{domain_id}</span>' \<br />
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
