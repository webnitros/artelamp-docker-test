id: 48
source: 1
name: msExportOrdersExcel
description: 'msExportOrdersExcel snippet btn'
category: msExportOrdersExcel
properties: 'a:1:{s:26:"msexportordersexcel_tplBtn";a:7:{s:4:"name";s:26:"msexportordersexcel_tplBtn";s:4:"desc";s:51:"msexportordersexcel_prop_msexportordersexcel_tplBtn";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:26:"msExportOrdersExcel.button";s:7:"lexicon";s:30:"msexportordersexcel:properties";s:4:"area";s:0:"";}}'
static_file: core/components/msexportordersexcel/elements/snippets/msexportordersexcel.php

-----

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