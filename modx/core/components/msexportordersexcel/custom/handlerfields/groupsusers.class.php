<?php
class msExportOrdersExcelHandlerFieldsGroupsUsersHandler extends msExportOrdersExcelHandlerFieldsHandler
{
    /* @inheritdoc */
    public function processValue($field, $oldvalue, $newvalue = '', $row = array())
    {

        $rows = null;
        $data = explode(',', $oldvalue);
        $q = $this->modx->newQuery('modUserGroup');
        $q->select('name');
        $q->sortby('id');
        $q->where(array(
            'id:IN' => $data,
        ));
        if ($q->prepare() && $q->stmt->execute()){
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row['name'];
            }
        }
        return $rows ? implode(',', $rows) : '';
    }

}
