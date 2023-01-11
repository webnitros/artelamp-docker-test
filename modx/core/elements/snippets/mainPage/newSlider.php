<?php

$key = '';
$optionsCache = array(
    xPDO::OPT_CACHE_KEY => 'default/timecache',
    xPDO::OPT_CACHE_HANDLER => 'xPDOFileCache'
);
/* @var modCacheManager $cacheManager */
$cacheManager = $modx->getCacheManager();
$return = $cacheManager->get($key, $optionsCache);
if (empty($return)) {
    $tpl = '@FILE chunks/catalog/product/row.tpl';
    /** @var modX $modx */
    /** @var pdoTools $pdoTools */
    $pdoTools = $modx->getService('pdoTools');

    $q = $modx->newQuery('msProduct');
    $q->limit(21);
    $q->where([
        'Data.new' => TRUE,
        'Data.in_stock' => TRUE,
    ]);
    $q->innerJoin('msProductData', 'Data', 'Data.id = msProduct.id');
    $buffer = [];
    $echo = [];
    if ($products = $modx->getIterator('msProduct', $q)) {
        /** @var msProduct $product */
        $i = 0;
        foreach ($products as $product) {
            $product = $product->toArray();
            switch ($i) {
                case 0:
                case 1:
                    $product['isBig'] = 0;
                    $buffer[0] .= $pdoTools->getChunk($tpl, $product);
                    break;
                case 2:
                case 3:
                    $product['isBig'] = 0;
                    $buffer[1] .= $pdoTools->getChunk($tpl, $product);
                    break;
                case 4:
                case 5:
                    $product['isBig'] = 0;
                    $buffer[2] .= $pdoTools->getChunk($tpl, $product);
                    break;
                case 6:
                default:
                    $product['isBig'] = 1;
                    $buffer[3] .= $pdoTools->getChunk($tpl, $product);
                    $echo[] = $buffer;
                    $buffer = [];
                    $i = 0;
                    continue 2;
            }
            $i++;
        }
    }
    $return = '';
    foreach ($echo as $num => $buffer) {
        foreach ($buffer as $k => $v) {
            if ($k == 3) {
                $buffer[$k] = "<div class=\"swiper-slide unit_double\">{$v}</div>";
            } else {
                $buffer[$k] = "<div class=\"swiper-slide\">{$v}</div>";
            }
        }
        $return .= implode("\n", $buffer);
    }
    if (!$response = $cacheManager->set($key, $return, 10000, $optionsCache)) {
        return false;
    }
}
return $return;
