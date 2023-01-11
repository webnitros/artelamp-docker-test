<?php
if (!class_exists('msExportUsersExcelPhpExcelExcelController')) {
    include_once dirname(dirname(__FILE__)) . '/excel.class.php';
}
class msExportUsersExcelPhpExcelXlsController extends msExportUsersExcelPhpExcelExcelController
{
    /* @var string $class */
    protected $class = 'Excel5';
    protected $extension = 'xls';
}
return 'msExportUsersExcelPhpExcelXlsController';