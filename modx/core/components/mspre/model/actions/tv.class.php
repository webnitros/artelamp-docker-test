<?php

/**
 * The base class for Nsi.
 */
class mspreActionsTv extends mspreActions
{
    /* @var modTemplateVar $modTemplate */
    /* @var modTemplateVarTemplate $modTemplateVarTemplate */
    public $modTemplateVar;
    public $modTemplateVarTemplate;

    /* @var modTemplateVar $tv */
    public $tv;

    /* @inheritdoc */
    public function getMenus($actions = array())
    {
        return $this->getTvActions();
    }

    /**
     * @return array|string
     */
    public function init($field)
    {
        if (!$this->tv = $this->modx->getObject('modTemplateVar', array('name' => $field))) {
            return false;
        }
        return true;
    }

    /**
     * @return array|string
     */
    public function accessTvTemplate($template_id)
    {
        return $this->tv->hasTemplate($template_id);
    }

    /**
     * @return array|string
     */
    private function parser($key, $value)
    {
        return array(
            'id' => $key,
            'name' => $value,
        );
    }


    /**
     * @return array|string
     */
    public function possibleValues()
    {
        $data = array();
        $default_text = $this->modTemplateVar->get('elements');
        if (!empty($default_text)) {
            $ext = explode('||', $default_text);
            if (count($ext) > 0) {
                foreach ($ext as $ex) {
                    $arr = explode('==', $ex);
                    if (count($arr) > 1) {
                        $value = isset($arr[0]) ? $arr[0] : '';
                        $key = isset($arr[1]) ? $arr[1] : '';
                    } else {
                        $value = $key = $arr[0];
                    }
                    $data[] = $this->parser($key, $value);
                }
            }
        }
        return $data;
    }

    /**
     * @return array|string
     */
    public function enteredValues($name, $ids, $values)
    {
        /* @var mspre $mspre */
        #$mspre = $this->modx->getService('mspre');
        $data = array();

        /* @var modTemplateVarResource $object */
        $q = $this->modx->newQuery('modTemplateVarResource');
        $q->select('value');
        $q->where(array(
            'tmplvarid' => $this->modTemplateVarTemplate->tmplvarid,
            'contentid:IN' => $ids
        ));
        if ($q->prepare() && $q->stmt->execute()) {
            while ($row = $q->stmt->fetch(PDO::FETCH_ASSOC)) {
                $separator = false;
                switch ($this->modTemplateVar->get('name')) {
                    case 'autotag':
                        break;
                    default:
                        $separator = '||';
                        break;
                }

                if ($separator) {
                    $enteredvalue = explode($separator, $row['value']);
                } else {
                    $enteredvalue = array($row['value']);
                }

                if (is_array($enteredvalue)) {
                    foreach ($enteredvalue as $k) {
                        foreach ($values as $default) {
                            if ($default['id'] == $k) {
                                $data[] = $default;
                            }
                        }
                    }
                }
            }
        }
        return $data;
    }


    /** @return string Fields Massiv Actions */
    public function getTvCollection($tvName)
    {

        /* @var modResource $product */
        if ($product = $this->modx->getObject($this->classKey, 4)) {
            $resources = $product->getTemplateVars();

            /* @var modTemplateVar $TemplateVav */
            foreach ($resources as $TemplateVav) {
                $tv_type = $TemplateVav->get('type');
                $tv_name = $TemplateVav->get('name');
                if ($tv_name == $tvName) {
                    switch ($tv_type) {
                        case 'autotag':
                        case 'tag':
                        case 'email':
                        case 'date':
                        case 'option':
                        case 'listbox-multiple':
                        case 'listbox':
                        case 'resourcelist':
                        case 'hidden':
                        case 'text':
                        case 'textarea':
                        case 'list-multiple-legacy':
                        case 'checkbox':
                        case 'number':
                            return $TemplateVav->renderInput($product);
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        return false;
    }

    /** @return array|bool Fields Massiv Actions */
    private function getTemplate()
    {
        $templates = false;
        $q = $this->modx->newQuery($this->classKey);
        $q->select('template');

        if ($this->classKey == 'msProduct') {
            $q->where(array('class_key' => 'msProduct'));
        }
        $q->groupby('template');
        if ($q->prepare() && $q->stmt->execute()) {
            $rows = $q->stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($rows)) {
                $templates = array_column($rows, 'template');
            }
        }
        return $templates;
    }

    /** @return array Fields Massiv Actions */
    protected function getTvActions()
    {
        $input = '';
        return array(
            0 => array(
                'menu' => true,
                'mode' => 'add',
                'cls' => '',
                'icon' => 'icon icon-plus',
                'title' => $this->modx->lexicon('mspre_action_add_tv'),
                'action' => 'windowTvTemplate',
                'field_params' => array(
                    'mode' => 'add',
                    'input' => $input,
                    'xtype' => 'textfield',
                    'field' => 'text',
                    'title' => $this->modx->lexicon('mspre_name_field_tv'),
                    //'fields' => $xtypes,
                )
            ),
            1 => array(
                'menu' => true,
                'cls' => '',
                'mode' => 'replace',
                'icon' => 'icon icon-edit',
                'title' => $this->modx->lexicon('mspre_action_replace_tv'),
                'action' => 'windowTvTemplate',
                'field_params' => array(
                    'mode' => 'replace',
                    'input' => $input,
                    'xtype' => 'textfield',
                    'field' => 'text',
                    'title' => $this->modx->lexicon('mspre_name_field_tv'),
                    //'fields' => $xtypes,
                )
            ),
            2 => array(
                'menu' => true,
                'cls' => '',
                'mode' => 'remove',
                'icon' => 'icon icon-remove',
                'title' => $this->modx->lexicon('mspre_action_remove_tv'),
                'action' => 'windowTvTemplate',
                'field_params' => array(
                    'mode' => 'remove',
                    'input' => $input,
                    'xtype' => 'textfield',
                    'field' => 'text',
                    'title' => $this->modx->lexicon('mspre_name_field_tv'),
                    //'fields' => $xtypes,
                )
            )
        );
    }


    /**
     * Вернет тип поля
     * @param $field
     * @param string $key
     * @return bool|string
     */

    protected function getType($field_name, $key)
    {
        $metaData = $this->mspre->loadClassMeta('tv')->getMeta($field_name);
        return $metaData['actions'][$key];
    }


    /**
     * Insert
     * @param array $data
     * @return array
     */
    public function getNewField($data)
    {
        return $data;
    }

    /**
     * add
     * @param string $field
     * @return array
     */
    public function fieldAdd($field)
    {
        $default = array(
            'namecombo' => 'masstv-add',
            'title' => 'Добавить значение',
            'fields' => array(
                0 => array(
                    'xtype' => 'displayfield',
                    'fieldLabel' => $this->lexicon('mspre_field'),
                    'anchor' => '90%',
                    'value' => $field,
                ),
                1 => $this->getNewField(array(
                    'id' => 'mspre-tv-field_value-'.$field,
                    'xtype' => $this->getType($field, 'new'),
                    'fieldLabel' => $this->lexicon('mspre_tv_value'),
                    'name' => 'field_value',
                    'allowBlank' => false,
                    'hiddenName' => 'field_value',
                    'anchor' => '90%',
                    'whatValues' => 'possible',
                )),
                2 => array(
                    'xtype' => 'xcheckbox',
                    'fieldLabel' => $this->lexicon('mspre_combo_tv_offset'),
                    'description' => $this->lexicon('mspre_combo_tv_offset_desc'),
                    'name' => 'offset_resource',
                    'allowBlank' => 'false',
                    'hiddenName' => 'offset_resource',
                    'anchor' => '90%',
                    'checked' => false,
                )
            ),

        );
        return $default;
    }

    /**
     * Replace
     * @param string $field
     * @return array
     */
    public function fieldReplace($field)
    {
        $default = array(
            'namecombo' => 'masstv-replace',
            'title' => 'Обновить значение',
            'fields' => array(
                0 => array(
                    'xtype' => 'displayfield',
                    'fieldLabel' => $this->lexicon('mspre_field'),
                    'anchor' => '90%',
                    'value' => $field,
                ),
                1 => $this->getNewField(array(
                    'id' => 'mspre-tv-field_value-'.$field,
                    'xtype' => $this->getType($field, 'old'),
                    'fieldLabel' => $this->lexicon('mspre_old_entered'),
                    'name' => 'field_value',
                    'allowBlank' => false,
                    'hiddenName' => 'field_value',
                    'anchor' => '90%',
                    'whatValues' => 'entered',
                )),
                2 => $this->getNewField(array(
                    'id' => 'mspre-tv-field_replace-'.$field,
                    'xtype' => $this->getType($field, 'replace'),
                    'fieldLabel' => $this->lexicon('mspre_new_possible'),
                    'name' => 'field_replace',
                    'allowBlank' => false,
                    'hiddenName' => 'field_replace',
                    'anchor' => '90%',
                    'whatValues' => 'possible',
                )),
                3 => array(
                    'xtype' => 'xcheckbox',
                    'fieldLabel' => $this->lexicon('mspre_combo_tv_offset'),
                    'description' => $this->lexicon('mspre_combo_tv_offset_desc'),
                    'name' => 'offset_resource',
                    'allowBlank' => 'false',
                    'hiddenName' => 'offset_resource',
                    'anchor' => '90%',
                    'checked' => false,
                )
            ),

        );
        return $default;
    }


    /**
     * Remove
     * @param string $field
     * @return array
     */
    public function fieldRemove($field)
    {
        $default = array(
            'namecombo' => 'masstv-remove',
            'title' => 'Удалить значение',
            'fields' => array(
                0 => array(
                    'xtype' => 'displayfield',
                    'fieldLabel' => $this->lexicon('mspre_field'),
                    'anchor' => '90%',
                    'value' => $field,
                ),
                1 => $this->getNewField(array(
                    'id' => 'mspre-tv-field_value-'.$field,
                    'xtype' => $this->getType($field, 'remove'),
                    'fieldLabel' => $this->lexicon('mspre_remove_possibles'),
                    'name' => 'field_value',
                    'allowBlank' => false,
                    'hiddenName' => 'field_value',
                    'anchor' => '90%',
                    'whatValues' => 'entered',
                )),
                2 => array(
                    'xtype' => 'xcheckbox',
                    'fieldLabel' => $this->lexicon('mspre_combo_tv_offset'),
                    'description' => $this->lexicon('mspre_combo_tv_offset_desc'),
                    'name' => 'offset_resource',
                    'allowBlank' => 'false',
                    'hiddenName' => 'offset_resource',
                    'anchor' => '90%',
                    'checked' => false,
                )
            ),
        );
        return $default;
    }

}

return 'mspreActionsTv';