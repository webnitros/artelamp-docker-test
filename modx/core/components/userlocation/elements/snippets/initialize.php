<?php

/** @var array $scriptProperties */
/** @var UserLocation $UserLocation */
if ($UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
    $UserLocation->initialize($modx->context->key, $scriptProperties);
    $UserLocation->injectScript();
}

return '';