<?php

/**
 * Класс для обработчки значения boolean
 */
class msExportUsersExcelHandlerFieldsExportFieldsHandler extends msExportUsersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '')
    {

        $rows = null;
        $q = $this->modx->newQuery('msProductFile');
        $q->select('file');
        $q->sortby('rank');
        $q->where(array(
            'product_id' => $oldvalue,
            'parent' => 0,
        ));
        if ($q->prepare() && $q->stmt->execute()){
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = 'data/'.$row['file'];
            }
        }
        return $rows ? implode(',', $rows) : '';
    }

}
