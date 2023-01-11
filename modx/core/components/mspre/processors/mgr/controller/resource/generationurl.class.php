<?php
if (!class_exists('modGenerationUrlMultipleProcessor')) {
    include_once MODX_CORE_PATH . 'components/mspre/processors/mgr/common/generationurl.class.php';
}

class modmodResourceGenerationUrlProcessor extends modGenerationUrlMultipleProcessor
{
    public $classKey = 'modResource';
}

return 'modmodResourceGenerationUrlProcessor';