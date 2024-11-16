<?php defined('ALTUMCODE') || die() ?>

<div class="app-sidebar">
    <div class="app-sidebar-title text-truncate">
        <a
                href="<?= url() ?>"
                data-logo
                data-light-value="<?= settings()->main->logo_light != '' ? \Altum\Uploads::get_full_url('logo_light') . settings()->main->logo_light : settings()->main->title ?>"
                data-light-class="<?= settings()->main->logo_light != '' ? 'img-fluid navbar-logo' : '' ?>"
                data-light-tag="<?= settings()->main->logo_light != '' ? 'img' : 'span' ?>"
                data-dark-value="<?= settings()->main->logo_dark != '' ? \Altum\Uploads::get_full_url('logo_dark') . settings()->main->logo_dark : settings()->main->title ?>"
                data-dark-class="<?= settings()->main->logo_dark != '' ? 'img-fluid navbar-logo' : '' ?>"
                data-dark-tag="<?= settings()->main->logo_dark != '' ? 'img' : 'span' ?>"
        >
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= \Altum\Uploads::get_full_url('logo_' . \Altum\ThemeStyle::get()) . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="img-fluid navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <?= settings()->main->title ?>
            <?php endif ?>
        </a>
    </div>

    <div class="app-sidebar-links-wrapper flex-grow-1">
        <ul class="app-sidebar-links">
            <?php if(\Altum\Authentication::check()): ?>
                <li class="<?= \Altum\Router::$controller == 'Dashboard' ? 'active' : null ?> d-flex dropdown" id="internal_notifications">
                    <a href="<?= url('dashboard') ?>"><i class="fas fa-fw fa-sm fa-th mr-2"></i> <?= l('dashboard.menu') ?></a>

                    <?php if(settings()->internal_notifications->users_is_enabled): ?>
                        <a id="internal_notifications_link" href="#" class="default w-auto dropdown-toggle dropdown-toggle-simple ml-1" data-internal-notifications="user" data-tooltip data-tooltip-hide-on-click title="<?= l('internal_notifications.menu') ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-boundary="window">
                            <span id="internal_notifications_icon_wrapper" class="fa-layers fa-fw">
                                <i class="fas fa-fw fa-bell"></i>
                                <?php if($this->user->has_pending_internal_notifications): ?>
                                    <span class="fa-layers-counter text-danger internal-notification-icon">&nbsp;</span>
                                <?php endif ?>
                            </span>
                        </a>

                        <div id="internal_notifications_content" class="dropdown-menu dropdown-menu-right px-4 py-2" style="width: 550px;max-width: 550px;"></div>

                        <?php include_view(THEME_PATH . 'views/partials/internal_notifications_js.php', ['has_pending_internal_notifications' => $this->user->has_pending_internal_notifications]) ?>
                    <?php endif ?>
                </li>

                <?php if(settings()->links->biolinks_is_enabled): ?>
                    <li class="<?= (\Altum\Router::$controller == 'Links' && ($_GET['type'] ?? null) == 'biolink') || (\Altum\Router::$controller == 'Link' && $this->link->type == 'biolink') ? 'active' : null ?>">
                        <a href="<?= url('links?type=biolink') ?>"><i class="fas fa-fw fa-sm fa-hashtag mr-2"></i> <?= l('links.menu.biolink') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->shortener_is_enabled): ?>
                    <li class="<?= (\Altum\Router::$controller == 'Links' && ($_GET['type'] ?? null) == 'link') || (\Altum\Router::$controller == 'Link' && $this->link->type == 'link') ? 'active' : null ?>">
                        <a href="<?= url('links?type=link') ?>"><i class="fas fa-fw fa-sm fa-link mr-2"></i> <?= l('links.menu.link') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->files_is_enabled): ?>
                    <li class="<?= (\Altum\Router::$controller == 'Links' && ($_GET['type'] ?? null) == 'file') || (\Altum\Router::$controller == 'Link' && $this->link->type == 'file') ? 'active' : null ?>">
                        <a href="<?= url('links?type=file') ?>"><i class="fas fa-fw fa-sm fa-file mr-2"></i> <?= l('links.menu.file') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->vcards_is_enabled): ?>
                    <li class="<?= (\Altum\Router::$controller == 'Links' && ($_GET['type'] ?? null) == 'vcard') || (\Altum\Router::$controller == 'Link' && $this->link->type == 'vcard') ? 'active' : null ?>">
                        <a href="<?= url('links?type=vcard') ?>"><i class="fas fa-fw fa-sm fa-id-card mr-2"></i> <?= l('links.menu.vcard') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->events_is_enabled): ?>
                    <li class="<?= (\Altum\Router::$controller == 'Links' && ($_GET['type'] ?? null) == 'event') || (\Altum\Router::$controller == 'Link' && $this->link->type == 'event') ? 'active' : null ?>">
                        <a href="<?= url('links?type=event') ?>"><i class="fas fa-fw fa-sm fa-calendar mr-2"></i> <?= l('links.menu.event') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->static_is_enabled): ?>
                    <li class="<?= (\Altum\Router::$controller == 'Links' && ($_GET['type'] ?? null) == 'static') || (\Altum\Router::$controller == 'Link' && $this->link->type == 'static') ? 'active' : null ?>">
                        <a href="<?= url('links?type=static') ?>"><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('links.menu.static') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->codes->qr_codes_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['QrCodes', 'QrCodeUpdate', 'QrCodeCreate']) ? 'active' : null ?>">
                        <a href="<?= url('qr-codes') ?>"><i class="fas fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('qr_codes.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix')): ?>
                    <div class="divider-wrapper">
                        <div class="divider"></div>
                    </div>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->documents_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Documents', 'DocumentUpdate', 'DocumentCreate']) ? 'active' : null ?>">
                        <a href="<?= url('documents') ?>"><i class="fas fa-fw fa-sm fa-robot mr-2"></i> <?= l('documents.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->images_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Images', 'ImageUpdate', 'ImageCreate']) ? 'active' : null ?>">
                        <a href="<?= url('images') ?>"><i class="fas fa-fw fa-sm fa-icons mr-2"></i> <?= l('images.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->transcriptions_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Transcriptions', 'TranscriptionUpdate', 'TranscriptionCreate']) ? 'active' : null ?>">
                        <a href="<?= url('transcriptions') ?>"><i class="fas fa-fw fa-sm fa-microphone-alt mr-2"></i> <?= l('transcriptions.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->syntheses_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Syntheses', 'SynthesisUpdate', 'SynthesisCreate']) ? 'active' : null ?>">
                        <a href="<?= url('syntheses') ?>"><i class="fas fa-fw fa-sm fa-voicemail mr-2"></i> <?= l('syntheses.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('aix') && settings()->aix->chats_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Chats', 'Chat', 'ChatCreate']) ? 'active' : null ?>">
                        <a href="<?= url('chats') ?>"><i class="fas fa-fw fa-sm fa-comments mr-2"></i> <?= l('chats.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(\Altum\Plugin::is_active('email-signatures') && settings()->signatures->is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Signatures', 'SignatureUpdate', 'SignatureCreate']) ? 'active' : null ?>">
                        <a href="<?= url('signatures') ?>"><i class="fas fa-fw fa-sm fa-file-signature mr-2"></i> <?= l('signatures.menu') ?></a>
                    </li>
                <?php endif ?>
            <?php endif ?>

            <?php if(settings()->tools->is_enabled && (settings()->tools->access == 'everyone' || (settings()->tools->access == 'users' && \Altum\Authentication::check()))): ?>
                <li class="<?= \Altum\Router::$controller == 'Tools' ? 'active' : null ?>">
                    <a href="<?= url('tools') ?>"><i class="fas fa-fw fa-sm fa-tools mr-2"></i> <?= l('tools.menu') ?></a>
                </li>
            <?php endif ?>

            <div class="divider-wrapper">
                <div class="divider"></div>
            </div>

            <?php if(\Altum\Authentication::check()): ?>

                <?php if(settings()->links->domains_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Domains', 'DomainUpdate', 'DomainCreate']) ? 'active' : null ?>">
                        <a href="<?= url('domains') ?>"><i class="fas fa-fw fa-sm fa-globe mr-2"></i> <?= l('domains.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->pixels_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['Pixels', 'PixelUpdate', 'PixelCreate']) ? 'active' : null ?>">
                        <a href="<?= url('pixels') ?>"><i class="fas fa-fw fa-sm fa-adjust mr-2"></i> <?= l('pixels.menu') ?></a>
                    </li>
                <?php endif ?>

                <li class="<?= in_array(\Altum\Router::$controller, ['Projects', 'ProjectUpdate', 'ProjectCreate']) ? 'active' : null ?>">
                    <a href="<?= url('projects') ?>"><i class="fas fa-fw fa-sm fa-project-diagram mr-2"></i> <?= l('projects.menu') ?></a>
                </li>

                <?php if(settings()->links->splash_page_is_enabled): ?>
                    <li class="<?= in_array(\Altum\Router::$controller, ['SplashPages', 'SplashPageUpdate', 'SplashPageCreate']) ? 'active' : null ?>">
                        <a href="<?= url('splash-pages') ?>"><i class="fas fa-fw fa-sm fa-droplet mr-2"></i> <?= l('splash_pages.menu') ?></a>
                    </li>
                <?php endif ?>

                <?php if(settings()->links->biolinks_is_enabled): ?>
                    <li class="<?= \Altum\Router::$controller == 'Data' ? 'active' : null ?>">
                        <a href="<?= url('data') ?>"><i class="fas fa-fw fa-sm fa-database mr-2"></i> <?= l('data.menu') ?></a>
                    </li>

                    <?php if(\Altum\Plugin::is_active('payment-blocks')): ?>
                        <li class="<?= in_array(\Altum\Router::$controller, ['PaymentProcessors', 'PaymentProcessorUpdate', 'PaymentProcessorCreate']) ? 'active' : null ?>">
                            <a href="<?= url('payment-processors') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('payment_processors.menu') ?></a>
                        </li>
                        <li class="<?= \Altum\Router::$controller == 'GuestsPayments' ? 'active' : null ?>">
                            <a href="<?= url('guests-payments') ?>"><i class="fas fa-fw fa-sm fa-coins mr-2"></i> <?= l('guests_payments.menu') ?></a>
                        </li>
                    <?php endif ?>
                <?php endif ?>
            <?php endif ?>

            <?php if(settings()->links->biolinks_is_enabled && settings()->links->directory_is_enabled && (settings()->links->directory_access == 'everyone' || (settings()->links->directory_access == 'users' && \Altum\Authentication::check()))): ?>
                <li class="<?= \Altum\Router::$controller == 'Directory' ? 'active' : null ?>">
                    <a href="<?= url('directory') ?>"><i class="fas fa-fw fa-sm fa-sitemap mr-2"></i> <?= l('directory.menu') ?></a>
                </li>
            <?php endif ?>

            <?php foreach($data->pages as $page): ?>
                <li>
                    <a href="<?= $page->url ?>" target="<?= $page->target ?>">
                        <?php if($page->icon): ?>
                            <i class="<?= $page->icon ?> fa-fw fa-sm mr-2"></i>
                        <?php endif ?>

                        <?= $page->title ?>
                    </a>
                </li>
            <?php endforeach ?>
        </ul>
    </div>

    <?php if(\Altum\Authentication::check()): ?>

        <div class="app-sidebar-footer dropdown">
            <a href="#" class="dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <div class="d-flex align-items-center app-sidebar-footer-block">
                    <img src="<?= get_gravatar($this->user->email) ?>" class="app-sidebar-avatar mr-3" loading="lazy" />

                    <div class="app-sidebar-footer-text d-flex flex-column text-truncate">
                        <span class="text-truncate"><?= $this->user->name ?></span>
                        <small class="text-truncate"><?= $this->user->email ?></small>
                    </div>
                </div>
            </a>

            <div class="dropdown-menu dropdown-menu-right">
                <?php if(!\Altum\Teams::is_delegated()): ?>
                    <?php if(\Altum\Authentication::is_admin()): ?>
                        <a class="dropdown-item" href="<?= url('admin') ?>"><i class="fas fa-fw fa-sm fa-fingerprint text-primary mr-2"></i> <?= l('global.menu.admin') ?></a>
                        <div class="dropdown-divider"></div>
                    <?php endif ?>

                    <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['Account']) ? 'active' : null ?>" href="<?= url('account') ?>"><i class="fas fa-fw fa-sm fa-user-cog mr-2"></i> <?= l('account.menu') ?></a>

                    <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountPreferences']) ? 'active' : null ?>" href="<?= url('account-preferences') ?>"><i class="fas fa-fw fa-sm fa-sliders-h mr-2"></i> <?= l('account_preferences.menu') ?></a>

                    <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountPlan']) ? 'active' : null ?>" href="<?= url('account-plan') ?>"><i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('account_plan.menu') ?></a>

                    <?php if(settings()->payment->is_enabled): ?>
                        <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountPayments']) ? 'active' : null ?>" href="<?= url('account-payments') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('account_payments.menu') ?></a>

                        <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                            <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['Referrals']) ? 'active' : null ?>" href="<?= url('referrals') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('referrals.menu') ?></a>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if(settings()->main->api_is_enabled): ?>
                        <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['AccountApi']) ? 'active' : null ?>" href="<?= url('account-api') ?>"><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('account_api.menu') ?></a>
                    <?php endif ?>

                    <?php if(\Altum\Plugin::is_active('teams')): ?>
                        <a class="dropdown-item <?= in_array(\Altum\Router::$controller, ['TeamsSystem', 'Teams', 'Team', 'TeamCreate', 'TeamUpdate', 'TeamsMember', 'TeamsMembers', 'TeamsMemberCreate', 'TeamsMemberUpdate']) ? 'active' : null ?>" href="<?= url('teams-system') ?>"><i class="fas fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('teams_system.menu') ?></a>
                    <?php endif ?>

                    <?php if(settings()->sso->is_enabled && settings()->sso->display_menu_items && count((array) settings()->sso->websites)): ?>
                        <div class="dropdown-divider"></div>

                        <?php foreach(settings()->sso->websites as $website): ?>
                            <a class="dropdown-item" href="<?= url('sso/switch?to=' . $website->id) ?>"><i class="<?= $website->icon ?> fa-fw fa-sm mr-2"></i> <?= sprintf(l('sso.menu'), $website->name) ?></a>
                        <?php endforeach ?>

                        <div class="dropdown-divider"></div>
                    <?php endif ?>
                <?php endif ?>

                <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fas fa-fw fa-sm fa-sign-out-alt mr-2"></i> <?= l('global.menu.logout') ?></a>
            </div>
        </div>

    <?php else: ?>

        <ul class="app-sidebar-links">
            <li>
                <a class="nav-link" href="<?= url('login') ?>"><i class="fas fa-fw fa-sm fa-sign-in-alt mr-2"></i> <?= l('login.menu') ?></a>
            </li>

            <?php if(settings()->users->register_is_enabled): ?>
                <li><a class="nav-link" href="<?= url('register') ?>"><i class="fas fa-fw fa-sm fa-user-plus mr-2"></i> <?= l('register.menu') ?></a></li>
            <?php endif ?>
        </ul>

    <?php endif ?>
</div>

<?php ob_start() ?>
<script>
    document.querySelector('ul[class="app-sidebar-links"] li.active') && document.querySelector('ul[class="app-sidebar-links"] li.active').scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
