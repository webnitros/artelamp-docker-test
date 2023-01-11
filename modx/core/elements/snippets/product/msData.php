<?php
/** @var modX $modx */
if ($modx->resource->get('class_key') === 'msProduct') {
    $data = $modx->resource->toArray();
}
return $data;
