<?php
if (!class_exists('modmsMultipleDefaultProcessor')) {
    include_once dirname(dirname(dirname(__FILE__))) . '/multiple.php';
}

/**
 * Multiple a msProduct
 */
class modmsProductMultipleProcessor extends modmsMultipleDefaultProcessor
{
    public $classKey = 'msProduct';
    public $processors_path = __DIR__.'/';
}

return 'modmsProductMultipleProcessor';