<?php

/**
 * Multiple a modResource
 */
abstract class msPreModProcessor extends modProcessor
{
    public $languageTopics = array('mspre:default');

    public function multiple($data = array())
    {
        if (!$classKey = $this->getProperty('class_key', false)) {
            return $this->failure('class_key не указан');
        }
        $processor = null;
        switch ($classKey) {
            case 'msProduct':
                $processor = MODX_CORE_PATH . 'components/mspre/processors/mgr/controller/product/';
                break;
            case 'modResource':
                $processor = MODX_CORE_PATH . 'components/mspre/processors/mgr/controller/resource/';
                break;
            default:
                break;
        }


        if (empty($processor)) {
            return $this->failure($this->modx->lexicon('mspre_error_processor'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('mspre_error_ids'));
        }
        if (empty($data)) {
            return $this->failure($this->modx->lexicon('mspre_error_data'));
        }
        $i = 0;
        foreach ($ids as $id) {
            $i++;
            if (!empty($id)) {
                $this->modx->error->message = null;
                $this->modx->error->errors = array();


                if ($response = $this->modx->runProcessor('update', array_merge(array(
                    'id' => $id
                ), $data),
                    array('processors_path' => $processor))
                ) {
                    if ($response->isError()) {
                        return $response->getResponse();
                    }
                }
            }
        }

        return $this->success();
    }

    public $processor = null;

    /**
     * @return bool|string
     */
    public function getProcossor()
    {
        if (!$classKey = $this->getProperty('class_key', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_class_key'));
        }
        $processor = null;
        switch ($classKey) {
            case 'msProduct':
                $processor = dirname(dirname(dirname(__FILE__))) . '/controller/product/';
                break;
            case 'modResource':
                $processor = dirname(dirname(dirname(__FILE__))) . '/controller/resource/';
                break;
            default:
                break;
        }


        if (empty($processor)) {
            return $this->failure($this->modx->lexicon('mspre_error_processor'));
        }

        $this->processor = $processor;
        return true;
    }
}
