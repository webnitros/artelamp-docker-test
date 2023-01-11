<?php
class msExportUsersExcelHandlerFieldsDateHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
    {
        if ($this->config['date_process']) {
            if (!is_int($oldvalue)) {
                $oldvalue = strtotime($oldvalue);
            }
            $oldvalue = (int)$oldvalue;
            $format = $this->config['date_format'];
            $newvalue = empty($oldvalue) ? '' : date($format, $oldvalue);
        } else {
            $newvalue = $oldvalue;
        }
        if ($newvalue == '01.01.1970 03:00:00') {
            $newvalue = '';
        }
        return $newvalue;
    }
}