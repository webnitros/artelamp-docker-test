<?php
class msExportOrdersExcelHandlerFieldsGenderHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {
        if ($oldvalue == 1) {
            $newvalue = $this->modx->lexicon('male');
        } else if ($oldvalue == 2) {
            $newvalue = $this->modx->lexicon('female');
        } else if ($oldvalue == 3) {
            $newvalue = $this->modx->lexicon('user_hidden');
        } else if ($oldvalue == 0) {
            $newvalue = '';
        }
        return $newvalue;
    }

}
