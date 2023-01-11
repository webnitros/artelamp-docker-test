<?php

class msPreEnteredOptionsProcessor extends modObjectProcessor
{
    /* @var msOption $Option */
    public $Option = null;

    public function process()
    {

        $key = $this->getProperty('key');
        $product_id = trim($this->getProperty('product_id'));

        /* @var msProduct $Product $Product */
        if (!$Product = $this->modx->getObject('msProduct', $product_id)) {
            return $this->failure($this->modx->lexicon('mspre_options_product_error', array('key' => $key, 'product_id' => $product_id)));
        }

        // Только для полей с без префикса
        if (!$newkey = prefixOptions($key)) {
            $values = $Product->get($key);
            if (is_array($values)) {
                $values = $this->prepareValues($values);
            }
            $values = array(
                'fieldLabel' => $this->modx->lexicon('mspre_values'),
                'anchor' => '99%',
                'ids' => $this->modx->toJSON(array($product_id)),
                'name' => $key,
                'field' => $key,
                'hiddenName' => 'new_value[]',
                'value' => $values,
                'ext_field' => "{xtype:'minishop2-combo-options'}",
            );
            return $this->success('', $values);
        }

        $key = prefixOptions($key);
        $response = $this->xtype($Product, $key);


        if ($response['success'] !== true) {
            return $response;
        }
        return $response;
    }

    /**
     * @param msProduct $Product
     * @param $key
     * @return array|string
     */
    public function xtype($Product, $key)
    {
        $product_id = trim($this->getProperty('product_id'));
        if ($this->Option = $this->modx->getObject('msOption', array('key' => $key))) {
            $field = null;

            /* @var msCategory $Category */
            if ($Category = $Product->getOne('Category')) {
                if (!$categoryBinding = $this->categoryBindingCheck(array(
                    'option_id' => $this->Option->get('id'),
                    'category_id' => $Category->get('id'),
                    'active' => 1,
                ))) {
                    return $this->failure($this->modx->lexicon('mspre_options_category_link_error', array('product_id' => $product_id, 'key' => $key, 'category_id' => $Category->get('id'), 'category_name' => $Category->get('pagetitle'))));
                }
            } else {
                return $this->failure($this->modx->lexicon('mspre_options_category_error', array('category_id' => $Product->get('parent'), 'product_id' => $product_id, 'category_name' => $Category->get('pagetitle'))));
            }


            $possible = null;
            $properties = $this->Option->get('properties');
            if (isset($properties['values'])) {
                $possible = $properties['values'];
            }


            $values = null;
            $criteria = array(
                'product_id' => $product_id,
                'key' => $this->Option->get('key'),
            );


            switch ($this->Option->get('type')) {
                case 'combo-options':
                case 'combo-multiple':
                    /* @var msProductOption $object */
                    $q = $this->modx->newQuery('msProductOption');
                    $q->where($criteria);
                    if ($objectList = $this->modx->getCollection('msProductOption', $q)) {
                        foreach ($objectList as $ProductOption) {
                            $values[] = $ProductOption->get('value');
                        }
                    }
                    break;
                default:
                    if ($ProductOption = $this->modx->getObject('msProductOption', $criteria)) {
                        $values = $ProductOption->get('value');
                    }
                    break;
            }


            /* if (is_array($values)) {
                 foreach ($values as $id => $value) {
                     $values[$id] = array('value' => $value);
                 }
             }*/


            if (is_array($values)) {
                $values = $this->prepareValues($values);
            }


            $ext_field = $this->Option->getManagerField($this->Option->toArray());


            $hiddenName = is_array($values) ? 'new_value[]' : 'new_value';


            $keyValue = 'value';
            if ($this->Option->get('type') == 'checkbox') {
                $keyValue = 'checked';
            }


            $prefix = prefixOptionsAdd($key);
            $values = array(
                'fieldLabel' => $this->modx->lexicon('mspre_values'),
                'anchor' => '99%',
                'ids' => $this->modx->toJSON(array($product_id)),
                'name' => $prefix,
                'field' => $prefix,
                'hiddenName' => $hiddenName,
                $keyValue => $values,
                'ext_field' => $ext_field,
            );


            if ('textarea' == $this->Option->get('type')) {
                $values['width'] = 700;
                $values['maxWidth'] = 700;
                $values['height'] = 300;
                $values['maxHeight'] = 700;
            }

            return $this->success('', $values);
        }
        return $this->failure($this->modx->lexicon('mspre_options_type_error', array('key' => $key)));
    }


    /**
     * @param array $criteria
     * @return msCategoryOption|bool
     */
    public function categoryBindingCheck(array $criteria = array())
    {
        if ($object = $this->modx->getObject('msCategoryOption', $criteria)) {
            return $object;
        }
        return false;
    }

    public function prepareValues($values, $query = '')
    {
        if ($words = array_diff(array_map('trim', explode('|', $query)), array(''))) {
            $search = array();
            foreach ($words as $word) {
                $s = preg_quote($word, '\\');
                $found = preg_grep("!{$s}!usi", $values);
                if (is_array($found) && !preg_grep("!^{$s}$!si", $found)) {
                    array_unshift($found, $word);
                }
                $search = $found ? array_merge($search, $found) : $search;
            }
            $values = $search;
        }

        $values = array_keys(array_flip($values));
        $values = array_diff($values, array(''));
        foreach ($values as $id => $value) {
            $values[$id] = array('value' => $value);
        }

        return $values;
    }
}

return 'msPreEnteredOptionsProcessor';