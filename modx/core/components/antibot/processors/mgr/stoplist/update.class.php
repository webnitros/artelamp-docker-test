<?php

class antiBotStopListUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'antiBotStopList';
    public $classKey = 'antiBotStopList';
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'save';

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
        $id = (int)$this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon('antibot_item_err_ns');
        }

        /*$name = trim($this->getProperty('name'));
        if (empty($name)) {
            $this->modx->error->addField('name', $this->modx->lexicon('modextra_item_err_name'));
        } elseif ($this->modx->getCount($this->classKey, ['name' => $name, 'id:!=' => $id])) {
            $this->modx->error->addField('name', $this->modx->lexicon('modextra_item_err_ae'));
        }*/

        return parent::beforeSet();
    }
}

return 'antiBotStopListUpdateProcessor';
