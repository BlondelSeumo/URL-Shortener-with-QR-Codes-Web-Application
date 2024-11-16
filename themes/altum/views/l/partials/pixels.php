<?php defined('ALTUMCODE') || die() ?>

<?php foreach($data->pixels as $pixel): ?>

    <?php if($pixel->type == 'facebook'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            !function(f,b,e,v,n,t,s)
            {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
                n.callMethod.apply(n,arguments):n.queue.push(arguments)};
                if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
                n.queue=[];t=b.createElement(e);t.async=!0;
                t.src=v;s=b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t,s)}(window, document,'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '<?= $pixel->pixel ?>');
            fbq('track', 'PageView');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'google_analytics'): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= $pixel->pixel ?>" <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="analytics"' : null ?>></script>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="analytics"' : null ?>>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', '<?= $pixel->pixel ?>');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'google_tag_manager'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?= $pixel->pixel ?>');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'linkedin'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            _linkedin_data_partner_id = "<?= $pixel->pixel ?>";
        </script>

        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            (function(){var s = document.getElementsByTagName("script")[0];
                var b = document.createElement("script");
                b.type = "text/javascript";b.async = true;
                b.src = "https://snap.licdn.com/li.lms-analytics/insight.min.js";
                s.parentNode.insertBefore(b, s);})();
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'pinterest'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            !function(e){if(!window.pintrk){window.pintrk=function(){window.pintrk.queue.push(Array.prototype.slice.call(arguments))};var n=window.pintrk;n.queue=[],n.version="3.0";var t=document.createElement("script");t.async=!0,t.src=e;var r=document.getElementsByTagName("script")[0];r.parentNode.insertBefore(t,r)}}("https://s.pinimg.com/ct/core.js");
            pintrk('load', '<?= $pixel->pixel ?>');
            pintrk('page');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'twitter'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            !function(e,t,n,s,u,a){e.twq||(s=e.twq=function(){s.exe?s.exe.apply(s,arguments):s.queue.push(arguments);
            },s.version='1.1',s.queue=[],u=t.createElement(n),u.async=!0,u.src='//static.ads-twitter.com/uwt.js',
                a=t.getElementsByTagName(n)[0],a.parentNode.insertBefore(u,a))}(window,document,'script');

            twq('init', '<?= $pixel->pixel ?>');
            twq('track', 'PageView');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'quora'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            !function(q,e,v,n,t,s){if(q.qp) return; n=q.qp=function(){n.qp?n.qp.apply(n,arguments):n.queue.push(arguments);}; n.queue=[];t=document.createElement(e);t.async=!0;t.src=v; s=document.getElementsByTagName(e)[0]; s.parentNode.insertBefore(t,s);}(window, 'script', 'https://a.quora.com/qevents.js');
            qp('init', '<?= $pixel->pixel ?>');
            qp('track', 'ViewContent');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'tiktok'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            !function (w, d, t) {
                w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};

                ttq.load('<?= $pixel->pixel ?>');
                ttq.page();
            }(window, document, 'ttq');
        </script>
    <?php endif ?>

    <?php if($pixel->type == 'snapchat'): ?>
        <script <?= settings()->cookie_consent->is_enabled ? 'type="text/plain" data-category="targeting"' : null ?>>
            (function(e,t,n){if(e.snaptr)return;var a=e.snaptr=function()
            {a.handleRequest?a.handleRequest.apply(a,arguments):a.queue.push(arguments)};
                a.queue=[];var s='script';r=t.createElement(s);r.async=!0;
                r.src=n;var u=t.getElementsByTagName(s)[0];
                u.parentNode.insertBefore(r,u);})(window,document,
                'https://sc-static.net/scevent.min.js');

            snaptr('init', '<?= $pixel->pixel ?>');

            snaptr('track', 'PAGE_VIEW');
        </script>
    <?php endif ?>

<?php endforeach ?>

<?php if(count($data->pixels) && settings()->cookie_consent->is_enabled): ?>
    <?php ob_start() ?>
        <link href="<?= ASSETS_FULL_URL . 'css/' . \Altum\ThemeStyle::get_file() . '?v=' . PRODUCT_CODE ?>" id="css_theme_style" rel="stylesheet" media="screen,print">
        <?php foreach(['custom.css'] as $file): ?>
            <link href="<?= ASSETS_FULL_URL . 'css/' . $file . '?v=' . PRODUCT_CODE ?>" rel="stylesheet" media="screen,print">
        <?php endforeach ?>
    <?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

    <?php require THEME_PATH . 'views/partials/cookie_consent.php' ?>
<?php endif ?>

