<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportUsersExcelHandlerFieldsPathExportHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
    {

        $newpath = dirname($oldvalue, 2);
        $value = str_ireplace($newpath.'/', '', $oldvalue);
        return $value;
    }

}
