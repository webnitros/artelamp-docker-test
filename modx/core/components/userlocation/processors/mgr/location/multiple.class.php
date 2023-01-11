<?php

/**
 * Multiple a ulLocation
 */
class ulLocationMultipleProcessor extends modProcessor
{
    public $classKey = 'ulLocation';

    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);

        if (!empty($ids)) {
            foreach ($ids as $id) {
                if (!empty($id)) {
                    if ($response = $this->modx->runProcessor($method,
                        [
                            'id'          => $id,
                            'field_name'  => $this->getProperty('field_name', null),
                            'field_value' => $this->getProperty('field_value', null),
                        ],
                        ['processors_path' => dirname(__FILE__).'/']
                    )
                    ) {
                        if ($response->isError()) {
                            return $response->getResponse();
                        }
                    }
                }
            }
        } elseif ($this->getProperty('field_name') == 'false') {
            if ($response = $this->modx->runProcessor($method,
                [],
                ['processors_path' => dirname(__FILE__).'/']
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

return 'ulLocationMultipleProcessor';