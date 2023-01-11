id: 56
source: 1
name: mspc2CartKey
category: msPromoCode2
properties: 'a:0:{}'
static_file: core/components/mspromocode2/elements/snippets/cart_key.php

-----

/** @var modX $modx */
/** @var msPromoCode2 $mspc2 */
/** @var array $scriptProperties */
$sp = &$scriptProperties;
// if (!$mspc2 = $modx->getService('mspromocode2', 'msPromoCode2',
//     $modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/', $sp)
// ) {
//     return 'Could not load msPromoCode2 class!';
// }
// $mspc2->initialize($modx->context->key);

//
$cart_product = isset($sp['input']) ? $sp['input'] : null;
$cart_product = isset($sp['product']) ? $sp['product'] : $cart_product;
if (!is_array($cart_product)) {
    return null;
}

//
$cart_product = array_intersect_key($cart_product, array_flip(['id', 'options']));
$cart_product['options'] = empty($cart_product['options']) ? [] : $cart_product['options'];
if (empty($cart_product['id'])) {
    return null;
}

return sha1(serialize($cart_product));