<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>
    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
        <nav aria-label="breadcrumb">
            <ol class="custom-breadcrumbs small">
                <li>
                    <a href="<?= url('teams-system') ?>"><?= l('teams_system.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li>
                    <a href="<?= url('teams') ?>"><?= l('teams.breadcrumb') ?></a><i class="fas fa-fw fa-angle-right"></i>
                </li>
                <li class="active" aria-current="page"><?= l('team.breadcrumb') ?></li>
            </ol>
        </nav>
    <?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= sprintf(l('team.header'), $data->team->name) ?></h1>
        </div>

        <div class="col-12 col-lg-auto d-flex d-print-none">
            <div>
                <?php if($this->user->plan_settings->team_members_limit != -1 && $data->total_team_members >= $this->user->plan_settings->team_members_limit): ?>
                    <button type="button" class="btn btn-primary disabled" data-toggle="tooltip" title="<?= l('global.info_message.plan_feature_limit') ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('team_members.create') ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('team-member-create/' . $data->team->team_id) ?>" class="btn btn-primary" data-toggle="tooltip" data-html="true" title="<?= get_plan_feature_limit_info($data->total_team_members, $this->user->plan_settings->team_members_limit, isset($data->filters) ? !$data->filters->has_applied_filters : true) ?>">
                        <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('team_members.create') ?>
                    </a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn btn-light dropdown-toggle-simple <?= count($data->team_members) ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.export') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-download"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right d-print-none">
                        <a href="<?= url('team/' . $data->team->team_id . '?' . $data->filters->get_get() . '&export=csv')  ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-csv mr-2"></i> <?= sprintf(l('global.export_to'), 'CSV') ?>
                        </a>
                        <a href="<?= url('team/' . $data->team->team_id . '?' . $data->filters->get_get() . '&export=json') ?>" target="_blank" class="dropdown-item">
                            <i class="fas fa-fw fa-sm fa-file-code mr-2"></i> <?= sprintf(l('global.export_to'), 'JSON') ?>
                    </a>
                    <a href="#" onclick="window.print();return false;" class="dropdown-item">
                        <i class="fas fa-fw fa-sm fa-file-pdf mr-2"></i> <?= sprintf(l('global.export_to'), 'PDF') ?>
                    </a>
                    </div>
                </div>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= $data->filters->has_applied_filters ? 'btn-dark' : 'btn-light' ?> filters-button dropdown-toggle-simple <?= count($data->team_members) || $data->filters->has_applied_filters ? null : 'disabled' ?>" data-toggle="dropdown" data-boundary="viewport" data-tooltip title="<?= l('global.filters.header') ?>" data-tooltip-hide-on-click>
                        <i class="fas fa-fw fa-sm fa-filter"></i>
                    </button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                            <?php if($data->filters->has_applied_filters): ?>
                                <a href="<?= url(\Altum\Router::$original_request) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="search" class="small"><?= l('global.filters.search') ?></label>
                                <input type="search" name="search" id="search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="search_by" class="small"><?= l('global.filters.search_by') ?></label>
                                <select name="search_by" id="search_by" class="custom-select custom-select-sm">
                                    <option value="user_email" <?= $data->filters->search_by == 'user_email' ? 'selected="selected"' : null ?>><?= l('global.email') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_by" class="small"><?= l('global.filters.order_by') ?></label>
                                <select name="order_by" id="order_by" class="custom-select custom-select-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                    <option value="user_email" <?= $data->filters->order_by == 'user_email' ? 'selected="selected"' : null ?>><?= l('global.email') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="order_type" class="small"><?= l('global.filters.order_type') ?></label>
                                <select name="order_type" id="order_type" class="custom-select custom-select-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                                <select name="results_per_page" id="results_per_page" class="custom-select custom-select-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

            <div class="ml-3">
                <?= include_view(THEME_PATH . 'views/team/team_dropdown_button.php', ['id' => $data->team->team_id, 'resource_name' => $data->team->name]) ?>
            </div>
        </div>
    </div>

    <?php if(count($data->team_members)): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                <tr>
                    <th><?= l('team_members.table.member') ?></th>
                    <th><?= sprintf(l('team_members.input.access'), l('global.all')) ?></th>
                    <th><?= l('global.status') ?></th>
                    <th><?= l('team_members.table.datetime') ?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($data->team_members as $row): ?>
                    <tr>
                        <td class="text-nowrap">
                            <div class="d-flex">
                                <?php if($row->status): ?>
                                    <img src="<?= get_gravatar($row->email, 45) ?>" class="rounded-circle mr-3" alt="" />

                                    <div class="d-flex flex-column">
                                        <span class="font-weight-bold"><?= $row->name ?></span>
                                        <span class="text-muted"><?= $row->email ?></span>
                                    </div>
                                <?php else: ?>
                                    <img src="<?= get_gravatar($row->user_email, 45) ?>" class="rounded-circle mr-3" alt="" />

                                    <div class="d-flex flex-column align-self-center">
                                        <span class="text-muted"><?= $row->user_email ?></span>
                                    </div>
                                <?php endif ?>
                            </div>
                        </td>

                        <td class="text-nowrap">
                            <?php
                            $access_html = [];
                            foreach($data->teams_access as $key => $value) {
                                $access_html[$key] = '';
                                foreach($data->teams_access[$key] as $access_key => $access_translation) {
                                    $access_html[$key] .= ($row->access->{$access_key} ? $access_translation : '<s>' . $access_translation . '</s>') . '<br />';
                                }
                            }
                            ?>
                            <span class="badge badge-secondary mx-2" data-toggle="tooltip" data-html="true" title="<?= $access_html['read'] ?>">
                                <i class="fas fa-fw fa-sm fa-eye"></i> <?= l('team_members.input.access.read') ?>
                            </span>

                            <span class="badge badge-success mx-2" data-toggle="tooltip" data-html="true" title="<?= $access_html['create'] ?>">
                                <i class="fas fa-fw fa-plus-circle fa-sm mr-1"></i> <?= l('team_members.input.access.create') ?>
                            </span>

                            <span class="badge badge-info mx-2" data-toggle="tooltip" data-html="true" title="<?= $access_html['update'] ?>">
                                <i class="fas fa-fw fa-sm fa-pencil-alt"></i> <?= l('team_members.input.access.update') ?>
                            </span>

                            <span class="badge badge-danger mx-2" data-toggle="tooltip" data-html="true" title="<?= $access_html['delete'] ?>">
                                <i class="fas fa-fw fa-sm fa-trash-alt"></i> <?= l('team_members.input.access.delete') ?>
                            </span>
                        </td>

                        <td class="text-nowrap">
                            <?php if($row->status): ?>
                                <span class="badge badge-success"><?= l('team_members.table.status_accepted') ?></span>
                            <?php else: ?>
                                <span class="badge badge-warning"><?= l('team_members.table.status_invited') ?></span>
                            <?php endif ?>
                        </td>

                        <td class="text-nowrap"><span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime, 1) ?>"><?= \Altum\Date::get_timeago($row->datetime) ?></span></td>

                        <td>
                            <div class="d-flex justify-content-end">
                                <?= include_view(THEME_PATH . 'views/team/team_member_dropdown_button.php', ['id' => $row->team_member_id, 'resource_name' => $row->user_email]) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="mt-3"><?= $data->pagination ?></div>
    <?php else: ?>
        <?= include_view(THEME_PATH . 'views/partials/no_data.php', [
            'filters_get' => $data->filters->get ?? [],
            'name' => 'team_members',
            'has_secondary_text' => true,
        ]); ?>
    <?php endif ?>
</div>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'team',
    'resource_id' => 'team_id',
    'has_dynamic_resource_name' => true,
    'path' => 'teams/delete'
]), 'modals'); ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/partials/universal_delete_modal_form.php', [
    'name' => 'team_member',
    'resource_id' => 'team_member_id',
    'has_dynamic_resource_name' => true,
    'path' => 'teams-members/delete'
]), 'modals'); ?>
