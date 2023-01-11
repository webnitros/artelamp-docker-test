<?php
/** @var modX $modx */
define('MODX_API_MODE', true);
/** @noinspection PhpIncludeInspection */
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
$modx->getService('error', 'error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->reset();

/* @var msExportOrdersExcel $msExportOrdersExcel */
$msExportOrdersExcel = $modx->getService('msExportOrdersExcel', 'msExportOrdersExcel', MODX_CORE_PATH . 'components/msexportordersexcel/model/');

$src = isset($_REQUEST['src']) ? (string)trim(trim($_REQUEST['src'])) : '';
$source_id = isset($_REQUEST['source']) ? (int)trim($_REQUEST['source']) : '';

$remove = false;
if (isset($_REQUEST['remove'])) {
    $remove = !empty($_REQUEST['remove']);       
}
$profile = isset($_REQUEST['profile']) ? (int)trim($_REQUEST['profile']) : '';


/*if (!empty($profile)) {
    if ($msExportOrdersExcelProfileHandler = $modx->getObject('msExportOrdersExcelProfile', $profile)) {
        $remove = $msExportOrdersExcelProfileHandler->get('remove');
    }
}*/

/* @var msExportOrdersExcelProfile $Profile */
/* @var modFileMediaSource $source */
if ($source = $msExportOrdersExcel->loadSourceInitialize($source_id)) {

    // Проверяем политику доступ для пользователя
    // Обычный метод $source->hasPermission('file_view') отдает не правильную политику доступа для объекта modAccessibleObject а не для sources.modAccessMediaSource
    if (!$source->checkPolicy('file_view', 'sources.modAccessMediaSource')) {
        $modx->sendUnauthorizedPage();
    }

    $modx->lexicon->load('core:file');
    $path = $source->getBasePath() . $src; // Абсолютынй путь до файла
    if ($file = $msExportOrdersExcel->getFile($path, $source)) {
        if ($file->exists()) {
            $file->download($remove);
        }
    }

}


$modx->log(modX::LOG_LEVEL_ERROR, "[msExportOrdersExcel] Error could not found file params " . print_r($_REQUEST, 1));
$error_page = $modx->getOption('msexportordersexcel_error_page', null, null);
if ($error_page) {
    header("HTTP/1.0 404 Not Found");
    $modx->sendForward($error_page, array('src' => $src));
} else {
    $modx->sendErrorPage();
}
echo 'Could not file';
die();




