<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.date_to_unix_timestamp.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.date_to_unix_timestamp.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.date_to_unix_timestamp.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form" enctype="multipart/form-data">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />

                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="year"><?= ucfirst(l('global.date.year')) ?></label>
                            <input type="number" id="year" name="year" class="form-control" value="<?= $data->values['year'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="month"><?= ucfirst(l('global.date.month')) ?></label>
                            <input type="number" id="month" name="month" class="form-control" value="<?= $data->values['month'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="day"><?= ucfirst(l('global.date.day')) ?></label>
                            <input type="number" id="day" name="day" class="form-control" value="<?= $data->values['day'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="hour"><?= ucfirst(l('global.date.hour')) ?></label>
                            <input type="number" id="hour" name="hour" class="form-control" value="<?= $data->values['hour'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="minute"><?= ucfirst(l('global.date.minute')) ?></label>
                            <input type="number" id="minute" name="minute" class="form-control" value="<?= $data->values['minute'] ?>" required="required" />
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="form-group">
                            <label for="second"><?= ucfirst(l('global.date.second')) ?></label>
                            <input type="number" id="second" name="second" class="form-control" value="<?= $data->values['second'] ?>" required="required" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="timezone"><?= l('tools.date_to_unix_timestamp.timezone') ?></label>
                    <select id="timezone" name="timezone" class="custom-select">
                        <?php foreach(DateTimeZone::listIdentifiers() as $timezone) echo '<option value="' . $timezone . '" ' . ($data->values['timezone'] == $timezone ? 'selected="selected"' : null) . '>' . $timezone . '</option>' ?>
                    </select>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary" data-is-ajax><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">
            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="result"><?= l('tools.date_to_unix_timestamp.result') ?></label>
                            <div>
                                <button
                                        type="button"
                                        class="btn btn-link text-secondary"
                                        data-toggle="tooltip"
                                        title="<?= l('global.clipboard_copy') ?>"
                                        aria-label="<?= l('global.clipboard_copy') ?>"
                                        data-copy="<?= l('global.clipboard_copy') ?>"
                                        data-copied="<?= l('global.clipboard_copied') ?>"
                                        data-clipboard-target="#result"
                                        data-clipboard-text
                                >
                                    <i class="fas fa-fw fa-sm fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <textarea id="result" class="form-control"><?= $data->result ?></textarea>
                    </div>

                </div>
            </div>
        </div>
    <?php endif ?>

    <?= $this->views['extra_content'] ?>

    <?= $this->views['similar_tools'] ?>

    <?= $this->views['popular_tools'] ?>
</div>

<?php include_view(THEME_PATH . 'views/partials/clipboard_js.php') ?>
