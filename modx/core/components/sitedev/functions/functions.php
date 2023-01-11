<?php


if (!function_exists('formatPrice')) {
    function formatPrice($price = 0)
    {
        global $modx;
        if (!$pf = json_decode($modx->getOption('ms2_price_format', null, '[2, ".", " "]'), true)) {
            $pf = array(2, '.', ' ');
        }

        $price = number_format($price, $pf[0], $pf[1], $pf[2]);

        if ($modx->getOption('ms2_price_format_no_zeros', null, true)) {
            $tmp = explode($pf[1], $price);
            $tmp[1] = rtrim(rtrim(@$tmp[1], '0'), '.');
            $price = !empty($tmp[1])
                ? $tmp[0] . $pf[1] . $tmp[1]
                : $tmp[0];
        }

        return $price;
    }
}
if (!function_exists('cacheValuesSite')) {
    /**
     * @param modX $modx
     * @param $key
     * @param $callback
     * @param bool $cache
     * @return mixed|null
     */
    function cacheValuesSite(modX $modx, $key, $callback, $cache = true, $lifetime = 3600)
    {
        $optionsCache = array(
            xPDO::OPT_CACHE_KEY => 'default/site_cache/',
            xPDO::OPT_CACHE_HANDLER => 'xPDOFileCache'
        );

        $newValues = null;
        if ($cache) {
            /* @var modCacheManager $cacheManager */
            $cacheManager = $modx->getCacheManager();
            $newValues = $cacheManager->get($key, $optionsCache);
        }
        if (empty($newValues)) {
            $newValues = $callback($modx);
            if ($cache and !empty($newValues)) {
                if (!$response = $cacheManager->set($key, $newValues, $lifetime, $optionsCache)) {
                    $modx->log(modX::LOG_LEVEL_ERROR, "Error save " . $key . ' values ' . print_r($newValues, 1), '', __METHOD__, __FILE__, __LINE__);
                }
            }
        }
        return $newValues;
    }
}
if (!function_exists('getContentRemote')) {
    /**
     * @param modX $modx
     * @param $key
     * @param $callback
     * @param bool $cache
     * @return mixed|null
     */
    function getContentRemote(modX $modx, $url, $timeout = 10)
    {
        // timeout of one second
        $context = stream_context_create(array('http' => array(
            'timeout' => $timeout,
            'ignore_errors' => true,
        )));

        $response = [
            'success' => true
        ];
        $content = @file_get_contents($url, false, $context);
        if ($content === false && count($http_response_header) === 0) {
            $response = [
                'success' => true,
                'code' => 500,
                'msg' => 'Сервер ничего не вернул ' . $url,
            ];
        } else {
            $code = (int)substr($http_response_header[0], 9, 3);
            if ($code !== 200) {
                $response['success'] = false;
            }
            $response['code'] = $code;
            $res = !empty($response) ? $modx->fromJSON($content) : $content;
            $response['response'] = $res;
        }
        return $response;
    }
}
