id: 25
source: 1
name: msFavoritesList
category: msFavorites
properties: 'a:3:{s:4:"list";a:7:{s:4:"name";s:4:"list";s:4:"desc";s:21:"msfavorites_prop_list";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:7:"user_id";a:7:{s:4:"name";s:7:"user_id";s:4:"desc";s:24:"msfavorites_prop_user_id";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}s:13:"toPlaceholder";a:7:{s:4:"name";s:13:"toPlaceholder";s:4:"desc";s:30:"msfavorites_prop_toPlaceholder";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:22:"msfavorites:properties";s:4:"area";s:0:"";}}'
static_file: core/components/msfavorites/elements/snippets/snippet.list.php

-----

$msFavorites = $modx->getService('msfavorites','msFavorites',$modx->getOption('msfavorites_core_path',null,$modx->getOption('core_path').'components/msfavorites/').'model/msfavorites/',$scriptProperties);
if (!($msFavorites instanceof msFavorites)) return '';
$msFavorites->initialize($modx->context->key);

if (empty($list)) {$list = 'list';}
if (empty($user_id)) {$user_id = $modx->user->id;}

$mode = $modx->getOption('msfavorites_mode', $config, 0);
if(($mode == 2) && ($user_id !== 0)) $mode = 0;
elseif(($mode == 2) && ($user_id == 0)) $mode = 1;

switch ($mode) {
	case 0:
	{
		if ($user_id == 0) {
			$total = 0;
			$output = '-0';
		}
		else {
			$msFavorites->move2base($list, $user_id);
			$msf = $modx->getIterator('msFavoritesList', array('user_id' => $user_id, 'list' => $list, 'msf_id:!=' => 0));
			foreach($msf as $m){
				$o['id'][] = $m->msf_id;
			}
			$total = count($o['id']);
			$output = ($total > 0) ? implode(',',$o['id']) : '-0';
		}
		break;
	}
	case 1:
	{
		$ids = !empty($_SESSION['msfavorites'][$list])
			? $_SESSION['msfavorites'][$list]['ids']
			: array();
		$total = count($ids);
		$output = ($total > 0) ? implode(',',array_keys($ids)) : '-0';
		break;
	}
		break;
}

$modx->regClientScript('<script type="text/javascript">Favorites.list.initialize(".msfavorites-list",{list:"'.$list.'"});</script>', true);
$modx->setPlaceholder('msfavorites.total.all', $total);
$modx->setPlaceholder('msfavorites.total.all.' . $list , $total);
$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);
if (!empty($toPlaceholder)) {
	$modx->setPlaceholder($toPlaceholder, $output);
	return '';
}
return $output;