<?php
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var msppayanyway $msppayanyway */
$msppayanyway = $modx->getService('msppayanyway', 'msppayanyway', $modx->getOption('msppayanyway_core_path', null,
        $modx->getOption('core_path') . 'components/msppayanyway/') . 'model/msppayanyway/');
$modx->lexicon->load('msppayanyway:default');

// handle request
$corePath = $modx->getOption('msppayanyway_core_path', null,
    $modx->getOption('core_path') . 'components/msppayanyway/');
$path = $modx->getOption('processorsPath', $msppayanyway->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location'        => '',
));