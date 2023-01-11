<?php

class mspc2JoinGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'mspc2Join';
    public $classKey = 'mspc2Join';
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'DESC';
    public $permission = 'list';

    /**
     * @return boolean|string
     */
    public function initialize()
    {
        return parent::initialize();
    }

    /**
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        $this->setProperty('sort', str_replace('_formatted', '', $this->getProperty('sort')));

        return parent::beforeQuery();
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        //
        $c->leftJoin('modResource', 'modResource', 'modResource.id = mspc2Join.resource');

        //
        $c->select([
            $this->modx->getSelectColumns('mspc2Join', 'mspc2Join'),
        ]);
        $c->select([
            'modResource.pagetitle as pagetitle',
            'modResource.context_key as context_key',
        ]);

        // // Фильтр по свойствам основного объекта
        // foreach (array('group') as $v) {
        //     if (${$v} = $this->getProperty($v)) {
        //         if (${$v} == '_') {
        //             $c->where(array(
        //                 '(' . $this->classKey . '.' . $v . ' = "" OR ' . $this->classKey . '.' . $v . ' IS NULL)',
        //             ));
        //         } else {
        //             $c->where(array(
        //                 $this->classKey . '.' . $v => ${$v},
        //             ));
        //         }
        //     }
        // }

        //
        $type = $this->getProperty('type', null);
        if (!empty($type)) {
            $c->where([
                $this->classKey . '.type' => $type,
            ]);
        }

        //
        $coupon_id = (int)$this->getProperty('coupon', 0);
        $c->where([
            $this->classKey . '.coupon' => $coupon_id,
        ]);

        // Search by query
        if ($query = trim($this->getProperty('query'))) {
            $c->where([
                $this->classKey . '.resource:LIKE' => "%{$query}%",
                'OR:' . $this->classKey . '.discount:LIKE' => "%{$query}%",
                'OR:modResource.pagetitle:LIKE' => "%{$query}%",
                'OR:modResource.longtitle:LIKE' => "%{$query}%",
            ]);
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
        $data = $object->toArray();

        //
        $data['parents'] = [];
        $parents = $this->modx->getParentIds($data['resource'], 2, [
            'context' => $data['context_key'],
        ]);
        if (empty($parents[count($parents) - 1])) {
            unset($parents[count($parents) - 1]);
        }
        if (!empty($parents) && is_array($parents)) {
            $q = $this->modx->newQuery('msCategory', ['id:IN' => $parents]);
            $q->select('id,pagetitle');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $key = array_search($row['id'], $parents);
                    if ($key !== false) {
                        $parents[$key] = $row;
                    }
                }
            }
            $data['parents'] = array_reverse($parents);
        }

        // Buttons
        $data['actions'] = $this->getActions($data);

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function getActions(array $data)
    {
        $actions = [];
        $actions[] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('mspc2_button_remove'),
            'multiple' => $this->modx->lexicon('mspc2_button_remove_multiple'),
            'action' => 'removeObject',
            'button' => true,
            'menu' => true,
        ];

        return $actions;
    }
}

return 'mspc2JoinGetListProcessor';