<?php

class modTemplateVarGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'mspreTvField';
    public $languageTopics = array('mspre');
    public $permission = 'view_tv';
    //public $permission = 'view_template';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        /*
         options-
         * $c = parent::prepareQueryBeforeCount($c);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'templatename:LIKE' => "$query%"
            ));
        }*/
        return $c;
    }

    public function beforeIteration(array $list) {
        if ($this->getProperty('combo',false) && !$this->getProperty('query', false)) {
            $empty = array(
                'id' => 0,
                'templatename' => $this->modx->lexicon('mspre_empty_change_tv'),
                'description' => '',
                'editor_type' => 0,
                'icon' => '',
                'template_type' => 0,
                'content' => '',
                'locked' => false,
            );
            $empty['name'] = '';
            $list[] = $empty;
        }
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $name = $objectArray['name'];
        $objectArray['field_name'] = $name;
        $caption = $objectArray['caption'];
        $objectArray['name'] = '('.$name.') '.$caption;
        return $objectArray;
    }

}

return 'modTemplateVarGetListProcessor';