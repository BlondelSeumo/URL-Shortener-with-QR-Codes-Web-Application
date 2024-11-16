<?php defined('ALTUMCODE') || die() ?>

<?php if($this->link->settings->scroll_buttons_is_enabled): ?>
    <div id="scroll_buttons" style="position: fixed; left: 1rem; top: 1rem; z-index: 1;">
        <div class="mb-2">
            <button type="button" class="btn share-button zoom-animation-subtle" onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" data-toggle="tooltip" data-placement="right" title="<?= l('global.scroll_top') ?>" data-tooltip-hide-on-click>
                <i class="fas fa-fw fa-arrow-up"></i>
            </button>
        </div>
        <div>
            <button type="button" class="btn share-button zoom-animation-subtle" onclick="window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });" data-toggle="tooltip" data-placement="right" title="<?= l('global.scroll_bottom') ?>" data-tooltip-hide-on-click>
                <i class="fas fa-fw fa-arrow-down"></i>
            </button>
        </div>
    </div>

    <?php ob_start() ?>
    <script>
        'use strict';

        const toggle_scroll_buttons = () => {
            const scroll_buttons = document.getElementById('scroll_buttons');
            scroll_buttons.style.display = document.body.scrollHeight > window.innerHeight ? 'block' : 'none';
        };

        window.addEventListener('load', toggle_scroll_buttons);
        window.addEventListener('resize', toggle_scroll_buttons);
    </script>

    <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
<?php endif ?>
