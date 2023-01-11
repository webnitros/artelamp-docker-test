<?php
if (!class_exists('modmsMultipleDefaultProcessor')) {
    include_once dirname(dirname(dirname(__FILE__))) . '/multiple.php';
}

/**
 * Multiple a modResource
 */
class modmodResourceMultipleProcessor extends modmsMultipleDefaultProcessor
{
    public $classKey = 'modResource';
    public $processors_path = __DIR__.'/';
}

return 'modmodResourceMultipleProcessor';