<?php defined('ALTUMCODE') || die() ?>

<div class="container">
    <?= \Altum\Alerts::output_alerts() ?>

    <div class="row mb-4">
        <div class="col-12 col-lg d-flex align-items-center mb-3 mb-lg-0 text-truncate">
            <h1 class="h4 m-0 text-truncate"><i class="fas fa-fw fa-xs fa-screwdriver-wrench mr-1"></i> <?= l('tools.header') ?></h1>

            <div class="ml-2">
                <span data-toggle="tooltip" title="<?= l('tools.subheader') ?>">
                    <i class="fas fa-fw fa-info-circle text-muted"></i>
                </span>
            </div>
        </div>
    </div>

    <form action="" method="get" role="form">
        <div class="form-group">
            <input id="search" type="search" name="search" class="form-control form-control-lg" value="" placeholder="<?= l('global.filters.search') ?>" aria-label="<?= l('global.filters.search') ?>" />
        </div>
    </form>

    <div id="tools">
        <?php function get_tools_section_output($file_name, $user, $data, $category_properties) { ?>
            <?php $enabled_tools_html = $disabled_tools_html = ''; ?>
            <?php foreach(require APP_PATH . 'includes/tools/' . $file_name . '.php' as $key => $value): ?>
                <?php if(settings()->tools->available_tools->{$key}): ?>
                    <?php ob_start() ?>
                    <?php
                    /* Determine the tool name / description */
                    if(isset($value['category']) && $value['category'] == 'data_converter') {
                        /* Process the tool */
                        $exploded = explode('_to_', $key);
                        $from = $exploded[0];
                        $to = $exploded[1];

                        $name = sprintf(l('tools.data_converter.name'), l('tools.' . $from), l('tools.' . $to));
                        $description = sprintf(l('tools.data_converter.description'), l('tools.' . $from), l('tools.' . $to));
                    } else {
                        $name = l('tools.' . $key . '.name');
                        $description = l('tools.' . $key . '.description');
                    }
                    ?>

                    <div class="col-12 col-lg-6 p-3 position-relative" data-tool-id="<?= $key ?>" data-tool-name="<?= $name ?>" data-tool-category="<?= $file_name ?>" data-aos="fade-up">
                        <div class="card d-flex flex-row h-100 overflow-hidden">
                            <div class="tool-icon-wrapper d-flex flex-column justify-content-center">
                                <div class="d-flex align-items-center justify-content-center rounded tool-icon" style="background: <?= $category_properties['faded_color'] ?>;">
                                    <i class="<?= $value['icon'] ?> fa-fw" style="color: <?= $category_properties['color'] ?>"></i>
                                </div>
                            </div>

                            <div class="card-body text-truncate">
                                <a href="<?= url('tools/' . str_replace('_', '-', $key)) ?>" class="stretched-link text-decoration-none">
                                    <strong><?= $name ?></strong>
                                </a>
                                <p class="text-truncate small m-0"><?= $description ?></p>
                            </div>

                            <?php if(settings()->tools->views_is_enabled): ?>
                                <div class="p-3">
                                    <div class="badge badge-gray-100" data-toggle="tooltip" title="<?= l('tools.total_views') ?>">
                                        <i class="fas fa-fw fa-sm fa-eye mr-1"></i> <?= nr($data->tools_usage[$key]->total_views ?? 0) ?>
                                    </div>
                                </div>
                            <?php endif ?>
                        </div>
                    </div>

                    <?php $enabled_tools_html .= ob_get_clean(); ?>
                <?php endif ?>
            <?php endforeach ?>

            <?php return ['enabled_tools_html' => $enabled_tools_html] ?>
        <?php } ?>

        <?php foreach(require APP_PATH . 'includes/tools/categories.php' as $tool => $tool_properties): ?>
            <?php ${$tool} = get_tools_section_output($tool, $this->user, $data, $tool_properties); ?>
            <?php if(empty(${$tool}['enabled_tools_html'])) continue; ?>

            <div class="card mt-5 mb-4 position-relative" data-category="<?= $tool ?>" style="background: <?= $tool_properties['color'] ?>; border-color: <?= $tool_properties['color'] ?>; color: white;" data-aos="fade-up">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex text-truncate">
                            <div class="d-flex align-items-center justify-content-center rounded mr-3 tool-icon" style="background: <?= $tool_properties['faded_color'] ?>;">
                                <i class="<?= $tool_properties['icon'] ?> fa-fw" style="color: <?= $tool_properties['color'] ?>"></i>
                            </div>

                            <div class="text-truncate ml-3">
                                <strong><?= l('tools.' . $tool) ?></strong>
                                <p class="text-truncate small m-0"><?= l('tools.' . $tool . '_help') ?></p>
                            </div>
                        </div>


                        <div class="ml-3">
                            <a href="#" class="stretched-link" data-toggle="collapse" data-target="<?= '#' . $tool . '_tools' ?>" style="color: white !important;" role="button" aria-expanded="false" aria-controls="<?=  $tool . '_tools' ?>" data-category-collapse-button>
                                <i class="fas fa-fw fa-lg fa-circle-chevron-down"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div id="<?= $tool . '_tools' ?>" class="row collapse" data-category-tools>
                <?php echo ${$tool}['enabled_tools_html']; ?>
            </div>
        <?php endforeach ?>

        <?php ob_start() ?>
        <script>
            'use strict';

            document.querySelector('#search').addEventListener('submit', event => {
                event.preventDefault();
            });

            let tools = [];

            document.querySelectorAll('#tools [data-tool-id]').forEach(element => {
                let category = element.getAttribute('data-tool-category').toLowerCase();

                tools.push({
                    id: element.getAttribute('data-tool-id'),
                    name: element.getAttribute('data-tool-name').toLowerCase(),
                    category,
                })
            });

            /* Keep the state of the current search value */
            let search_value = document.querySelector('input[name="search"]').value.toLowerCase();

            /* Debounce on search */
            let timer = null;

            ['change', 'paste', 'keyup', 'search'].forEach(event_type => {
                document.querySelector('input[name="search"]').addEventListener(event_type, event => {
                    clearTimeout(timer);

                    let string = document.querySelector('input[name="search"]').value.toLowerCase();

                    /* Do not search if the value did not change */
                    if(string == search_value) {
                        return true;
                    }

                    /* Add loading state */
                    let tools_element = document.querySelector('#tools');
                    tools_element.classList.add('position-relative');

                    if(!document.querySelector('#tools-loading-overlay')) {
                        let overlay = document.createElement('div');
                        overlay.id = 'tools-loading-overlay';
                        overlay.classList.add('loading-overlay');
                        overlay.innerHTML = '<div class="spinner-border spinner-border-lg" role="status"></div>';
                        tools_element.prepend(overlay);
                    }

                    timer = setTimeout(() => {

                        /* Do not use collapse when searching */
                        document.querySelectorAll('#tools [data-category-tools]').forEach(element => {
                            if(string.length) {
                                element.classList.remove('collapse');
                            } else {
                                element.classList.add('collapse');
                            }
                        });

                        document.querySelectorAll('#tools [data-category-collapse-button]').forEach(element => {
                            if(string.length) {
                                element.classList.add('d-none');
                                element.classList.remove('stretched-link');
                            } else {
                                element.classList.remove('d-none');
                                element.classList.add('stretched-link');
                            }
                        });

                        /* Hide header sections */
                        document.querySelectorAll('#tools [data-category]').forEach(element => {
                            element.removeAttribute('data-aos');

                            if(string.length) {
                                element.classList.add('d-none');
                            } else {
                                element.classList.remove('d-none');
                            }
                        });

                        for(let tool of tools) {
                            document.querySelector(`#tools [data-tool-id="${tool.id}"][data-aos]`) && document.querySelector(`#tools [data-tool-id="${tool.id}"][data-aos]`).removeAttribute('data-aos');

                            if(tool.name.includes(string)) {
                                document.querySelector(`#tools [data-tool-id="${tool.id}"]`).classList.remove('d-none');
                                document.querySelector(`#tools [data-category="${tool.category}"]`).classList.remove('d-none');
                            } else {
                                document.querySelector(`#tools [data-tool-id="${tool.id}"]`).classList.add('d-none');
                            }
                        }

                        /* Set new search value */
                        search_value = string;

                        /* Remove loading state */
                        tools_element.classList.remove('position-relative');
                        tools_element.querySelector('#tools-loading-overlay').remove();
                    }, 300);
                });
            });
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
    </div>
</div>
