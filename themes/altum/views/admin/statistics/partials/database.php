<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4 mb-4"><i class="fas fa-fw fa-database fa-xs text-primary-900 mr-2"></i> <?= l('admin_statistics.database.header') ?></h2>

        <?php //ALTUMCODE:DEMO if(DEMO) echo 'hidden on demo'; ?>
        <?php //ALTUMCODE:DEMO if(!DEMO): ?>
        <div class="table-responsive table-custom-container">
            <table class="table table-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?= l('global.name') ?></th>
                        <th><?= l('admin_statistics.database.rows') ?></th>
                        <th><?= l('admin_statistics.database.size') ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($data->tables as $key => $table): ?>
                    <tr>
                        <td class="text-nowrap">
                            <span class="text-muted"><?= nr($key + 1) ?></span>
                        </td>

                        <td class="text-nowrap">
                            <?= $table->table ?>
                        </td>

                        <td class="text-nowrap">
                            <span class="badge badge-info">~<?= nr($table->rows) ?></span>
                        </td>

                        <td class="text-nowrap">
                            <span class="badge badge-light"><?= get_formatted_bytes($table->bytes) ?></span>
                        </td>
                    </tr>
                <?php endforeach ?>

                </tbody>
            </table>
        </div>
        <?php //ALTUMCODE:DEMO endif ?>
    </div>
</div>

<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript, 'has_datepicker' => false] ?>
