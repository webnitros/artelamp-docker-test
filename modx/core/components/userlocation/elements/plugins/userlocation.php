<?php
/** @var array $scriptProperties */
/** @var UserLocation $UserLocation */
if ($UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
    if (method_exists($UserLocation, 'processEvent')) {
        return $UserLocation->processEvent($modx->event, $scriptProperties);
    }
}