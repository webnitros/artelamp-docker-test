<?php

$msFavorites = $modx->getService('msfavorites','msFavorites',$modx->getOption('msfavorites_core_path',null,$modx->getOption('core_path').'components/msfavorites/').'model/msfavorites/',$scriptProperties);
if (!($msFavorites instanceof msFavorites)) return '';
$msFavorites->initialize($modx->context->key);

if (empty($tpl)) {$tpl = 'tpl.msFavorites.add';}
if (empty($list)) {$list = 'list';}
if (empty($id)) {$id = $modx->resource->id;}
if (empty($list_id) || !is_numeric($list_id)) {
	return $modx->lexicon('msfavorites_err_add_resource');
}
if (empty($user_id)) {$user_id = $modx->user->id;}
if (empty($nouserhide)) {$nouserhide = false;}

$mode = $modx->getOption('msfavorites_mode', $config, 0);
if(($mode == 2) && ($user_id !== 0)) $mode = 0;
elseif(($mode == 2) && ($user_id == 0)) $mode = 1;

switch ($mode) {
	case 0:
	{
		if ($user_id == 0) {
			$total = 0;
			$count = 0;
		}
		else {
			$total = $modx->getCount('msFavoritesList', array('user_id' => $user_id, 'list' => $list, 'msf_id:!=' => 0));
			$count = $modx->getCount('msFavoritesList', array('user_id' => $user_id, 'msf_id' => $id, 'list' => $list));
		}
		break;
	}
	case 1:
	{
		$ids = !empty($_SESSION['msfavorites'][$list])
			? $_SESSION['msfavorites'][$list]['ids']
			: array();
		$count = !empty($_SESSION['msfavorites'][$list]['ids'][$id])
			? 1
			: 0;
		$total = count($ids);
		break;
	}
	break;
}

$pls = array(
	'list' => $list,
	'id' => $id,
	'list_id' => $list_id,
	'msfavorites.total' => $total,
	'msfavorites.link' => urldecode($modx->context->makeUrl($list_id, '', $modx->getOption('link_tag_scheme'))),
	'msfavorites.added' => ($count > 0) ? 'added' : '',
);

if($nouserhide) $output = '';
else $output = $modx->getChunk($tpl, $pls);

$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder,$output);
	return '';
}

$modx->regClientScript('<script type="text/javascript">Favorites.add.initialize(".favorites-default", {list:"'.$list.'",list_id:"'.$list_id.'"});</script>', true);
return $output;