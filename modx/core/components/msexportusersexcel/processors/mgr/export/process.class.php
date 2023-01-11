<?php

abstract class ExportExportProcessor extends modObjectGetListProcessor
{
    /** @var msExportUsersExcel $msExportUsersExcel */
    public $msExportUsersExcel = null;
    public $languageTopics = array('msexportusersexcel:manager');

    /**
     * Allow stoppage of process before the query
     * @return boolean
     */
    public function beforeQuery()
    {
        /* @var msExportUsersExcel $msExportUsersExcel */
        $this->msExportUsersExcel = $this->modx->getService('msExportUsersExcel', 'msExportUsersExcel', MODX_CORE_PATH . 'components/msexportusersexcel/model/');
        $this->msExportUsersExcel->initialize();
        $this->classKey = $this->getProperty('classKey');
        return true;
    }


    /**
     * Get the data of the query
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
        if (empty($sortKey)) $sortKey = $this->getProperty('sort');
        $c->sortby($sortKey, $this->getProperty('dir'));
        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $rows = array();
        if ($c->prepare() && $c->stmt->execute()) {
            while ($row = $c->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }
        }

        $data['results'] = $rows;
        return $data;
    }


    /**
     * Iterate across the data
     *
     * @param array $data
     * @return array
     */
    public function iterate(array $data)
    {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;

        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            #if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;
            $objectArray = $object;
            #$objectArray = $this->prepareRow($object);
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }

        $list = $this->afterIteration($list);
        return $list;
    }


    /**
     * @param xPDOQuery $c
     */
    public function prepareDependentProfile(xPDOQuery $c)
    {
        $owner = $this->getProperty('owner', null);
        if ($owner and is_array($owner)) {
            $c->where(array(
                $this->classKey.'.order_id' => $owner['id']
            ));
        }

    }

    /* @inheritdoc */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        /* @var $object $object */
        $this->msExportUsersExcel->query->newQuery($this->getProperties());
        $this->msExportUsersExcel->query->prepareQueryBeforeCount($c);
        $this->prepareDependentProfile($c);
        return parent::prepareQueryBeforeCount($c);
    }


    /**
     * Перечисление необходимых полей для экспорта
     * @param null|array $fields
     */
    public function fieldsProperty($fields = null)
    {
        $baseParams = $this->getProperty('baseParams');
        if (($fields and is_array($fields)) and (!empty($baseParams) and is_array($baseParams))) {
            $params = array_intersect_key($fields, $baseParams);
            foreach ($params as $key => $property) {
                $this->properties[$key] = $baseParams[$key];
            }
        }
    }

}