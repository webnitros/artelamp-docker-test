<?php

class antiBotHitsRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'antiBotHits';
    public $classKey = 'antiBotHits';
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

        if (!isset($this->properties['ids'])) {
            /* @var antiBotHits $object */
            $q = $this->modx->newQuery($this->classKey);
            if ($objectList = $this->modx->getCollection($this->classKey, $q)) {
                foreach ($objectList as $object) {
                    $object->remove();
                }
            }
        } else {
            $ids = $this->modx->fromJSON($this->getProperty('ids'));
            if (empty($ids)) {
                return $this->failure($this->modx->lexicon('antibot_hit_err_ns'));
            }
            foreach ($ids as $id) {
                /** @var antiBotHits $object */
                if (!$object = $this->modx->getObject($this->classKey, $id)) {
                    return $this->failure($this->modx->lexicon('antibot_hit_err_nf'));
                }

                $object->remove();
            }
        }
        return $this->success();
    }

}

return 'antiBotHitsRemoveProcessor';