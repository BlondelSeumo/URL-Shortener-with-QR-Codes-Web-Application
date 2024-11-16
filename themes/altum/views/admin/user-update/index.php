<?php defined('ALTUMCODE') || die() ?>

<?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/users') ?>"><?= l('admin_users.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= l('admin_user_update.breadcrumb') ?></li>
    </ol>
</nav>
<?php endif ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 text-truncate"><i class="fas fa-fw fa-xs fa-user text-primary-900 mr-2"></i> <?= l('admin_user_update.header') ?></h1>

    <?= include_view(THEME_PATH . 'views/admin/users/admin_user_dropdown_button.php', ['id' => $data->user->user_id, 'resource_name' => $data->user->name]) ?>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<?php //ALTUMCODE:DEMO if(DEMO) {$data->user->email = 'hidden@demo.com'; $data->user->name = $data->user->ip = 'hidden on demo';} ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

            <div class="form-group">
                <label for="user_id"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('admin_users.main.user_id') ?></label>
                <input type="text" id="user_id" name="user_id" class="form-control <?= \Altum\Alerts::has_field_errors('user_id') ? 'is-invalid' : null ?>" value="<?= $data->user->user_id ?>" disabled="disabled" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="name"><i class="fas fa-fw fa-sm fa-signature text-muted mr-1"></i> <?= l('global.name') ?></label>
                <input id="name" type="text" name="name" class="form-control <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->user->name ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="email"><i class="fas fa-fw fa-sm fa-envelope text-muted mr-1"></i> <?= l('global.email') ?></label>
                <input id="email" type="email" name="email" class="form-control <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->user->email ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('email') ?>
            </div>

            <div class="form-group">
                <label for="status"><i class="fas fa-fw fa-sm fa-circle-dot text-muted mr-1"></i> <?= l('global.status') ?></label>
                <select id="status" name="status" class="custom-select">
                    <option value="2" <?= $data->user->status == 2 ? 'selected="selected"' : null ?>><?= l('admin_users.main.status_disabled') ?></option>
                    <option value="1" <?= $data->user->status == 1 ? 'selected="selected"' : null ?>><?= l('admin_users.main.status_active') ?></option>
                    <option value="0" <?= $data->user->status == 0 ? 'selected="selected"' : null ?>><?= l('admin_users.main.status_unconfirmed') ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="type"><i class="fas fa-fw fa-sm fa-user text-muted mr-1"></i> <?= l('admin_users.main.type') ?></label>
                <select id="type" name="type" class="custom-select">
                    <option value="1" <?= $data->user->type == 1 ? 'selected="selected"' : null ?>><?= l('admin_users.main.type_admin') ?></option>
                    <option value="0" <?= $data->user->type == 0 ? 'selected="selected"' : null ?>><?= l('admin_users.main.type_user') ?></option>
                </select>
                <small class="form-text text-muted"><?= l('admin_users.main.type_help') ?></small>
            </div>

            <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                <div class="form-group">
                    <label for="referred_by"><i class="fas fa-fw fa-sm fa-user-plus text-muted mr-1"></i> <?= l('admin_users.main.referred_by') ?></label>
                    <input id="referred_by" type="number" name="referred_by" class="form-control <?= \Altum\Alerts::has_field_errors('referred_by') ? 'is-invalid' : null ?>" value="<?= $data->user->referred_by ?>" />
                    <?= \Altum\Alerts::output_field_error('referred_by') ?>
                </div>
            <?php endif ?>

            <h2 class="h4 mt-5 mb-4"><?= l('admin_user_update.plan.header') ?></h2>

            <div class="form-group">
                <label for="plan_id"><i class="fas fa-fw fa-sm fa-box-open text-muted mr-1"></i> <?= l('admin_users.main.plan_id') ?></label>
                <select id="plan_id" name="plan_id" class="custom-select">
                    <option value="free" <?= $data->user->plan_id == 'free' ? 'selected="selected"' : null ?>><?= settings()->plan_free->name ?></option>
                    <option value="custom" <?= $data->user->plan_id == 'custom' ? 'selected="selected"' : null ?>><?= settings()->plan_custom->name ?></option>

                    <?php foreach($data->plans as $plan): ?>
                        <option value="<?= $plan->plan_id ?>" <?= $data->user->plan_id == $plan->plan_id ? 'selected="selected"' : null ?>><?= $plan->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group custom-control custom-switch">
                <input id="plan_trial_done" name="plan_trial_done" type="checkbox" class="custom-control-input" <?= $data->user->plan_trial_done ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="plan_trial_done"><i class="fas fa-fw fa-sm fa-calendar-minus text-muted mr-1"></i> <?= l('admin_users.main.plan_trial_done') ?></label>
            </div>

            <div id="plan_expiration_date_container" class="form-group">
                <label for="plan_expiration_date"><i class="fas fa-fw fa-sm fa-hourglass-end text-muted mr-1"></i> <?= l('admin_users.main.plan_expiration_date') ?></label>
                <input id="plan_expiration_date" type="text" name="plan_expiration_date" class="form-control" autocomplete="off" value="<?= $data->user->plan_expiration_date ?>">
                <div class="invalid-feedback">
                    <?= l('admin_user_update.plan.plan_expiration_date_invalid') ?>
                </div>
            </div>

            <div id="plan_settings" style="display: none">

                <h2 class="h4 mt-5 mb-4"><?= l('admin_user_update.plan.settings') ?></h2>

                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="url_minimum_characters"><?= l('admin_plans.plan.url_minimum_characters') ?></label>
                            <input type="number" id="url_minimum_characters" name="url_minimum_characters" min="1" class="form-control" value="<?= $data->user->plan_settings->url_minimum_characters ?? 1 ?>" />
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="form-group">
                            <label for="url_maximum_characters"><?= l('admin_plans.plan.url_maximum_characters') ?></label>
                            <input type="number" id="url_maximum_characters" name="url_maximum_characters" min="1" max="256" class="form-control" value="<?= $data->user->plan_settings->url_maximum_characters ?? 64 ?>" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="projects_limit"><?= l('admin_plans.plan.projects_limit') ?></label>
                    <input type="number" id="projects_limit" name="projects_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->projects_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="splash_pages_limit"><?= l('admin_plans.plan.splash_pages_limit') ?></label>
                    <input type="number" id="splash_pages_limit" name="splash_pages_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->splash_pages_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="pixels_limit"><?= l('admin_plans.plan.pixels_limit') ?></label>
                    <input type="number" id="pixels_limit" name="pixels_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->pixels_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="qr_codes_limit"><?= l('admin_plans.plan.qr_codes_limit') ?></label>
                    <input type="number" id="qr_codes_limit" name="qr_codes_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->qr_codes_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="qr_codes_bulk_limit"><?= l('admin_plans.plan.qr_codes_bulk_limit') ?></label>
                    <input type="number" id="qr_codes_bulk_limit" name="qr_codes_bulk_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->qr_codes_bulk_limit ?>" />
                </div>

                <div class="form-group">
                    <label for="biolinks_limit"><?= l('admin_plans.plan.biolinks_limit') ?></label>
                    <input type="number" id="biolinks_limit" name="biolinks_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->biolinks_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.biolinks_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="biolink_blocks_limit"><?= l('admin_plans.plan.biolink_blocks_limit') ?></label>
                    <input type="number" id="biolink_blocks_limit" name="biolink_blocks_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->biolink_blocks_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                </div>

                <div class="form-group">
                    <label for="links_limit"><?= l('admin_plans.plan.links_limit') ?></label>
                    <input type="number" id="links_limit" name="links_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->links_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.links_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="files_limit"><?= l('admin_plans.plan.files_limit') ?></label>
                    <input type="number" id="files_limit" name="files_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->files_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.files_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="vcards_limit"><?= l('admin_plans.plan.vcards_limit') ?></label>
                    <input type="number" id="vcards_limit" name="vcards_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->vcards_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.vcards_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="events_limit"><?= l('admin_plans.plan.events_limit') ?></label>
                    <input type="number" id="events_limit" name="events_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->events_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.events_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="static_limit"><?= l('admin_plans.plan.static_limit') ?></label>
                    <input type="number" id="static_limit" name="static_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->static_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.static_limit_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="domains_limit"><?= l('admin_plans.plan.domains_limit') ?></label>
                    <input type="number" id="domains_limit" name="domains_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->domains_limit ?>" />
                    <small class="form-text text-muted"><?= l('admin_plans.plan.domains_limit_help') ?></small>
                </div>

                <?php if(\Altum\Plugin::is_active('payment-blocks')): ?>
                    <div class="form-group">
                        <label for="payment_processors_limit"><?= l('admin_plans.plan.payment_processors_limit') ?></label>
                        <input type="number" id="payment_processors_limit" name="payment_processors_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->payment_processors_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('email-signatures')): ?>
                    <div class="form-group">
                        <label for="signatures_limit"><?= l('admin_plans.plan.signatures_limit') ?></label>
                        <input type="number" id="signatures_limit" name="signatures_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->signatures_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix')): ?>
                    <div class="form-group custom-control custom-switch">
                        <input id="exclusive_personal_api_keys" name="exclusive_personal_api_keys" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->exclusive_personal_api_keys ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="exclusive_personal_api_keys"><?= l('admin_plans.plan.exclusive_personal_api_keys') ?></label>
                        <div><small class="form-text text-muted"><?= l('admin_plans.plan.exclusive_personal_api_keys_help') ?></small></div>
                    </div>

                    <div class="form-group">
                        <label for="documents_model"><?= l('admin_plans.plan.documents_model') ?></label>
                        <select id="documents_model" name="documents_model" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_text_models.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->user->plan_settings->documents_model == $key ? 'selected="selected"' : null ?>><?= $value['name'] . ' - ' . $key . ' (' . l('global.plan_settings.documents_model.' . str_replace('-', '_', $key)) . ')' ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.documents_model_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="documents_per_month_limit"><?= l('admin_plans.plan.documents_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="documents_per_month_limit" name="documents_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->documents_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="words_per_month_limit"><?= l('admin_plans.plan.words_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="words_per_month_limit" name="words_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->words_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="images_api"><?= l('admin_plans.plan.images_api') ?></label>
                        <select id="images_api" name="images_api" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_image_models.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->user->plan_settings->images_api == $key ? 'selected="selected"' : null ?>><?= $value['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.images_api_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="images_per_month_limit"><?= l('admin_plans.plan.images_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="images_per_month_limit" name="images_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->images_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="transcriptions_per_month_limit"><?= l('admin_plans.plan.transcriptions_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="transcriptions_per_month_limit" name="transcriptions_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->transcriptions_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="transcriptions_file_size_limit"><?= l('admin_plans.plan.transcriptions_file_size_limit') ?></label>
                        <div class="input-group">
                            <input type="number" id="transcriptions_file_size_limit" name="transcriptions_file_size_limit" min="0" max="<?= get_max_upload() > 25 ? 25 : get_max_upload() ?>" step="any" class="form-control" value="<?= $data->user->plan_settings->transcriptions_file_size_limit ?>" />
                            <div class="input-group-append">
                                <span class="input-group-text"><?= l('global.mb') ?></span>
                            </div>
                        </div>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.transcriptions_file_size_limit_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chats_model"><?= l('admin_plans.plan.chats_model') ?></label>
                        <select id="chats_model" name="chats_model" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_chat_models.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->user->plan_settings->chats_model == $key ? 'selected="selected"' : null ?>><?= $value['name'] . ' - ' . $key . ' (' . l('global.plan_settings.documents_model.' . str_replace('-', '_', $key)) . ')' ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.chats_model_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chats_per_month_limit"><?= l('admin_plans.plan.chats_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="chats_per_month_limit" name="chats_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->chats_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chat_messages_per_chat_limit"><?= l('admin_plans.plan.chat_messages_per_chat_limit') ?></small></label>
                        <input type="number" id="chat_messages_per_chat_limit" name="chat_messages_per_chat_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->chat_messages_per_chat_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="chat_image_size_limit"><?= l('admin_plans.plan.chat_image_size_limit') ?></label>
                        <div class="input-group">
                            <input type="number" id="chat_image_size_limit" name="chat_image_size_limit" min="0" max="<?= get_max_upload() > 20 ? 20 : get_max_upload() ?>" step="any" class="form-control" value="<?= $data->user->plan_settings->chat_image_size_limit ?>" />
                            <div class="input-group-append">
                                <span class="input-group-text"><?= l('global.mb') ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="syntheses_api"><?= l('admin_plans.plan.syntheses_api') ?></label>
                        <select id="syntheses_api" name="syntheses_api" class="custom-select">
                            <?php foreach(require \Altum\Plugin::get('aix')->path . 'includes/ai_syntheses_apis.php' as $key => $value): ?>
                                <option value="<?= $key ?>" <?= $data->user->plan_settings->syntheses_api == $key ? 'selected="selected"' : null ?>><?= $value['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="form-text text-muted"><?= l('admin_plans.plan.syntheses_api_help') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="syntheses_per_month_limit"><?= l('admin_plans.plan.syntheses_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="syntheses_per_month_limit" name="syntheses_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->syntheses_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="synthesized_characters_per_month_limit"><?= l('admin_plans.plan.synthesized_characters_per_month_limit') ?> <small class="form-text text-muted"><?= l('admin_plans.plan.per_month') ?></small></label>
                        <input type="number" id="synthesized_characters_per_month_limit" name="synthesized_characters_per_month_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->synthesized_characters_per_month_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('teams')): ?>
                    <div class="form-group">
                        <label for="teams_limit"><?= l('admin_plans.plan.teams_limit') ?></label>
                        <input type="number" id="teams_limit" name="teams_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->teams_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>

                    <div class="form-group">
                        <label for="team_members_limit"><?= l('admin_plans.plan.team_members_limit') ?></label>
                        <input type="number" id="team_members_limit" name="team_members_limit" min="-1" class="form-control" value="<?= $data->user->plan_settings->team_members_limit ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.unlimited') ?></small>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                    <div class="form-group">
                        <label for="affiliate_commission_percentage"><?= l('admin_plans.plan.affiliate_commission_percentage') ?></label>
                        <input type="number" id="affiliate_commission_percentage" name="affiliate_commission_percentage" min="0" max="100" class="form-control" value="<?= $data->user->plan_settings->affiliate_commission_percentage ?>" />
                        <small class="form-text text-muted"><?= l('admin_plans.plan.affiliate_commission_percentage_help') ?></small>
                    </div>
                <?php endif ?>

                <div class="form-group">
                    <label for="track_links_retention"><?= l('admin_plans.plan.track_links_retention') ?></label>
                    <div class="input-group">
                        <input type="number" id="track_links_retention" name="track_links_retention" min="-1" class="form-control" value="<?= $data->user->plan_settings->track_links_retention ?>" />
                        <div class="input-group-append">
                            <span class="input-group-text"><?= l('global.date.days') ?></span>
                        </div>
                    </div>
                    <small class="form-text text-muted"><?= l('admin_plans.plan.track_links_retention_help') ?></small>
                </div>

                <div class="form-group">
                    <label for="biolinks_templates"><?= l('admin_plans.plan.biolinks_templates') ?></label>
                    <select id="biolinks_templates" name="biolinks_templates[]" class="custom-select" multiple="multiple">
                        <?php foreach($data->biolinks_templates as $biolink_template): ?>
                            <option value="<?= $biolink_template->biolink_template_id ?>" <?= in_array($biolink_template->biolink_template_id, $data->user->plan_settings->biolinks_templates ?? [])  ? 'selected="selected"' : null ?>>
                                <?= $biolink_template->name ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="additional_domains"><?= l('admin_plans.plan.additional_domains') ?></label>
                    <select id="additional_domains" name="additional_domains[]" class="custom-select" multiple="multiple">
                        <?php foreach($data->additional_domains as $domain): ?>
                            <option value="<?= $domain->domain_id ?>" <?= in_array($domain->domain_id, $data->user->plan_settings->additional_domains ?? [])  ? 'selected="selected"' : null ?>>
                                <?= $domain->host ?>
                            </option>
                        <?php endforeach ?>
                    </select>
                </div>

                <?php foreach(require APP_PATH . 'includes/links_types.php' as $key => $value): ?>
                <div class="form-group custom-control custom-switch">
                    <input id="<?= 'force_splash_page_on_' . $key ?>" name="<?= 'force_splash_page_on_' . $key ?>" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->{'force_splash_page_on_' . $key} ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="<?= 'force_splash_page_on_' . $key ?>"><?= l('admin_plans.plan.' . 'force_splash_page_on_' . $key) ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.' . 'force_splash_page_on_' . $key . '_help') ?></small></div>
                </div>
                <?php endforeach ?>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_url" name="custom_url" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->custom_url ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_url"><?= l('admin_plans.plan.custom_url') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_url_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="deep_links" name="deep_links" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->deep_links ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="deep_links"><?= l('admin_plans.plan.deep_links') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.deep_links_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->no_ads ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="no_ads"><?= l('admin_plans.plan.no_ads') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.no_ads_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="removable_branding" name="removable_branding" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->removable_branding ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="removable_branding"><?= l('admin_plans.plan.removable_branding') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.removable_branding_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_branding" name="custom_branding" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->custom_branding ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_branding"><?= l('admin_plans.plan.custom_branding') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_branding_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="statistics" name="statistics" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->statistics ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="statistics"><?= l('admin_plans.plan.statistics') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.statistics_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="temporary_url_is_enabled" name="temporary_url_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->temporary_url_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="temporary_url_is_enabled"><?= l('admin_plans.plan.temporary_url_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.temporary_url_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="cloaking_is_enabled" name="cloaking_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->cloaking_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="cloaking_is_enabled"><?= l('admin_plans.plan.cloaking_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.cloaking_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="app_linking_is_enabled" name="app_linking_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->app_linking_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="app_linking_is_enabled"><?= l('admin_plans.plan.app_linking_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.app_linking_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="seo" name="seo" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->seo ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="seo"><?= l('admin_plans.plan.seo') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.seo_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="utm" name="utm" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->utm ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="utm"><?= l('admin_plans.plan.utm') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.utm_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="fonts" name="fonts" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->fonts ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="fonts"><?= l('admin_plans.plan.fonts') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.fonts_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="password" name="password" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->password ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="password"><?= l('admin_plans.plan.password') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.password_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="sensitive_content" name="sensitive_content" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->sensitive_content ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="sensitive_content"><?= l('admin_plans.plan.sensitive_content') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.sensitive_content_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="leap_link" name="leap_link" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->leap_link ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="leap_link"><?= l('admin_plans.plan.leap_link') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.leap_link_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="dofollow_is_enabled" name="dofollow_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->dofollow_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="dofollow_is_enabled"><?= l('admin_plans.plan.dofollow_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.dofollow_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->api_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="api_is_enabled"><?= l('admin_plans.plan.api_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.api_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_css_is_enabled" name="custom_css_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->custom_css_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_css_is_enabled"><?= l('admin_plans.plan.custom_css_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_css_is_enabled_help') ?></small></div>
                </div>

                <div class="form-group custom-control custom-switch">
                    <input id="custom_js_is_enabled" name="custom_js_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->custom_js_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_js_is_enabled"><?= l('admin_plans.plan.custom_js_is_enabled') ?></label>
                    <div><small class="form-text text-muted"><?= l('admin_plans.plan.custom_js_is_enabled_help') ?></small></div>
                </div>

                <h3 class="h5 my-4"><?= l('admin_plans.plan.enabled_biolink_blocks') ?></h3>

                <div class="row">
                    <?php foreach(require APP_PATH . 'includes/biolink_blocks.php' as $key => $value): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="enabled_biolink_blocks_<?= $key ?>" name="enabled_biolink_blocks[]" value="<?= $key ?>" type="checkbox" class="custom-control-input" <?= $data->user->plan_settings->enabled_biolink_blocks->{$key} ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="enabled_biolink_blocks_<?= $key ?>"><?= l('link.biolink.blocks.' . mb_strtolower($key)) ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="mt-5"></div>

            <h2 class="h4"><?= l('admin_user_update.change_password.header') ?></h2>
            <p class="text-muted"><?= l('admin_user_update.change_password.subheader') ?></p>

            <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                <label for="new_password"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_user_update.change_password.new_password') ?></label>
                <input id="new_password" type="password" name="new_password" class="form-control <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" />
                <?= \Altum\Alerts::output_field_error('new_password') ?>
            </div>

            <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                <label for="repeat_password"><i class="fas fa-fw fa-sm fa-key text-muted mr-1"></i> <?= l('admin_user_update.change_password.repeat_password') ?></label>
                <input id="repeat_password" type="password" name="repeat_password" class="form-control <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" />
                <?= \Altum\Alerts::output_field_error('new_password') ?>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>
        </form>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/libraries/daterangepicker.min.css?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js?v=' . PRODUCT_CODE ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js?v=' . PRODUCT_CODE ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    let check_plan_id = () => {
        let selected_plan_id = document.querySelector('[name="plan_id"]').value;

        if(selected_plan_id == 'free') {
            document.querySelector('#plan_expiration_date_container').style.display = 'none';
        } else {
            document.querySelector('#plan_expiration_date_container').style.display = 'block';
        }

        if(selected_plan_id == 'custom') {
            document.querySelector('#plan_settings').style.display = 'block';
        } else {
            document.querySelector('#plan_settings').style.display = 'none';
        }
    };

    check_plan_id();

    /* Dont show expiration date when the chosen plan is the free one */
    document.querySelector('[name="plan_id"]').addEventListener('change', check_plan_id);

    /* Check for expiration date to show a warning if expired */
    let check_plan_expiration_date = () => {
        let plan_expiration_date = document.querySelector('[name="plan_expiration_date"]');

        let plan_expiration_date_object = new Date(plan_expiration_date.value);
        let today_date_object = new Date();

        if(plan_expiration_date_object < today_date_object) {
            plan_expiration_date.classList.add('is-invalid');
        } else {
            plan_expiration_date.classList.remove('is-invalid');
        }
    };

    check_plan_expiration_date();
    document.querySelector('[name="plan_expiration_date"]').addEventListener('change', check_plan_expiration_date);

    /* Daterangepicker */
    $('[name="plan_expiration_date"]').daterangepicker({
        startDate: <?= json_encode($data->user->plan_expiration_date) ?>,
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {
        check_plan_expiration_date()
    });

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
