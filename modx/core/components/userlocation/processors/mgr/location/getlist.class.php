<?php

class ulLocationGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'ulLocation';
    public $languageTopics = ['default', 'userlocation'];
    public $defaultSortField = 'name';
    public $defaultSortDirection = 'ASC';
    public $permission = '';

    /** @var  xPDOQuery $query */
    protected $query;

    protected $isActiveField;

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->modx->hasPermission($this->permission)) {
            return $this->modx->lexicon('access_denied');
        }

        /** @var UserLocation $UserLocation */
        if ($UserLocation = $this->modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
            $this->isActiveField = in_array('active', $UserLocation->getGridLocationFields());
        }


        return parent::initialize();
    }


    /**
     * @param  xPDOQuery  $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {

        $c->leftJoin('ulLocation', 'ParentLocation', 'ulLocation.parent = ParentLocation.id');
        $c->leftJoin('modResource', 'Resource', 'ulLocation.resource = Resource.id');

        $query = trim($this->getProperty('query'));
        if (!empty($query)) {
            $c->where([
                'id:LIKE'             => "{$query}%",
                'OR:name:LIKE'        => "{$query}%",
                'OR:postal:LIKE'      => "{$query}%",
                'OR:gninmb:LIKE'      => "%{$query}%",
                'OR:okato:LIKE'       => "%{$query}%",
                'OR:oktmo:LIKE'       => "%{$query}%",
                'OR:fias:LIKE'        => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }

        if ($parent = $this->getProperty('parent')) {
            $c->where([
                'ulLocation.parent' => $parent,
            ]);
        }
        if ($type = $this->getProperty('type')) {
            $c->where([
                'ulLocation.type' => $type,
            ]);
        }

        $id = $this->getProperty('id');
        if (!empty($id) AND $this->getProperty('combo')) {
            $c->sortby("FIELD (ulLocation.id, '{$id}')", "DESC");
        }

        $this->query = clone $c;

        if (!$this->getProperty('combo')) {
            $c->select($this->modx->getSelectColumns('ulLocation', 'ulLocation', '', [], true));
            $c->select($this->modx->getSelectColumns('ulLocation', 'ParentLocation', 'parent_', ['name'], false));
            $c->select($this->modx->getSelectColumns('modResource', 'Resource', 'resource_', ['pagetitle'], false));

        } else {
            $c->select($this->modx->getSelectColumns('ulLocation', 'ulLocation', '', ['id', 'name', 'type'], false));
        }

        $c->groupby($this->classKey.'.id');

        return $c;
    }


    /**
     * @param  xPDOQuery  $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryAfterCount(xPDOQuery $c)
    {
        $total = 0;
        $limit = (int)$this->getProperty('limit');
        $start = (int)$this->getProperty('start');

        $q = clone $c;
        $q->query['columns'] = ['SQL_CALC_FOUND_ROWS ulLocation.id'];
        $sortClassKey = $this->getSortClassKey();
        $sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', [$this->getProperty('sort')]);
        if (empty($sortKey)) {
            $sortKey = $this->getProperty('sort');
        }
        $q->sortby($sortKey, $this->getProperty('dir'));
        if ($limit > 0) {
            $q->limit($limit, $start);
        }

        $ids = [];
        if ($q->prepare() AND $q->stmt->execute()) {
            $ids = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            $total = $this->modx->query('SELECT FOUND_ROWS()')->fetchColumn();
        }
        $ids = empty($ids) ? "(0)" : "('".implode("','", $ids)."')";
        $c->query['where'] = [
            [
                new xPDOQueryCondition(['sql' => 'ulLocation.id IN '.$ids, 'conjunction' => 'AND']),
            ],
        ];
        $c->sortby($sortKey, $this->getProperty('dir'));

        $this->setProperty('total', $total);

        return $c;
    }


    /**
     * @return array
     */
    public function getData()
    {
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $c = $this->prepareQueryAfterCount($c);
        $data = [
            'results' => ($c->prepare() AND $c->stmt->execute()) ? $c->stmt->fetchAll(PDO::FETCH_ASSOC) : [],
            'total'   => (int)$this->getProperty('total'),
        ];

        return $data;
    }


    /**
     * @param  array  $data
     *
     * @return array
     */
    public function iterate(array $data)
    {
        $list = [];
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $array) {
            $list[] = $this->prepareArray($array);
            $this->currentIndex++;
        }
        $list = $this->afterIteration($list);

        return $list;
    }


    /**
     * @param  array  $data
     *
     * @return array
     */
    public function prepareArray(array $data)
    {
        $data['active'] = (int)$data['active'];
        if (empty($data['description'])) {
            $data['description'] = '';
        }


        $icon = 'icon';

        $actions = [];
        // Edit
        $actions[] = [
            'cls'    => '',
            'icon'   => "$icon $icon-edit green",
            'title'  => $this->modx->lexicon('userlocation_action_update'),
            'action' => 'update',
            'button' => true,
            'menu'   => true,
        ];

        // sep
        $actions[] = [
            'cls'    => '',
            'icon'   => '',
            'title'  => '',
            'action' => 'sep',
            'button' => false,
            'menu'   => true,
        ];

        if (!$data['active']) {
            $actions[] = [
                'cls'    => '',
                'icon'   => "$icon $icon-toggle-off red",
                'title'  => $this->modx->lexicon('userlocation_action_active'),
                'action' => 'active',
                'button' => !$this->isActiveField,
                'menu'   => true,
            ];
        } else {
            $actions[] = [
                'cls'    => '',
                'icon'   => "$icon $icon-toggle-on green",
                'title'  => $this->modx->lexicon('userlocation_action_inactive'),
                'action' => 'inactive',
                'button' => !$this->isActiveField,
                'menu'   => true,
            ];
        }

        // sep
        $actions[] = [
            'cls'    => '',
            'icon'   => '',
            'title'  => '',
            'action' => 'sep',
            'button' => false,
            'menu'   => true,
        ];
        // Remove
        $actions[] = [
            'cls'    => '',
            'icon'   => "$icon $icon-trash-o red",
            'title'  => $this->modx->lexicon('userlocation_action_remove'),
            'action' => 'remove',
            'button' => false,
            'menu'   => true,
        ];

        if (!$this->getProperty('combo')) {
            $data['actions'] = $actions;
        }


        return $data;
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