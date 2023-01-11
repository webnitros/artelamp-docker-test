<?php
/**
 * Класс для обработчки значения boolean
*/
class msExportUsersExcelHandlerFieldsBooleanHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
    {
        return !empty($oldvalue) ? $this->modx->lexicon('msexportusersexcel_yes') : $this->modx->lexicon('msexportusersexcel_no');
    }

}
