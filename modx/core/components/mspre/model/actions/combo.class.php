<?php

/**
 * The base class for Nsi.
 */
class mspreActionsCombo extends mspreActions
{
    /* @inheritdoc */
    public function getMenus($actions = array())
    {
        $dateFormat = $this->mspre->getOption('manager_date_format', array(), 'Y-m-d');
        $timeFormat = $this->mspre->getOption('manager_time_format', array(), 'g:i a');
        $menus = array(
            0 => array(
                'namecombo' => 'template',
                'icon' => 'icon icon-columns',
                'action' => 'defaultCombo',
                'combo_id' => 'mspre-window-update-default-combo',
                'field_params' => array(
                    'xtype' => 'mspre-combo-template',
                    'name' => 'template',
                    'hiddenName' => 'template',
                    'baseParams' => array(
                        'action' => 'mgr/common/additional/updatetemplate'
                    ),
                ),
            ),
            2 => array(
                'namecombo' => 'parent',
                'icon' => 'icon icon-folder-open-o',
                'action' => 'defaultCombo',
                'combo_id' => 'mspre-window-update-default-combo',
                'field_params' => array(
                    'xtype' => 'mspre-combo-parent',
                    'name' => 'parent',
                    'hiddenName' => 'parent',
                    'baseParams' => array(
                        'action' => 'mgr/common/additional/updateparent'
                    ),
                ),
            ),

            // Product
            3 => array(
                'namecombo' => 'vendor',
                'icon' => 'icon icon-copyright',
                'action' => 'defaultCombo',
                'combo_id' => 'mspre-window-update-default-combo',
                'field_params' => array(
                    'title' => $this->lexicon('mspre_combo_vendor_title'),
                    'baseParams' => array(
                        'action' => '/mgr/common/additional/updatevendor'
                    ),
                    'fields' => array(
                        0 => array(
                            'xtype' => 'hidden',
                            'name' => 'field',
                            'value' => 'vendor',
                            'allowBlank' => false,
                        ),
                        1 => array(
                            'xtype' => 'displayfield',
                            'fieldLabel' => $this->modx->lexicon('mspre_field'),
                            'anchor' => '90%',
                            'value' => 'vendor',
                        ),
                        2 => array(
                            'xtype' => 'mspre-combo-vendor',
                            'name' => 'vendor',
                            'hiddenName' => 'vendor',
                        )
                    )
                ),
            ),
            4 => array(
                'namecombo' => 'source',
                'icon' => 'icon tree-context',
                'action' => 'defaultCombo',
                'combo_id' => 'mspre-window-update-default-combo',
                'field_params' => array(
                    'title' => $this->lexicon('mspre_combo_source_title'),
                    'baseParams' => array(
                        'action' => 'mgr/common/additional/updatesource'
                    ),
                    'fields' => array(
                        0 => array(
                            'xtype' => 'hidden',
                            'name' => 'field',
                            'value' => 'source',
                            'allowBlank' => false,
                        ),
                        1 => array(
                            'xtype' => 'displayfield',
                            'fieldLabel' => $this->modx->lexicon('mspre_field'),
                            'anchor' => '90%',
                            'value' => 'source',
                        ),
                        2 => array(
                            'xtype' => 'mspre-combo-source',
                            'name' => 'source',
                            'hiddenName' => 'source',
                        )
                    )
                ),
            ),

            5 => array(
                'namecombo' => 'textinsert',
                'icon' => 'icon icon-files-o',
                'action' => 'defaultCombo',
                'combo_id' => 'mspre-window-update-default-combo',
                'field_params' => array(
                    'title' => $this->lexicon('mspre_combo_textinsert_title'),
                    'baseParams' => array(
                        'action' => 'mgr/common/additional/updatetext'
                    ),
                    'fields' => array(
                        0 => array(
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textinsert_field'),
                            'xtype' => 'mspre-combo-text-replace',
                            'anchor' => '90%',
                            'name' => 'field',
                            'value' => 'pagetitle',
                        ),
                        1 => array(
                            'xtype' => 'textfield',
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textinsert_new_value'),
                            'name' => 'new_value',
                            'anchor' => '90%',
                        ),
                        2 => array(
                            'xtype' => 'xcheckbox',
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textreplace_empty'),
                            'description' => $this->modx->lexicon('mspre_combo_textreplace_empty_desc'),
                            'name' => 'empty',
                            'checked' => false,
                            'hiddenName' => 'empty',
                        )
                    )
                ),
            ),
            6 => array(
                'namecombo' => 'textreplace',
                'icon' => 'icon icon-files-o',
                'action' => 'defaultCombo',
                'combo_id' => 'mspre-window-update-default-combo',
                'field_params' => array(
                    'title' => $this->lexicon('mspre_combo_textreplace_title'),
                    'baseParams' => array(
                        'action' => 'mgr/common/additional/updatetext'
                    ),
                    'fields' => array(
                        0 => array(
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textreplace_field'),
                            'xtype' => 'mspre-combo-text-replace',
                            'anchor' => '90%',
                            'name' => 'field',
                            'value' => 'pagetitle',
                        ),
                        1 => array(
                            'xtype' => 'textfield',
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textreplace_replace'),
                            'anchor' => '90%',
                            'name' => 'replace',
                        ),
                        2 => array(
                            'xtype' => 'textfield',
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textreplace_new_value'),
                            //'description' => $this->modx->lexicon('mspre_combo_pagetitle_new_value_desc'),
                            'name' => 'new_value',
                            'anchor' => '90%',
                        ),
                        3 => array(
                            'xtype' => 'xcheckbox',
                            'fieldLabel' => $this->modx->lexicon('mspre_combo_textreplace_empty'),
                            'description' => $this->modx->lexicon('mspre_combo_textreplace_empty_desc'),
                            'name' => 'empty',
                            'checked' => false,
                            'hiddenName' => 'empty',
                        ),
                        4 => array(
                            'xtype' => 'modx-description',
                            'style' => 'margin-top: 10px;',
                            'html' => "<p>{$this->modx->lexicon('mspre_combo_textreplace_all_replace')}</p>",
                        )
                    )
                ),
            ),
        );


        $menus[] = array(
            'cls' => '',
            'icon' => 'icon-gear icon icon-large',
            'title' => $this->modx->lexicon('mspre_menu_more_features'),
            'menu' => array(
                0 => array(
                    'menu' => true,
                    'title' => $this->lexicon('mspre_combo_contenttype_title'),
                    'namecombo' => 'contenttype',
                    'icon' => 'icon icon-recycle',
                    'action' => 'defaultCombo',
                    'combo_id' => 'mspre-window-update-default-combo',
                    'field_params' => array(
                        'title' => $this->lexicon('mspre_combo_contenttype_title'),
                        'baseParams' => array(
                            'action' => 'mgr/common/additional/contenttype'
                        ),
                        'fields' => array(
                            0 => array(
                                'xtype' => 'hidden',
                                'name' => 'ids',
                            ),
                            1 => array(
                                'xtype' => 'hidden',
                                'name' => 'field',
                                'value' => 'content_type',
                                'allowBlank' => false,
                            ),
                            2 => array(
                                //modx-resource-content-type
                                'xtype' => 'modx-combo-content-type',
                                'fieldLabel' => $this->modx->lexicon('mspre_combo_contenttype_label'),
                                'anchor' => '90%',
                                'name' => 'content_type',
                            )
                        )
                    ),
                ),
                1 => array(
                    'menu' => true,
                    'title' => $this->lexicon('mspre_combo_resourcegroup_title'),
                    'namecombo' => 'resourcegroup',
                    'icon' => 'icon tree-context',
                    'action' => 'defaultCombo',
                    'combo_id' => 'mspre-window-update-default-combo',
                    'field_params' => array(
                        'title' => $this->lexicon('mspre_combo_resourcegroup_title'),
                        'baseParams' => array(
                            'action' => 'mgr/common/additional/updateresourcegroup'
                        ),
                        'fields' => array(
                            0 => array(
                                'xtype' => 'hidden',
                                'name' => 'ids',
                            ),
                            1 => array(
                                'xtype' => 'modx-combo-resourcegroup',
                                'fieldLabel' => $this->modx->lexicon('mspre_combo_resourcegroup_label'),
                                'anchor' => '90%',
                                'name' => 'resourcegroup',
                            ),
                            2 => array(
                                'xtype' => 'xcheckbox',
                                'fieldLabel' => $this->modx->lexicon('mspre_combo_resourcegroup_access'),
                                'description' => $this->modx->lexicon('mspre_combo_resourcegroup_access_desc'),
                                'name' => 'access',
                                'checked' => true,
                                'hiddenName' => 'access',
                            )
                        )
                    ),
                ),
                2 => array(
                    'menu' => true,
                    'namecombo' => 'dates',
                    'icon' => 'icon icon-calendar',
                    'title' => $this->modx->lexicon('mspre_action_update_date'),
                    'action' => 'defaultCombo',
                    'combo_id' => 'mspre-window-update-default-combo',
                    'field_params' => array(
                        'title' => $this->modx->lexicon('mspre_action_update_date'),
                        'baseParams' => array(
                            'action' => 'mgr/common/additional/updatedates'
                        ),
                        'fields' => array(
                            0 => array(
                                'xtype' => 'hidden',
                                'name' => 'ids',
                            ),
                            1 => array(
                                'xtype' => 'modx-description',
                                'style' => 'margin-top: 10px;',
                                'html' => "<p>{$this->modx->lexicon('mspre_date_desc')}</p>",
                            ),
                            2 => array(
                                'xtype' => 'xdatetime',
                                'fieldLabel' => $this->modx->lexicon('mspre_createdon'),
                                'name' => 'createdon',
                                'hiddenName' => 'createdon',
                                'anchor' => '90%',
                                'allowBlank' => true,
                                'dateFormat' => $dateFormat,
                                'timeFormat' => $timeFormat,
                                'dateWidth' => 120,
                                'timeWidth' => 120
                            ),
                            3 => array(
                                'xtype' => 'xdatetime',
                                'fieldLabel' => $this->modx->lexicon('mspre_editedon'),
                                'name' => 'editedon',
                                'hiddenName' => 'editedon',
                                'anchor' => '90%',
                                'allowBlank' => true,
                                'dateFormat' => $dateFormat,
                                'timeFormat' => $timeFormat,
                                'dateWidth' => 120,
                                'timeWidth' => 120
                            ),
                            4 => array(
                                'xtype' => 'xdatetime',
                                'fieldLabel' => $this->modx->lexicon('mspre_publishedon'),
                                'description' => $this->modx->lexicon('mspre_publishedon_help'),
                                'name' => 'publishedon',
                                'hiddenName' => 'publishedon',
                                'anchor' => '90%',
                                'allowBlank' => true,
                                'dateFormat' => $dateFormat,
                                'timeFormat' => $timeFormat,
                                'dateWidth' => 120,
                                'timeWidth' => 120
                            ),

                            5 => array(
                                'xtype' => 'xdatetime',
                                'fieldLabel' => $this->modx->lexicon('mspre_pub_date'),
                                'name' => 'pub_date',
                                'hiddenName' => 'pub_date',
                                'description' => $this->modx->lexicon('mspre_pub_date_help'),
                                'anchor' => '90%',
                                'allowBlank' => true,
                                'dateFormat' => $dateFormat,
                                'timeFormat' => $timeFormat,
                                'dateWidth' => 120,
                                'timeWidth' => 120
                            ),
                            6 => array(
                                'xtype' => 'xdatetime',
                                'fieldLabel' => $this->modx->lexicon('mspre_unpub_date'),
                                'description' => $this->modx->lexicon('mspre_unpub_date_help'),
                                'name' => 'unpub_date',
                                'hiddenName' => 'unpub_date',
                                'anchor' => '90%',
                                'allowBlank' => true,
                                'dateFormat' => $dateFormat,
                                'dateWidth' => 120,
                                'timeWidth' => 120
                            )
                        ),
                    ),
                ),
                3 => array(
                    'menu' => true,
                    'title' => $this->lexicon('mspre_combo_user_title'),
                    'namecombo' => 'user',
                    'icon' => 'icon icon-user-o',
                    'action' => 'defaultCombo',
                    'combo_id' => 'mspre-window-update-default-combo',
                    'field_params' => array(
                        'title' => $this->lexicon('mspre_combo_user_title'),
                        'baseParams' => array(
                            'action' => 'mgr/common/additional/updateuser'
                        ),
                        'fields' => array(
                            0 => array(
                                'xtype' => 'hidden',
                                'name' => 'ids',
                            ),
                            1 => array(
                                'xtype' => 'modx-combo-user',
                                'fieldLabel' => $this->modx->lexicon('mspre_createdby'),
                                'name' => 'createdby',
                                'hiddenName' => 'createdby',
                                'anchor' => '90%',
                            ),
                            2 => array(
                                'xtype' => 'modx-combo-user',
                                'fieldLabel' => $this->modx->lexicon('mspre_editedby'),
                                'name' => 'editedby',
                                'hiddenName' => 'editedby',
                                'anchor' => '90%',
                            ),
                            3 => array(
                                'xtype' => 'modx-combo-user',
                                'fieldLabel' => $this->modx->lexicon('mspre_publishedby'),
                                'name' => 'publishedby',
                                'hiddenName' => 'publishedby',
                                'anchor' => '90%',
                            )
                        ),
                    ),
                ),
            ),
        );
        return $this->prepareMenus('mspre_additional_actions', 'icon icon-star', $menus, '', true);
    }
}

return 'mspreActionsCombo';