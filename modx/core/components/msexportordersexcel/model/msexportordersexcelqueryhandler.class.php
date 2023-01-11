<?php

interface msExportOrdersExcelQueryInterface
{
    /**
     * Запуск обработчика поелй
     * @return string
     */
    public function process();

    /**
     * Метож для добавления в процессоры
     * @param xPDOQuery $c
     * @return xPDOQuery $c
     */
    public function prepareQueryBeforeCount(xPDOQuery $c);

    /**
     * Вернет общее количество записей
     * @return int;
     */
    public function getTotal();

    /**
     * Вернет общее количество результатов
     * @return int;
     */
    public function getTotalExport();


}

class msExportOrdersExcelQueryHandler implements msExportOrdersExcelQueryInterface
{

    /** @var modX $modx */
    public $modx;
    /** @var msExportOrdersExcel $msExportOrdersExcel */
    public $msExportOrdersExcel;

    /* @var array|null $config */
    protected $config = null;

    /** @var xPDOQuery|null $c */
    public $c = null;

    /* @var int $total_export */
    protected $total_export = 0;
    /* @var int $total */
    protected $total = 0;

    /* @var array|null $data */
    protected $data = null;


    /**
     * msExportOrdersExcelQueryHandler constructor.
     * @param msExportOrdersExcel $msExportOrdersExcel
     * @param array $config
     */
    function __construct(msExportOrdersExcel $msExportOrdersExcel, array $config = array())
    {
        $this->modx = $msExportOrdersExcel->modx;
        $this->msExportOrdersExcel = $msExportOrdersExcel;
        $this->setDefault($config);
    }

    /**
     * @param array $config
     */
    protected function setDefault($config = array())
    {
        $this->config = array(
            'processor' => $this->modx->getOption('msexportordersexcel_processor', null, 'core/components/msexportordersexcel/processors/mgr/export/default'),
            'limit' => $this->modx->getOption('msexportordersexcel_limit', null, 5000),
            'start' => $this->modx->getOption('msexportordersexcel_start', null, 0),
            'sort' => $this->modx->getOption('msexportordersexcel_sort', null, 'id'),
            'dir' => $this->modx->getOption('msexportordersexcel_dir', null, 'ASC'),
            'select' => $this->modx->getOption('msexportordersexcel_select', null, ''),
            'leftjoin' => $this->modx->getOption('msexportordersexcel_leftjoin', null, ''),
            'innerjoin' => $this->modx->getOption('msexportordersexcel_innerjoin', null, ''),
            'groupby' => $this->modx->getOption('msexportordersexcel_groupby', null, ''),
            'having' => $this->modx->getOption('msexportordersexcel_having', null, ''),
            'profile' => null,
        );
        if (is_array($config) and count($config) > 0) {
            $this->config = array_merge($this->config, $config);
        }
    }


    /**
     * @param array $config
     * @return msExportOrdersExcelQueryHandler
     */
    public function newQuery($config = array())
    {
        $this->setDefault($config);
        return $this;
    }


    /**
     * @return xPDOQuery
     */
    public function process()
    {
        $this->c = $this->modx->newQuery($this->getProperty('classKey'));
        $this->prepareQueryBeforeCount($this->c);

        $this->c->sortby($this->getProperty('sort'), $this->getProperty('dir'));
        $this->c->limit($this->getProperty('limit'), $this->getProperty('start'));
        $this->c->prepare();
        $this->c->stmt->execute();
        return $this->c;
    }

    /**
     * Метож для добавления в процессоры
     * @param xPDOQuery $c
     * @return xPDOQuery $c
     */
    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->select($this->modx->getSelectColumns($this->getProperty('classKey'), $this->getProperty('classKey')));

        $jsonFields = array('leftjoin', 'innerjoin', 'where');
        foreach ($jsonFields as $field) {
            $this->addQuery($this->modx, $field, $this->getProperty($field), $c);
        }

        $having = trim($this->getProperty('having'));
        if (!empty($having)) {
            $c->having($having);
        }

        $groupby = trim($this->getProperty('groupby'));
        if (!empty($groupby)) {
            $c->groupby($groupby);
        }

        // add select
        $select = trim($this->getProperty('select'));
        if (!empty($select)) {
            $c->select($select);
        }

        return $c;
    }

    /**
     * @param modX $modx
     * @param string $field
     * @param mixed $value
     * @param xPDOQuery $c
     */
    static private function addQuery(modX $modx, $field, $value, xPDOQuery $c)
    {
        $str = trim($value);
        if (!empty($str)) {
            $array = $modx->fromJSON($str);
            if (is_array($array) and count($array) > 0) {
                if ($field == 'where') {
                    $c->where($array);
                } else {
                    foreach ($array as $alias => $param) {
                        if (is_array($param)) {
                            $class = $param['class'];
                            $on = isset($param['on']) ? $param['on'] : array();
                            $prefix = isset($param['prefix']) ? $param['prefix'] : $alias . '.';

                            switch ($field) {
                                case 'leftjoin':
                                    $c->leftJoin($class, $alias, $on);
                                    $c->select($modx->getSelectColumns($class, $alias, $prefix));
                                    break;
                                case 'innerjoin':
                                    $c->innerJoin($class, $alias, $on);
                                    $c->select($modx->getSelectColumns($class, $alias, $prefix));
                                    break;
                                default:
                                    break;
                            }

                        } else {
                            $modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error is field {$field} not empty. Is array empty. string {$str}");
                        }
                    }
                }
            } else {
                $modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error is field {$field} not empty. Is array empty. string {$str}");
            }
        }


    }


    /**
     * Вернет общее количество записей
     * @return int;
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Вернет общее количество результатов
     * @return int;
     */
    public function getTotalExport()
    {
        return $this->total_export;
    }



    /**
     * Вернет общее количество результатов
     * @return array|null;
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * * Запуск процессора для запрос в базу данных
     * @param array $baseParams
     * @return array|bool
     */
    public function runProcessorData($baseParams = null)
    {
        
        $config = $this->config;
        if (!empty($baseParams) and is_array($baseParams)) {
            $params = array_intersect_key($baseParams, $config);
            $config = array_merge($config,$params);
            $config['baseParams'] = $baseParams;
        }

        $processor = $this->getProperty('processor');
        $processor = str_ireplace('core/components/', 'components/' , $processor);

        /* @var modProcessorResponse $res */
        $res = $this->modx->runProcessor($processor, $config, array(
            'processors_path' => MODX_CORE_PATH
        ));
        if ($res->isError()) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error export data " . print_r($res->getAllErrors(), 1));
            return false;
        }
        if (!is_array($res->response)) {
            $response = $this->modx->fromJSON($res->response);
        } else {
            $response = $res->response;
        }
        if (!$response['success']) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error get data " . print_r($res->getAllErrors(), 1));
            return false;
        }

        if ($response['success']) {
            $this->total_export = count($response['results']);
            $this->total = $response['total'];
            $this->data = $response['results'];
            return true;
        }
        return false;
    }


    /**
     * Get a specific property.
     * @param string $k
     * @param mixed $default
     * @return mixed
     */
    protected function getProperty($k, $default = null)
    {
        return array_key_exists($k, $this->config) ? $this->config[$k] : $default;
    }
}