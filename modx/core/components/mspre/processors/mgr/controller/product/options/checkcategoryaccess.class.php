<?php

class msPreOptionsAccessCategoryProcessor extends modProcessor
{
    /* @var msOption $Option */
    public $Option = null;
    /* @var modResource|null $resource */
    public $resource = null;

    /* @var msCategory|null $category */
    public $category = null;

    public $languageTopics = array('mspre:default');


    public function initialize()
    {

        $category = (int)$this->getProperty('category');
        $resource = (int)$this->getProperty('resource');
        if (empty($resource) and empty($category)) {
            return $this->modx->lexicon('mspre_err_access_category');
        }

        $option = $this->getProperty('option');
        if (empty($option)) {
            return $this->modx->lexicon('mspre_err_option');
        }

        if (!$key = prefixOptions($option)) {
            return $this->modx->lexicon('mspre_err_option_could_not_found_key', array('key' => $option));
        }
        if (!$this->Option = $this->modx->getObject('msOption', array('key' => $key))) {
            return $this->modx->lexicon('mspre_err_option_could_not_found', array('key' => $key));
        }

        if ($resource) {
            if (!$this->resource = $this->modx->getObject('modResource', $resource)) {
                return $this->modx->lexicon('mspre_err_tvname_could_not_resource');
            } else {
                $category = $this->resource->get('parent');
            }
        }

        if ($category) {
            if (!$this->category = $this->modx->getObject('msCategory', $category)) {
                return $this->modx->lexicon('mspre_err_resource_category', array('category' => $category));
            }
        }

        return true;
    }

    public function process()
    {
        $options = array();
        $access = false;
        $option_id = $this->Option->get('id');
        $category_id = $this->category->get('id');

        if ($object = $this->modx->getObject('msCategoryOption', array(
            'option_id' => $option_id,
            'category_id' => $this->category->get('id')
        ))) {

            if ($object->get('active')) {
                $access = true;
            } else {
                $options = array(
                    'action' => 'mgr/category/option/multiple',
                    'method' => 'activate',
                    'ids' => '[' . $this->modx->toJSON(array(
                            'option_id' => $option_id,
                            'category_id' => $category_id,
                        )) . ']'
                );
            }
        } else {
            $options = array(
                'action' => 'mgr/category/option/add',
                'option_id' => $option_id,
                'category_id' => $category_id,
                'value' => '',
                'active' => 1,
                'required' => 0,
            );
        }


        $options = $this->modx->toJSON($options);
        return $this->success('', array(
            'access' => $access,
            'category_id' => $this->category->get('id'),
            'category_name' => $this->category->get('pagetitle'),
            'option_key' => $this->Option->get('key'),
            'option_caption' => $this->Option->get('caption'),
            'options' => $options,
        ));
    }

}

return 'msPreOptionsAccessCategoryProcessor';