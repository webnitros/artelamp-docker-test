<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportOrdersExcelHandlerFieldsJsonHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {
        if (!empty($oldvalue)) {
            $arr = $this->modx->fromJSON($oldvalue);
            $newvalue = json_encode($arr,JSON_UNESCAPED_UNICODE);
        }
        return $newvalue;
    }

}
