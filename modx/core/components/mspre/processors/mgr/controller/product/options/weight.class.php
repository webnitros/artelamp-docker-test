<?php
include_once dirname(__FILE__).'/default.php';
/**
 * Multiple a msProduct
 */
class modmsProductMultipleUpdateWeightProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    /* @inheritdoc */
    public function preapreData($id)
    {
        return $this->getValue(0.000);
    }
}

return 'modmsProductMultipleUpdateWeightProcessor';
