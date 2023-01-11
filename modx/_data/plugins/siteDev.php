id: 2
source: 1
name: siteDev
category: siteDev
properties: 'a:0:{}'
static_file: core/components/sitedev/elements/plugins/sitedev.php

-----

/** @var modX $modx */
/** @var array $scriptProperties */
/* @var siteDev $siteDev */
switch ($modx->event->name) {
    case 'OnMODXInit':
        if ($siteDev = $modx->getService('sitedev', 'siteDev', MODX_CORE_PATH . 'components/sitedev/model/')) {
            $siteDev->initialize();
        }
        break;
    default:
        if ($siteDev = $modx->getService('siteDev')) {
            $siteDev->handleEvent($modx->event, $scriptProperties);
        }
}