<?php
include_once dirname(__FILE__).'/default.php';

/**
 * Multiple a msProduct
 */
class modmsProductMultipleUpdateStringProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    /* @inheritdoc */
    public function preapreData($id)
    {
        $value = $this->getValue();
        $enable = (boolean)$this->modx->getOption('mspre_check_string_values_htmlentities', null,true);
        if ($enable) {
            $value = (string)htmlentities($value, ENT_COMPAT | ENT_HTML401, 'UTF-8');
        }
        return $value;
    }
}
return 'modmsProductMultipleUpdateStringProcessor';
