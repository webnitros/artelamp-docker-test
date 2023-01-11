<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportUsersExcelHandlerFieldsJsonHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
    {
        if (!empty($oldvalue)) {
            $arr = $this->modx->fromJSON($oldvalue);
            $newvalue = json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
        return $newvalue;
    }

}
