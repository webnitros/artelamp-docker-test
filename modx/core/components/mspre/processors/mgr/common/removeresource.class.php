<?php

class mspreRemoveResourceProcessor extends modObjectProcessor
{

    public $classKey = null;

    public function process()
    {
        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        /* @var msProduct|modResource $object */
        if ($object = $this->modx->getObject($this->classKey, array('id' => $id, 'deleted' => 1))) {
            $object->remove();
        }
        return $this->success();
    }
}

return 'mspreRemoveResourceProcessor';
