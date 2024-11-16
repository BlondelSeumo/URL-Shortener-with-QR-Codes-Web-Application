<?php defined('ALTUMCODE') || die() ?>

<div id="<?= 'biolink_block_id_' . $data->link->biolink_block_id ?>" data-biolink-block-id="<?= $data->link->biolink_block_id ?>" class="col-12 my-<?= $data->biolink->settings->block_spacing ?? '2' ?>">
    <a href="#" data-toggle="modal" data-target="<?= '#email_collector_' . $data->link->biolink_block_id ?>" class="btn btn-block btn-primary link-btn <?= ($data->biolink->settings->hover_animation ?? 'smooth') != 'false' ? 'link-hover-animation-' . ($data->biolink->settings->hover_animation ?? 'smooth') : null ?> <?= 'link-btn-' . $data->link->settings->border_radius ?> <?= $data->link->design->link_class ?>" style="<?= $data->link->design->link_style ?>" data-text-color data-border-width data-border-radius data-border-style data-border-color data-border-shadow data-animation data-background-color data-text-alignment>
        <div class="link-btn-image-wrapper <?= 'link-btn-' . $data->link->settings->border_radius ?>" <?= $data->link->settings->image ? null : 'style="display: none;"' ?>>
            <img src="<?= $data->link->settings->image ? \Altum\Uploads::get_full_url('block_thumbnail_images') . $data->link->settings->image : null ?>" class="link-btn-image" loading="lazy" />
        </div>

        <span data-icon>
            <?php if($data->link->settings->icon): ?>
                <i class="<?= $data->link->settings->icon ?> mr-1"></i>
            <?php endif ?>
        </span>

        <span data-name><?= $data->link->settings->name ?></span>
    </a>
</div>

<?php ob_start() ?>
<div class="modal fade" id="<?= 'email_collector_' . $data->link->biolink_block_id ?>" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title"><?= $data->link->settings->name ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form id="<?= 'email_collector_form_' . $data->link->biolink_block_id ?>" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="biolink_block_id" value="<?= $data->link->biolink_block_id ?>" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-gray-50"><i class="fas fa-fw fa-envelope"></i></div>
                            </div>
                            <input type="email" class="form-control" name="email" maxlength="320" required="required" placeholder="<?= $data->link->settings->email_placeholder ?>" aria-label="<?= $data->link->settings->email_placeholder ?>" />
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text bg-gray-50"><i class="fas fa-fw fa-signature"></i></div>
                            </div>
                            <input type="text" class="form-control" name="name" maxlength="64" required="required" placeholder="<?= $data->link->settings->name_placeholder ?>" aria-label="<?= $data->link->settings->name_placeholder ?>" />
                        </div>
                    </div>

                    <?php if($data->link->settings->show_agreement): ?>
                        <div class="custom-control custom-switch">
                            <input type="checkbox" id="<?= 'email_collector_agreement_' . $data->link->biolink_block_id ?>" name="agreement" class="custom-control-input" required="required" />
                            <label class="custom-control-label font-weight-normal" for="<?= 'email_collector_agreement_' . $data->link->biolink_block_id ?>">
                                <?= $data->link->settings->agreement_text ?>

                                <?php if($data->link->settings->show_agreement): ?>
                                    <a href="<?= $data->link->settings->agreement_url ?>" target="_blank"><i class="fas fa-fw fa-sm fa-external-link-alt"></i></a>
                                <?php endif ?>
                            </label>
                        </div>
                    <?php endif ?>

                    <?php if(settings()->captcha->biolink_is_enabled && settings()->captcha->type != 'basic'): ?>
                    <div class="form-group">
                        <?php (new \Altum\Captcha())->display() ?>
                    </div>
                    <?php endif ?>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-lg btn-primary" data-is-ajax><?= $data->link->settings->button_text ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<?php \Altum\Event::add_content(ob_get_clean(), 'modals') ?>


<?php if(!\Altum\Event::exists_content_type_key('javascript', 'email_collector')): ?>
    <?php ob_start() ?>
    <script>
        'use strict';

        /* Go over all mail buttons to make sure the user can still submit mail */
        $('form[id^="email_collector_"]').each((index, element) => {
            let biolink_block_id = $(element).find('input[name="biolink_block_id"]').val();
            let is_converted = sessionStorage.getItem(`email_collector_${biolink_block_id}`);

            if(is_converted) {
                /* Set the submit button to disabled */
                $(element).find('button[type="submit"]').attr('disabled', 'disabled');
            }
        });
        /* Form handling for mail submissions if any */
        $('form[id^="email_collector_"]').on('submit', event => {
            let biolink_block_id = $(event.currentTarget).find('input[name="biolink_block_id"]').val();
            let is_converted = sessionStorage.getItem(`email_collector_${biolink_block_id}`);

            let notification_container = event.currentTarget.querySelector('.notification-container');
            notification_container.innerHTML = '';
            pause_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

            if(!is_converted) {
                $.ajax({
                    type: 'POST',
                    url: `${site_url}l/link/email_collector`,
                    data: $(event.currentTarget).serialize(),
                    dataType: 'json',
                    success: (data) => {
                        enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));

                        if(data.status == 'error') {
                            display_notifications(data.message, 'error', notification_container);
                        } else if(data.status == 'success') {
                            display_notifications(data.message, 'success', notification_container);

                            setTimeout(() => {

                                /* Hide modal */
                                $(event.currentTarget).closest('.modal').modal('hide');

                                /* Remove the notification */
                                notification_container.innerHTML = '';

                                /* Set the localstorage to mention that the user was converted */
                                sessionStorage.setItem(`email_collector_${biolink_block_id}`, true);

                                /* Set the submit button to disabled */
                                $(event.currentTarget).find('button[type="submit"]').attr('disabled', 'disabled');

                                if(data.details.thank_you_url) {
                                    window.location.replace(data.details.thank_you_url);
                                }

                            }, 750);
                        }

                        /* Reset captcha */
                        try {
                            grecaptcha.reset();
                            hcaptcha.reset();
                            turnstile.reset();
                        } catch (error) {}
                    },
                    error: () => {
                        enable_submit_button(event.currentTarget.querySelector('[type="submit"][name="submit"]'));
                        display_notifications(<?= json_encode(l('global.error_message.basic')) ?>, 'error', notification_container);
                    },
                });

            }

            event.preventDefault();
        })
    </script>
    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript', 'email_collector') ?>
<?php endif ?>

