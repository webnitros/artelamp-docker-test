<?php

/**
 * Добавляет url сайта к значению
 */
class msExportOrdersExcelHandlerFieldsUrlHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {
        $site_url = $this->modx->getOption('site_url');
        return $site_url.$oldvalue;
    }

}
