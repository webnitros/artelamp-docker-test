<?php

class mspc2CouponGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'mspc2Coupon';
    public $classKey = 'mspc2Coupon';
    public $defaultSortField = 'createdon';
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
        // $c->leftJoin('modResource', 'modResource', 'modResource.id = mspc2Coupon.parent');

        $c->select(array($this->modx->getSelectColumns('mspc2Coupon', 'mspc2Coupon')));
        // $c->select(array('modResource.pagetitle as parent_formatted'));

        // Фильтр по свойствам основного объекта
        foreach (array('list') as $v) {
            if (${$v} = $this->getProperty($v)) {
                if (${$v} == '_') {
                    $c->where(array(
                        '(' . $this->classKey . '.' . $v . ' = "" OR ' . $this->classKey . '.' . $v . ' IS NULL)',
                    ));
                } else {
                    $c->where(array(
                        $this->classKey . '.' . $v => ${$v},
                    ));
                }
            }
        }

        // Поиск
        if ($query = trim($this->getProperty('query'))) {
            $c->where(array(
                $this->classKey . '.code:LIKE' => "%{$query}%",
                'OR:' . $this->classKey . '.description:LIKE' => "%{$query}%",
            ));
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

        // Get orders count
        $data['orders'] = $this->modx->getCount('mspc2CouponOrder', [
            'coupon' => $data['id'],
        ]);

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
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('mspc2_button_update'),
            'action' => 'updateObject',
            'button' => true,
            'menu' => true,
        ];
        if ($data['active']) {
            $actions[] = [
                'cls' => '',
                'icon' => 'icon icon-cog action-gray',
                'title' => $this->modx->lexicon('mspc2_button_config'),
                'action' => 'configObject',
                'button' => true,
                'menu' => true,
            ];
            $actions[] = [
                'cls' => '',
                'icon' => 'icon icon-list-alt action-gray',
                'title' => $this->modx->lexicon('mspc2_button_joins'),
                'action' => 'joinsObject',
                'button' => true,
                'menu' => true,
            ];
            $actions[] = [
                'cls' => '',
                'icon' => 'icon icon-toggle-off action-red',
                'title' => $this->modx->lexicon('mspc2_button_disable'),
                'multiple' => $this->modx->lexicon('mspc2_button_disable_multiple'),
                'action' => 'disableObject',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $actions[] = [
                'cls' => '',
                'icon' => 'icon icon-toggle-on action-green',
                'title' => $this->modx->lexicon('mspc2_button_enable'),
                'multiple' => $this->modx->lexicon('mspc2_button_enable_multiple'),
                'action' => 'enableObject',
                'button' => true,
                'menu' => true,
            ];
        }
        $actions[] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('mspc2_button_remove'),
            'multiple' => $this->modx->lexicon('mspc2_button_remove_multiple'),
            'action' => 'removeObject',
            'button' => false,
            'menu' => true,
        ];

        return $actions;
    }
}

return 'mspc2CouponGetListProcessor';