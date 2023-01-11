<?php

class msCategoryGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'msCategory';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';

    /* @var null|array $parents */
    public $parents = null;

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->where(array(
            'class_key' => 'msCategory',
        ));

        if ($query = $this->getProperty('query')) {
            $c->where(array('pagetitle:LIKE' => "%$query%"));
            $c->where(array('OR:longtitle:LIKE' => "%$query%"));
            $c->where(array('OR:menutitle:LIKE' => "%$query%"));
        }


        if ($ids = $this->getProperty('ids')) {
            $ids = $this->modx->fromJSON($ids);

            if (count($ids) > 0) {
                $q = $this->modx->newQuery('msProduct');
                $q->select('id,parent, count(id) as count');
                $q->groupby('parent');
                $q->where(array(
                    'id:IN' => $ids,
                ));
                if ($q->prepare() && $q->stmt->execute()) {
                    while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $this->parents[$row['parent']] = $row['count'];
                    }
                }
                if ($this->parents) {
                    $c->where(array(
                        'id:IN' => array_keys($this->parents),
                    ));
                }
            }
        }

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
                'pagetitle' => $object->get('pagetitle'),
            );
        } else {
            $array = $object->toArray();
        }


        $count = 0;
        if (isset($this->parents[$array['id']])) {
            $count = $this->parents[$array['id']];
        }
        $array['count'] = $count;
        return $array;
    }

    /**
     * Can be used to insert a row after iteration
     * @param array $list
     * @return array
     */
    public function afterIteration(array $list)
    {

        if (!function_exists('mspreFunctionsCountSort')) {
            /**
             * Сортировка категорий по количеству товаров в ней
             * @param $a
             * @param $b
             * @return int
             */
            function mspreFunctionsCountSort($a, $b)
            {
                if ($a['count'] === $b['count']) return 0;
                return $a['count'] < $b['count'] ? 1 : -1;
            }
        }

        uasort($list, 'mspreFunctionsCountSort');


        return array_values($list);
    }
}

return 'msCategoryGetListProcessor';