<?php

/**
 * Get an modResource
 */
class modmodResourceGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'modResource';
    public $classKey = 'modResource';
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

return 'modmodResourceGetProcessor';