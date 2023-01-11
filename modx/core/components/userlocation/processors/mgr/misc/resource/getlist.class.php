<?php


class modResourceGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'modResource';
    public $classKey = 'modResource';
    public $languageTopics = ['default', 'resource', 'userlocation'];
    public $defaultSortField = 'pagetitle';

    /** {@inheritDoc} */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $id = $this->getProperty('id');
        if (!empty($id) AND $this->getProperty('combo')) {
            $c->sortby("FIELD (id, {$id})", "DESC");
        }

        if ($this->getProperty('combo')) {
            $c->select('id,pagetitle');
        }
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(['pagetitle:LIKE' => '%'.$query.'%']);
        }

        return $c;
    }

    /** {@inheritDoc} */
    public function prepareRow(xPDOObject $object)
    {
        if ($this->getProperty('combo')) {
            $array = [
                'id'        => $object->get('id'),
                'pagetitle' => $object->get('pagetitle'),
            ];
        } else {
            $array = $object->toArray();
        }

        return $array;
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive([
                [
                    'id'   => '0',
                    'pagetitle' => $this->modx->lexicon('userlocation_all'),
                ],
            ], $array);
        }
        if ($this->getProperty('novalue')) {
            $array = array_merge_recursive([
                [
                    'id'   => '0',
                    'pagetitle' => $this->modx->lexicon('userlocation_no'),
                ],
            ], $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'modResourceGetListProcessor';