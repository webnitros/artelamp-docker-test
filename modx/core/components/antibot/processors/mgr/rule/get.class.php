<?php

class antiBotRuleGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'antiBotRule';
    public $classKey = 'antiBotRule';
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'view';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return mixed
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        return parent::process();
    }

}

return 'antiBotRuleGetProcessor';
