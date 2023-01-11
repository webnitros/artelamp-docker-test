<?php

/**
 * formConstruct
 *
 * @package form_construct
 */

/**
 * Get an Item
 *
 * @package form_construct
 * @subpackage processors
 */
if (empty($scriptProperties['id'])) return $modx->error->failure($modx->lexicon('form_construct.item_err_ns'));
/* @var msProduct $item */
$item = $modx->getObject('msProduct', $scriptProperties['id']);
if (!$item) return $modx->error->failure($modx->lexicon('form_construct.item_err_nf'));

/* output */
$itemArray = $item->toArray('', true);

$categories = array();
$item->loadParents($item);
$codes = $item->getCodes();
foreach ($codes as $code) {
    $categories[] = $code;
}



$itemArray['fields_data'] = $categories;
#$itemArray['fields_data'] = json_decode($categories,true);

return $modx->error->success('', $itemArray);