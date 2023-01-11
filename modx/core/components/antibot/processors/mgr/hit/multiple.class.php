<?php

class antiBotMultipleProcessor extends modProcessor
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

        /** @var antiBot $antiBot */
        $antiBot = $this->modx->getService('antiBot');
        foreach ($ids as $id) {
            /** @var modProcessorResponse $response */
            $response = $this->modx->runProcessor('mgr/hit/' . $method, array('id' => $id), array(
                'processors_path' => MODX_CORE_PATH . 'components/antibot/processors/mgr/'
            ));
            if ($response->isError()) {
                return $response->getResponse();
            }
        }

        return $this->success();
    }


}

return 'antiBotMultipleProcessor';