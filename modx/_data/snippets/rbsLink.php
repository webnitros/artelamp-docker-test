id: 49
source: 1
name: rbsLink
properties: 'a:0:{}'

-----

return $modx->cacheManager->get($id, [xPDO::OPT_CACHE_KEY => 'rbs']);