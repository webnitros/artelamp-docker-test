<?php

$msFavorites = $modx->getService('msfavorites','msFavorites',$modx->getOption('msfavorites_core_path',null,$modx->getOption('core_path').'components/msfavorites/').'model/msfavorites/',$scriptProperties);
if (!($msFavorites instanceof msFavorites)) return '';
$msFavorites->initialize($modx->context->key);
//
if (empty($tpl)) {$tpl = 'tpl.msFavorites.add';}
if (empty($list)) {$list = 'list';}
if (empty($id)) {$id = $modx->resource->id;}
//
$mode = $modx->getOption('msfavorites_mode', $config, 0);
if($mode == 1) {return;}
//
$total = $modx->getCount('msFavoritesList', array('list' => $list, 'msf_id' => $id, 'msf_id:!=' => 0));
if(empty($showZero) && (empty($total))) {return;}
//
$pls = array(
	'list' => $list,
	'id' => $id,
	'list_id' => $list_id,
	'msfavorites.total' => $total,
	'msfavorites.link' => !empty($list_id)
			? urldecode($modx->context->makeUrl($list_id, '', $modx->getOption('link_tag_scheme')))
			: '',
);
$output = $modx->getChunk($tpl, $pls);
//
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
	return '';
}
return $output;