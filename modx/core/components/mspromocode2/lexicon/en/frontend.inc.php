<?php
require dirname(__FILE__) . '/../ru/frontend.inc.php';

// Errors
$_lang['mspc2_front_err_code_empty'] = 'Enter promo code';
$_lang['mspc2_front_err_coupon_exist'] = 'Promo code not found';
$_lang['mspc2_front_err_coupon_count'] = 'Promo code not available';
$_lang['mspc2_front_err_coupon_active'] = 'Promo code not active';
$_lang['mspc2_front_err_coupon_startedon'] = 'Promo code has not yet entered into force';
$_lang['mspc2_front_err_coupon_stoppedon'] = 'Promo code has already expired';
$_lang['mspc2_front_err_coupon_cart_is_null'] = 'There are no products matching this promo code in your cart';

// Messages
$_lang['mspc2_front_message_coupon_cart_is_null'] = 'There are no products matching this promo code in your cart';

// Success
$_lang['mspc2_front_success_set'] = 'Promo code applied';
$_lang['mspc2_front_success_unset'] = 'Promo code cancelled';