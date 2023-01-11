<?php
/** @var array $scriptProperties */
/** @var Comparison $Comparison */
$Comparison = $modx->getService('comparison','Comparison',$modx->getOption('comparison_core_path',null,$modx->getOption('core_path').'components/comparison/').'model/comparison/',$scriptProperties);
if (!($Comparison instanceof Comparison)) return '';
$Comparison->initialize($modx->context->key);
/** @var pdoTools $pdoTools */
$scriptProperties['nestedChunkPrefix'] = 'comparison_';
$pdoTools = $modx->getService('pdoTools');
$pdoTools->setConfig($scriptProperties);

$list = trim($modx->getOption('list', $scriptProperties, 'default'));
$tpl = $modx->getOption('tpl', $scriptProperties, 'tpl.Comparison.get');

$can_compare = $added = false;
$link = '#';
$count = 0;
if (!empty($_SESSION['Comparison'][$modx->context->key][$list])) {
	$params = $_SESSION['Comparison'][$modx->context->key][$list];
	$count = count($params['ids']);
	if ($count >= $params['minItems']) {
		$can_compare = true;
	}

	$link_params = array();
	if ($list != 'default') {
		$link_params['list'] = $list;
	}
	$link_params['cmp_ids'] = implode(',', array_keys($params['ids']));
	$link = $modx->makeUrl($params['list_id'], '', $link_params);
}

$added = $modx->resource->id != $params['list_id'];

return $pdoTools->getChunk($tpl, array(
	'link' => $link,
	'count' => $count,
	'list' => $list,
	'can_compare' => $can_compare,
	'added' => $added
));