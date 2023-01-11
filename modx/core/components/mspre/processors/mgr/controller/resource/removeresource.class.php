<?php
if (!class_exists('mspreRemoveResourceProcessor')) {
    include_once MODX_CORE_PATH . 'components/mspre/processors/mgr/common/removeresource.class.php';
}

class modmodResourceRemoveResourceProcessor extends mspreRemoveResourceProcessor
{
    public $classKey = 'modResource';
}

return 'modmodResourceRemoveResourceProcessor';