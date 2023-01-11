<?php

class msResourceGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $defaultSortField = 'pagetitle';


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        if ($this->getProperty('combo')) {
            $c->select('id,pagetitle');
        }
        if ($id = (int)$this->getProperty('id')) {
            $c->where(array('id' => $id));
        }
        if ($query = trim($this->getProperty('query'))) {
            $c->where(array('pagetitle:LIKE' => "%{$query}%"));
        }


        if ($context_key = trim($this->getProperty('context_key', null))) {
            $c->where(array('context_key' => $context_key));
        }

        $c->where(array('class_key' => 'msCategory'));
/*
        $c->where(array('class_key:IN' => array(
            'msCategory',
            'modDocument'
        )));
        */
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
                'pagetitle' => '(' . $object->get('id') . ') ' . $object->get('pagetitle'),
            );
        } else {
            $array = $object->toArray();
        }

        return $array;
    }

}

return 'msResourceGetListProcessor';