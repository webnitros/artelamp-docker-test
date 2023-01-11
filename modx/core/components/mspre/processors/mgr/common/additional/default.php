<?php

abstract class modmodResourceMultipleUpdateAdditionalDefaultProcessor extends modProcessor
{
    public $classKey = 'modResource';
    public $fieldUpdate = null;
    public $languageTopics = array('mspre:default');

    public function initialize()
    {
        $this->classKey = $this->getProperty('class_key');
        $field = $this->getProperty('field', '');
        if (empty($field)) {
            return $this->modx->lexicon('mspre_err_field');
        }
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
        return $this->getValue();
    }


    public function process()
    {
        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        $processor = null;
        switch ($this->classKey) {
            case 'modResource':
                $processor = dirname(dirname(dirname(__FILE__))) . '/controller/resource/';
                break;
            case 'msProduct':
                $processor = dirname(dirname(dirname(__FILE__))) . '/controller/product/';
                break;
            default:
                break;
        }

        if (empty($processor)) {
            return $this->failure($this->modx->lexicon('mspre_error_processor'));
        }

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
                    array('processors_path' => $processor)
                )
                ) {
                    if ($response->isError()) {
                        $this->modx->log(modX::LOG_LEVEL_ERROR, "Error " . print_r($response->getResponse(), 1), '', __METHOD__, __FILE__, __LINE__);

                        return $response->getResponse();
                    }
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
}