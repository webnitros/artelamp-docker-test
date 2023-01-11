<?php
class modmsProductAddMultipleProcessor extends modObjectProcessor {
    public $classKey = 'msProduct';
    public $objectType = 'ms2_option';
    public $languageTopics = array('tenders:default');

    /** @var  mtCategory */
    public $object;
    /** @var msProduct */
    public $option;

    public function process() {

        $optionIds = $this->getProperty('categorys');
        if (empty($optionIds)) {
            return $this->failure($this->modx->lexicon($this->objectType.'_err_ns'));
        }

        $categoryIds = $this->getProperty('categories');
        if (empty($categoryIds)) {
            return $this->failure($this->modx->lexicon('ms2_category_err_ns'));
        }

        $options = explode(',',$optionIds);
        $categories = $this->modx->fromJSON($categoryIds);
        foreach ($options as $id) {
            if (!empty($id)) {
                /* @var msProduct $object */
                if($object = $this->modx->getObject('msProduct', $id)){
                    $old_category = array();
                    if ($Data = $object->loadData()) {
                        $old_category = $Data->get('categories');
                    }
                    if (is_array($old_category) and count($old_category) > 0) {
                        $newCategories = array_merge($old_category,$categories);
                        $newCategories = array_filter($newCategories);
                        $newCategories = array_unique($newCategories);
                    } else {
                        $newCategories = $categories;
                    }

                    $object->set('categories', $newCategories);
                    $object->save();
                }

            }
        }
        return $this->success();
    }

}

return 'modmsProductAddMultipleProcessor';
