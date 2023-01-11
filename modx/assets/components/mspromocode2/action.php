<?php
/** @var modX $modx */
if (!isset($modx)) {
    define('MODX_API_MODE', true);
    while (!isset($modx) && ($i = isset($i) ? --$i : 10)) {
        if (($file = dirname(!empty($file) ? dirname($file) : __FILE__) . '/index.php') AND !file_exists($file)) {
            continue;
        }
        require_once $file;
    }
    if (!is_object($modx)) {
        exit('{"success":false,"message":"Access denied"}');
    }
    $modx->getService('error', 'error.modError');
    $modx->getRequest();
    $modx->setLogLevel(modX::LOG_LEVEL_ERROR);
    $modx->setLogTarget('FILE');
    $modx->error->message = null;
    $modx->lexicon->load('default');
}
$ctx = !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : $modx->context->get('key');
if ($ctx != $modx->context->get('key')) {
    $modx->switchContext($ctx);
}

/** @var msPromoCode2 $mspc2 */
if (!$mspc2 = $modx->getService('mspromocode2', 'msPromoCode2',
    $modx->getOption('mspc2_core_path', null, MODX_CORE_PATH . 'components/mspromocode2/') . 'model/mspromocode2/')) {
    exit($modx->toJSON(array('success' => false, 'message' => 'Class msPromoCode2 not found')));
}
$mspc2->initialize($ctx, ['jsonResponse' => true]);
$manager = $mspc2->getManager();

//
if (empty($_REQUEST['action'])) {
    exit($mspc2->tools->failure('Access denied'));
}

// Load script properties
$snippetProperties = [];
if ($propkey = (@$_REQUEST['propkey'] ?: null)) {
    $snippetProperties = @$_SESSION['msPromoCode2']['properties'][$propkey];
    if (empty($snippetProperties) || !is_array($snippetProperties)) {
        exit($mspc2->tools->failure('Access denied'));
    }
}

//
switch ($_REQUEST['action']) {
    /**
     * Set coupon
     */
    case 'coupon/set':
        $code = @$_REQUEST['code'] ?: '';
        if (empty($code)) {
            $response = $mspc2->tools->failure('mspc2_front_err_code_empty');
            break;
        }

        //
        $coupon = $manager->setCoupon((string)$code);
        if (is_array($coupon)) {
            //
            $manager->refreshCartProductKeys();

            // Get discount amount
            $discount_amount = $_SESSION['msPromoCode2']['discount_amount'] ?: 0;

            //
            $message_info = $manager->getMessageSession('info');

            //
            $response = $mspc2->tools->success('mspc2_front_success_set', [
                'info' => $message_info,
                'coupon' => $coupon,
                'discount_amount' => $discount_amount,
            ]);
        } elseif (is_string($coupon)) {
            $response = $mspc2->tools->failure($coupon);
        } else {
            $response = $mspc2->tools->failure('mspc2_err_unexpected');
        }
        break;

    /**
     * Unset coupon
     */
    case 'coupon/unset':
        //
        $manager->unsetCoupon();

        //
        $manager->refreshCartProductKeys();

        //
        $response = $mspc2->tools->success('mspc2_front_success_unset', [
            // 'code' => $code,
            'discount_amount' => 0,
        ]);
        break;

    /**
     * Prices refresh
     */
    case 'prices/refresh':
        $products = @$_REQUEST['products'] ?: [];
        // if (empty($products)) {
        //     $response = $mspc2->tools->failure('mspc2_err_unexpected');
        //     break;
        // }

        // Get discount amount
        $discount_amount = $_SESSION['msPromoCode2']['discount_amount'] ?: 0;

        // Prepare products list
        foreach ($products as &$product) {
            $product['options'] = $product['options'] === '[]' ? [] : $product['options'];
        }
        unset($product);

        //
        if (!empty($products)) {
            $products = $manager->prepareProductPrices($products);
        }
        $response = $mspc2->tools->success('', [
            'products' => $products,
            'discount_amount' => $discount_amount,
        ]);
        break;

    /**
     * Generate / Coupon
     */
    case 'generate/coupon':
        if (empty($snippetProperties)) {
            $response = $mspc2->tools->failure('mspc2_err_unexpected');
            break;
        }
        if (empty($snippetProperties['format'])) {
            $response = $mspc2->tools->failure('mspc2_err_unexpected');
            break;
        }
        if ($snippetProperties['closed'] === true) {
            $response = $mspc2->tools->failure('');
            break;
        }

        // Get coupon
        if (!empty($snippetProperties['coupon'])) {
            $coupon = $manager->getCoupon($snippetProperties['coupon']);
        } else {
            $coupon = $manager->generateCoupon($snippetProperties['format'], $snippetProperties);
        }

        //
        if (empty($coupon) || !is_array($coupon)) {
            $response = $mspc2->tools->failure('mspc2_err_unexpected');
            break;
        }

        // Save to session
        $snippetProperties['coupon'] = (int)$coupon['id'];
        $_SESSION['msPromoCode2']['properties'][$propkey] = $snippetProperties;

        // Get template
        $wrap = $mspc2->tools->getChunk($snippetProperties['tpl'], array_merge($snippetProperties, [
            'coupon' => $coupon,
        ]));

        //
        $response = $mspc2->tools->success('', [
            'coupon' => $coupon,
            'wrap' => $wrap,
        ]);
        break;

    /**
     * Generate / Close
     */
    case 'generate/close':
        if (empty($snippetProperties)) {
            $response = $mspc2->tools->failure('mspc2_err_unexpected');
            break;
        }

        // Save to session
        $snippetProperties['closed'] = true;
        $_SESSION['msPromoCode2']['properties'][$propkey] = $snippetProperties;

        //
        $response = $mspc2->tools->success('', []);
        break;

    default:
        $response = $mspc2->tools->failure('Access denied');
}

@session_write_close();
exit($response);