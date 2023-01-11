<?php
include_once 'default.php';
/**
 * Multiple a modResource
 */
class modmodResourceMultipleUpdateStringProcessor extends modmodResourceMultipleUpdateOptionsDefaultProcessor
{

    /* @inheritdoc */
    public function preapreData($id)
    {
        $value = $this->getValue();
        $value = (string)htmlentities($value, ENT_COMPAT | ENT_HTML401, 'UTF-8');
        return $value;
    }
}
return 'modmodResourceMultipleUpdateStringProcessor';
