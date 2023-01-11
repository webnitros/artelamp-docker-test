<?php

class ReadLogJsonMultipleProcessor extends modProcessor
{


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$method = $this->getProperty('method', false)) {
            return $this->failure();
        }
        $ids = json_decode($this->getProperty('ids'), true);
        if (empty($ids)) {
            return $this->success();
        }

        /** @var ReadLogJson $ReadLogJson */
        $ReadLogJson = $this->modx->getService('ReadLogJson');
        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $ReadLogJson->runProcessor('mgr/request/' . $method, array('id' => $id), array(
                'processors_path' => MODX_CORE_PATH . 'components/readlogjson/processors/mgr/'
            ));
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }


}

return 'ReadLogJsonMultipleProcessor';
