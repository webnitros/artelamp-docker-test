<?php
if (!class_exists('msExportUsersExcelPhpExcelExcelController')) {
    include_once dirname(dirname(__FILE__)) . '/excel.class.php';
}
class msExportUsersExcelPHPExcelExcelXlsxController extends msExportUsersExcelPhpExcelExcelController
{
    /* @var string $class */
    protected $class = 'Excel2007';
    protected $extension = 'xlsx';
}
return 'msExportUsersExcelPHPExcelExcelXlsxController';
