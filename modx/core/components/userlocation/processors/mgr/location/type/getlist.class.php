<?php

class ulLocationGetListProcessor extends modObjectProcessor
{
    public $objectType = 'ulLocation';
    public $classKey = 'ulLocation';
    public $languageTopics = ['default', 'userlocation'];
    public $permission = '';

    /** {@inheritDoc} */
    public function process()
    {
        $query = $this->getProperty('query');

        $c = $this->modx->newQuery($this->classKey);
        $c->sortby('type', 'ASC');
        $c->select('type as name, type as id');
        $c->groupby('type');
        $c->limit(0);
        if (!empty($query)) {
            $c->where(['type:LIKE' => "%{$query}%"]);
        }

        $array = [];
        if ($c->prepare() && $c->stmt->execute()) {
            while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
                //$row['name'] = $this->modx->lexicon("userlocation_type_" . strtolower($row['name']));
                $array[] = $row;
            }
        }

        return $this->outputArray($array);
    }

    /** {@inheritDoc} */
    public function outputArray(array $array, $count = false)
    {
        if ($this->getProperty('addall')) {
            $array = array_merge_recursive([
                [
                    'id'   => '',
                    'name' => $this->modx->lexicon('userlocation_all'),
                ],
            ], $array);
        }
        if ($this->getProperty('novalue')) {
            $array = array_merge_recursive([
                [
                    'id'   => '',
                    'name' => $this->modx->lexicon('userlocation_no'),
                ],
            ], $array);
        }

        return parent::outputArray($array, $count);
    }

}

return 'ulLocationGetListProcessor';