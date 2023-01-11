<?php

class MyController1cOrderRemove extends modRestController
{
    /* @inheritdoc */
    public function get()
    {
        $this->failure('POST запрос');
    }

    /* @inheritdoc */
    public function post()
    {
        $order_1c_id = $this->getProperty('order_1c_id');
        if (empty($order_1c_id)) {
            $this->failure('Не указан UUID заказа');
        } else {
            /* @var msOrder $object */
            if ($object = $this->modx->getObject('msOrder', ['operation_uuid' => $order_1c_id])) {
                $object->remove();
            }
            $this->success('');
        }
    }

}
