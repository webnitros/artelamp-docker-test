<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once dirname(__FILE__, 1) . '/RequestTrait.php';

class ReadLogJsonRequestCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $languageTopics = ['readlogjson:manager'];
    //public $permission = 'create';
    use RequestTrait;

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $this->setProperty('mode', 'new');
        $this->checkValidate();

        return parent::beforeSet();
    }

}

return 'ReadLogJsonRequestCreateProcessor';
