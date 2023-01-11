<?php
include_once dirname(dirname(__FILE__)) . '/default.php';

/**
 * Multiple a msProduct
 */
class modmsProductMultipleInsertJsonProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    public function initialize()
    {
        if ($this->setCheckbox('complete_replacement')) {
            if (!isset($this->properties['new_value'])) {
                $value = $this->getProperty($this->getProperty('field'));
                $this->setProperty('new_value', $value);
            }
        }


        if (!isset($this->properties['new_value'])) {
            if (!$this->getNewString()) {
                return $this->failure($this->modx->lexicon('mspre_err_new_value'));
            }
        }

        return parent::initialize();
    }

    /* @inheritdoc */
    public function preapreData($id)
    {
        $new_value = $this->getProperty('new_value', '');
        $complete_replacement = $this->setCheckbox('complete_replacement');

        return $this->updateJSON($id, $this->fieldUpdate, $new_value, $complete_replacement);
    }


    /**
     * @param $id
     * @param $field
     * @param $new_value
     * @param bool $complete_replacement true установит новое значение в поле без дополнения старого
     * @return array
     */
    public function updateJSON($id, $field, $new_value, $complete_replacement = false)
    {
        if ($complete_replacement) {
            return $new_value;
        }

        $isArray = false;
        if (is_array($new_value)) {
            $isArray = true;
        }

        $json = array();
        $field = $this->fieldGet ? $this->fieldGet : $field;
        if ($object = $this->modx->getObject('msProduct', $id)) {
            if ($key = prefixOptions($field)) {
                $field = prefixOptionsAdd($key);
            }
            $tmps = $object->get($field);
            if ($isArray) {
                if (empty($tmps)) {
                    $json = $new_value;
                } else {
                    $json = $tmps;
                    foreach ($new_value as $k => $value) {
                        $json[] = $value;
                    }
                }
            } else {
                $json = $new_value;
            }

        }

        if ($isArray) {
            $json = array_unique($json);
        }
        return $json;
    }

}

return 'modmsProductMultipleInsertJsonProcessor';