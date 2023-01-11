<?php
include_once 'default.php';
/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateWeightProcessor extends modmodResourceMultipleUpdateOptionsDefaultProcessor
{
    /* @inheritdoc */
    public function preapreData($id)
    {
        return $this->getValue(0.000);
    }
}

return 'modmodResourceMultipleUpdateWeightProcessor';
