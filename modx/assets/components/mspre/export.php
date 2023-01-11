<?php
ini_set('display_errors', 1);
$file = dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
if (!file_exists($file)) {
    $file = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
require_once $file;
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';


define('MODX_API_MODE', true);
require_once MODX_CONNECTORS_PATH . 'index.php';



/* @var msPre $mspre */
$corePath = $modx->getOption('mspre.core_path', null, $modx->getOption('core_path') . 'components/mspre/');
$mspre = $modx->getService('mspre', 'mspre', $corePath . 'model/');
$modx->mspre = $mspre;


ini_set("memory_limit", $mspre->getOption('export_memory_limit'));
ini_set("max_execution_time", $mspre->getOption('export_memory_limit'));

$context_key = !empty($_GET['context_key']) ? $_GET['context_key'] : 'web';
$format = 'CSV';
if (isset($_GET['format'])) {
    $format = $modx->stripTags($_GET['format']);
}
$controller = 'product';
if (isset($_GET['controller'])) {
    $controller = $modx->stripTags($_GET['controller']);
}


// Получаем все ID экспортируемых товаров
$ids = $mspre->getCacheManager();
if (empty($ids)) {
    exit('Передано 0 товаров для экспорта');
}




if ($Execel = $mspre->loadExecel()) {
    include_once $corePath . 'lib/msprefillingresource.class.php';
    $msPreFillingResource = new msPreFillingResource($mspre);
    $resourcePrepare = $msPreFillingResource->process($controller, $ids, $context_key);

    $today = date('Y-m-d H:i:s', time());
    $filename = "export_{$controller}s_{$today}." . mb_strtolower($format);
    $Execel->setFilename($filename);
    $Execel->setFormat($format);
    $Execel->setTabName($controller);
    $Execel->setColumns(array_keys($resourcePrepare[0]));
    $Execel->export($resourcePrepare);
}
@session_write_close();
exit();