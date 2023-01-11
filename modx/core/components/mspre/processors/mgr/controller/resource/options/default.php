<?php

/**
 * Multiple a modResource
 */
abstract class modmodResourceMultipleUpdateOptionsDefaultProcessor extends modProcessor
{
    public $classKey = 'modResource';
    public $fieldUpdate = null;
    public $languageTopics = array('mspre:default');

    /* @var boolean $enableTransition */
    protected $enableTransition = true;

    /* @var array|null $transaction */
    protected $transaction = null;

    
    public function initialize()
    {
        $field = $this->getProperty('field', '');
        if (empty($field)) {
            return $this->modx->lexicon('mspre_err_field');
        }
        $enableTransition = $this->getProperty('enableTransition', true);
        $this->enableTransition = $enableTransition;
        $this->fieldUpdate = $field;

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->modx->lexicon('mspre_err_ids');
        }
        return true;
    }


    /**
     * @param int $id
     * @return array|null
     */
    public function preapreData($id)
    {
        return null;
    }


    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (!empty($ids)) {
            $i = 0;
            foreach ($ids as $id) {
                $i++;

                $id = (int)$id;

                if (empty($id)) {
                    return $this->failure($this->modx->lexicon('mspre_err_id'));
                }


                $value = $this->preapreData($id);
                if (is_null($value)) {
                    return $this->failure($this->modx->lexicon('mspre_err_data'));
                }

                $data = array(
                    $this->fieldUpdate => $value
                );

                if ($response = $this->modx->runProcessor('update',
                    array_merge(array(
                        'id' => $id
                    ), $data),
                    array('processors_path' => dirname(dirname(__FILE__)) . '/')
                )
                ) {
                    if ($response->isError()) {
                        return $response->getResponse();
                    }
                }

                if ($this->enableTransition) {
                    $this->setTransaction($id);
                }
            }
        }
        return $this->success();
    }

    /**
     * Вернет значение поля
     * @param mixed $default
     * @return mixed
     */
    public function getValue($default = null)
    {
        return $this->getProperty($this->fieldUpdate, $default);
    }

    /**
     * Запись транзакции
     * @param int $id
     * @return mixed
     */
    public function setTransaction($id)
    {
        /* @var mspreTransactions $object*/
        $object = $this->modx->newObject('mspreTransactions');
        $object->set('product_id',$id);
        $object->fromArray($this->transaction);

        $this->transaction = null;
        return $object->save();
    }
}