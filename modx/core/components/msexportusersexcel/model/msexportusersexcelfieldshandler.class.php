<?php

interface msExportUsersExcelFieldHandlerInterface
{
    /**
     * Функция обработки поля
     * @param string $field
     * @param mixed $oldvalue
     * @param string $newvalue
     * @return string
     */
    public function processValue($field, $oldvalue, $newvalue = '');

}

class msExportUsersExcelHandlerFieldsHandler {

    /** @var modX $modx */
    public $modx;

    /** @var msExportUsersExcel $msExportUsersExcel */
    public $msExportUsersExcel;


    /**
     * @param msExportUsersExcelProfileHandler $profile
     */
    function __construct(msExportUsersExcel $msExportUsersExcel, array $config = array())
    {
        $this->config = $config;
        $this->msExportUsersExcel = &$msExportUsersExcel;
        $this->modx = &$msExportUsersExcel->modx;
    }

    /**
     * Функция обработки поля
     * @param string $field
     * @param mixed $oldvalue
     * @param string $newvalue
     * @return string
     */
    public function processValue($field, $oldvalue, $newvalue = '')
    {
        return $oldvalue;
    }
}