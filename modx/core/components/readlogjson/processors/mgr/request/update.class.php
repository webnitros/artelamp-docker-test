<?php
require_once dirname(__FILE__) . '/RequestTrait.php';

class ReadLogJsonRequestUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $languageTopics = ['readlogjson:manager'];
    //public $permission = 'save';
    use RequestTrait;

    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {


        $this->checkValidate();
        return parent::beforeSet();
    }
}

return 'ReadLogJsonRequestUpdateProcessor';
