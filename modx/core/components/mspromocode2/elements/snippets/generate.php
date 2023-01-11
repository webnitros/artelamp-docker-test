<?php
/** @var modX $modx */
/** @var msPromoCode2 $mspc2 */
/** @var array $scriptProperties */
$sp = &$scriptProperties;
if (!$mspc2 = $modx->getService('mspromocode2', 'msPromoCode2',
    $modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/', $sp)
) {
    return 'Could not load msPromoCode2 class!';
}
$mspc2->initialize($modx->context->key);
$ms2 = $mspc2->getMiniShop2();
$manager = $mspc2->getManager();

//
$sp['seconds'] = $modx->getOption('seconds', $sp, 0);
$sp['discount'] = $modx->getOption('discount', $sp, '10%', true);
if (empty(floatval($sp['discount']))) {
    return;
}
$sp['tpl'] = $modx->getOption('tpl', $sp, 'tpl.msPromoCode2.generate');

// Save properties to session
$sp['propkey'] = sha1(serialize($sp));
if (isset($_SESSION['msPromoCode2']['properties'][$sp['propkey']])) {
    $sp = array_merge($_SESSION['msPromoCode2']['properties'][$sp['propkey']], $sp);
}
$_SESSION['msPromoCode2']['properties'][$sp['propkey']] = $sp;

// Get coupon data
if ($sp['coupon']) {
    $sp['coupon'] = $manager->getCoupon($sp['coupon']);
    if (!is_array($sp['coupon'])) {
        $_SESSION['msPromoCode2']['properties'][$sp['propkey']]['coupon'] = null;
    }
}

//
$mspc2->loadFrontendScripts([
    'generate' => [],
]);

//
$output = $mspc2->tools->getChunk($sp['tpl'], array_merge($sp, [
]));

return $output;