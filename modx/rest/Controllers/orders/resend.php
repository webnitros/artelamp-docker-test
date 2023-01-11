<?php

/**
 * Повторная отправка письма на емаил
 */
class MyControllerOrdersReSend extends ApiInterface
{
    public $protected = false;

    public function post()
    {

        $token = $this->getProperty('token');
        if ($token != 'token') {
            return $this->failure('Не верный токен');
        }

        $order_id = $this->getProperty('order_id');
        if (empty($order_id)) {
            return $this->failure('не переда id заказа');
        }


        /* @var msOrder $object */
        if (!$Order = $this->modx->getObject('msOrder', ['num' => $order_id])) {
            return $this->failure('Заказ не найден');
        }

        $num = $Order->get('num');
        $bxSender = $this->modx->getService('bxsender', 'bxSender', MODX_CORE_PATH . 'components/bxsender/model/');

        $email = 'info@fandeco.ru';
        #$email = getenv('ENV') === 'production' ? 'info@fandeco.ru' : 'info@bustep.ru';

        $q = $this->modx->newQuery('bxQueue');
        $q->where(array(
            'email_subject:LIKE' => '%' . $num . '%',
            'email_to' => $email,
        ));

        /* @var bxQueue $Queue */
        if (!$Queue = $this->modx->getObject('bxQueue', $q)) {
            return $this->failure('Письмо для заказа не найдено №' . $num);
        }


        $data = [
            'id' => $Queue->get('id')
        ];

        $email = $this->getProperty('email');
        if (!empty($email)) {
            $data['email'] = $email;
        }

        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor('queue/action/send', $data, array(
            'processors_path' => MODX_CORE_PATH . 'components/bxsender/processors/mgr/'
        ));
        if ($response->isError()) {
            return $this->failure($response->getMessage());
        }

        return $this->success();
    }

}
