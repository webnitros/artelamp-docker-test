<?php

class MyController1cOrderStatus extends modRestController
{
    /* @inheritdoc */
    public function get()
    {
        $this->failure('GET запросы запрещены');
    }

    /* @inheritdoc */
    public function post()
    {
        /* @var fdkKassa $fdkKassa */
        $fdkKassa = $this->modx->getService('fdkkassa', 'fdkKassa', $this->modx->getOption('fdkkassa_core_path', [], $this->modx->getOption('core_path') . 'components/fdkkassa/') . 'model/');
        if (!$fdkKassa instanceof fdkKassa) {
            $this->failure('Произошла ошибка');
            return false;
        }

        $order_1c_id = $this->getProperty('order_1c_id');
        if (empty($order_1c_id)) {
            $this->failure('Не передан order_1c_id заказа');
            return false;
        } else {
            /* @var xPDOObject|msOrder $Order */
            if ($Order = $this->modx->getObject('msOrder', ['operation_uuid' => $order_1c_id])) {
                $date_payment = '';
                $order_id = $Order->get('id');
                if ($Log = $Order->getOne('Log', ['order_id' => $order_id, 'action' => 'status', 'entry' => 2])) {
                    $date_payment = date(DATE_ATOM, strtotime($Log->get('timestamp')));
                }

                $this->success('', [
                    'status' => $Order->get('id'),
                    'date_payment' => $date_payment,
                    'status_name' => ($tmp = $Order->getOne('Status')) ? $tmp->get('name') : '',
                ]);
            } else {
                $this->failure('Заказ с номером ' . $order_1c_id . ' не найден');
                return false;
            }
        }
    }

}
