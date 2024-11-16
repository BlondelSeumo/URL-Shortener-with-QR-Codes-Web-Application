<?php defined('ALTUMCODE') || die() ?>

<div class="modal fade" id="biolink_link_create_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable" role="document">
        <div class="modal-content">

            <div class="modal-body">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="modal-title">
                        <i class="fas fa-fw fa-sm fa-circle-plus text-dark mr-2"></i>
                        <?= l('biolink_link_create_modal.header') ?>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" title="<?= l('global.close') ?>">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="" method="get" role="form" id="search">
                    <div class="form-group">
                        <input type="search" name="search" class="form-control form-control-lg" value="" placeholder="<?= l('global.filters.search') ?>" aria-label="<?= l('global.filters.search') ?>" />
                    </div>
                </form>

                <?php foreach(require APP_PATH . 'includes/biolink_blocks_categories.php' as $biolink_block_category_key => $biolink_block_category): ?>
                    <?php $enabled_blocks_html = $disabled_blocks_html = ''; ?>

                    <?php foreach(require APP_PATH . 'includes/enabled_biolink_blocks.php' as $key => $value): ?>

                        <?php if($value['category'] != $biolink_block_category_key) continue ?>

                        <?php ob_start() ?>
                        <?php if($this->user->plan_settings->enabled_biolink_blocks->{$key}): ?>
                            <div class="col-12 col-lg-6" data-block-category="<?= $value['category'] ?>" data-block-id="<?= $key ?>" data-block-name="<?= l('link.biolink.blocks.' . $key) ?>">
                                <button
                                    type="button"
                                    data-dismiss="modal"
                                    data-toggle="modal"
                                    data-target="#create_biolink_<?= $key ?>"
                                    data-tooltip
                                    title="<?= l('create_biolink_' . $key . '_modal.subheader') ?>"
                                    class="btn btn-light btn-block btn-lg mb-3 text-left d-flex align-items-center"
                                >
                                    <span class="fa-stack fa-stack-small mr-2">
                                        <i class="fas fa-circle fa-stack-2x" style="color: <?= $data->biolink_blocks[$key]['color'] ?>"></i>
                                        <i class="<?= $data->biolink_blocks[$key]['icon'] ?> fa-stack-1x fa-inverse"></i>
                                    </span>

                                    <?= l('link.biolink.blocks.' . $key) ?>
                                </button>
                            </div>
                            <?php $enabled_blocks_html .= ob_get_clean(); ?>
                        <?php else: ?>
                            <div class="col-12 col-lg-6" data-block-category="<?= $value['category'] ?>" data-block-id="<?= $key ?>" data-block-name="<?= l('link.biolink.blocks.' . $key) ?>">
                                <button
                                    type="button"
                                    data-toggle="tooltip"
                                    title="<?= l('global.info_message.plan_feature_no_access') ?>"
                                    class="btn btn-light btn-block btn-lg mb-3 disabled text-left"
                                >
                                    <span class="fa-stack fa-stack-small mr-2">
                                        <i class="fas fa-circle fa-stack-2x" style="color: <?= $data->biolink_blocks[$key]['color'] ?>"></i>
                                        <i class="<?= $data->biolink_blocks[$key]['icon'] ?> fa-stack-1x fa-inverse"></i>
                                    </span>

                                    <s><?= l('link.biolink.blocks.' . $key) ?></s>
                                </button>
                            </div>
                            <?php $disabled_blocks_html .= ob_get_clean(); ?>
                        <?php endif ?>
                    <?php endforeach ?>

                    <?php if($enabled_blocks_html || $disabled_blocks_html): ?>
                        <div class="mb-4" data-category="<?= $biolink_block_category_key ?>">
                            <div class="card text-white border-0 mb-3" style="background: <?= $biolink_block_category['color'] ?>">
                                <div class="card-body d-flex justify-content-between align-items-center">
                                    <div>
                                        <span class="h6"><?= l('biolink_link_create_modal.category.' . $biolink_block_category_key) ?></span>
                                        <p class="small mb-0"><?= l('biolink_link_create_modal.category.' . $biolink_block_category_key . '_subheader') ?></p>
                                    </div>

                                    <div>
                                        <i class="fas fa-fw fa-lg <?= $biolink_block_category['icon'] ?>"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <?= $enabled_blocks_html ?>
                                <?= $disabled_blocks_html ?>
                            </div>
                        </div>
                    <?php endif ?>
                <?php endforeach ?>
            </div>
        </div>
    </div>
</div>

<?php ob_start() ?>
<script>
    'use strict';

    document.querySelector('#search').addEventListener('submit', event => {
        event.preventDefault();
    });

    let blocks = [];
    document.querySelectorAll('[data-block-id]').forEach(element => blocks.push({
        id: element.getAttribute('data-block-id'),
        name: element.getAttribute('data-block-name').toLowerCase(),
        category: element.getAttribute('data-block-category').toLowerCase(),
    }));

    ['keyup', 'change', 'search'].forEach(event_key => document.querySelector('#biolink_link_create_modal input').addEventListener(event_key, event => {
        let string = event.currentTarget.value.toLowerCase();

        /* Hide header sections */
        document.querySelectorAll('[data-category]').forEach(element => {
            if(string.length) {
                element.classList.add('d-none');
            } else {
                element.classList.remove('d-none');
            }
        });

        for(let block of blocks) {
            if(block.name.includes(string)) {
                document.querySelector(`[data-block-id="${block.id}"]`).classList.remove('d-none');
                document.querySelector(`[data-category="${block.category}"]`).classList.remove('d-none');
            } else {
                document.querySelector(`[data-block-id="${block.id}"]`).classList.add('d-none');
            }
        }
    }));
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
