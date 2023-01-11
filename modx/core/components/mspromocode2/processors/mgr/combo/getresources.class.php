<?php

class mspc2ComboResourceGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('default', 'minishop2:product');
    public $defaultSortField = 'menuindex';
    public $defaultSortDirection = 'ASC';
    public $parent = 0;

    /**
     * @return bool
     */
    public function initialize()
    {
        if (!$this->getProperty('limit')) {
            $this->setProperty('limit', 20);
        }

        return parent::initialize();
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        // Get joins type
        $type = $this->getProperty('type', '');
        $coupon_id = $this->getProperty('coupon', 0);

        //
        if ($type === 'product') {
            $c->leftJoin('msProductData', 'Data', 'modResource.id = Data.id');
            $c->leftJoin('msCategoryMember', 'Member', 'modResource.id = Member.product_id');
        }
        $c->leftJoin('msCategory', 'Category', 'Category.id = modResource.parent');

        //
        $c->select('modResource.id, modResource.pagetitle, modResource.context_key, modResource.published');

        //
        if ($type === 'category') {
            $c->where([
                'class_key' => 'msCategory',
                'isfolder' => true,
            ]);
        } elseif ($type === 'product') {
            $c->where([
                'class_key' => 'msProduct',
                'isfolder' => false,
            ]);
        }

        // Exclude joined ids
        $q = $this->modx->newQuery('mspc2Join', ['coupon' => $coupon_id])
            ->select('resource')
            ;
        if ($q->prepare() && $q->stmt->execute()) {
            $exclude_ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            if (!empty($exclude_ids)) {
                $c->where([
                    'id:NOT IN' => $exclude_ids,
                ]);
            }
        }
        unset($q);

        //
        if ($query = $this->getProperty('query', '')) {
            $condition = [
                'modResource.id' => $query,
                'OR:modResource.pagetitle:LIKE' => '%' . $query . '%',
                'OR:modResource.description:LIKE' => '%' . $query . '%',
                'OR:modResource.introtext:LIKE' => '%' . $query . '%',
                'OR:Category.pagetitle:LIKE' => '%' . $query . '%',
            ];
            if ($type === 'product') {
                $condition['OR:Data.article:LIKE'] = '%' . $query . '%';
                $condition['OR:Data.made_in:LIKE'] = '%' . $query . '%';
            }
            $c->where($condition);
        }

        // $parent = $this->getProperty('parent');
        // if (!empty($parent)) {
        //     $category = $this->modx->getObject('modResource', $this->getProperty('parent'));
        //     $this->parent = $parent;
        //     $parents = array($parent);
        //     if ($this->modx->getOption('ms2_category_show_nested_products', null, true)) {
        //         $tmp = $this->modx->getChildIds($parent, 10, array('context' => $category->get('context_key')));
        //         foreach ($tmp as $v) {
        //             $parents[] = $v;
        //         }
        //     }
        //     $c->orCondition(array('parent:IN' => $parents, 'Member.category_id:IN' => $parents), '', 1);
        // }

        return $c;
    }

    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $c->groupby($this->classKey . '.id');

        return $c;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey, $c);
        $c = $this->prepareQueryAfterCount($c);

        $sortClassKey = $this->getSortClassKey();
        $sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', array($this->getProperty('sort')));
        if (empty($sortKey)) {
            $sortKey = $this->getProperty('sort');
        }
        $c->sortby($sortKey, $this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        if ($c->prepare() && $c->stmt->execute()) {
            $data['results'] = $c->stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $array) {
            $list[] = $this->prepareArray($array);
            ++$this->currentIndex;
        }
        $list = $this->afterIteration($list);

        return $list;
    }

    /**
     * @param array $resourceArray
     *
     * @return array
     */
    public function prepareArray(array $resourceArray)
    {
        $resourceArray['parents'] = array();
        $parents = $this->modx->getParentIds($resourceArray['id'], 2, array(
            'context' => $resourceArray['context_key'],
        ));
        if (empty($parents[count($parents) - 1])) {
            unset($parents[count($parents) - 1]);
        }
        if (!empty($parents) && is_array($parents)) {
            $q = $this->modx->newQuery('msCategory', array('id:IN' => $parents));
            $q->select('id,pagetitle');
            if ($q->prepare() && $q->stmt->execute()) {
                while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                    $key = array_search($row['id'], $parents);
                    if ($key !== false) {
                        $parents[$key] = $row;
                    }
                }
            }
            $resourceArray['parents'] = array_reverse($parents);
        }

        // $this->modx->log(MODX::LOG_LEVEL_ERROR, print_r($resourceArray,1));

        return $resourceArray;
    }
}

return 'mspc2ComboResourceGetListProcessor';