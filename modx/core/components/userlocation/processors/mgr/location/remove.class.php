<?php

/**
 * Remove a ulLocation
 */
class ulLocationRemoveProcessor extends modObjectRemoveProcessor
{
    public $classKey = 'ulLocation';
    public $languageTopics = ['userlocation'];
    public $permission = '';

    /** {@inheritDoc} */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        return parent::initialize();
    }

}

return 'ulLocationRemoveProcessor';