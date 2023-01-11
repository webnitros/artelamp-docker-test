id: 43
source: 1
name: bxSubscribeSegments
description: 'bxSender сниппет вывода сегментов подписок'
category: bxSender
properties: 'a:1:{s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:17:"bxsender_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:21:"tpl.bxSender.Segments";s:7:"lexicon";s:19:"bxsender:properties";s:4:"area";s:0:"";}}'
static_file: core/components/bxsender/elements/snippets/segments.php

-----

/** @var array $scriptProperties */
/** @var bxSender $bxSender */
$bxSender = $modx->getService('bxsender', 'bxSender', $modx->getOption('bxsender_core_path', null, $modx->getOption('core_path') . 'components/bxsender/') . 'model/', $scriptProperties);
if (!($bxSender instanceof bxSender)) return '';

$bxSender->loadPdoTools();

$tpl = $modx->getOption('tpl', $scriptProperties, 'bxSegments');
$checkeds = $modx->getOption('checkeds', $scriptProperties, '');
if (!empty($checkeds)) {
    $checkeds = explode(',', $checkeds);
} else {
    $checkeds = array();
}


$data = array();
$segments = array();

/* @var bxSegment $object */
$q = $modx->newQuery('bxSegment');
$q->where(array(
    'active' => 1,
    'allow_subscription' => 1
));
$q->sortby('rank', 'ASC');
if ($objectList = $modx->getCollection('bxSegment', $q)) {
    foreach ($objectList as $object) {
        $row = $object->toArray();

        $row['checked'] = in_array($row['id'], $checkeds) ? 'checked' : '';
        $segments[] = $row;
    }
}

$data['segments'] = $segments;
$outer = $bxSender->pdoFetch->getChunk($tpl, $data);
return $outer;