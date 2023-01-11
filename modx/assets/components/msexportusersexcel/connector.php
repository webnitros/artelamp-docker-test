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
/** @var msExportUsersExcel $msExportUsersExcel */
$msExportUsersExcel = $modx->getService('msExportUsersExcel', 'msExportUsersExcel', MODX_CORE_PATH . 'components/msexportusersexcel/model/');
$modx->lexicon->load('msexportusersexcel:default');
$modx->lexicon->load('msexportusersexcel:manager');

// handle request
$corePath = $modx->getOption('msexportusersexcel_core_path', null, $modx->getOption('core_path') . 'components/msexportusersexcel/');
$path = $modx->getOption('processorsPath', $msExportUsersExcel->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);