id: 31
source: 1
name: mspc2System
category: msPromoCode2
properties: null
static_file: core/components/mspromocode2/elements/plugins/system.php

-----

/** @var modX $modx */
/** @var msPromoCode2 $mspc2 */
/** @var array $scriptProperties */

if (!$mspc2 = $modx->getService('mspromocode2', 'msPromoCode2',
    $modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/')) {
    return;
}

$className = 'mspc2' . $modx->event->name;
$modx->loadClass('mspc2Plugin', $mspc2->config['pluginsPath'], true, true);
$modx->loadClass($className, $mspc2->config['pluginsPath'], true, true);
/** @var mspc2Plugin $handler */
if (class_exists($className)) {
    $handler = new $className($mspc2, $scriptProperties);
    $handler->run();
} else {
    // Удаляем событие у плагина, если такого класса не существует
    if ($event = $modx->getObject('modPluginEvent', array(
        'pluginid' => $modx->event->plugin->get('id'),
        'event' => $modx->event->name,
    ))) {
        $event->remove();
    }
}
return;