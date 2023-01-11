<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/* @var antiBot $antiBot */
switch ($modx->event->name) {
    case 'OnHandleRequest':
    case 'OnPageNotFound' :
        if ($antiBot = $modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/')) {
            $antiBot->loadHandlerEvent($modx->event, $scriptProperties);
        }
        break;
}