<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportOrdersExcelHandlerFieldsMsOptionsHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {
        if (!empty($oldvalue)) {
            $arr = $this->modx->fromJSON($oldvalue);
            if (count($arr) > 0) {
                $newvalue = json_encode($arr, JSON_UNESCAPED_UNICODE);
            } else {
                $newvalue = '';
            }
        }
        return $newvalue;
    }

}
