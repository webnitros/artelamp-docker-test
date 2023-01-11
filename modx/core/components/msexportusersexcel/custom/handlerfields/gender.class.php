<?php
class msExportUsersExcelHandlerFieldsGenderHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
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
