<?php

/**
 * The base class for Nsi.
 */
class mspreActionsOptions extends mspreActions
{

    /**
     * Insert
     * @param string $field
     * @return array|null|string
     */
    protected function extField($field)
    {
        $ext_field = null;
        if ($classMeta = $this->mspre->loadClassMeta('options')) {
            $type = $classMeta->getType($field);
            switch ($type) {
                case 'combo-multiple':

                    if ($key = prefixOptions($field)) {
                        $values = array();
                        if ($object = $this->modx->getObject('msOption', array('key' => $key))) {
                            $properties = $object->get('properties');
                            if (isset($properties['values'])) {
                                $values = json_encode(array_chunk($properties['values'], 1));
                            } else {
                                $values = '[]';
                            }
                        }
                        $ext_field = "{
                        xtype: 'minishop2-combo-options',
                        allowAddNewData: false,
                        pinList: true,
                        mode: 'local',
                        store: new Ext.data.SimpleStore({
                            fields: ['value'],
                            data: {$values}
                        })
                    }";

                    }
                    break;
                case 'combobox':

                    if ($key = prefixOptions($field)) {
                        $values = array();
                        if ($object = $this->modx->getObject('msOption', array('key' => $key))) {
                            $properties = $object->get('properties');
                            if (isset($properties['values'])) {
                                $values = json_encode(array_chunk($properties['values'], 1));
                            } else {
                                $values = '[]';
                            }
                        }
                        $ext_field = "{
                        xtype: 'modx-combo',
                        mode: 'local',
                        store: new Ext.data.SimpleStore({
                            fields: ['value'],
                            data: {$values}
                        })
                    }";

                    }
                    break;
                default:
                    break;
            }
        }

        return $ext_field;
    }
    /**
     * Вернет тип поля
     * @param $field
     * @param null $name
     * @return bool|string
     */

    protected function getType($field_name, $key)
    {
        $metaData = $this->mspre->loadClassMeta('options')->getMeta($field_name);
        return $metaData['actions'][$key];
    }


    /**
     * Insert
     * @param string $field
     * @return array
     */
    public function getNewField($data)
    {

        switch ($data['xtype']){
            case 'xdatetime':
                /* $data= array_merge($data,array(
                     'dateFormat' => 'MODx.config.manager_date_format',
                     'hiddenFormat' => 'MODx.config.manager_date_format',
                     'startDay' => 'parseInt(MODx.config.manager_week_start)',
                     #'hideTime' => true,
                     #'timeWidth' => 0,
                     #'ctCls' => 'x-no-time',
                 ));*/
                $data= array_merge($data,array(
                    'dateFormat' => $this->modx->getOption('manager_date_format'),
                    'hiddenFormat' => $this->modx->getOption('manager_date_format'),
                    #'startDay' => $this->modx->getOption('manager_week_start'),
                    #'startDay' => 'parseInt('.$this->modx->getOption('manager_week_start').')',
                    'hideTime' => true,
                    'timeWidth' => 0,
                    'ctCls' => 'x-no-time',
                ));
                break;
            default:
                break;
        }

        return $data;
    }

    /**
     * Insert
     * @param string $field
     * @return array
     */
    public function fieldAdd($field)
    {
        $ext_field = $this->extField($field);
        $default = array(
            'field' => $field,
            'optionsfield' => $field,
            'namecombo' => 'json',
            'icon' => 'icon icon-update',
            'title' => $this->getLexiconOptions('insert', $field),
            'action' => 'loadComboOptionsDefault',
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'title' => $this->lexicon('mspre_options_actions_add'),
                'optionskey' => $field,
                'baseParams' => array(
                    'action' => 'mgr/controller/product/options/json/insert',
                    'insert' => true
                ),
                'fields' => array(
                    0 => array(
                        'xtype' => 'hidden',
                        'name' => 'field',
                        'value' => $field,
                        'allowBlank' => false,
                    ),
                    1 => array(
                        'xtype' => 'displayfield',
                        'fieldLabel' => $this->lexicon('mspre_field'),
                        'anchor' => '90%',
                        'hiddenName' => 'showname',
                        'value' => $field,
                    ),
                    2 => $this->getNewField(array(
                        'xtype' => $this->getType($field, 'new'),
                        'fieldLabel' => $this->lexicon('mspre_new_possibles'),
                        'name' => $field,
                        'field' => $field,
                        'allowBlank' => false,
                        'mode' => 'remote',
                        'hiddenName' => 'new_value[]',
                        'anchor' => '90%',
                        'whatValues' => 'possible',
                        'ext_field' => $ext_field,
                    )),
                    3 => array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                        'allowBlank' => false,
                    ),
                    4 => array(
                        'xtype' => 'xcheckbox',
                        'fieldLabel' => $this->lexicon('mspre_combo_options_offset'),
                        'description' => $this->lexicon('mspre_combo_options_offset_desc'),
                        'name' => 'offset_resource',
                        'allowBlank' => 'false',
                        'hiddenName' => 'offset_resource',
                        'anchor' => '90%',
                        'checked' => false,
                    ),
                ),
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
        $ext_field = $this->extField($field);
        $default = array(
            'field' => $field,
            'namecombo' => 'json',
            'icon' => 'icon icon-update',
            'title' => $this->getLexiconOptions('update', $field),
            'action' => 'defaultCombo',
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'title' => $this->lexicon('mspre_options_actions_update'),
                'baseParams' => array(
                    'action' => 'mgr/controller/product/options/json/update'
                ),
                'fields' => array(
                    0 => array(
                        'xtype' => 'hidden',
                        'name' => 'field',
                        'value' => $field,
                        'allowBlank' => false,
                    ),
                    1 => array(
                        'xtype' => 'displayfield',
                        'fieldLabel' => $this->modx->lexicon('mspre_field'),
                        'anchor' => '90%',
                        'hiddenName' => 'showname',
                        'value' => $field,
                    ),
                    2 => array(
                        'xtype' => $this->getType($field, 'old'),
                        'fieldLabel' => $this->modx->lexicon('mspre_old_entered'),
                        'name' => $field,
                        'field' => $field,
                        'allowBlank' => false,
                        'hiddenName' => 'old_value',
                        'anchor' => '90%',
                        'whatValues' => 'entered',
                    ),
                    3 => $this->getNewField(array(
                        'xtype' => $this->getType($field, 'replace'),
                        'fieldLabel' => $this->modx->lexicon('mspre_new_possible'),
                        'name' => $field,
                        'field' => $field,
                        'allowBlank' => false,
                        'hiddenName' => 'replace_value[]',
                        'anchor' => '90%',
                        'whatValues' => 'possible',
                        'mode' => 'remote',
                        'ext_field' => $ext_field,
                    )),
                    4 => array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                        'allowBlank' => false,
                    ),
                    5 => array(
                        'xtype' => 'xcheckbox',
                        'fieldLabel' => $this->lexicon('mspre_combo_options_offset'),
                        'description' => $this->lexicon('mspre_combo_options_offset_desc'),
                        'name' => 'offset_resource',
                        'allowBlank' => 'false',
                        'hiddenName' => 'offset_resource',
                        'anchor' => '90%',
                        'checked' => false,
                    ),
                ),
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
            'field' => $field,
            'namecombo' => 'json',
            'icon' => 'icon icon-update',
            'title' => $this->getLexiconOptions('remove', $field),
            'action' => 'defaultCombo',
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'title' => $this->lexicon('mspre_options_actions_remove'),
                'baseParams' => array(
                    'action' => 'mgr/controller/product/options/json/remove',
                    'insert' => true
                ),
                'fields' => array(
                    0 => array(
                        'xtype' => 'hidden',
                        'name' => 'field',
                        'value' => $field,
                        'allowBlank' => false,
                    ),
                    1 => array(
                        'xtype' => 'displayfield',
                        'fieldLabel' => $this->lexicon('mspre_field'),
                        'anchor' => '90%',
                        'hiddenName' => 'showname',
                        'value' => $field,
                    ),
                    2 => array(
                        'xtype' => $this->getType($field, 'remove'),
                        'fieldLabel' => $this->lexicon('mspre_remove_possibles'),
                        'name' => $field,
                        'field' => $field,
                        'allowBlank' => false,
                        'hiddenName' => 'remove_value',
                        'anchor' => '90%',
                        'whatValues' => 'entered',
                    ),
                    3 => array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                        'allowBlank' => false,
                    ),
                    4 => array(
                        'xtype' => 'xcheckbox',
                        'fieldLabel' => $this->lexicon('mspre_combo_options_offset'),
                        'description' => $this->lexicon('mspre_combo_options_offset_desc'),
                        'name' => 'offset_resource',
                        'allowBlank' => 'false',
                        'hiddenName' => 'offset_resource',
                        'anchor' => '90%',
                        'checked' => false,
                    ),
                ),
            ),

        );
        return $default;
    }

    /* @inheritdoc */
    public function getMenus($actions = array())
    {
        $this->actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-plus',
            'title' => $this->modx->lexicon('mspre_options_actions_add'),
            'action' => 'windowOptionsCategory',
            'mode' => 'add',
            'combo_id' => 'add',
        );

        $this->actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('mspre_options_actions_replace'),
            'action' => 'windowOptionsCategory',
            'mode' => 'replace',
            'combo_id' => 'replace',
        );

        $this->actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-remove',
            'title' => $this->modx->lexicon('mspre_options_actions_remove'),
            'action' => 'windowOptionsCategory',
            'mode' => 'remove',
            'combo_id' => 'remove',
        );

        /*$this->actions[] = '-';


        $this->actions[] = array(
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-sort red',
            'title' => $this->lexicon('mspre_json_fields'),
            'action' => 'windowFields',
            'combo_id' => 'options',
        );*/
        return $this->actions;
    }

    /**
     * @param $action
     * @param $field
     * @return string
     */
    protected function getLexiconOptions($action, $field)
    {
        $meta = null;
        $metaFields = $this->mspre->getFieldMeta();
        if (isset($metaFields[$field])) {
            $meta = $metaFields[$field];
        }
        $value = prefixOptions($field);
        return $meta['text'] . " ({$value})";
    }

}

return 'mspreActionsOptions';