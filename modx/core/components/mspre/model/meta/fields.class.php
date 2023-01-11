<?php
include_once dirname(__FILE__) . '/default.php';

/**
 * The base class for Nsi.
 */
class mspreMetaFields extends mspreMeta
{
    public $prefix = 'fields';

    /**
     * @return bool
     */
    public function initialize()
    {
        return parent::initialize();
    }

    /**
     * @return array|null
     */
    public function loadFields()
    {
        if (is_null($this->fields)) {
            $this->fields = array();
            $fields = $this->modx->getFieldMeta($this->mspre->classKey);
            foreach ($fields as $field => $meta) {
                $key = $this->getPrefix($field);
                $this->fields[$key] = $field;
            }

            if ($this->mspre->controller == 'product') {
                $fields = $this->modx->getFieldMeta('msProductData');
                foreach ($fields as $field => $meta) {
                    $key = $this->getPrefix($field);
                    $this->fields[$key] = $field;
                }
            }
            $this->fields['fields-actions'] = 'actions';
            $this->fields['fields-product_link'] = 'product_link';
            $this->fields['fields-additional_categories'] = 'additional_categories';
            $this->fields['fields-category_name'] = 'category_name';
        }
        return $this->fields;
    }

    /**
     * Загрузка типов
     * @return array|null
     */
    public function loadTypes()
    {
        if (is_null($this->types)) {
            $this->types = $this->mspre->loadData($this->prefix);
            foreach ($this->fields as $key => $field) {
                if (!isset($this->types[$field])) {
                    $this->types[$field] = array(
                        'id' => $field,
                        'dataIndex' => $field,
                        'sortable' => false,
                        'editor' => false,
                        'actions' => false
                    );
                }
            }
        }
        return $this->types;
    }

}

return 'mspreMetaFields';