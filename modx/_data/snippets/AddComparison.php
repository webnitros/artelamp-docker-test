id: 30
source: 1
name: AddComparison
category: Comparison
properties: 'a:6:{s:2:"id";a:7:{s:4:"name";s:2:"id";s:4:"desc";s:18:"comparison_prop_id";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}s:4:"list";a:7:{s:4:"name";s:4:"list";s:4:"desc";s:20:"comparison_prop_list";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:7:"default";s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}s:7:"list_id";a:7:{s:4:"name";s:7:"list_id";s:4:"desc";s:23:"comparison_prop_list_id";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:1;s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}s:8:"maxItems";a:7:{s:4:"name";s:8:"maxItems";s:4:"desc";s:24:"comparison_prop_maxItems";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:10;s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}s:8:"minItems";a:7:{s:4:"name";s:8:"minItems";s:4:"desc";s:24:"comparison_prop_minItems";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:1;s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:19:"comparison_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:18:"tpl.Comparison.add";s:7:"lexicon";s:21:"comparison:properties";s:4:"area";s:0:"";}}'
static_file: core/components/comparison/elements/snippets/snippet.addcomparison.php

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

if (empty($tpl)) {$tpl = 'tpl.Comparison.add';}
if (empty($list)) {$list = 'cmp';}
if (empty($id)) {$id = $modx->resource->id;}
if (empty($minItems)) {$minItems = 1;}
if (empty($maxItems)) {$maxItems = 10;}
if (empty($id)) {$id = $modx->resource->id;}
if (empty($list_id) || !is_numeric($list_id)) {
	return $modx->lexicon('comparison_err_no_list_id');
}

$ids = !empty($_SESSION['Comparison'][$modx->context->key][$list])
	? $_SESSION['Comparison'][$modx->context->key][$list]['ids']
	: array();
$_SESSION['Comparison'][$modx->context->key][$list] = array(
	'list_id' => $list_id,
	'minItems' => $minItems,
	'maxItems' => $maxItems,
	'ids' => $ids,
);

$pls = array(
	'list' => $list,
	'id' => $id,
	'list_id' => $list_id,
	'added' => isset($ids[$id]),
	'can_compare' => count($ids) > 1,
	'total' => count($ids),
);
$modx->regClientScript('<script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){ Comparison.add.initialize(".comparison-'.$list.'", {minItems:'.$minItems.'});});</script>', true);


return $pls;

$link_params = array();
if ($list != 'default') {
	$link_params['list'] = $list;
}
if (!empty($ids)) {
	$link_params['cmp_ids'] = implode(',', array_keys($ids));
}
$pls['link'] = !empty($link_params['cmp_ids'])
	? urldecode($modx->context->makeUrl($list_id, $link_params, $modx->getOption('link_tag_scheme')))
	: '#';

$modx->regClientScript('<script type="text/javascript">document.addEventListener("DOMContentLoaded", function(){ Comparison.add.initialize(".comparison-'.$list.'", {minItems:'.$minItems.'});});</script>', true);
return $pdoTools->getChunk($tpl, $pls);