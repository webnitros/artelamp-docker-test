<?php
class modmsProductUnCategoriesMultipleProcessor extends modObjectProcessor {
    public function process() {

        $id = $this->getProperty('id');
        if (empty($id)) {
            return $this->modx->lexicon($this->objectType.'_err_ns');
        }
        if (!empty($id)) {
            $this->modx->removeCollection('msCategoryMember', array('product_id' => $id));
        }
        return $this->success();
    }

}

return 'modmsProductUnCategoriesMultipleProcessor';
