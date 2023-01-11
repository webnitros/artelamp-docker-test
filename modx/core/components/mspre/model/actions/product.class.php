<?php

/**
 * The base class for Nsi.
 */
class mspreActionsProduct extends mspreActions
{

    /* @inheritdoc */
    public function getMenus($actions = array())
    {
        $actions[] = $this->menuPrice();
        $actions[] = $this->menuString();
        $actions[] = $this->menuWeight();

        if ($menu = $this->menuBoolean()) {
            $actions[] = $menu;
        }

        $actions[] = '-';


        //loadComboDefaultOptions[namecombo] => price
        $actions[] = array(
            'namecombo' => 'optionsdefault',
            'cls' => '',
            'icon' => 'icon-square-o icon icon-large',
            'title' => $this->modx->lexicon('mspre_menu_options_default'),
            'menu' => array(
                0 => array(
                    'menu' => true,
                    'cls' => '',
                    'icon' => 'icon icon-square-o grean',
                    'combo_id' => 'tags',
                    'title' => 'tags',
                    'action' => 'loadComboDefaultOptions',
                ),
                1 => array(
                    'menu' => true,
                    'cls' => '',
                    'icon' => 'icon icon-square-o red',
                    'combo_id' => 'color',
                    'title' => 'color',
                    #'title' => $this->lexicon('mspre_action_uncategories'),
                    'action' => 'loadComboDefaultOptions',
                ),
                2 => array(
                    'menu' => true,
                    'cls' => '',
                    'combo_id' => 'size',
                    'icon' => 'icon icon-square-o',
                    'title' => 'size',
                    #'title' => $this->lexicon('mspre_action_image_generation'),
                    'action' => 'loadComboDefaultOptions',
                ),
            ),
        );


        $actions[] = '-';

        $actions[] = $this->productFieldBoolean('new', 'icon icon-bell', 'icon icon-bell green', 'icon icon-bell-slash-o red', true);
        $actions[] = $this->productFieldBoolean('popular', 'icon icon-thumbs-up', 'icon icon-thumbs-up green', 'icon icon-thumbs-o-up red', true);
        $actions[] = $this->productFieldBoolean('favorite', 'icon icon-star', 'icon icon-star green', 'icon icon-star red', true);
        $actions[] = '-';


        $actions[] = array(
            'cls' => '',
            'icon' => 'icon-gear icon icon-large',
            'title' => $this->modx->lexicon('mspre_menu_more_features'),
            'menu' => array(
                0 => array(
                    'menu' => true,
                    'cls' => '',
                    'icon' => 'icon icon-sitemap grean',
                    'title' => $this->lexicon('mspre_action_checked_categories'),
                    'action' => 'assignSelected',
                ),
                1 => array(
                    'combo_id' => 'uncategories',
                    'menu' => true,
                    'cls' => '',
                    'icon' => 'icon icon-trash-o red',
                    'title' => $this->lexicon('mspre_action_uncategories'),
                    'action' => 'defaultActionProgress',
                ),
                2 => '-',
                3 => array(
                    'menu' => true,
                    'combo_id' => 'imagegeneration',
                    'cls' => '',
                    'icon' => 'icon icon-trash-o',
                    'title' => $this->lexicon('mspre_action_image_generation'),
                    'action' => 'defaultActionProgress',
                ),
                4 => array(
                    'menu' => true,
                    'combo_id' => 'imageremove',
                    'cls' => '',
                    'icon' => 'icon icon-refresh',
                    'title' => $this->lexicon('mspre_action_image_removes'),
                    'action' => 'defaultActionProgress',
                ),

                5 => '-',
                6 => array(
                    'namecombo' => 'product_link_create',
                    'icon' => 'icon icon-link grean',
                    'title' => $this->modx->lexicon('mspre_action_product_link'),
                    'action' => 'defaultCombo',
                    'menu' => true,
                    'cls' => true,
                    'combo_id' => 'mspre-window-update-default-combo',
                    'field_params' => array(
                        'title' => $this->modx->lexicon('mspre_action_product_link'),
                        'baseParams' => array(
                            'action' => $this->action('productlink/create')
                        ),
                        'fields' => array(
                            0 => array(
                                'xtype' => 'minishop2-combo-link',
                                'fieldLabel' => $this->modx->lexicon('mspre_product_link_create_label'),
                                'name' => 'id',
                                'hiddenName' => 'id',
                                'anchor' => '90%',
                            ),
                            1 => array(
                                'xtype' => 'minishop2-combo-product',
                                'fieldLabel' => $this->modx->lexicon('mspre_product_link_slave_label'),
                                'name' => 'slave',
                                'hiddenName' => 'slave',
                                'anchor' => '90%',
                            ),
                            2 => array(
                                'xtype' => 'hidden',
                                'name' => 'ids',
                            )
                        ),
                    ),

                ),
                7 => array(
                    'namecombo' => 'un_product_link_create',
                    'icon' => 'icon icon-unlink red',
                    'title' => $this->modx->lexicon('mspre_action_unproduct_link'),
                    'action' => 'defaultCombo',
                    'menu' => true,
                    'cls' => true,
                    'combo_id' => 'mspre-window-update-default-combo',
                    'field_params' => array(
                        'title' => $this->modx->lexicon('mspre_action_unproduct_link'),
                        'baseParams' => array(
                            'action' => $this->action('productlink/remove')
                        ),
                        'fields' => array(
                            0 => array(
                                'xtype' => 'minishop2-combo-link',
                                'fieldLabel' => $this->modx->lexicon('mspre_product_link_create_label'),
                                'name' => 'id',
                                'hiddenName' => 'id',
                                'anchor' => '90%',
                            ),
                            1 => array(
                                'xtype' => 'hidden',
                                'name' => 'ids',
                            )
                        ),
                    ),

                ),

            ),
        );
        return $actions;
    }

    protected function productFieldBoolean($field, $icon = null, $firstIcon = null, $lastIcon = null, $progress = false)
    {
        $menus = $this->fieldBoolean($field, $icon, $firstIcon, $lastIcon, $progress);

        /*$menus['menu'][2] = array(
            'menu' => 1,
            'icon' => 'icon icon-calendar',
            'title' => 'Признак "Новинка" действует до',
            'action' => 'setPropertyMassivProgress',
            'field_name' => 'new',
            'field_value' => 0,
        );*/
        return $menus;
    }

    protected function menuString($menus = array())
    {
        return $this->prepareMenus('mspre_options_actions_string', 'icon icon-barcode', $this->optionsList('string', $menus));
    }

    protected function menuWeight($menus = array())
    {
        return $this->prepareMenus('mspre_options_actions_weight', 'icon icon-barcode', $this->optionsList('weight', $menus));
    }

    protected function menuBoolean($menus = array())
    {
        $fields = null;
        $map = $this->modx->getFieldMeta('msProductData');
        foreach ($map as $field => $meta) {
            if ($meta['phptype'] == 'boolean') {
                switch ($field){
                    case 'new':
                    case 'favorite':
                    case 'popular':
                        break;
                    default:
                        $fields[] = $field;
                        break;
                }
            }
        }
        if ($fields) {
            $this->mspre->config['mspre_field_boolean'] = implode(',', $fields);
            return $this->prepareMenus('mspre_options_actions_boolean', 'icon icon-barcode', $this->optionsList('boolean', $menus));
        }
        return false;
    }

    protected function menuPrice($menus = array())
    {
        $menus = $this->prepareMenus('mspre_options_actions_price', 'icon icon-barcode', $this->optionsList('price', $menus));
        $menus['menu'][] = '-';
        $menus['menu'][] = array(
            'namecombo' => 'price_transfer',
            'icon' => 'icon icon-files-o',
            'title' => $this->modx->lexicon('mspre_action_transfer'),
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

return 'mspreActionsProduct';