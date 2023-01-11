<?php

class mspremodTemplateGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modTemplate';
    public $defaultSortField = 'templatename';
    public $defaultSortDirection = 'ASC';

    /* @var null|array $templates */
    public $templates = null;

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c = parent::prepareQueryBeforeCount($c);
        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->where(array(
                'templatename:LIKE' => "$query%"
            ));
        }

        if ($ids = $this->getProperty('ids')) {
            $ids = $this->modx->fromJSON($ids);

            if (count($ids) > 0) {
                $q = $this->modx->newQuery('modResource');
                $q->select('id,template, count(id) as count');
                $q->groupby('template');
                $q->where(array(
                    'id:IN' => $ids,
                ));
                if ($q->prepare() && $q->stmt->execute()) {
                    while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                        $this->templates[$row['template']] = $row['count'];
                    }
                }
                if ($this->templates) {
                    $c->where(array(
                        'id:IN' => array_keys($this->templates),
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
                'templatename' => $object->get('templatename'),
            );
        } else {
            $array = $object->toArray();
        }


        $count = 0;
        if (isset($this->templates[$array['id']])) {
            $count = $this->templates[$array['id']];
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

return 'mspremodTemplateGetListProcessor';