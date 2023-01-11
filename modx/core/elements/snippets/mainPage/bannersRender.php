<?php
/** @var modX $modx */
/** @var pdoTools $pdoTools */
$assets_source = isset($assets_source) ? $assets_source : '/inc/';
$pdoTools = $modx->getService('pdoTools');
$slider = $modx->resource->getTVValue('slider');
$outer = '';
if (!empty($slider)) {
    $sliders = json_decode($slider, 1);
    if ($sliders and is_array($sliders) and !empty($sliders)) {
        foreach ($sliders as $slider) {
            if ($slider['active'] == 1 and $slider['chunk']) {
                $slider['assets_source'] = $assets_source;
                $outer .= $pdoTools->getChunk(trim($slider['chunk']), $slider);
            }
        }
    }
}
return $outer;