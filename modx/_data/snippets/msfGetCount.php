id: 27
source: 1
name: msfGetCount
category: msFavorites
properties: 'a:6:{s:2:"id";a:7:{s:4:"name";s:2:"id";s:4:"desc";s:19:"msfavorites_prop_id";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:4:"list";a:7:{s:4:"name";s:4:"list";s:4:"desc";s:21:"msfavorites_prop_list";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:7:"list_id";a:7:{s:4:"name";s:7:"list_id";s:4:"desc";s:24:"msfavorites_prop_list_id";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:1;s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:8:"showZero";a:7:{s:4:"name";s:8:"showZero";s:4:"desc";s:25:"msfavorites_prop_showZero";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:13:"toPlaceholder";a:7:{s:4:"name";s:13:"toPlaceholder";s:4:"desc";s:30:"msfavorites_prop_toPlaceholder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:20:"msfavorites_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"tpl.msfGetCount";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}}'
static_file: core/components/msfavorites/elements/snippets/snippet.msf_get_count.php

-----

return !empty($_SESSION['msfavorites']['list']['ids'])  ? count($_SESSION['msfavorites']['list']['ids']) : 0;

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
return $pls;
$output = $modx->getChunk($tpl, $pls);
//
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
	return '';
}
return $output;