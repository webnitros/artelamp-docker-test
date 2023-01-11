<?php

class ReadLogJsonRequestGetProcessor extends modObjectGetProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $languageTopics = ['readlogjson:manager'];
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


    /**
     * Return the response
     * @return array
     */
    public function cleanup()
    {
        $array = $this->object->cleanup();
        $array['method_name'] = $array['method'];
        return $this->success('', $array);
    }

}

return 'ReadLogJsonRequestGetProcessor';
