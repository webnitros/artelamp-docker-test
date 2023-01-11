id: 24
source: 1
name: msFavorites
category: msFavorites
properties: 'a:7:{s:2:"id";a:7:{s:4:"name";s:2:"id";s:4:"desc";s:19:"msfavorites_prop_id";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:4:"list";a:7:{s:4:"name";s:4:"list";s:4:"desc";s:21:"msfavorites_prop_list";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:7:"list_id";a:7:{s:4:"name";s:7:"list_id";s:4:"desc";s:24:"msfavorites_prop_list_id";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:1;s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:10:"nouserhide";a:7:{s:4:"name";s:10:"nouserhide";s:4:"desc";s:27:"msfavorites_prop_nouserhide";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:13:"toPlaceholder";a:7:{s:4:"name";s:13:"toPlaceholder";s:4:"desc";s:30:"msfavorites_prop_toPlaceholder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:20:"msfavorites_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:19:"tpl.msFavorites.add";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:7:"user_id";a:7:{s:4:"name";s:7:"user_id";s:4:"desc";s:24:"msfavorites_prop_user_id";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}}'
static_file: core/components/msfavorites/elements/snippets/snippet.msfavorites.php

-----

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
	'total' => $total,
	'link' => urldecode($modx->context->makeUrl($list_id, '', $modx->getOption('link_tag_scheme'))),
	'added' => ($count > 0) ? 'added' : '',
);


$modx->regClientScript('<script type="text/javascript">Favorites.add.initialize(".favorites-default", {list:"'.$list.'",list_id:"'.$list_id.'"});</script>', true);

return $pls;
if($nouserhide) $output = '';
else $output = $modx->getChunk($tpl, $pls);

$toPlaceholder = $modx->getOption('toPlaceholder',$scriptProperties,false);
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder,$output);
	return '';
}

return $output;