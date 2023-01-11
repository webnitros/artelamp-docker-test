<?php

/**
 * Multiple a msProduct
 */
class mspreTransactionsCanceledProcessor extends modProcessor
{
    public $classKey = 'msProduct';
    public $languageTopics = array('mspre');
    public function processUpdate($product_id, $data)
    {
        $data = array_merge(array(
            'ids' => $this->modx->toJSON(array($product_id))
        ), $data);


        /* @var modProcessorResponse $response */
        if ($response = $this->modx->runProcessor('controller/product/options/price', $data,
            array('processors_path' => MODX_CORE_PATH . 'components/mspre/processors/mgr/')
        )
        ) {
            if ($response->isError()) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error canceled operations " . print_r($response->getResponse(), 1));
                return false;
            }
            return true;
        }
        return false;
    }

    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));

        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mspre_error_ids'));
        }

        $i = 0;

        /* @var mspreTransactions $object */
        $q = $this->modx->newQuery('mspreTransactions');
        $q->where(array(
            'id:IN' => $ids
        ));
        if ($objectList = $this->modx->getCollection('mspreTransactions', $q)) {
            foreach ($objectList as $object) {
                $id = $object->get('id');
                $product_id = $object->get('product_id');
                $field = $object->get('field');
                $round = $object->get('round');
                $oldValue = $object->get('oldValue');


                $oldValue = (float)str_ireplace(',', '.', $oldValue);
                $data = array(
                    'field' => $field,
                    'round' => $round,
                    'increase' => 'new',
                    $field => $oldValue,
                    'enableTransition' => false,
                );

                $response = $this->processUpdate($product_id, $data);
                if ($response !== true) {
                    return $this->failure($this->modx->lexicon("Не удалось вернуть транзакцию {$id} товара {$product_id}. Подробней в журнале ошибок"));
                }
                // Если отмена операции прошла удачно то удаляем её
                $object->remove();
            }
        }


        return $this->success();
    }
}

return 'mspreTransactionsCanceledProcessor';
