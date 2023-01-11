<?php
include_once dirname(dirname(__FILE__)) . '/default.php';

/**
 * Multiple a msProduct
 */
class modmsProductMultipleUpdateJsonProcessor extends modmsProductMultipleUpdateOptionsDefaultProcessor
{
    public function initialize()
    {
        if (empty($this->getProperty('replace_value'))) {
            if (!$this->getNewString()) {
                return $this->failure($this->modx->lexicon('mspre_err_new_value'));
            }
        }

        if (empty($this->getProperty('old_value'))) {
            return $this->failure($this->modx->lexicon('mspre_err_old_value'));
        }
        return parent::initialize();
    }

    /* @inheritdoc */
    public function preapreData($id)
    {
        $old_value = $this->getProperty('old_value', '');
        $replace_value = $this->getProperty('replace_value', '');
        return $this->updateJSON($id, $this->fieldUpdate, $old_value, $replace_value);
    }


    public function updateJSON($id, $field, $old_value, $replace_value)
    {
        $json = array();
        $field = $this->fieldGet ? $this->fieldGet : $field;

        $isOption = false;
        if ($key = prefixOptions($field)) {
            $isOption = true;
        }


        if ($object = $this->modx->getObject('msProduct', $id)) {

            if ($isOption) {
                $field = prefixOptionsAdd($field);
            }
            $tmps = $object->get($field);


            if (empty($tmps)) {
                $json = $replace_value;
            } else {

                if (isset($replace_value)) {
                    foreach ($tmps as $k => $value) {
                        if ($value == $old_value) {
                            unset($tmps[$k]);
                        }
                    }
                    
               
                    
                    if (!empty($replace_value)) {
                        $json = array_merge($tmps, $replace_value);
                    } else {
                        $json = $tmps;
                    }

                } else {

                    foreach ($tmps as $k => $value) {
                        if ($value == $old_value) {
                            $json[$k] = $replace_value;
                        } else {
                            $json[$k] = $value;
                        }
                    }
                }


                /*
                 * Старый механизм для установки только одного значение
                 * в файле нужно установить
                 * core/components/mspre/model/actions/options.class.php
                 *
                    update 'new_value' => 'mspre-combo-autocomplete-options'

                и у поля с множественного сделать одиночным
                'xtype' => $this->getType('update', $field, 'new_value'),


                        'hiddenName' => 'new_value[]',

                 * foreach ($tmps as $k => $value) {
                    if ($value == $old_value) {
                        $json[$k] = $new_value;
                    } else {
                        $json[$k] = $value;
                    }
                }*/
            }
        }

      
        // Если строкое значение то оно будет передавать как массив
        if (!$this->isArray and !empty($json)) {
            $json = $replace_value;
        }
        return $json;
    }

}

return 'modmsProductMultipleUpdateJsonProcessor';