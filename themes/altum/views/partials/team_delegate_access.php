<?php if(\Altum\Authentication::check() && \Altum\Teams::is_delegated()): ?>
    <div class="team-delegate-access-wrapper py-2 bg-gray-200 small text-muted">
        <div class="container d-flex justify-content-between">
            <div><i class="fas fa-fw fa-sm fa-user-shield mr-1"></i> <?= sprintf(l('global.team_delegate_access_help'), '<strong>' . $this->user->name . '</strong>', '<strong>' . \Altum\Teams::$team->name . '</strong>') ?></div>
            <div><a href="<?= url('logout?team') ?>" class="text-decoration-none font-weight-bold"><i class="fas fa-fw fa-times mr-1"></i> <?= l('global.team_delegate_access_logout') ?></a></div>
        </div>
    </div>
<?php endif ?>
