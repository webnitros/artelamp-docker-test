<?php

/**
 * Добавляет url сайта к значению
 */
class msExportUsersExcelHandlerFieldsUrlHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
    {
        $site_url = $this->modx->getOption('site_url');
        return $site_url.$oldvalue;
    }

}
