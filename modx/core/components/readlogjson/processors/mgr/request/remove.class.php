<?php
class ReadLogJsonRequestRemoveProcessor extends modObjectRemoveProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $languageTopics = ['readlogjson:manager'];
    #public $permission = 'remove';

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }
        return parent::initialize();
    }
}

return 'ReadLogJsonRequestRemoveProcessor';
