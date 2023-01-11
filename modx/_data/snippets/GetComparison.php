id: 31
source: 1
name: GetComparison
category: Comparison
properties: 'a:2:{s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:19:"comparison_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"tpl.Comparison.get";s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}s:4:"list";a:7:{s:4:"name";s:4:"list";s:4:"desc";s:20:"comparison_prop_list";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:7:"default";s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}}'
static_file: core/components/comparison/elements/snippets/snippet.getcomparison.php

-----

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

return array(
	'link' => $link,
	'count' => $count,
	'list' => $list,
	'can_compare' => $can_compare,
	'added' => $added
);

return $pdoTools->getChunk($tpl, array(
	'link' => $link,
	'count' => $count,
	'list' => $list,
	'can_compare' => $can_compare,
	'added' => $added
));