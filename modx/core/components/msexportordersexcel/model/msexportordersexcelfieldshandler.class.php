<?php

interface msExportOrdersExcelFieldHandlerInterface
{
    /**
     * Функция обработки поля
     * @param string $field
     * @param mixed $oldvalue
     * @param string $newvalue
     * @return string
     */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array());

}

class msExportOrdersExcelHandlerFieldsHandler {

    /** @var modX $modx */
    public $modx;

    /** @var msExportOrdersExcel $msExportOrdersExcel */
    public $msExportOrdersExcel;

    /** @var array $config */
    public $config = array();


    /**
     * @param msExportOrdersExcelProfileHandler $profile
     */
    function __construct(msExportOrdersExcel $msExportOrdersExcel, array $config = array())
    {
        $this->config = $config;
        $this->msExportOrdersExcel = &$msExportOrdersExcel;
        $this->modx = &$msExportOrdersExcel->modx;
    }

    /**
     * Функция обработки поля
     * @param string $field
     * @param mixed $oldvalue
     * @param string $newvalue
     * @param array $row
     * @return string
     */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {
        return $oldvalue;
    }
}