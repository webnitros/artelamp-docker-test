<?php

/** @var modX $modx */
switch ($modx->event->name) {
    case 'OnWebPageInit':
        if (getenv('ENV') === 'production') {
            $modx->addPackage("ms2analytics", MODX_CORE_PATH . "components/ms2analytics/model/");
            include_once MODX_CORE_PATH . 'components/ms2analytics/model/ms2a_util.php';
            if (class_exists('ms2a_util')) {
                $ms2a_util = new ms2a_util($modx);
                if ($ms2a_util->config) {
                    $gid = $ms2a_util->config['gid'];
                    $yid = $ms2a_util->config['yid'];
                    if ($gid or $yid) {
                        $script = rtrim($modx->getOption('assets_url'), '/') . '/components/ms2analytics/js/web/default.js?v=2';
                        $modx->util->addStartupJsText('<script defer type="text/javascript" src="' . $script . '"></script>');
                    }
                    if ($gid) {
                        $modx->util->addStartupJs('https://www.googletagmanager.com/gtag/js', NULL, FALSE, [
                            'id' => $gid,
                        ]);
                        $modx->util->addStartupJsText('
						<script  defer async type="text/javascript" class="google">
							window.ms2a_google = true
							window.dataLayer = window.dataLayer || [];
							function gtag(){dataLayer.push(arguments);}
							gtag("js", new Date());
							gtag("config", "' . $gid . '");
						</script>
						');
                    }
                    if ($yid) {
                        $ms2a_clickmap = (int)(bool)$ms2a_util->config['clickmap'];
                        $ms2a_trackLinks = (int)(bool)$ms2a_util->config['trackLinks'];
                        $ms2a_accurateTrackBounce = (int)(bool)$ms2a_util->config['accurateTrackBounce'];
                        $ms2a_webvisor = (int)(bool)$ms2a_util->config['webvisor'];
                        $ms2a_trackHash = (int)(bool)$ms2a_util->config['trackHash'];
                        $ms2a_container = (string)$ms2a_util->config['container'];
                        $modx->util->addStartupJsText("
						<!-- Yandex.Metrika counter -->
						<script defer async type=\"text/javascript\" class='yandex'>
						   window.ms2a_yandex = true 
						   window.{$ms2a_container} = window.{$ms2a_container} || [];
						   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
						   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
						   (window, document, \"script\", \"https://mc.yandex.ru/metrika/tag.js\", \"ym\");
						
						   ym({$yid}, \"init\", {
						        clickmap: {$ms2a_clickmap},
						        trackLinks: {$ms2a_trackLinks},
						        accurateTrackBounce: {$ms2a_accurateTrackBounce},
						        webvisor: {$ms2a_webvisor},
						        trackHash: {$ms2a_trackHash},
						        ecommerce:\"{$ms2a_container}\"
						   });
						</script>
						<noscript><div><img src=\"https://mc.yandex.ru/watch/{$yid}\" style=\"position:absolute; left:-9999px;\" alt=\"\" /></div></noscript>
						<!-- /Yandex.Metrika counter -->
						");
                        $modx->util->addStartupJsText('<script defer type="text/javascript" src="' . $script . '"></script>');
                    }
                }

            }
        }
        break;
}
