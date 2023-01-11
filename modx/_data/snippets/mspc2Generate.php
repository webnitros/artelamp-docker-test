id: 55
source: 1
name: mspc2Generate
category: msPromoCode2
properties: 'a:16:{s:6:"format";a:7:{s:4:"name";s:6:"format";s:4:"desc";s:24:"mspromocode2_prop_format";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:15:"[a-zA-Z0-9]{12}";s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:7:"seconds";a:7:{s:4:"name";s:7:"seconds";s:4:"desc";s:25:"mspromocode2_prop_seconds";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:90;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:4:"list";a:7:{s:4:"name";s:4:"list";s:4:"desc";s:22:"mspromocode2_prop_list";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:8:"generate";s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:5:"count";a:7:{s:4:"name";s:5:"count";s:4:"desc";s:23:"mspromocode2_prop_count";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:1;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:8:"discount";a:7:{s:4:"name";s:8:"discount";s:4:"desc";s:26:"mspromocode2_prop_discount";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";s:3:"10%";s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:11:"description";a:7:{s:4:"name";s:11:"description";s:4:"desc";s:29:"mspromocode2_prop_description";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:8:"showinfo";a:7:{s:4:"name";s:8:"showinfo";s:4:"desc";s:26:"mspromocode2_prop_showinfo";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:1;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:7:"oneunit";a:7:{s:4:"name";s:7:"oneunit";s:4:"desc";s:25:"mspromocode2_prop_oneunit";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:8:"onlycart";a:7:{s:4:"name";s:8:"onlycart";s:4:"desc";s:26:"mspromocode2_prop_onlycart";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:11:"unsetifnull";a:7:{s:4:"name";s:11:"unsetifnull";s:4:"desc";s:29:"mspromocode2_prop_unsetifnull";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:15:"unsetifnull_msg";a:7:{s:4:"name";s:15:"unsetifnull_msg";s:4:"desc";s:33:"mspromocode2_prop_unsetifnull_msg";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:0:"";s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:8:"oldprice";a:7:{s:4:"name";s:8:"oldprice";s:4:"desc";s:26:"mspromocode2_prop_oldprice";s:4:"type";s:13:"combo-boolean";s:7:"options";a:0:{}s:5:"value";b:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:8:"lifetime";a:7:{s:4:"name";s:8:"lifetime";s:4:"desc";s:26:"mspromocode2_prop_lifetime";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:9:"startedon";a:7:{s:4:"name";s:9:"startedon";s:4:"desc";s:27:"mspromocode2_prop_startedon";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:9:"stoppedon";a:7:{s:4:"name";s:9:"stoppedon";s:4:"desc";s:27:"mspromocode2_prop_stoppedon";s:4:"type";s:11:"numberfield";s:7:"options";a:0:{}s:5:"value";i:0;s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}s:3:"tpl";a:7:{s:4:"name";s:3:"tpl";s:4:"desc";s:21:"mspromocode2_prop_tpl";s:4:"type";s:9:"textfield";s:7:"options";a:0:{}s:5:"value";s:25:"tpl.msPromoCode2.generate";s:7:"lexicon";s:23:"mspromocode2:properties";s:4:"area";s:0:"";}}'
static_file: core/components/mspromocode2/elements/snippets/generate.php

-----

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