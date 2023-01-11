<?php

/**
 * The base class for Nsi.
 */
class mspreActionsFields extends mspreActions
{

    /**
     * Type
     * @param string $field
     * @param string $key
     * @return string|null
     */
    protected function getType($field, $key)
    {
        if ($ClassMeta = $this->mspre->loadClassMeta('fields')) {
            $metaData = $ClassMeta->getMeta($field);
            if (!isset($metaData['actions'][$key])) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "Error could not type field:" . $field, '', __METHOD__, __FILE__, __LINE__);
            }
            return isset($metaData['actions'][$key]) ? $metaData['actions'][$key] : null;
        }
        return null;
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
     * Insert
     * @param string $field
     * @return array
     */
    public function fieldAdd($field)
    {
        $field = prefixFields($field);

        $default = array(
            'field' => $field,
            'optionsfield' => $field,
            'namecombo' => 'json',
            'icon' => 'icon icon-update',
            'title' => $field,
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
                    )),
                    3 => array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                        'allowBlank' => false,
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
        $field = prefixFields($field);
        $default = array(
            'field' => $field,
            'namecombo' => 'json',
            'icon' => 'icon icon-update',
            'title' => $field,
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
                    )),
                    4 => array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                        'allowBlank' => false,
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
        $field = prefixFields($field);
        $default = array(
            'field' => $field,
            'namecombo' => 'json',
            'icon' => 'icon icon-update',
            'title' => $field,
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
                ),
            ),

        );
        return $default;
    }

    /* @inheritdoc */
    public function getMenus($actions = array())
    {
        return $this->actions;
    }
}

return 'mspreActionsFields';