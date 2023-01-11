<?php

abstract class msOneClickFormDefaultProcessor extends modProcessor
{
    /** @var  msOneClick $ms */
    protected $ms;
    /** @var  miniShop2 $ms2 */
    protected $ms2;

    /* @var msProductData $msProduct */
    protected $product = null;


    /* @var msPayment $payment */
    protected $payment = null;


    /* @var msDelivery $payment */
    protected $delivery = null;


    /**
     * Проверка действий для интерет магазина
     * @param string $ctx
     * @return bool|null|string
     */
    public function actionCheckMinishop2()
    {
        $product_id = $this->getProperty('product_id');
        if (!is_numeric($product_id) or empty($product_id)) {
            return $this->modx->lexicon('msoc_err_ms2_id_product_valid', array('id' => $product_id));
        }

        $res = $this->getPayment();
        if ($res !== true) {
            return $res;
        }

        $res = $this->getDelivery();
        if ($res !== true) {
            return $res;
        }

        $res = $this->getProduct();
        if ($res !== true) {
            return $res;
        }

        $payment_id = $this->getProperty('payment');
        $delivery_id = $this->getProperty('delivery');

        /** @var msDeliveryMember $member */
        if (!$member = $this->modx->getObject('msDeliveryMember', array('payment_id' => $payment_id, 'delivery_id' => $delivery_id))) {
            return $this->modx->lexicon('msoc_err_payment_delivery', array('payment_id' => $payment_id, 'delivery_id' => $delivery_id));
        }

        return true;
    }

    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->ms = $this->modx->getService('msoneclick', 'msOneClick', $this->modx->getOption('msoneclick_core_path', null, $this->modx->getOption('core_path') . 'components/msoneclick/') . 'model/msoneclick/', array())) {
            return 'Could not load msOneClick class!';
        }

        $ctx = $this->getProperty('ctx');
        $this->ms2 = $this->modx->getService('miniShop2');
        $this->ms2->initialize($ctx);

        $this->modx->lexicon->load('minishop2:default');
        $this->modx->lexicon->load('minishop2:product');


        if (!$this->getSession()) {
            $mes = $this->modx->lexicon('msoc_err_session', array('hash' => $this->getProperty('hash')));
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": " . $mes);
            return $mes;
        }

        $method = $this->getProperty('method');
        switch ($method) {
            case 'CALLBACK':
                break;
            default:
                $response = $this->actionCheckMinishop2();
                if ($response !== true) {
                    return $response;
                }
        }


        return parent::initialize();
    }



    /**
     * @return array|string
     */
    public function process()
    {
        return $this->success();
    }

    /**
     * Вернет конфиг
     * @return array|boolean
     */
    public function getSession()
    {
        if ($hash = $this->getProperty('hash', null)) {
            if (isset($_SESSION['msOneClickConfig'][$hash])) {
                foreach ($_SESSION['msOneClickConfig'][$hash] as $k => $v) {
                    $this->setProperty($k, $v);
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Product
     * @return string|boolean
     */
    public function getProduct()
    {
        if (is_null($this->product)) {
            $product_id = $this->getProperty('product_id');
            /* @var msProductData $msProduct */
            if (!$this->product = $this->modx->getObject('msProduct', $product_id)) {
                return $this->modx->lexicon('msoc_err_get_msProduct');
            }
        }
        return true;
    }

    /**
     * Payment
     * @return string|boolean
     */
    public function getPayment()
    {
        if (!$this->getProperty('payment', null)) {
            $this->setProperty('payment', (int)$this->modx->getOption('msoneclick_payments', null, 0));
        }

        if (!$msPayment = $this->modx->getObject('msPayment', array(
            'id' => $this->getProperty('payment'),
            //'active' => 0
        ))) {
            return $this->modx->lexicon('msoc_err_get_msPayment_active');
        }

        $this->payment = $msPayment;

        return true;
    }


    /**
     * Delivery
     * @return string|boolean
     */
    public function getDelivery()
    {
        if (!$this->getProperty('delivery', null)) {
            $this->setProperty('delivery', (int)$this->modx->getOption('msoneclick_deliverys', null, 0));;
        }
        if (!$msDelivery = $this->modx->getObject('msDelivery', array(
            'id' => $this->getProperty('delivery'),
            //'active' => 0
        ))) {
            return $this->modx->lexicon('msoc_err_get_msDelivery_active');
        }

        $this->delivery = $msDelivery;
        return true;
    }


    /**
     * Delivery
     */
    public function enabledDeliveryPayment()
    {
        // Enabled delivery
        $this->delivery->set('active', true);
        $this->delivery->save();
        // Enabled payment
        $this->payment->set('active', true);
        $this->payment->save();

    }


    /**
     * Delivery
     */
    public function disabledDeliveryPayment()
    {
        // Enabled delivery
        $this->delivery->set('active', false);
        $this->delivery->save();
        // Enabled payment
        $this->payment->set('active', false);
        $this->payment->save();
    }


    /**
     * @param array $data
     */
    protected function setOrder($data = array())
    {
        $order = $this->getProperty('order', array());
        if (!empty($data)) {
            $order = array_merge($order, $data);
            $this->setProperty('order', $order);
        }
    }
}

return 'msOneClickFormDefaultProcessor';