<?php

/**
 * Multiple a msProduct
 */
abstract class modmsProductMultipleUpdateOptionsDefaultProcessor extends modProcessor
{
    public $classKey = 'msProduct';
    public $fieldUpdate = null;
    public $fieldGet = null;
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

    /**
     * @return array|string
     */
    public function accessCategory()
    {

        /* @var mspre $mspre */
        $mspre = $this->modx->getService('mspre', 'mspre', MODX_CORE_PATH . 'components/mspre/model/');
        if (!$field = prefixOptions($this->fieldUpdate)) {
            return true;
        }


        $ids = $this->modx->fromJSON($this->getProperty('ids'));

        $offset_resource = $this->setCheckbox('offset_resource');


        $newIds = array();

        /* @var msProduct $object */
        $q = $this->modx->newQuery('msProduct');
        $q->where(array(
            'id:IN' => $ids
        ));
        if ($objectList = $this->modx->getCollection('msProduct', $q)) {
            foreach ($objectList as $object) {

                $response = $mspre->categoryAccessCheck($object, $field);
                if ($response !== true) {
                    // Не возвращаем информацию о ошибках
                    if (!$offset_resource) {
                        return $this->failure($response);
                    }
                } else {
                    $newIds[] = $object->get('id');
                }
            }
        }

        $this->setProperty('ids', $this->modx->toJSON($newIds));

        return true;
    }


    public function process()
    {

        $response = $this->accessCategory();
        if ($response !== true) {
            return $response;
        }

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

                $this->modx->error->message = null;
                $this->modx->error->errors = array();
                
                // Путь к процессору должен быть везде индентичным иначе класс будет инициализировать по новой
                $processors_path = MODX_CORE_PATH.'components/mspre/processors/mgr/controller/product/';

                if ($response = $this->modx->runProcessor('update',
                    array_merge(array(
                        'id' => $id
                    ), $data),
                    array(
                        'processors_path' => $processors_path
                    )
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
        /* @var mspreTransactions $object */
        $object = $this->modx->newObject('mspreTransactions');
        $object->set('product_id', $id);
        $object->fromArray($this->transaction);

        $this->transaction = null;
        return $object->save();
    }

    public $isArray = true;

    /**
     * Получение значения строкового или числового
     * @return bool
     */
    public function getNewString()
    {
        // Для одиночных значений
        $field = $this->getProperty('field');

        $new_value = $this->getProperty($field);
        $this->setProperty('new_value', $new_value);
        $this->unsetProperty($field);
        if (empty($new_value)) {
            return false;
        }

        $value = prefixOptions($field);
        $this->fieldGet = $field;
        $this->isArray = false;
        return true;
    }

}