<?php if(\Altum\Authentication::check() && isset($_SESSION['admin_user_id'])): ?>
    <div class="team-delegate-access-wrapper py-2 bg-dark small text-light">
        <div class="container d-flex justify-content-between">
            <div><i class="fas fa-fw fa-sm fa-fingerprint mr-1"></i> <?= l('global.admin_impersonate_user_help') ?> <span class="font-weight-bold"><?= $this->user->name . ' (' . $this->user->email . ')' ?></span></div>
            <div><a href="<?= url('logout?admin_impersonate_user') ?>" class="text-light text-decoration-none"><i class="fas fa-fw fa-sm fa-times mr-1"></i> <?= l('global.admin_impersonate_user_logout') ?></a></div>
        </div>
    </div>
<?php endif ?>
