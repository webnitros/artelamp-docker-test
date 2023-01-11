<?php

class modResourceGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('mspre');
    public $defaultSortField = 'class_key';

    //public $permission = 'view_template';

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'class_key:LIKE' => "$query%"
            ));
        }

        $c->groupby('class_key');
        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        if ($this->getProperty('combo')) {
            $array = array(
                'id' => $object->get('id'),
                'class_key' => $object->get('class_key'),
            );
        } else {
            $array = $object->toArray();
        }
        return $array;
    }

}

return 'modResourceGetListProcessor';