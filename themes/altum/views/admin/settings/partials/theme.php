<?php defined('ALTUMCODE') || die() ?>

<div>
    <ul class="nav nav-pills d-flex flex-fill flex-column flex-lg-row mb-3" role="tablist">
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link active" id="pills-light-tab" data-toggle="pill" href="#pills-light" role="tab" aria-controls="pills-light" aria-selected="true">
                <i class="fas fa-fw fa-sm fa-sun mr-1"></i> <?= l('admin_settings.theme.light') ?>
            </a>
        </li>
        <li class="nav-item flex-fill text-center" role="presentation">
            <a class="nav-link" id="pills-dark-tab" data-toggle="pill" href="#pills-dark" role="tab" aria-controls="pills-dark" aria-selected="false">
                <i class="fas fa-fw fa-sm fa-moon mr-1"></i> <?= l('admin_settings.theme.dark') ?>
            </a>
        </li>
    </ul>

    <?php
    $defaults = [
        'light' => [
            'primary' => [
                '50'  => '#eff8ff',
                '100' => '#e1f2fe',
                '200' => '#b9e5fc',
                '300' => '#82d3fb',
                '400' => '#3fbaf8',
                '500' => '#0ea5ea',
                '600' => '#1180c5',
                '700' => '#0c68a1',
                '800' => '#0d5a86',
                '900' => '#0f496f',
            ],
            'gray' => [
                '25'  => '#fcfcfd',
                '50'  => '#fcfcfc',
                '100' => '#f6f7f8',
                '200' => '#f0f2f3',
                '300' => '#e5e7ea',
                '400' => '#a6afb9',
                '500' => '#9ba4b0',
                '600' => '#6b7789',
                '700' => '#4c5361',
                '800' => '#31363e',
                '900' => '#1d1f25',
            ]
        ],
        'dark' => [
            'primary' => [
                '900' => '#cde9fd',
                '800' => '#a1dcfc',
                '700' => '#6eccfb',
                '600' => '#2bb3f7',
                '500' => '#0e9ee1',
                '400' => '#0f76b6',
                '300' => '#0b5e93',
                '200' => '#0b4d74',
                '100' => '#0e4062',
                '50'  => '#010b12',
            ],
            'gray' => [
                '900' => '#f0f2f3',
                '800' => '#d7dbdf',
                '700' => '#b4bbc4',
                '600' => '#a0aab4',
                '500' => '#707d8e',
                '400' => '#606b7a',
                '300' => '#3c424d',
                '200' => '#2f333b',
                '100' => '#181a1f',
                '50'  => '#0d0e11',
                '25'  => '#0a0c10',
            ]
        ]
    ];
    ?>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="pills-light" role="tabpanel" aria-labelledby="pills-light-tab">
            <?php $mode = 'light' ?>

            <div class="form-group custom-control custom-switch">
                <input id="<?= $mode . '_is_enabled' ?>" name="<?= $mode . '_is_enabled' ?>" type="checkbox" class="custom-control-input" <?= settings()->theme->{$mode . '_is_enabled'} ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="<?= $mode . '_is_enabled' ?>"><i class="fas fa-fw fa-sm fa-dot-circle text-muted mr-1"></i> <?= l('global.status') ?></label>
            </div>

            <h2 class="h5"><?= l('admin_settings.theme.primary') ?></h2>
            <p class="text-muted"><?= l('admin_settings.theme.primary_help') ?></p>

            <?php foreach(['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'] as $key): ?>
                <div class="form-group">
                    <label for="<?= $mode . '_primary_' . $key ?>">Primary <?= $key ?></label>
                    <input id="<?= $mode . '_primary_' . $key ?>" type="hidden" name="<?= $mode . '_primary_' . $key ?>" class="form-control" value="<?= settings()->theme->{$mode . '_primary_' . $key} ?? $defaults[$mode]['primary'][$key] ?>" data-color-picker />
                </div>
            <?php endforeach ?>

            <h2 class="h5"><?= l('admin_settings.theme.gray') ?></h2>
            <p class="text-muted"><?= l('admin_settings.theme.gray_help') ?></p>

            <?php foreach(['25', '50', '100', '200', '300', '400', '500', '600', '700', '800', '900'] as $key): ?>
                <div class="form-group">
                    <label for="<?= $mode . '_gray_' . $key ?>">Gray <?= $key ?></label>
                    <input id="<?= $mode . '_gray_' . $key ?>" type="hidden" name="<?= $mode . '_gray_' . $key ?>" class="form-control" value="<?= settings()->theme->{$mode . '_gray_' . $key} ?? $defaults[$mode]['gray'][$key] ?>" data-color-picker />
                </div>
            <?php endforeach ?>

            <h2 class="h5"><?= l('admin_settings.theme.others') ?></h2>
            <div class="form-group" data-range-counter data-range-counter-suffix="rem">
                <label for="<?= $mode . '_border_radius' ?>"><?= l('admin_settings.theme.border_radius') ?></label>
                <input id="<?= $mode . '_border_radius' ?>" name="<?= $mode . '_border_radius' ?>" type="range" step=".1" min="0" max="1" class="form-control-range" value="<?= settings()->theme->{$mode . '_border_radius'} ?? null ?>" />
            </div>

            <div class="form-group">
                <label for="<?= $mode . '_font_family' ?>"><?= l('admin_settings.theme.font_family') ?></label>
                <input id="<?= $mode . '_font_family' ?>" name="<?= $mode . '_font_family' ?>" type="text" class="form-control" value="<?= settings()->theme->{$mode . '_font_family'} ?? null ?>" />
                <small class="form-text text-muted"><?= l('admin_settings.theme.font_family_help') ?></small>
            </div>
        </div>

        <div class="tab-pane fade" id="pills-dark" role="tabpanel" aria-labelledby="pills-dark-tab">
            <?php $mode = 'dark' ?>

            <div class="form-group custom-control custom-switch">
                <input id="<?= $mode . '_is_enabled' ?>" name="<?= $mode . '_is_enabled' ?>" type="checkbox" class="custom-control-input" <?= settings()->theme->{$mode . '_is_enabled'} ? 'checked="checked"' : null?>>
                <label class="custom-control-label" for="<?= $mode . '_is_enabled' ?>"><?= l('global.status') ?></label>
            </div>

            <h2 class="h5"><?= l('admin_settings.theme.primary') ?></h2>
            <p class="text-muted"><?= l('admin_settings.theme.primary_help') ?></p>

            <?php foreach(array_reverse(['50', '100', '200', '300', '400', '500', '600', '700', '800', '900']) as $key): ?>
                <div class="form-group">
                    <label for="<?= $mode . '_primary_' . $key ?>">Primary <?= $key ?></label>
                    <input id="<?= $mode . '_primary_' . $key ?>" type="hidden" name="<?= $mode . '_primary_' . $key ?>" class="form-control" value="<?= settings()->theme->{$mode . '_primary_' . $key} ?? $defaults[$mode]['primary'][$key] ?>" data-color-picker />
                </div>
            <?php endforeach ?>

            <h2 class="h5"><?= l('admin_settings.theme.gray') ?></h2>
            <p class="text-muted"><?= l('admin_settings.theme.gray_help') ?></p>

            <?php foreach(array_reverse(['25', '50', '100', '200', '300', '400', '500', '600', '700', '800', '900']) as $key): ?>
                <div class="form-group">
                    <label for="<?= $mode . '_gray_' . $key ?>">Gray <?= $key ?></label>
                    <input id="<?= $mode . '_gray_' . $key ?>" type="hidden" name="<?= $mode . '_gray_' . $key ?>" class="form-control" value="<?= settings()->theme->{$mode . '_gray_' . $key} ?? $defaults[$mode]['gray'][$key] ?>" data-color-picker />
                </div>
            <?php endforeach ?>

            <h2 class="h5"><?= l('admin_settings.theme.others') ?></h2>
            <div class="form-group" data-range-counter data-range-counter-suffix="rem">
                <label for="<?= $mode . '_border_radius' ?>"><?= l('admin_settings.theme.border_radius') ?></label>
                <input id="<?= $mode . '_border_radius' ?>" name="<?= $mode . '_border_radius' ?>" type="range" step=".1" min="0" max="1" class="form-control-range" value="<?= settings()->theme->{$mode . '_border_radius'} ?? null ?>" />
            </div>

            <div class="form-group">
                <label for="<?= $mode . '_font_family' ?>"><?= l('admin_settings.theme.font_family') ?></label>
                <input id="<?= $mode . '_font_family' ?>" name="<?= $mode . '_font_family' ?>" type="text" class="form-control" value="<?= settings()->theme->{$mode . '_font_family'} ?? null ?>" />
                <small class="form-text text-muted"><?= l('admin_settings.theme.font_family_help') ?></small>
            </div>
        </div>
    </div>
</div>

<button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= l('global.update') ?></button>

<?php include_view(THEME_PATH . 'views/partials/color_picker_js.php') ?>
