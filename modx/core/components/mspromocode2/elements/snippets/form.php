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
$tpl = $modx->getOption('tpl', $sp, 'tpl.msPromoCode2.form');

// // Записываем параметры сниппета в сессию
// $sp['propkey'] = sha1(serialize($sp));
// $_SESSION['msPromoCode2']['properties'][$sp['propkey']] = $sp;

// Check is active
$is_active = false;
if ($coupon = $manager->getCurrentCoupon()) {
    $is_active = is_array($coupon);
}
if ($is_active === false) {
    $manager->unsetCoupon();
}

//
$message_info = '';
if (is_array($coupon)) {
    $message_info = $manager->getMessageSession('info');
} else {
    $manager->unsetMessageSession('info');
}

//
// $message_error = $manager->getMessageSession();
// if (is_string($coupon) && !empty($coupon)) {
//     $message_error = $coupon;
// }
$message_error = is_string($coupon) ? $coupon : '';

//
$mspc2->loadFrontendScripts([
    'main' => [],
]);

//
$output = $mspc2->tools->getChunk($tpl, [
    // 'propkey' => $sp['propkey'],

    //
    'coupon' => $coupon ?: [],
    'discount_amount' => $ms2->formatPrice(@$_SESSION['msPromoCode2']['discount_amount'] ?: 0),
    'is_active' => $is_active,

    //
    'message_info' => $message_info,
    'message_error' => $message_error,
    'message_success' => '',
]);

return $output;