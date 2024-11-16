<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>

<div class="card">
    <div class="card-body">

        <form name="update_vcard" action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />
            <input type="hidden" name="request_type" value="update" />
            <input type="hidden" name="type" value="vcard" />
            <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />

            <div class="notification-container"></div>

            <div class="form-group">
                <label for="url"><i class="fas fa-fw fa-bolt fa-sm text-muted mr-1"></i> <?= l('link.settings.url') ?></label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <?php if(count($data->domains)): ?>
                            <select name="domain_id" class="appearance-none custom-select form-control input-group-text">
                                <?php if(settings()->links->main_domain_is_enabled || \Altum\Authentication::is_admin()): ?>
                                    <option value="" <?= $data->link->domain ? 'selected="selected"' : null ?> data-full-url="<?= SITE_URL ?>"><?= remove_url_protocol_from_url(SITE_URL) ?></option>
                                <?php endif ?>

                                <?php foreach($data->domains as $row): ?>
                                    <option value="<?= $row->domain_id ?>" <?= $data->link->domain && $row->domain_id == $data->link->domain->domain_id ? 'selected="selected"' : null ?>  data-full-url="<?= $row->url ?>" data-type="<?= $row->type ?>"><?= remove_url_protocol_from_url($row->url) ?></option>
                                <?php endforeach ?>
                            </select>
                        <?php else: ?>
                            <span class="input-group-text"><?= remove_url_protocol_from_url(SITE_URL) ?></span>
                        <?php endif ?>
                    </div>

                    <input
                            id="url"
                            type="text"
                            class="form-control"
                            name="url"
                            placeholder="<?= l('global.url_slug_placeholder') ?>"
                            value="<?= $data->link->url ?>"
                            maxlength="<?= $this->user->plan_settings->url_maximum_characters ?? 64 ?>"
                            onchange="update_this_value(this, get_slug)"
                            onkeyup="update_this_value(this, get_slug)"
                        <?= !$this->user->plan_settings->custom_url ? 'readonly="readonly"' : null ?>
                        <?= $this->user->plan_settings->custom_url ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>
                    />
                </div>
                <small class="form-text text-muted"><?= l('link.settings.url_help') ?></small>
            </div>

            <?php if(count($data->domains)): ?>
                <div id="is_main_link_wrapper" class="form-group custom-control custom-switch">
                    <input id="is_main_link" name="is_main_link" type="checkbox" class="custom-control-input" <?= $data->link->domain_id && $data->domains[$data->link->domain_id]->link_id == $data->link->link_id ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="is_main_link"><?= l('link.settings.is_main_link') ?></label>
                    <small class="form-text text-muted"><?= l('link.settings.is_main_link_help') ?></small>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label for="vcard_avatar"><i class="fas fa-fw fa-image fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_avatar') ?></label>
                <div class="row">
                    <div class="col">
                        <input id="vcard_avatar" type="file" name="vcard_avatar" accept="<?= \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards_avatars') ?>" class="form-control-file altum-file-input" />
                    </div>

                    <div id="vcard_avatar_preview" data-image-container="vcard_avatar" class="col-3 <?= !empty($data->link->settings->vcard_avatar) ? null : 'd-none' ?>">
                        <div class="d-flex justify-content-center align-items-center">
                            <a href="<?= $data->link->settings->vcard_avatar ? \Altum\Uploads::get_full_url('avatars') . $data->link->settings->vcard_avatar : null ?>" target="_blank" data-toggle="tooltip" title="<?= l('global.view') ?>">
                                <img src="<?= $data->link->settings->vcard_avatar ? \Altum\Uploads::get_full_url('avatars') . $data->link->settings->vcard_avatar : null ?>" class="altum-file-input-preview rounded <?= !empty($data->link->settings->vcard_avatar) ? null : 'd-none' ?>" loading="lazy" />
                            </a>
                        </div>
                    </div>

                    <div data-image-container="vcard_avatar" class="col-12 <?= !empty($data->link->settings->vcard_avatar) ? null : 'd-none' ?>">
                        <div class="custom-control custom-checkbox my-2">
                            <input id="vcard_avatar_remove" name="vcard_avatar_remove" type="checkbox" class="custom-control-input" onchange="document.querySelectorAll('#vcard_avatar,#vcard_avatar_preview').forEach(element => this.checked ? element.classList.add('d-none') : element.classList.remove('d-none'))">
                            <label class="custom-control-label" for="vcard_avatar_remove">
                                <span class="text-muted"><?= l('global.delete_file') ?></span>
                            </label>
                        </div>
                    </div>
                </div>
                <small class="form-text text-muted"><?= sprintf(l('global.accessibility.whitelisted_file_extensions'), \Altum\Uploads::get_whitelisted_file_extensions_accept('vcards_avatars')) . ' ' . sprintf(l('global.accessibility.file_size_limit'), 0.75) ?></small>
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="<?= 'vcard_first_name_' . $data->link->link_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_first_name') ?></label>
                        <input type="text" id="<?= 'vcard_first_name_' . $data->link->link_id ?>" name="vcard_first_name" class="form-control" value="<?= $data->link->settings->vcard_first_name ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['first_name']['max_length'] ?>" />
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="<?= 'vcard_last_name_' . $data->link->link_id ?>"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_last_name') ?></label>
                        <input type="text" id="<?= 'vcard_last_name_' . $data->link->link_id ?>" name="vcard_last_name" class="form-control" value="<?= $data->link->settings->vcard_last_name ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['last_name']['max_length'] ?>" />
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_email_' . $data->link->link_id ?>"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('global.email') ?></label>
                <input type="email" id="<?= 'vcard_email_' . $data->link->link_id ?>" name="vcard_email" class="form-control" value="<?= $data->link->settings->vcard_email ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['email']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_url_' . $data->link->link_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_url') ?></label>
                <input type="url" id="<?= 'vcard_url_' . $data->link->link_id ?>" name="vcard_url" class="form-control" value="<?= $data->link->settings->vcard_url ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['url']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_company_' . $data->link->link_id ?>"><i class="fas fa-fw fa-building fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_company') ?></label>
                <input type="text" id="<?= 'vcard_company_' . $data->link->link_id ?>" name="vcard_company" class="form-control" value="<?= $data->link->settings->vcard_company ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['company']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_job_title_' . $data->link->link_id ?>"><i class="fas fa-fw fa-user-tie fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_job_title') ?></label>
                <input type="text" id="<?= 'vcard_job_title_' . $data->link->link_id ?>" name="vcard_job_title" class="form-control" value="<?= $data->link->settings->vcard_job_title ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['job_title']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_birthday_' . $data->link->link_id ?>"><i class="fas fa-fw fa-birthday-cake fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_birthday') ?></label>
                <input type="date" id="<?= 'vcard_birthday_' . $data->link->link_id ?>" name="vcard_birthday" class="form-control" value="<?= $data->link->settings->vcard_birthday ?? null ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_street_' . $data->link->link_id ?>"><i class="fas fa-fw fa-road fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_street') ?></label>
                <input type="text" id="<?= 'vcard_street_' . $data->link->link_id ?>" name="vcard_street" class="form-control" value="<?= $data->link->settings->vcard_street ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['street']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_city_' . $data->link->link_id ?>"><i class="fas fa-fw fa-city fa-sm text-muted mr-1"></i> <?= l('global.city') ?></label>
                <input type="text" id="<?= 'vcard_city_' . $data->link->link_id ?>" name="vcard_city" class="form-control" value="<?= $data->link->settings->vcard_city ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['city']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_zip_' . $data->link->link_id ?>"><i class="fas fa-fw fa-mail-bulk fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_zip') ?></label>
                <input type="text" id="<?= 'vcard_zip_' . $data->link->link_id ?>" name="vcard_zip" class="form-control" value="<?= $data->link->settings->vcard_zip ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['zip']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_region_' . $data->link->link_id ?>"><i class="fas fa-fw fa-flag fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_region') ?></label>
                <input type="text" id="<?= 'vcard_region_' . $data->link->link_id ?>" name="vcard_region" class="form-control" value="<?= $data->link->settings->vcard_region ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['region']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_country_' . $data->link->link_id ?>"><i class="fas fa-fw fa-globe fa-sm text-muted mr-1"></i> <?= l('global.country') ?></label>
                <input type="text" id="<?= 'vcard_country_' . $data->link->link_id ?>" name="vcard_country" class="form-control" value="<?= $data->link->settings->vcard_country ?? null ?>" maxlength="<?= $data->links_types['vcard']['fields']['country']['max_length'] ?>" />
            </div>

            <div class="form-group">
                <label for="<?= 'vcard_note_' . $data->link->link_id ?>"><i class="fas fa-fw fa-paragraph fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_note') ?></label>
                <textarea id="<?= 'vcard_note_' . $data->link->link_id ?>" name="vcard_note" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['note']['max_length'] ?>"><?= $data->link->settings->vcard_note ?? null ?></textarea>
            </div>

            <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'vcard_phone_numbers_container_' . $data->link->link_id ?>" aria-expanded="false" aria-controls="<?= 'vcard_phone_numbers_container_' . $data->link->link_id ?>">
                <i class="fas fa-fw fa-phone-square-alt fa-sm mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_numbers') ?>
            </button>

            <div class="collapse" id="<?= 'vcard_phone_numbers_container_' . $data->link->link_id ?>">
                <div id="<?= 'vcard_phone_numbers_' . $data->link->link_id ?>" data-biolink-block-id="<?= $data->link->link_id ?>">
                    <?php foreach($data->link->settings->vcard_phone_numbers ?? [] as $key => $phone_number): ?>
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="<?= 'vcard_phone_number_label_' . $key . '_' . $data->link->link_id ?>"><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_label') ?></label>
                                <input id="<?= 'vcard_phone_number_label_' . $key . '_' . $data->link->link_id ?>" type="text" name="vcard_phone_number_label[<?= $key ?>]" class="form-control" value="<?= $phone_number->label ?>" maxlength="<?= $data->links_types['vcard']['fields']['phone_number_label']['max_length'] ?>" />
                                <small class="form-text text-muted"><?= l('create_biolink_vcard_modal.vcard_phone_number_label_help') ?></small>
                            </div>

                            <div class="form-group">
                                <label for="<?= 'vcard_phone_number_value_' . $key . '_' . $data->link->link_id ?>"><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_value') ?></label>
                                <input id="<?= 'vcard_phone_number_value_' . $key . '_' . $data->link->link_id ?>" type="text" name="vcard_phone_number_value[<?= $key ?>]" value="<?= $phone_number->value ?>" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['phone_number_value']['max_length'] ?>" required="required" />
                            </div>

                            <button type="button" data-remove="vcard_phone_numbers" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                        </div>
                    <?php endforeach ?>
                </div>

                <div class="mb-3">
                    <button data-add="vcard_phone_numbers" data-biolink-block-id="<?= $data->link->link_id ?>" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                </div>
            </div>

            <button class="btn btn-block btn-gray-300 my-4" type="button" data-toggle="collapse" data-target="#<?= 'vcard_socials_container_' . $data->link->link_id ?>" aria-expanded="false" aria-controls="<?= 'vcard_socials_container_' . $data->link->link_id ?>">
                <i class="fas fa-fw fa-share-alt fa-sm mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_socials') ?>
            </button>

            <div class="collapse" id="<?= 'vcard_socials_container_' . $data->link->link_id ?>">
                <div id="<?= 'vcard_socials_' . $data->link->link_id ?>" data-biolink-block-id="<?= $data->link->link_id ?>">
                    <?php foreach($data->link->settings->vcard_socials ?? [] as $key => $social): ?>
                        <div class="mb-4">
                            <div class="form-group">
                                <label for="<?= 'vcard_social_label_' . $key . '_' . $data->link->link_id ?>"><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_label') ?></label>
                                <input id="<?= 'vcard_social_label_' . $key . '_' . $data->link->link_id ?>" type="text" name="vcard_social_label[<?= $key ?>]" class="form-control" value="<?= $social->label ?>" maxlength="<?= $data->links_types['vcard']['fields']['social_label']['max_length'] ?>" required="required" />
                            </div>

                            <div class="form-group">
                                <label for="<?= 'vcard_social_value_' . $key . '_' . $data->link->link_id ?>"><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_value') ?></label>
                                <input id="<?= 'vcard_social_value_' . $key . '_' . $data->link->link_id ?>" type="url" name="vcard_social_value[<?= $key ?>]" value="<?= $social->value ?>" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['social_value']['max_length'] ?>" required="required" />
                            </div>

                            <button type="button" data-remove="vcard_social" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
                        </div>
                    <?php endforeach ?>
                </div>

                <div class="mb-3">
                    <button data-add="vcard_social" data-biolink-block-id="<?= $data->link->link_id ?>" type="button" class="btn btn-sm btn-outline-success"><i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('global.create') ?></button>
                </div>
            </div>

            <?php if(settings()->links->pixels_is_enabled): ?>
                <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#pixels_container" aria-expanded="false" aria-controls="pixels_container">
                    <i class="fas fa-fw fa-adjust fa-sm mr-1"></i> <?= l('link.settings.pixels_header') ?>
                </button>

                <div class="collapse" id="pixels_container">
                    <div class="form-group">
                        <div class="d-flex flex-column flex-xl-row justify-content-between">
                            <label><i class="fas fa-fw fa-sm fa-adjust text-muted mr-1"></i> <?= l('link.settings.pixels_ids') ?></label>
                            <a href="<?= url('pixels') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('pixels.create') ?></a>
                        </div>

                        <div class="row">
                            <?php $available_pixels = require APP_PATH . 'includes/pixels.php'; ?>
                            <?php foreach($data->pixels as $pixel): ?>
                                <div class="col-12 col-lg-6">
                                    <div class="custom-control custom-checkbox my-2">
                                        <input id="pixel_id_<?= $pixel->pixel_id ?>" name="pixels_ids[]" value="<?= $pixel->pixel_id ?>" type="checkbox" class="custom-control-input" <?= in_array($pixel->pixel_id, $data->link->pixels_ids) ? 'checked="checked"' : null ?>>
                                        <label class="custom-control-label d-flex align-items-center" for="pixel_id_<?= $pixel->pixel_id ?>">
                                            <span><?= $pixel->name ?></span>
                                            <small class="badge badge-light ml-1" data-toggle="tooltip" title="<?= $available_pixels[$pixel->type]['name'] ?>">
                                                <i class="<?= $available_pixels[$pixel->type]['icon'] ?> fa-fw fa-sm" style="color: <?= $available_pixels[$pixel->type]['color'] ?>"></i>
                                            </small>
                                        </label>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                </div>
            <?php endif ?>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#temporary_url_container" aria-expanded="false" aria-controls="temporary_url_container">
                <i class="fas fa-fw fa-clock fa-sm mr-1"></i> <?= l('link.settings.temporary_url_header') ?>
            </button>

            <div class="collapse" id="temporary_url_container">
                <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                        <div class="form-group custom-control custom-switch">
                            <input
                                    id="schedule"
                                    name="schedule"
                                    type="checkbox"
                                    class="custom-control-input"
                                <?= $data->link->settings->schedule && !empty($data->link->start_date) && !empty($data->link->end_date) ? 'checked="checked"' : null ?>
                                <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'disabled="disabled"' ?>
                            >
                            <label class="custom-control-label" for="schedule"><?= l('link.settings.schedule') ?></label>
                            <small class="form-text text-muted"><?= l('link.settings.schedule_help') ?></small>
                        </div>
                    </div>
                </div>

                <div id="schedule_container" style="display: none;">
                    <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="<?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-hourglass-start fa-sm text-muted mr-1"></i> <?= l('link.settings.start_date') ?></label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                name="start_date"
                                                value="<?= \Altum\Date::get($data->link->start_date, 1) ?>"
                                                placeholder="<?= l('link.settings.start_date') ?>"
                                                autocomplete="off"
                                                data-daterangepicker
                                        >
                                    </div>
                                </div>

                                <div class="col">
                                    <div class="form-group">
                                        <label><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.end_date') ?></label>
                                        <input
                                                type="text"
                                                class="form-control"
                                                name="end_date"
                                                value="<?= \Altum\Date::get($data->link->end_date, 1) ?>"
                                                placeholder="<?= l('link.settings.end_date') ?>"
                                                autocomplete="off"
                                                data-daterangepicker
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                        <label for="clicks_limit"><i class="fas fa-fw fa-mouse fa-sm text-muted mr-1"></i> <?= l('link.settings.clicks_limit') ?></label>
                        <input id="clicks_limit" type="number" class="form-control" name="clicks_limit" value="<?= $data->link->settings->clicks_limit ?>" />
                        <small class="form-text text-muted"><?= l('link.settings.clicks_limit_help') ?></small>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="form-group <?= $this->user->plan_settings->temporary_url_is_enabled ? null : 'container-disabled' ?>">
                        <label for="expiration_url"><i class="fas fa-fw fa-hourglass-end fa-sm text-muted mr-1"></i> <?= l('link.settings.expiration_url') ?></label>
                        <input id="expiration_url" type="url" class="form-control" name="expiration_url" value="<?= $data->link->settings->expiration_url ?>" maxlength="2048" />
                        <small class="form-text text-muted"><?= l('link.settings.expiration_url_help') ?></small>
                    </div>
                </div>

            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#protection_container" aria-expanded="false" aria-controls="protection_container">
                <i class="fas fa-fw fa-user-shield fa-sm mr-1"></i> <?= l('link.settings.protection_header') ?>
            </button>

            <div class="collapse" id="protection_container">
                <div <?= $this->user->plan_settings->password ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->password ? null : 'container-disabled' ?>">
                        <div class="form-group" data-password-toggle-view data-password-toggle-view-show="<?= l('global.show') ?>" data-password-toggle-view-hide="<?= l('global.hide') ?>">
                            <label for="qweasdzxc"><i class="fas fa-fw fa-key fa-sm text-muted mr-1"></i> <?= l('global.password') ?></label>
                            <input id="qweasdzxc" type="password" class="form-control" name="qweasdzxc" value="<?= $data->link->settings->password ?>" autocomplete="new-password" <?= !$this->user->plan_settings->password ? 'disabled="disabled"': null ?> />
                            <small class="form-text text-muted"><?= l('link.settings.password_help') ?></small>
                        </div>
                    </div>
                </div>

                <div <?= $this->user->plan_settings->sensitive_content ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                    <div class="<?= $this->user->plan_settings->sensitive_content ? null : 'container-disabled' ?>">
                        <div class="form-group custom-control custom-switch">
                            <input
                                    type="checkbox"
                                    class="custom-control-input"
                                    id="sensitive_content"
                                    name="sensitive_content"
                                <?= !$this->user->plan_settings->sensitive_content ? 'disabled="disabled"': null ?>
                                <?= $data->link->settings->sensitive_content ? 'checked="checked"' : null ?>
                            >
                            <label class="custom-control-label" for="sensitive_content"><?= l('link.settings.sensitive_content') ?></label>
                            <small class="form-text text-muted"><?= l('link.settings.sensitive_content_help') ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <button class="btn btn-block btn-gray-200 my-4" type="button" data-toggle="collapse" data-target="#advanced_container" aria-expanded="false" aria-controls="advanced_container">
                <i class="fas fa-fw fa-user-tie fa-sm mr-1"></i> <?= l('link.settings.advanced_header') ?>
            </button>

            <div class="collapse" id="advanced_container">
                <div class="form-group">
                    <div class="d-flex flex-column flex-xl-row justify-content-between">
                        <label for="project_id"><i class="fas fa-fw fa-sm fa-project-diagram text-muted mr-1"></i> <?= l('projects.project_id') ?></label>
                        <a href="<?= url('project-create') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('projects.create') ?></a>
                    </div>
                    <select id="project_id" name="project_id" class="custom-select">
                        <option value=""><?= l('global.none') ?></option>
                        <?php foreach($data->projects as $row): ?>
                            <option value="<?= $row->project_id ?>" <?= $data->link->project_id == $row->project_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                        <?php endforeach ?>
                    </select>
                </div>

                <?php if(settings()->links->splash_page_is_enabled): ?>
                    <div <?= $this->user->plan_settings->splash_pages_limit ? null : 'data-toggle="tooltip" title="' . l('global.info_message.plan_feature_no_access') . '"' ?>>
                        <div class="<?= $this->user->plan_settings->splash_pages_limit ? null : 'container-disabled' ?>">
                            <div class="form-group">
                                <div class="d-flex flex-column flex-xl-row justify-content-between">
                                    <label for="splash_page_id"><i class="fas fa-fw fa-sm fa-droplet text-muted mr-1"></i> <?= l('splash_pages.splash_page_id') ?></label>
                                    <a href="<?= url('splash-pages') ?>" target="_blank" class="small mb-2"><i class="fas fa-fw fa-sm fa-plus mr-1"></i> <?= l('splash_pages.create') ?></a>
                                </div>
                                <select id="splash_page_id" name="splash_page_id" class="custom-select">
                                    <option value=""><?= l('global.none') ?></option>
                                    <?php foreach($data->splash_pages as $row): ?>
                                        <option value="<?= $row->splash_page_id ?>" <?= $data->link->splash_page_id == $row->splash_page_id ? 'selected="selected"' : null?>><?= $row->name ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                <?php endif ?>
            </div>

            <div class="mt-4">
                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.update') ?></button>
            </div>
        </form>

    </div>
</div>

<template id="template_vcard_social">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_label') ?></label>
            <input id="" type="text" name="vcard_social_label[]" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['social_label']['max_length'] ?>" required="required" />
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-link fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_social_value') ?></label>
            <input id="" type="url" name="vcard_social_value[]" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['social_value']['max_length'] ?>" required="required" />
        </div>

        <button type="button" data-remove="vcard_social" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>

<template id="template_vcard_phone_numbers">
    <div class="mb-4">
        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-bookmark fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_label') ?></label>
            <input id="" type="text" name="vcard_phone_number_label[]" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['phone_number_label']['max_length'] ?>" />
            <small class="form-text text-muted"><?= l('create_biolink_vcard_modal.vcard_phone_number_label_help') ?></small>
        </div>

        <div class="form-group">
            <label for=""><i class="fas fa-fw fa-phone-square-alt fa-sm text-muted mr-1"></i> <?= l('create_biolink_vcard_modal.vcard_phone_number_value') ?></label>
            <input id="" type="text" name="vcard_phone_number_value[]" class="form-control" maxlength="<?= $data->links_types['vcard']['fields']['phone_number_value']['max_length'] ?>" required="required" />
        </div>

        <button type="button" data-remove="vcard_phone_numbers" class="btn btn-sm btn-block btn-outline-danger"><i class="fas fa-fw fa-times"></i> <?= l('global.delete') ?></button>
    </div>
</template>
<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
<script>
    /* Settings Tab */
    let schedule_handler = () => {
        if($('#schedule').is(':checked')) {
            $('#schedule_container').show();
        } else {
            $('#schedule_container').hide();
        }
    };

    $('#schedule').on('change', schedule_handler);

    schedule_handler();

    /* Daterangepicker */
    let locale = <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>;
    $('[data-daterangepicker]').daterangepicker({
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: {...locale, format: 'YYYY-MM-DD HH:mm:ss'},
        timePicker: true,
        timePicker24Hour: true,
        timePickerSeconds: true,
    }, (start, end, label) => {
    });

    /* Form handling */
    $('form[name="update_vcard"]').on('submit', event => {
        let form = $(event.currentTarget)[0];
        let data = new FormData(form);

        let notification_container = event.currentTarget.querySelector('.notification-container');
        notification_container.innerHTML = '';
        pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

        $.ajax({
            type: 'POST',
            enctype: 'multipart/form-data',
            processData: false,
            contentType: false,
            cache: false,
            url: `${url}link-ajax`,
            data: data,
            dataType: 'json',
            success: (data) => {
                display_notifications(data.message, data.status, notification_container);
                notification_container.scrollIntoView({ behavior: 'smooth', block: 'center' });
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                if(data.status == 'success') {
                    update_main_url(data.details.url);

                    if(data.details?.images) {
                        for(const [key, value] of Object.entries(data.details.images)) {
                            event.currentTarget.querySelector(`input[name="${key}"]`).value = null;

                            if(event.currentTarget.querySelector(`[name="${key}_remove"]`) && event.currentTarget.querySelector(`[name="${key}_remove"]`).checked) {
                                event.currentTarget.querySelector(`[name="${key}_remove"]`).click();
                            }

                            if(value) {
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('src', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).classList.remove('d-none');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).setAttribute('href', value);
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).classList.remove('d-none');
                                event.currentTarget.querySelectorAll(`[data-image-container="${key}"]`).forEach(element => element.classList.remove('d-none'));
                            } else {
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).setAttribute('src', '');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] img`).classList.add('d-none');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).setAttribute('href', '');
                                event.currentTarget.querySelector(`[data-image-container="${key}"] a`).classList.add('d-none');
                                event.currentTarget.querySelectorAll(`[data-image-container="${key}"]`).forEach(element => element.classList.add('d-none'));
                            }
                        }
                    }
                }
            },
            error: () => {
                enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
            },
        });

        event.preventDefault();
    })
</script>

<script>
    /* Vcard Social Script */
    'use strict';

    /* add new */
    let vcard_social_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_vcard_social`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="vcard_socials_${biolink_block_id}"] .mb-4`).length;

        if(count >= 20) return;

        clone.querySelector(`input[name="vcard_social_label[]"`).setAttribute('name', `vcard_social_label[${count}]`);
        clone.querySelector(`input[name="vcard_social_value[]"`).setAttribute('name', `vcard_social_value[${count}]`);

        document.querySelector(`[id="vcard_socials_${biolink_block_id}"]`).appendChild(clone);

        vcard_social_remove_initiator();
    };

    document.querySelectorAll('[data-add="vcard_social"]').forEach(element => {
        element.addEventListener('click', vcard_social_add);
    })

    /* remove */
    let vcard_social_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let vcard_social_remove_initiator = () => {
        document.querySelectorAll('[id^="vcard_socials_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', vcard_social_remove);
            element.addEventListener('click', vcard_social_remove)
        })
    };

    vcard_social_remove_initiator();
</script>

<script>
    /* Vcard Phone Numbers */
    'use strict';

    /* add new */
    let vcard_phone_number_add = event => {
        let biolink_block_id = event.currentTarget.getAttribute('data-biolink-block-id');
        let clone = document.querySelector(`#template_vcard_phone_numbers`).content.cloneNode(true);
        let count = document.querySelectorAll(`[id="vcard_phone_numbers_${biolink_block_id}"] .mb-4`).length;

        if(count >= 20) return;

        clone.querySelector(`input[name="vcard_phone_number_label[]"`).setAttribute('name', `vcard_phone_number_label[${count}]`);
        clone.querySelector(`input[name="vcard_phone_number_value[]"`).setAttribute('name', `vcard_phone_number_value[${count}]`);

        document.querySelector(`[id="vcard_phone_numbers_${biolink_block_id}"]`).appendChild(clone);

        vcard_phone_number_remove_initiator();
    };

    document.querySelectorAll('[data-add="vcard_phone_numbers"]').forEach(element => {
        element.addEventListener('click', vcard_phone_number_add);
    })

    /* remove */
    let vcard_phone_number_remove = event => {
        event.currentTarget.closest('.mb-4').remove();
    };

    let vcard_phone_number_remove_initiator = () => {
        document.querySelectorAll('[id^="vcard_phone_numbers_"] [data-remove]').forEach(element => {
            element.removeEventListener('click', vcard_phone_number_remove);
            element.addEventListener('click', vcard_phone_number_remove)
        })
    };

    vcard_phone_number_remove_initiator();
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
