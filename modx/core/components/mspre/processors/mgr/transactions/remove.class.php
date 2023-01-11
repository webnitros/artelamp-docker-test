<?php

/**
 * Multiple a msProduct
 */
class mspreTransactionsRemoveProcessor extends modProcessor
{
    public $languageTopics = array('mspre');
    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mspre_error_ids'));
        }


        /* @var mspreTransactions $object */
        $q = $this->modx->newQuery('mspreTransactions');
        $q->where(array(
            'id:IN' => $ids
        ));
        if ($objectList = $this->modx->getCollection('mspreTransactions', $q)) {
            foreach ($objectList as $object) {
                $object->remove();
            }
        }

        return $this->success();
    }
}

return 'mspreTransactionsRemoveProcessor';
