<?php

class modTemplateGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modTemplate';
    public $languageTopics = array('mspre');
    public $defaultSortField = 'templatename';
    //public $permission = 'view_template';

    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'templatename:LIKE' => "$query%"
            ));
        }
        return $c;
    }

    public function beforeIteration(array $list) {
        /*if ($this->getProperty('combo',false) && !$this->getProperty('query', false)) {
            $empty = array(
                'id' => 0,
                'templatename' => $this->modx->lexicon('mspre_empty_template'),
                'description' => '',
                'editor_type' => 0,
                'icon' => '',
                'template_type' => 0,
                'content' => '',
                'locked' => false,
            );
            $empty['category_name'] = '';
            $list[] = $empty;
        }*/
        return $list;
    }

    public function prepareRow(xPDOObject $object) {
        $objectArray = $object->toArray();
        $objectArray['category_name']= $object->get('category_name');
        unset($objectArray['content']);
        return $objectArray;
    }

}

return 'modTemplateGetListProcessor';