<?php

/**
 * Multiple a msProduct
 */
abstract class modmsMultipleDefaultProcessor extends modProcessor
{
    public $classKey = false;
    public $languageTopics = array('mspre');
    public $processors_path = null;

    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure($this->modx->lexicon('mspre_error_method'));
        }

        if (!$classKey = $this->getProperty('classKey', $this->classKey)) {
            return $this->failure($this->modx->lexicon('mspre_error_class_key'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        $processor = dirname(__FILE__) . '/common/';
        $defaultProccessor = array(
            'publish',
            'unpublish',
            'delete',
            'undelete'
        );
        if (in_array($method, $defaultProccessor)) {
            switch ($classKey) {
                case 'modResource':
                    $processor = MODX_CORE_PATH . 'model/modx/processors/resource/';
                    break;
                case 'msProduct':
                    $processor = MODX_CORE_PATH . 'components/minishop2/processors/mgr/product/';
                    break;
                default:
                    break;
            }
        }

        if ($this->processors_path) {
            $processor = $this->processors_path;
        }
        if (!empty($ids)) {
            $i = 0;
            if (!is_array($ids)) {
                $ids = array($ids);
            }
            foreach ($ids as $id) {
                $i++;

                $id = (int)$id;
                if (!empty($id)) {
                    /* @var modProcessorResponse $response */
                    if ($response = $this->modx->runProcessor($method,
                        array(
                            'id' => $id,
                            'class_key' => $classKey,
                            'field_name' => $this->getProperty('field_name', null),
                            'field_value' => $this->getProperty('field_value', null),
                            'field_replace' => $this->getProperty('field_replace', null)
                        ),
                        array('processors_path' => $processor)
                    )
                    ) {
                        if ($response->isError()) {
                            $errors = json_encode($response->getAllErrors(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                            return $this->failure($this->modx->lexicon('mspre_err_multisave', array('id' => $id, 'errors' => $errors)), $response->getAllErrors());
                        }
                    }

                }
            }
        } else if ($this->getProperty('field_name') == 'false') {
            if ($response = $this->modx->runProcessor($method,
                array(),
                array('processors_path' => dirname(__FILE__) . '/')
            )
            ) {
                if ($response->isError()) {
                    return $response->getResponse();
                }
            }
        }

        return $this->success();
    }
}