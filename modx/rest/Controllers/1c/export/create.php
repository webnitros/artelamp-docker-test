<?php

class MyController1cOrderCreate extends modRestController
{
    /* @inheritdoc */
    public function get()
    {
        $this->post();
    }

    /* @inheritdoc */
    public function post()
    {
        $properties = $this->getProperties();

        #$this->modx->log(modX::LOG_LEVEL_ERROR, "LOG ".print_r($properties,1), '', __METHOD__, __FILE__, __LINE__);
        $response = false;
        if ($this->getProperty('api_key') !== '8c84-11eb-8185-0052') {
            $this->failure('api key not correct');
            return false;
        }

        include_once MODX_CORE_PATH . 'classes/fdkkassa.php';

        /* @var fdkKassa $fdkKassa */
        $fdkKassa = new fdkkassa($this->modx);

        echo '<pre>';
        print_r(get_class($fdkKassa));
        die;

        /* @var modUser $object */
        if ($object = $this->modx->getObject('modUser', 2)) {
            $this->modx->user = $object;
        }

        if ($fdkKassa instanceof fdkKassa) {
            $response = $fdkKassa->createOrder($properties);
        }

        ##$this->modx->log(modX::LOG_LEVEL_ERROR, "properties ".print_r($properties,1), '', __METHOD__, __FILE__, __LINE__);
        #$this->modx->log(modX::LOG_LEVEL_ERROR, "LOG ".print_r($response,1), '', __METHOD__, __FILE__, __LINE__);

        if ($response['success'] === true) {
            $this->success($response['message'], $response['data']);
        } else {
            $this->failure($response['message'], $response['data']);
        }
    }

}
