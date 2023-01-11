id: 29
source: 1
name: addLooked
category: Looked
properties: 'a:2:{s:9:"templates";a:7:{s:4:"name";s:9:"templates";s:4:"desc";s:21:"looked_prop_templates";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}s:5:"limit";a:7:{s:4:"name";s:5:"limit";s:4:"desc";s:17:"looked_prop_limit";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:5;s:7:"lexicon";s:17:"looked:properties";s:4:"area";s:0:"";}}'
static_file: core/components/looked/elements/snippets/snippet.addlooked.php

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

$templates = $modx->getOption('templates', $scriptProperties, '');
$limit = $modx->getOption('limit', $scriptProperties, '5');

$id = $modx->resource->id;
$template = $modx->resource->template;
$arrTemplate = !empty($templates)
	? explode(',', str_replace(' ', '', $templates))
	: array();

if (empty($arrTemplate) || in_array($template, $arrTemplate)) {
	if (!isset($_SESSION['looked'])) {
		$_SESSION['looked'] = array();
		$_SESSION['looked'][] = $id;
	} else {
		if (in_array($id, $_SESSION['looked']) === false) {
			array_unshift($_SESSION['looked'], $id);
		}
		if (count($_SESSION['looked']) > $limit) {
			array_pop($_SESSION['looked']);
		}
	}
	return;
}
return;