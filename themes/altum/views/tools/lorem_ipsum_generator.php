<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <?php if(settings()->main->breadcrumbs_is_enabled): ?>
<nav aria-label="breadcrumb">
        <ol class="custom-breadcrumbs small">
            <li><a href="<?= url('tools') ?>"><?= l('tools.breadcrumb') ?></a> <i class="fas fa-fw fa-angle-right"></i></li>
            <li class="active" aria-current="page"><?= l('tools.lorem_ipsum_generator.name') ?></li>
        </ol>
    </nav>
<?php endif ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><?= l('tools.lorem_ipsum_generator.name') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.lorem_ipsum_generator.description') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form action="" method="post" role="form">
                <input type="hidden" name="token" value="<?= \Altum\Csrf::get() ?>" />


                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="amount"><i class="fas fa-fw fa-sort-numeric-up fa-sm text-muted mr-1"></i> <?= l('tools.lorem_ipsum_generator.amount') ?></label>
                            <input type="number" min="1" id="amount" name="amount" class="form-control <?= \Altum\Alerts::has_field_errors('amount') ? 'is-invalid' : null ?>" value="<?= $data->values['amount'] ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('amount') ?>
                        </div>
                    </div>

                    <div class="col-lg-8">
                        <div class="form-group">
                            <label for="type"><i class="fas fa-fw fa-sm fa-fingerprint text-muted mr-1"></i> <?= l('global.type') ?></label>
                            <select id="type" name="type" class="custom-select" required="required">
                                <option value="paragraphs" <?= $data->values['type'] == 'paragraphs' ? 'selected="selected"' : null ?>><?= l('tools.lorem_ipsum_generator.paragraphs') ?></option>
                                <option value="sentences" <?= $data->values['type'] == 'sentences' ? 'selected="selected"' : null ?>><?= l('tools.lorem_ipsum_generator.sentences') ?></option>
                                <option value="words" <?= $data->values['type'] == 'words' ? 'selected="selected"' : null ?>><?= l('tools.lorem_ipsum_generator.words') ?></option>
                            </select>
                        </div>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-block btn-primary"><?= l('global.submit') ?></button>
            </form>

        </div>
    </div>

    <?php if(isset($data->result)): ?>
        <div class="mt-4">

            <div class="card">
                <div class="card-body">

                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center">
                            <label for="result"><?= l('tools.lorem_ipsum_generator.' . $data->values['type']) ?></label>
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
