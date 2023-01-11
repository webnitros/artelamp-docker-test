<?php

class mspreSyncSityProcessor extends modObjectProcessor
{
    public $classKey = 'modResource';
    public function process()
    {
        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }
        /* @var msProduct $object */
        if ($object = $this->modx->getObject($this->classKey, $id)) {
            $object->clearCache($object->get('context_key'));
        }
        return $this->success();
    }
}

return 'mspreSyncSityProcessor';
