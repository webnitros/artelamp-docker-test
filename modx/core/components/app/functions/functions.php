<?php
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