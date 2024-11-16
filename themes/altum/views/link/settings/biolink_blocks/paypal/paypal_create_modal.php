<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="create_biolink_paypal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" data-toggle="modal" data-target="#biolink_link_create_modal" data-dismiss="modal" class="btn btn-sm btn-link"><i class="fas fa-fw fa-chevron-circle-left text-muted"></i></button>
                <h5 class="modal-title"><?= l('create_biolink_paypal_modal.header') ?></h5>
                <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <form name="create_biolink_paypal" method="post" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="request_type" value="create" />
                    <input type="hidden" name="link_id" value="<?= $data->link->link_id ?>" />
                    <input type="hidden" name="block_type" value="paypal" />

                    <div class="notification-container"></div>

                    <div class="form-group">
                        <label for="paypal_type"><i class="fab fa-fw fa-paypal fa-sm text-muted mr-1"></i> <?= l('create_biolink_paypal_modal.type') ?></label>
                        <select id="paypal_type" name="type" class="custom-select">
                            <?php foreach(['buy_now', 'add_to_cart', 'donation'] as $key): ?>
                                <option value="<?= $key ?>"><?= l('create_biolink_paypal_modal.type_' . $key) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="paypal_email"><i class="fas fa-fw fa-envelope fa-sm text-muted mr-1"></i> <?= l('create_biolink_paypal_modal.email') ?></label>
                        <input id="paypal_email" type="email" class="form-control" name="email" required="required" maxlength="320" />
                    </div>

                    <div class="form-group">
                        <label for="paypal_title"><i class="fas fa-fw fa-heading fa-sm text-muted mr-1"></i> <?= l('create_biolink_paypal_modal.title') ?></label>
                        <input id="paypal_title" type="text" name="title" maxlength="256" class="form-control" value="" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="paypal_currency"><i class="fas fa-fw fa-euro-sign fa-sm text-muted mr-1"></i> <?= l('create_biolink_paypal_modal.currency') ?></label>
                        <input id="paypal_currency" type="text" name="currency" maxlength="8" class="form-control" value="" placeholder="<?= l('create_biolink_paypal_modal.currency_placeholder') ?>" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="paypal_price"><i class="fas fa-fw fa-dollar-sign fa-sm text-muted mr-1"></i> <?= l('create_biolink_paypal_modal.price') ?></label>
                        <input id="paypal_price" type="number" name="price" min="1" step="0.01" class="form-control" value="" required="required" />
                    </div>

                    <div class="form-group">
                        <label for="paypal_name"><i class="fas fa-fw fa-signature fa-sm text-muted mr-1"></i> <?= l('create_biolink_link_modal.input.name') ?></label>
                        <input id="paypal_name" type="text" name="name" maxlength="128" class="form-control" required="required" />
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
