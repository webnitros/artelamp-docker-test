<?php

/**
 * The base class for Nsi.
 */
class mspreActionsResource extends mspreActions
{


    /* @inheritdoc */
    public function getMenus($actions = array())
    {

        $actions[] = $this->fieldBoolean('published', 'icon icon-toggle-on', 'icon icon-toggle-on green', 'icon icon-toggle-off red',true);

        $actions[] = $this->fieldBoolean('deleted', 'icon icon-trash-o', 'icon icon-trash-o red', 'icon icon-trash-o green',true);
        $actions[] = array(
            'cls' => '',
            'icon' => 'icon-square-o icon icon-large',
            'title' => $this->modx->lexicon('mspre_menu_more_actions'),
            'menu' => array(
                0 => $this->fieldBoolean('hidemenu', 'icon icon-eye', 'icon icon-eye red', 'icon icon-eye-slash green',true),
                1 => $this->fieldBoolean('searchable', 'icon icon-search', 'icon icon-search green', 'icon icon-search red',true),
                2 => $this->fieldBoolean('show_in_tree', 'icon icon-plus', 'icon icon-plus green', 'icon icon-minus red',true),
                3 => $this->fieldBoolean('cacheable', 'icon icon-barcode', 'icon icon-barcode green', 'icon icon-barcode red',true),
                4 => $this->fieldBoolean('uri_override', 'icon tree-context', 'icon tree-context green', 'icon tree-context red',true),
                5 => $this->fieldBoolean('richtext', 'icon tree-resource', 'icon tree-resource green', 'icon tree-resource red',true),
                6 => $this->fieldBoolean('hide_children_in_tree', 'icon icon-eye', 'icon icon-eye green', 'icon icon-eye red',true),
            ),
        );

        $actions[] = '-';


        $actions[] = array(
            'combo_id' => 'imagegeneration',
            'action' => 'defaultActionProgress',
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-times',
            'title' => $this->lexicon('mspre_action_imagegeneration'),
        );
        $actions[] = array(
            'combo_id' => 'generationurl',
            'action' => 'defaultActionProgress',
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-link',
            'title' => $this->lexicon('mspre_action_generation_url'),
        );
        $actions[] = array(
            'combo_id' => 'removeresource',
            'action' => 'defaultActionProgress',
            'menu' => true,
            'cls' => '',
            'icon' => 'icon icon-trash-o',
            'title' => $this->lexicon('mspre_action_removeresource'),
        );
        return $actions;
    }

    protected function menuString($menus = array())
    {
        return $this->prepareMenus('mspre_options_actions_string', 'icon icon-barcode', $this->optionsList('string', $menus));
    }

    protected function menuWeight($menus = array())
    {
        return $this->prepareMenus('mspre_options_actions_weight', 'icon icon-barcode', $this->optionsList('weight', $menus));
    }

    protected function menuPrice($menus = array())
    {
        $menus = $this->prepareMenus('mspre_options_actions_price', 'icon icon-barcode', $this->optionsList('price', $menus));

        $menus['menu'][] = '-';

        $menus['menu'][] = array(
            'namecombo' => 'price_transfer',
            'icon' => 'icon icon-files-o',
            'title' => 'Перенос цен',
            'action' => 'defaultCombo',
            'menu' => true,
            'cls' => true,
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'title' => $this->modx->lexicon('mspre_combo_price_transfer_title'),
                'baseParams' => array(
                    'action' => $this->action('options/transfer')
                ),
                'fields' => array(
                    0 => array(
                        'xtype' => 'mspre-combo-price',
                        'fieldLabel' => $this->modx->lexicon('mspre_source_value_price'),
                        'name' => 'source_value',
                        'hiddenName' => 'source_value',
                        'anchor' => '90%',
                    ),
                    1 => array(
                        'xtype' => 'mspre-combo-price',
                        'fieldLabel' => $this->modx->lexicon('mspre_target_value_price'),
                        'name' => 'target_value',
                        'hiddenName' => 'target_value',
                        'anchor' => '90%',
                    ),
                    2 => array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                    )
                ),
            ),
        );

        return $menus;
    }

}
return 'mspreActionsResource';