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
/** @var msExportOrdersExcel $msExportOrdersExcel */
$msExportOrdersExcel = $modx->getService('msExportOrdersExcel', 'msExportOrdersExcel', MODX_CORE_PATH . 'components/msexportordersexcel/model/');
$modx->lexicon->load('msexportordersexcel:default');
$modx->lexicon->load('msexportordersexcel:manager');

// handle request
$corePath = $modx->getOption('msexportordersexcel_core_path', null, $modx->getOption('core_path') . 'components/msexportordersexcel/');
$path = $modx->getOption('processorsPath', $msExportOrdersExcel->config, $corePath . 'processors/');
$modx->getRequest();


/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);