<?php defined('ALTUMCODE') || die() ?>

<section class="admin-sidebar">
    <div class="admin-sidebar-title">
        <div
                class="h3 m-0 text-decoration-none text-truncate"
                data-logo
                data-light-value="<?= settings()->main->logo_light != '' ? \Altum\Uploads::get_full_url('logo_light') . settings()->main->logo_light : settings()->main->title ?>"
                data-light-class="<?= settings()->main->logo_light != '' ? 'img-fluid admin-navbar-logo' : 'admin-navbar-brand text-truncate' ?>"
                data-light-tag="<?= settings()->main->logo_light != '' ? 'img' : 'div' ?>"
                data-dark-value="<?= settings()->main->logo_dark != '' ? \Altum\Uploads::get_full_url('logo_dark') . settings()->main->logo_dark : settings()->main->title ?>"
                data-dark-class="<?= settings()->main->logo_dark != '' ? 'img-fluid admin-navbar-logo' : 'admin-navbar-brand text-truncate' ?>"
                data-dark-tag="<?= settings()->main->logo_dark != '' ? 'img' : 'div' ?>"

                id="sidebar_title"
                tabindex="0"
                data-toggle="tooltip"
                data-placement="right"
                data-html="true"
                data-trigger="hover"
                data-delay='{ "hide": 5500 }'
                title="
            <div class='d-flex text-left flex-column'>
                <div class='mb-2'><a href='<?= url() ?>' class='text-gray-50 text-decoration-none'>🌐 &nbsp; <?= l('index.menu') ?></a></div>
                <div><a href='<?= url('dashboard') ?>' class='text-gray-50 text-decoration-none'>🖥️ &nbsp; <?= l('dashboard.menu') ?></a></div>
            </div>
            "
        >
            <?php if(settings()->main->{'logo_' . \Altum\ThemeStyle::get()} != ''): ?>
                <img src="<?= \Altum\Uploads::get_full_url('logo_' . \Altum\ThemeStyle::get()) . settings()->main->{'logo_' . \Altum\ThemeStyle::get()} ?>" class="img-fluid admin-navbar-logo" alt="<?= l('global.accessibility.logo_alt') ?>" />
            <?php else: ?>
                <div class="admin-navbar-brand text-truncate"><?= settings()->main->title ?></div>
            <?php endif ?>
        </div>
    </div>

    <div class="admin-sidebar-links-wrapper">
        <ul class="admin-sidebar-links">
            <li class="<?= \Altum\Router::$controller == 'AdminIndex' ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/') ?>">
                    <i class="fas fa-fw fa-sm fa-fingerprint mr-2"></i> <?= l('admin_index.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminUsers', 'AdminUserUpdate', 'AdminUserCreate', 'AdminUserView']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/users') ?>">
                    <i class="fas fa-fw fa-sm fa-users mr-2"></i> <?= l('admin_users.menu') ?>
                </a>
            </li>

            <li class="<?= \Altum\Router::$controller == 'AdminSettings' ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/settings') ?>">
                    <i class="fas fa-fw fa-sm fa-wrench mr-2"></i> <?= l('admin_settings.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminPlans', 'AdminPlanCreate', 'AdminPlanUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/plans') ?>">
                    <i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('admin_plans.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminLanguages', 'AdminLanguageCreate', 'AdminLanguageUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/languages') ?>">
                    <i class="fas fa-fw fa-sm fa-language mr-2"></i> <?= l('admin_languages.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminBroadcasts', 'AdminBroadcastCreate', 'AdminBroadcastUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/broadcasts') ?>">
                    <i class="fas fa-fw fa-sm fa-mail-bulk mr-2"></i> <?= l('admin_broadcasts.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminInternalNotifications', 'AdminInternalNotificationCreate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/internal-notifications') ?>">
                    <i class="fas fa-fw fa-sm fa-bell mr-2"></i> <?= l('admin_internal_notifications.menu') ?>
                </a>
            </li>

            <?php if(\Altum\Plugin::is_active('push-notifications')): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['AdminPushSubscribers', 'AdminPushNotifications', 'AdminPushNotificationCreate', 'AdminPushNotificationUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/push-notifications') ?>">
                        <i class="fas fa-fw fa-sm fa-bolt-lightning mr-2"></i> <?= l('admin_push_notifications.menu') ?>
                    </a>
                </li>
            <?php endif ?>

            <li class="<?= \Altum\Router::$controller == 'AdminPlugins' ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/plugins') ?>">
                    <i class="fas fa-fw fa-sm fa-puzzle-piece mr-2"></i> <?= l('admin_plugins.menu') ?>
                </a>
            </li>

            <li class="<?= \Altum\Router::$controller == 'AdminStatistics' ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/statistics') ?>">
                    <i class="fas fa-fw fa-sm fa-chart-bar mr-2"></i> <?= l('admin_statistics.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminPages', 'AdminPageCreate', 'AdminPageUpdate', 'AdminPagesCategories', 'AdminPagesCategoryCreate', 'AdminPagesCategoryUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="#admin_sidebar_resources_container" data-toggle="collapse" role="button" aria-expanded="false">
                    <i class="fas fa-fw fa-sm fa-info-circle mr-2"></i> <?= l('admin_resources.menu') ?> <i class="fas fa-fw fa-sm fa-caret-down"></i>
                </a>
            </li>

            <div id="admin_sidebar_resources_container" class="mt-1 collapse bg-gray-200 rounded <?= in_array(\Altum\Router::$controller, ['AdminPages', 'AdminPageCreate', 'AdminPageUpdate', 'AdminPagesCategories', 'AdminPagesCategoryCreate', 'AdminPagesCategoryUpdate']) ? 'show' : null ?>">
                <li class="<?= in_array(\Altum\Router::$controller, ['AdminPagesCategories', 'AdminPagesCategoryCreate', 'AdminPagesCategoryUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/pages-categories') ?>">
                        <i class="fas fa-fw fa-sm fa-book mr-2"></i> <?= l('admin_pages_categories.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminPages', 'AdminPageCreate', 'AdminPageUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/pages') ?>">
                        <i class="fas fa-fw fa-sm fa-copy mr-2"></i> <?= l('admin_pages.menu') ?>
                    </a>
                </li>
            </div>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminBlogPosts', 'AdminBlogPostCreate', 'AdminBlogPostUpdate', 'AdminBlogPostsCategories', 'AdminBlogPostsCategoryCreate', 'AdminBlogPostsCategoryUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="#admin_sidebar_blog_container" data-toggle="collapse" role="button" aria-expanded="false">
                    <i class="fas fa-fw fa-sm fa-blog mr-2"></i> <?= l('admin_blog.menu') ?> <i class="fas fa-fw fa-sm fa-caret-down"></i>
                </a>
            </li>

            <div id="admin_sidebar_blog_container" class="mt-1 collapse bg-gray-200 rounded <?= in_array(\Altum\Router::$controller, ['AdminBlogPosts', 'AdminBlogPostCreate', 'AdminBlogPostUpdate', 'AdminBlogPostsCategories', 'AdminBlogPostsCategoryCreate', 'AdminBlogPostsCategoryUpdate']) ? 'show' : null ?>">
                <li class="<?= in_array(\Altum\Router::$controller, ['AdminBlogPostsCategories', 'AdminBlogPostsCategoryCreate', 'AdminBlogPostsCategoryUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/blog-posts-categories') ?>">
                        <i class="fas fa-fw fa-sm fa-map mr-2"></i> <?= l('admin_blog_posts_categories.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminBlogPosts', 'AdminBlogPostCreate', 'AdminBlogPostUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/blog-posts') ?>">
                        <i class="fas fa-fw fa-sm fa-paste mr-2"></i> <?= l('admin_blog_posts.menu') ?>
                    </a>
                </li>
            </div>

            <li class="<?= \Altum\Router::$controller == 'AdminApiDocumentation' ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/api-documentation') ?>">
                    <i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('admin_api_documentation.menu') ?>
                </a>
            </li>

            <?php if(in_array(settings()->license->type, ['SPECIAL','Extended License', 'extended'])): ?>
                <div class="divider-wrapper">
                    <div class="divider"></div>
                </div>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminCodes', 'AdminCodeCreate', 'AdminCodeUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/codes') ?>">
                        <i class="fas fa-fw fa-sm fa-tags mr-2"></i> <?= l('admin_codes.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminTaxes', 'AdminTaxCreate', 'AdminTaxUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/taxes') ?>">
                        <i class="fas fa-fw fa-sm fa-paperclip mr-2"></i> <?= l('admin_taxes.menu') ?>
                    </a>
                </li>

                <li class="<?= \Altum\Router::$controller == 'AdminPayments' ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/payments') ?>">
                        <i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('admin_payments.menu') ?>
                    </a>
                </li>

                <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                    <li class="<?= \Altum\Router::$controller == 'AdminAffiliatesWithdrawals' ? 'active' : null ?>">
                        <a class="nav-link text-truncate" href="<?= url('admin/affiliates-withdrawals') ?>">
                            <i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('admin_affiliates_withdrawals.menu') ?>
                        </a>
                    </li>
                <?php endif ?>
            <?php endif ?>

            <div class="divider-wrapper">
                <div class="divider"></div>
            </div>

            <?php if(\Altum\Plugin::is_active('teams')): ?>
                <li class="<?= \Altum\Router::$controller == 'AdminTeams' ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/teams') ?>">
                        <i class="fas fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('admin_teams.menu') ?>
                    </a>
                </li>
            <?php endif ?>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminUsersLogs']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/users-logs') ?>">
                    <i class="fas fa-fw fa-sm fa-scroll mr-2"></i> <?= l('admin_users_logs.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminDomains', 'AdminDomainCreate', 'AdminDomainUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/domains') ?>">
                    <i class="fas fa-fw fa-sm fa-globe mr-2"></i> <?= l('admin_domains.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminLinks']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/links') ?>">
                    <i class="fas fa-fw fa-sm fa-link mr-2"></i> <?= l('admin_links.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminBiolinksBlocks']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/biolinks-blocks') ?>">
                    <i class="fas fa-fw fa-sm fa-table-cells-large mr-2"></i> <?= l('admin_biolinks_blocks.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminBiolinksThemes', 'AdminBiolinkThemeCreate', 'AdminBiolinkThemeUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/biolinks-themes') ?>">
                    <i class="fas fa-fw fa-sm fa-palette mr-2"></i> <?= l('admin_biolinks_themes.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminBiolinksTemplates', 'AdminBiolinkTemplateCreate', 'AdminBiolinkTemplateUpdate']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/biolinks-templates') ?>">
                    <i class="fas fa-fw fa-sm fa-moon mr-2"></i> <?= l('admin_biolinks_templates.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminQrCodes']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/qr-codes') ?>">
                    <i class="fas fa-fw fa-sm fa-qrcode mr-2"></i> <?= l('admin_qr_codes.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminProjects']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/projects') ?>">
                    <i class="fas fa-fw fa-sm fa-project-diagram mr-2"></i> <?= l('admin_projects.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminSplashPages']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/splash-pages') ?>">
                    <i class="fas fa-fw fa-sm fa-droplet mr-2"></i> <?= l('admin_splash_pages.menu') ?>
                </a>
            </li>

            <li class="<?= in_array(\Altum\Router::$controller, ['AdminPixels']) ? 'active' : null ?>">
                <a class="nav-link text-truncate" href="<?= url('admin/pixels') ?>">
                    <i class="fas fa-fw fa-sm fa-adjust mr-2"></i> <?= l('admin_pixels.menu') ?>
                </a>
            </li>

            <?php if(\Altum\Plugin::is_active('email-signatures')): ?>
                <li class="<?= in_array(\Altum\Router::$controller, ['AdminSignatures']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/signatures') ?>">
                        <i class="fas fa-fw fa-sm fa-file-signature mr-2"></i> <?= l('admin_signatures.menu') ?>
                    </a>
                </li>
            <?php endif ?>

            <?php if(\Altum\Plugin::is_active('aix')): ?>
                <div class="divider-wrapper">
                    <div class="divider"></div>
                </div>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminTemplatesCategories', 'AdminTemplateCategoryCreate', 'AdminTemplateCategoryUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/templates-categories') ?>">
                        <i class="fas fa-fw fa-sm fa-folder mr-2"></i> <?= l('admin_templates_categories.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminTemplates', 'AdminTemplateCreate', 'AdminTemplateUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/templates') ?>">
                        <i class="fas fa-fw fa-sm fa-moon mr-2"></i> <?= l('admin_templates.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminDocuments']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/documents') ?>">
                        <i class="fas fa-fw fa-sm fa-robot mr-2"></i> <?= l('admin_documents.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminTranscriptions']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/transcriptions') ?>">
                        <i class="fas fa-fw fa-sm fa-microphone-alt mr-2"></i> <?= l('admin_transcriptions.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminSyntheses']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/syntheses') ?>">
                        <i class="fas fa-fw fa-sm fa-voicemail mr-2"></i> <?= l('admin_syntheses.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminChatsAssistants', 'AdminChatAssistantCreate', 'AdminChatAssistantUpdate']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/chats-assistants') ?>">
                        <i class="fas fa-fw fa-sm fa-id-card-alt mr-2"></i> <?= l('admin_chats_assistants.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminChats']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/chats') ?>">
                        <i class="fas fa-fw fa-sm fa-comments mr-2"></i> <?= l('admin_chats.menu') ?>
                    </a>
                </li>

                <li class="<?= in_array(\Altum\Router::$controller, ['AdminImages']) ? 'active' : null ?>">
                    <a class="nav-link text-truncate" href="<?= url('admin/images') ?>">
                        <i class="fas fa-fw fa-sm fa-icons mr-2"></i> <?= l('admin_images.menu') ?>
                    </a>
                </li>
            <?php endif ?>
        </ul>

        <hr />

        <ul class="admin-sidebar-links">
            <li class="dropdown">
                <a class="nav-link text-truncate dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" href="#" aria-haspopup="true" aria-expanded="false">
                    <img src="<?= get_gravatar($this->user->email) ?>" class="admin-avatar mr-2" loading="lazy" />
                    <?= $this->user->name?>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item" href="<?= url('account') ?>"><i class="fas fa-fw fa-sm fa-user-cog mr-2"></i> <?= l('account.menu') ?></a>

                    <a class="dropdown-item" href="<?= url('account-preferences') ?>"><i class="fas fa-fw fa-sm fa-sliders-h mr-2"></i> <?= l('account_preferences.menu') ?></a>

                    <a class="dropdown-item" href="<?= url('account-plan') ?>"><i class="fas fa-fw fa-sm fa-box-open mr-2"></i> <?= l('account_plan.menu') ?></a>

                    <?php if(settings()->payment->is_enabled): ?>
                        <a class="dropdown-item" href="<?= url('account-payments') ?>"><i class="fas fa-fw fa-sm fa-credit-card mr-2"></i> <?= l('account_payments.menu') ?></a>

                        <?php if(\Altum\Plugin::is_active('affiliate')): ?>
                            <a class="dropdown-item" href="<?= url('referrals') ?>"><i class="fas fa-fw fa-sm fa-wallet mr-2"></i> <?= l('referrals.menu') ?></a>
                        <?php endif ?>
                    <?php endif ?>

                    <?php if(settings()->main->api_is_enabled): ?>
                        <a class="dropdown-item" href="<?= url('account-api') ?>"><i class="fas fa-fw fa-sm fa-code mr-2"></i> <?= l('account_api.menu') ?></a>
                    <?php endif ?>

                    <?php if(\Altum\Plugin::is_active('teams')): ?>
                        <a class="dropdown-item" href="<?= url('teams-system') ?>"><i class="fas fa-fw fa-sm fa-user-shield mr-2"></i> <?= l('teams_system.menu') ?></a>
                    <?php endif ?>

                    <?php if(settings()->sso->is_enabled && count((array) settings()->sso->websites)): ?>
                        <div class="dropdown-divider"></div>

                        <?php foreach(settings()->sso->websites as $website): ?>
                            <a class="dropdown-item" href="<?= url('sso/switch?to=' . $website->id . '&redirect=admin') ?>"><i class="<?= $website->icon ?> fa-fw fa-sm mr-2"></i> <?= sprintf(l('sso.menu'), $website->name) ?></a>
                        <?php endforeach ?>
                    <?php endif ?>
                    <div class="dropdown-divider"></div>

                    <a class="dropdown-item" href="<?= url('logout') ?>"><i class="fas fa-fw fa-sm fa-sign-out-alt mr-2"></i> <?= l('global.menu.logout') ?></a>
                </div>
            </li>
        </ul>
    </div>
</section>


<?php ob_start() ?>
<script>
    document.querySelector('ul[class="admin-sidebar-links"] li.active') && document.querySelector('ul[class="admin-sidebar-links"] li.active').scrollIntoView({ behavior: 'smooth', block: 'center' });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
