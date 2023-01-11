<?php

/**
 * Get an msProduct
 */
class modmsProductGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'msProduct';
    public $classKey = 'msProduct';
    public $languageTopics = array('nsi');
    public $permission = '';

    public $nsi;

    /** {@inheritDoc} */
    public function initialize()
    {
        /** @var nsi $nsi */
        $this->nsi = $this->modx->getService('nsi');
        $this->nsi->initialize($this->getProperty('context', $this->modx->context->key));

        return parent::initialize();
    }

    /**
     * @return array|string
     */
    public function cleanup()
    {
        $array = $this->object->toArray();

        return $this->success('', $array);
    }

}

return 'modmsProductGetProcessor';