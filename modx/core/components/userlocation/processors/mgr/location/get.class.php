<?php

/**
 * Get an ulLocation
 */
class ulLocationGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'ulLocation';
    public $classKey = 'ulLocation';
    public $languageTopics = ['userlocation'];
    public $permission = '';


    /** {@inheritDoc} */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }

}

return 'ulLocationGetProcessor';