<?php
/**
 * Класс для обработчки значения boolean
*/
class msExportOrdersExcelHandlerFieldsBooleanHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {
        return !empty($oldvalue) ? $this->modx->lexicon('msexportordersexcel_yes') : $this->modx->lexicon('msexportordersexcel_no');
    }

}
