<?php

interface mspreActionsInterface
{

    /**
     * Вернет меню
     * @param array $actions
     * @return array
     */
    public function getMenus($actions = array());

}

/**
 * The base class for Nsi.
 */
abstract class mspreActions implements mspreActionsInterface
{
    /* @var modX $modx */
    /* @var mspre $mspre */
    public $modx;
    public $mspre;
    public $actions = array();
    public $classKey = null;

    /**
     * @param mspre $mspre
     * @param array $config
     */
    function __construct(mspre &$mspre, array $config = array())
    {
        $this->mspre = $mspre;
        $this->modx =& $mspre->modx;
        $this->classKey =& $mspre->classKey;
    }

    /**
     * Загрузка действий
     * @param null|array $exclude исключаемые действия для одинаковых меню
     * @return array
     */
    public function excludeAction($exclude = null)
    {
        $actions = $this->getMenus();
        if ($exclude and count($exclude) > 0) {
            foreach ($actions as $i => $action) {
                if (isset($action['namecombo'])) {
                    if ($exclude) {
                        $namecombo = $action['namecombo'];
                        if (in_array($namecombo, $exclude)) {
                            unset($actions[$i]);
                        }
                    }
                }
            }
        }
        $actions = array_values($actions);
        return $actions;
    }

    /*
     * Действия для обновления опций
     * @return array Fields Massiv Actions
     * */
    public function action($action)
    {
        return $this->mspre->config['controllerPath'] . $action;
    }

    /*
     * Действия для обновления опций
     * @return array Fields Massiv Actions
     * */
    protected function setActions($actions)
    {
        $this->actions[] = $actions;
    }

    /**
     * @param $title
     * @param null $icon
     * @param $menus
     * @param string $cls
     * @param boolean $list
     * @return array
     */
    protected function prepareMenus($title, $icon = null, $menus, $cls = '', $list = false)
    {
        // load lexicon
        foreach ($menus as $k => $menu) {
            $namecombo = $menu['namecombo'];
            $menu['title'] = isset($menu['title']) ? $menu['title'] : $this->modx->lexicon('mspre_combo_' . $namecombo . '_title');
            $menu['menu'] = isset($menu['menu']) ? $menu['menu'] : true;
            $menu['cls'] = isset($menu['cls']) ? $menu['cls'] : true;
            if (isset($menu['field_params'])) {
                $fields = $menu['field_params'];
                $fields['xtype'] = isset($fields['xtype']) ? $fields['xtype'] : 'textfield';
                $fields['name'] = isset($fields['name']) ? $fields['name'] : 'value';
                $fields['hiddenName'] = isset($fields['hiddenName']) ? $fields['hiddenName'] : 'value';
                $fields['emptyText'] = isset($fields['emptyText']) ? $fields['emptyText'] : '';
                $fields['label'] = isset($fields['label']) ? $fields['label'] : $this->modx->lexicon('mspre_combo_' . $namecombo . '_label');
                $fields['title'] = isset($fields['title']) ? $fields['title'] : $this->modx->lexicon('mspre_combo_' . $namecombo . '_title');
                $fields['emptyText'] = isset($fields['emptyText']) ? $fields['emptyText'] : $this->modx->lexicon('mspre_combo_' . $namecombo . '_select');
                if (isset($fields['fields'])) {
                    $fields['fields'][] = array(
                        'xtype' => 'hidden',
                        'name' => 'ids',
                    );
                }
                $menu['field_params'] = $fields;
            }
            $menus[$k] = $menu;
        }

        if ($list) {
            return $menus;
        }
        return array(
            'cls' => $cls,
            'icon' => $icon,
            'title' => $this->modx->lexicon($title),
            'menu' => $menus,
        );
    }

    /**
     * Вернет меню для опций
     * @param array $menus
     * @return array
     */
    protected function optionsList($name, $menus = array(), $prefix = null, $key = null)
    {
        $key = !$key ? 'mspre_field_' . $name : $key;
        $fields = trim($this->mspre->getOption($key, null, ''));
        if (!empty($fields)) {
            $fun = 'field' . ucfirst($name);
            if ($prefix) {
                $fun .= ucfirst($prefix);
            }
            $fields = array_map('trim', explode(',', $fields));

            foreach ($fields as $field) {
                $menus[] = $this->{$fun}($field);
            }
        }
        return $menus;
    }

    /**
     * @param $name
     * @return null|string
     */
    protected function lexicon($name)
    {
        $value = $this->modx->lexicon($name);
        return $value;
    }


    /* Вернет массив для меню определенного типа */
    protected function fieldString($field)
    {
        $default = array(
            'namecombo' => $field,
            'icon' => 'icon icon-stream',
            'title' => $this->modx->lexicon('mspre_action_update_' . $field),
            'action' => 'defaultCombo',
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'baseParams' => array(
                    'action' => $this->action('options/string')
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
                        'value' => $field,
                    ),
                    2 => array(
                        'xtype' => 'textfield',
                        'fieldLabel' => $this->modx->lexicon('mspre_new_value'),
                        'name' => $field,
                        'hiddenName' => $field,
                        'anchor' => '90%',
                        'allowBlank' => true,
                    )
                ),
            ),
        );

        return $default;
    }

    protected function fieldWeight($field)
    {
        $default = array(
            'namecombo' => $field,
            'icon' => 'icon icon-balance-scale',
            'title' => $this->modx->lexicon('mspre_action_update_' . $field),
            'action' => 'defaultCombo',
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'baseParams' => array(
                    'action' => $this->action('options/weight')
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
                        'value' => $field,
                    ),
                    2 => array(
                        'xtype' => 'numberfield',
                        'decimalPrecision' => 3,
                        'fieldLabel' => $this->modx->lexicon('mspre_new_value'),
                        'name' => $field,
                        'hiddenName' => $field,
                        'anchor' => '90%',
                        'allowBlank' => true,
                    )
                ),
            ),
        );
        return $default;
    }

    protected function fieldPrice($field)
    {
        $default = array(
            'namecombo' => $field,
            'icon' => 'icon icon-money',
            'title' => $this->modx->lexicon('mspre_action_update_' . $field),
            'action' => 'defaultCombo',
            'combo_id' => 'mspre-window-update-default-combo',
            'field_params' => array(
                'baseParams' => array(
                    'action' => $this->action('options/price')
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
                        'value' => $field,
                    ),
                    2 => array(
                        'xtype' => 'numberfield',
                        'decimalPrecision' => 2,
                        'fieldLabel' => $this->modx->lexicon('mspre_' . $field),
                        'name' => $field,
                        'hiddenName' => $field,
                        'anchor' => '90%',
                        'allowBlank' => true,
                        'enableAutoComplete' => true,
                    ),
                    3 => array(
                        'xtype' => 'mspre-combo-price-increase',
                        'name' => 'increase',
                        'hiddenName' => 'increase',
                        'anchor' => '90%',
                        'allowBlank' => false,
                    ),
                    4 => array(
                        'xtype' => 'mspre-combo-price-round',
                        'name' => 'round',
                        'hiddenName' => 'round',
                        'anchor' => '90%',
                        'allowBlank' => true,
                    )
                ),
            )
        );
        return $default;
    }

    protected function fieldBoolean($field, $icon = null, $firstIcon = null, $lastIcon = null, $progress = true)
    {
        $key_text = 'mspre_action_' . $field;
        $key_text_un = 'mspre_action_un' . $field;

        $title = $this->modx->lexicon($key_text);
        if ($title == $key_text) {
            $title = $this->modx->lexicon('mspre_action_boolean_default');
        }


        $title_un = $this->modx->lexicon($key_text_un);
        if ($title_un == $key_text_un) {
            $title_un = $this->modx->lexicon('mspre_action_boolean_default_un');
        }


        $title_menu_key = 'mspre_menu_' . $field;
        $title_menu = $this->modx->lexicon($title_menu_key);
        if ($title_menu == $title_menu_key) {
            $title_menu = $field;
        }

        $this->modx->lexicon('mspre_action_un' . $field);
        return array(
            'cls' => '',
            'icon' => $icon,
            'title' => $title_menu,
            'menu' => array(
                0 => array(
                    'menu' => true,
                    'icon' => $firstIcon,
                    'title' => $title,
                    'action' => $progress ? 'setPropertyMassivProgress' : 'setPropertyMassiv',
                    'field_name' => $field,
                    'field_value' => '1',
                ),
                1 => array(
                    'menu' => true,
                    'icon' => $lastIcon,
                    'title' => $title_un,
                    'action' => $progress ? 'setPropertyMassivProgress' : 'setPropertyMassiv',
                    'field_name' => $field,
                    'field_value' => '0',
                )
            ),
        );
    }
}