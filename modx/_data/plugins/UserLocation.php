id: 14
source: 1
name: UserLocation
category: userlocation
properties: 'a:0:{}'
static_file: core/components/userlocation/elements/plugins/userlocation.php

-----

/** @var array $scriptProperties */
/** @var UserLocation $UserLocation */
if ($UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
    if (method_exists($UserLocation, 'processEvent')) {
        return $UserLocation->processEvent($modx->event, $scriptProperties);
    }
}