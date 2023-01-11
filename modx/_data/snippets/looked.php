id: 28
source: 1
name: looked
category: Looked
properties: 'a:9:{s:10:"frontendJs";a:7:{s:4:"name";s:10:"frontendJs";s:4:"desc";s:22:"looked_prop_frontendJs";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:34:"components/looked/js/web/looked.js";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:3:"ids";a:7:{s:4:"name";s:3:"ids";s:4:"desc";s:15:"looked_prop_ids";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:5:"limit";a:7:{s:4:"name";s:5:"limit";s:4:"desc";s:17:"looked_prop_limit";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:5;s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:7:"parents";a:7:{s:4:"name";s:7:"parents";s:4:"desc";s:19:"looked_prop_parents";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:7:"snippet";a:7:{s:4:"name";s:7:"snippet";s:4:"desc";s:19:"looked_prop_snippet";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:10:"msProducts";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:6:"sortby";a:7:{s:4:"name";s:6:"sortby";s:4:"desc";s:18:"looked_prop_sortby";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:7:"sortdir";a:7:{s:4:"name";s:7:"sortdir";s:4:"desc";s:19:"looked_prop_sortdir";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:15:"looked_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:9:"lookedTpl";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:8:"tplOuter";a:7:{s:4:"name";s:8:"tplOuter";s:4:"desc";s:20:"looked_prop_tplOuter";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:14:"lookedOuterTpl";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}}'
static_file: core/components/looked/elements/snippets/snippet.looked.php

-----

/** @var modX $modx */
/** @var array $scriptProperties */
/** @var Looked $Looked */

if (!$Looked = $modx->getService('looked', 'Looked', $modx->getOption('looked_core_path',
        null, $modx->getOption('core_path') . 'components/looked/') . 'model/looked/',
    $scriptProperties)
) {
	return '';
}

$output = '';

if (isset($_SESSION['looked']) && !empty($_SESSION['looked'])) {
	$id = $modx->resource->get('id');
	$arrIds = $_SESSION['looked'];
	$count = count($arrIds);
    if(($key = array_search($id, $arrIds)) !== false){
		unset($arrIds[$key]);
        $count = $count - 1;
	}
    $modx->toPlaceholder('count', $count, 'looked');
    $ids = implode(',', $arrIds);
} else {
	return '';
}
if (empty($ids))
    return '';

if ($scriptProperties['ids'] == true) {
	$output = $ids;
} else {
	if ($out = $Looked->process($scriptProperties, $ids)) {
        $output = $Looked->getChunk($scriptProperties['tplOuter'], array('output' => $out));
    }
}

if (!empty($frontendJs)) {
    $modx->regClientScript(MODX_ASSETS_URL . $scriptProperties['frontendJs']);
}
$modx->regClientHTMLBlock('<script>Looked.initialize({ 
    "actionUrl":"' . $Looked->config['actionUrl'] . '",
    "id":"' . $modx->resource->id . '"});
</script>');

return $output;