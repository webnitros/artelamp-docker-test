<?php
include_once dirname(dirname(__FILE__)) . '/default.php';

/**
 * Multiple a msProduct
 */
class modmsProductMultipleJsonRemoveProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    public function initialize()
    {
        if (empty($this->getProperty('remove_value'))) {
            return $this->failure($this->modx->lexicon('mspre_err_remove_value'));
        }
        return parent::initialize();
    }

    /* @inheritdoc */
    public function preapreData($id)
    {
        $remove_value = $this->getProperty('remove_value', '');

        $json = array();
        $field = $this->fieldGet ? $this->fieldGet : $this->fieldUpdate;
        if ($object = $this->modx->getObject('msProduct', $id)) {
            if ($key = prefixOptions($field)) {
                $field = $key;
            }

            $tmps = $object->get($field);
            if (empty($tmps)) {
                // TODO Возможно какая та сломанная логика реализована
                if ($key = prefixOptions($field)) {
                    $tmps = $object->get($key);
                }
                /*if (strripos($field, 'options-') !== false) {
                    $field = str_ireplace('options-', '', $field);
                    $tmps = $object->get($field);
                }*/
            }
            if (!empty($tmps)) {
                foreach ($tmps as $k => $value) {
                    if ($value != $remove_value) {
                        $json[$k] = $value;
                    }
                }
            }
        }
        return $json;
    }
}

return 'modmsProductMultipleJsonRemoveProcessor';