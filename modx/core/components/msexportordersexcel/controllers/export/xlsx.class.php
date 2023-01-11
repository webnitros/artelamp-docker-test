<?php
if (!class_exists('msExportOrdersExcelPhpExcelExcelController')) {
    include_once dirname(dirname(__FILE__)) . '/excel.class.php';
}
class msExportOrdersExcelPHPExcelExcelXlsxController extends msExportOrdersExcelPhpExcelExcelController
{
    /* @var string $class */
    protected $class = 'Excel2007';
    protected $extension = 'xlsx';
}
return 'msExportOrdersExcelPHPExcelExcelXlsxController';
