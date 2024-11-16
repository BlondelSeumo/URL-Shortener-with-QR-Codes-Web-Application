<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<script src="https://cdn.jsdelivr.net/npm/shiki@0.14.7/dist/index.jsdelivr.iife.min.js"></script>

<script>
    'use strict';

    shiki
        .getHighlighter({
            theme: 'dracula-soft',
            langs: ['json']
        })
        .then(highlighter => {
            document.querySelectorAll('[data-shiki]').forEach(element => {
                let lang = element.getAttribute('data-shiki');
                element.innerHTML = highlighter.codeToHtml(element.innerText.trim(), { lang: lang });
            })
        })
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
