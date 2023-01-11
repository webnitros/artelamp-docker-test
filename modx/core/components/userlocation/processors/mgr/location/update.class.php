<?php

/**
 * Update an ulLocation
 */
class ulLocationUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'ulLocation';
    public $classKey = 'ulLocation';
    public $languageTopics = ['userlocation'];
    public $permission = '';

    public function initialize()
    {
        $this->setDefaultProperties(array(
            'pk' => $this->getProperty('id'),
        ));

        $primaryKey = $this->getProperty('pk', false);
        if (empty($primaryKey)) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }
        $this->object = $this->modx->getObject($this->classKey, ['id' => $primaryKey]);
        if (empty($this->object)) {
            return $this->modx->lexicon($this->objectType.'_err_nfs', ['id' => $primaryKey]);
        }

        if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /** {@inheritDoc} */
    public function beforeSet()
    {
        $this->setProperties(array_merge($this->object->toArray(), $this->getProperties()));

        $required = ['pk', 'id', 'name'];
        foreach ($required as $field) {
            if (!$tmp = (string)trim($this->getProperty($field))) {
                $this->addFieldError($field, $this->modx->lexicon('field_required'));
            } else {
                $this->setProperty($field, $tmp);
            }
        }

        $this->handleCheckBoxes();

        return parent::beforeSet();
    }

    /** {@inheritDoc} */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        if ($this->getProperty('pk') != $this->getProperty('id')) {
            if ($this->modx->getCount('ulLocation', ['id' => $this->getProperty('id'), 'id!=' => $this->getProperty('pk'), 'type' => $this->getProperty('id')])) {
                $this->addFieldError('id', $this->modx->lexicon('userlocation_err_object_exists'));
            }
        }

        return true;
    }

    public function afterSave()
    {
        if ($this->getProperty('pk') != $this->getProperty('id')) {
            $this->object->remove();
            $this->object = $this->modx->newObject('ulLocation');
            $this->object->fromArray($this->getProperties(), '', true, true);
            $this->object->save();
        }

        return parent::afterSave();
    }

    public function handleCheckBoxes()
    {
        $required = ['active'];
        foreach ($required as $field) {
            $this->setCheckbox($field);
        }
    }

}

return 'ulLocationUpdateProcessor';
