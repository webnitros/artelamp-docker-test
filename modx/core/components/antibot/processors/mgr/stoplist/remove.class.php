<?php

class antiBotStopListRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'antiBotStopList';
    public $classKey = 'antiBotStopList';
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('antibot_item_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var antiBotStopList $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('antibot_item_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'antiBotStopListRemoveProcessor';