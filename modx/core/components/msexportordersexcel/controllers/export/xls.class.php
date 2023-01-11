<?php
if (!class_exists('msExportOrdersExcelPhpExcelExcelController')) {
    include_once dirname(dirname(__FILE__)) . '/excel.class.php';
}
class msExportOrdersExcelPhpExcelXlsController extends msExportOrdersExcelPhpExcelExcelController
{
    /* @var string $class */
    protected $class = 'Excel5';
    protected $extension = 'xls';
}
return 'msExportOrdersExcelPhpExcelXlsController';