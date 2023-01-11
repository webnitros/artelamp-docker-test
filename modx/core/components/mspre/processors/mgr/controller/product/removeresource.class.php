<?php
if (!class_exists('mspreRemoveResourceProcessor')) {
    include_once MODX_CORE_PATH . 'components/mspre/processors/mgr/common/removeresource.class.php';
}

class modmsProductRemoveResourceProcessor extends mspreRemoveResourceProcessor
{
    public $classKey = 'msProduct';
}

return 'modmsProductRemoveResourceProcessor';