id: 34
source: 1
name: mspPayAnyWaySystem
category: mspPayAnyWay
properties: null
static_file: core/components/msppayanyway/elements/plugins/plugin.system.php

-----

/** @var array $scriptProperties */
$corePath = $modx->getOption('msppayanyway_core_path', null,
    $modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/msppayanyway/');
$msppayanyway = $modx->getService('msppayanyway', 'msppayanyway', $corePath . 'model/msppayanyway/',
    array('core_path' => $corePath));
if (!$msppayanyway) {
    return;
}

$className = 'mspPayAnyWay' . $modx->event->name;
$modx->loadClass('mspPayAnyWayPlugin', $msppayanyway->getOption('modelPath') . 'msppayanyway/systems/', true, true);
$modx->loadClass($className, $msppayanyway->getOption('modelPath') . 'msppayanyway/systems/', true, true);
if (class_exists($className)) {
    /** @var $msppayanyway $handler */
    $handler = new $className($modx, $scriptProperties);
    $handler->run();
}
return;