<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportOrdersExcelHandlerFieldsPathExportHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {

        $newpath = dirname($oldvalue, 2);
        $value = str_ireplace($newpath.'/', '', $oldvalue);
        return $value;
    }

}
