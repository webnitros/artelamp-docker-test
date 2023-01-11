<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportUsersExcelHandlerFieldsPriceHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = 0)
    {
        /** @var miniShop2 $miniShop2 */
        $miniShop2 = $this->modx->getService('miniShop2');
        if ($miniShop2 instanceof miniShop2) {
            if (!empty($oldvalue)) {
                $newvalue = $miniShop2->formatPrice($oldvalue);
            }
        }
        return $newvalue;
    }
}
