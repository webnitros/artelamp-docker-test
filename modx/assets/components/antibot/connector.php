<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var antiBot $antiBot */
$antiBot = $modx->getService('antiBot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');
$modx->lexicon->load('antibot:default');

// handle request
$corePath = $modx->getOption('antibot_core_path', null, $modx->getOption('core_path') . 'components/antibot/');
$path = $modx->getOption('processorsPath', $antiBot->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);