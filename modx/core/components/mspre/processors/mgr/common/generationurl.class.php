<?php

class mspreGenerationUrlProcessor extends modObjectProcessor
{

    public $classKey = null;

    public function process()
    {
        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon($this->objectType . '_err_ns');
        }

        /* @var msProduct|modResource $object */
        if ($object = $this->modx->getObject($this->classKey, $id)) {
            $this->modx->call('modResource', 'refreshURIs', array(
                &$this->modx,
                $object->get('parent'),
                array(
                    'contexts' => $object->get('context_key')
                )
            ));
        }
        return $this->success();
    }
}
return 'mspreGenerationUrlProcessor';
