<?php
/** @var modX $modx */
/* @var mspre $msPree */
/* @var array $scriptProperties */
switch ($modx->event->name) {
    case 'OnManagerPageBeforeRender':
    case 'OnResourceToolbarLoad':
    case 'OnTVFormDelete':
        /* @var mspre $mspre */
        if ($mspre = $modx->getService('mspre', 'mspre', MODX_CORE_PATH . 'components/mspre/model/')) {
            $mspre->handleEvent($modx->event, $scriptProperties, $controller);
        }
        break;
    case 'OnDocFormRender':
        if (isset($_GET['mspre_iframe'])) {
            $modx->controller->addCss(MODX_ASSETS_URL . 'components/mspre/css/mgr/manageriframe.css');
        }
        break;
}