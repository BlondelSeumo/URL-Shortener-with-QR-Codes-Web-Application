<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="teams_member_join_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-check text-dark mr-2"></i>
                        <?= l('teams_member_join_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form name="teams_member_join_modal" method="post" action="<?= url('teams-members/join') ?>" role="form">
                    <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" required="required" />
                    <input type="hidden" name="team_member_id" value="" />

                    <p class="text-muted"><?= l('teams_member_join_modal.subheader') ?></p>

                    <div class="mt-4">
                        <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    /* On modal show load new data */
    $('#teams_member_join_modal').on('show.bs.modal', event => {
        let team_member_id = $(event.relatedTarget).data('team-member-id');

        $(event.currentTarget).find('input[name="team_member_id"]').val(team_member_id);
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
