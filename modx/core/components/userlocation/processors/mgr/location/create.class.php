<?php

/**
 * Create an ulLocation
 */
class ulLocationCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'ulLocation';
    public $classKey = 'ulLocation';
    public $languageTopics = ['userlocation'];
    public $permission = '';

    /** {@inheritDoc} */
    public function beforeSet()
    {
        $required = ['id', 'name'];
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

        if ($this->modx->getCount('ulLocation', ['id' => $this->getProperty('id'), 'type' => $this->getProperty('id')])) {
            $this->addFieldError('id', $this->modx->lexicon('userlocation_err_object_exists'));
        }
        $this->object->set('id', $this->getProperty('id'));

        return true;
    }

    public function handleCheckBoxes()
    {
        $required = ['active'];
        foreach ($required as $field) {
            $this->setCheckbox($field);
        }
    }

}

return 'ulLocationCreateProcessor';