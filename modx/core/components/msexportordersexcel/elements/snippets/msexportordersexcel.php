<?php
/** @var modX $modx */
/** @var array $scriptProperties */
/* @var msExportOrdersExcel $msExportOrdersExcel */
$msExportOrdersExcel = $modx->getService('msExportOrdersExcel', 'msExportOrdersExcel', MODX_CORE_PATH . 'components/msexportordersexcel/model/');
$tplBtn = $modx->getOption('tplBtn', $scriptProperties,'msExportOrdersExcel.button');
if (!$modx->user->isAuthenticated()) {
 #   return $modx->lexicon('msexportordersexcel_isauth');
}

$user_id = 2;
$count = $modx->getCount('msOrder', array('user_id' => $user_id));
return $modx->getChunk($tplBtn, array('total' => $count));