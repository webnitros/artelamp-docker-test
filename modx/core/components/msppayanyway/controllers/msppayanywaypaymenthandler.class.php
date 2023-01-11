<?php

if (!class_exists('msPaymentInterface')) {
    require_once MODX_CORE_PATH . 'components/minishop2/model/minishop2/mspaymenthandler.class.php';
}

class mspPayAnyWayPaymentHandler extends msPaymentHandler implements msPaymentInterface
{
    /** @var modX $modx */
    public $modx;
    /** @var miniShop2 $ms2 */
    public $ms2;
    /** @var msppayanyway $msppayanyway */
    public $msppayanyway;
    /** @var array $config */
    public $config = array();

    /** @var array $params */
    public $params;

    /**
     * @param xPDOObject $object
     * @param array      $config
     */
    function __construct(xPDOObject $object, $config = array())
    {
        parent::__construct($object, $config);

        $fqn = $this->modx->getOption('msppayanyway_class', null, 'msppayanyway.msppayanyway', true);
        $path = $this->modx->getOption('msppayanyway_core_path', null,
            $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/msppayanyway/');
        if (!$this->msppayanyway = $this->modx->getService($fqn, '', $path . 'model/',
            array('core_path' => $path))
        ) {
            return false;
        }

        $this->msppayanyway->initialize($this->modx->context->key, $this->config);
    }

    /**
     * @param       $n
     * @param array $p
     */
    public function __call($n, array$p)
    {

        echo __METHOD__ . ' says: ' . $n;
    }

    /**
     * @param       $key
     * @param array $config
     * @param null  $default
     * @param bool  $skipEmpty
     *
     * @return mixed|null
     */
    public function getOption($key, $config = array(), $default = null, $skipEmpty = false)
    {
        return $this->msppayanyway->getOption($key, $config, $default, $skipEmpty);
    }

    /**
     * @param msOrder $order
     * @param bool    $load
     *
     * @return array
     */
    protected function loadParams(msOrder $order, $load = false)
    {
        if (!$this->params OR $load) {
            $this->params = $order->toArray('order_');
            if ($payment = $order->getOne('Payment')) {
                $this->params = array_merge($this->params, $payment->toArray('payment_'));
            }
            if ($profile = $order->getOne('UserProfile')) {
                $this->params = array_merge($this->params, $profile->toArray('profile_'));
            }
        }

        return $this->params;
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentPaymentSystemUnitId(array $params = array(), array $form = array())
    {
        $properties = $this->getOption('payment_properties', $params, array(), true);

        return $this->getOption('paymentSystem.unitId', $properties);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentPaymentSystemLimitIds(array $params = array(), array $form = array())
    {
        $properties = $this->getOption('payment_properties', $params, array(), true);

        return $this->getOption('paymentSystem.limitIds', $properties);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return string
     */
    protected function _getPaymentMntAmount(array $params = array(), array $form = array())
    {
        return number_format($this->getOption('order_cost', $params, 0, true), 2, '.', '');
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentMntTransactionId(array $params = array(), array $form = array())
    {
        return $this->getOption('order_id', $params, 0, true);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return null
     */
    protected function _getPaymentMntDescription(array $params = array(), array $form = array())
    {
        return null;
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentMntSubscriberId(array $params = array(), array $form = array())
    {
        return $this->getOption('profile_email', $params);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return int
     */
    protected function _getPaymentMntTestMode(array $params = array(), array $form = array())
    {
        return (int)$this->getOption('payment_test_mode', null);
    }

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getMethodName($name = '')
    {
        return $this->msppayanyway->getMethodName($name);
    }

    /**
     * @param array $params
     * @param array $options
     *
     * @return array
     */
    public function getPaymentForm(array $params = array(), array $options = array())
    {
        $form = $options;
        $paymentKeys = $this->msppayanyway->getPaymentKeysPaymentForm();
        foreach ($paymentKeys as $apiKey => $dataKey) {
            if (isset($params[$apiKey])) {
                $value = $params[$apiKey];
            } elseif (!$value = $this->getOption($dataKey, $params)) {
                $getMethod = $this->getMethodName($apiKey);
                if (method_exists($this, $getMethod)) {
                    $value = $this->$getMethod($params, $form);
                }
            }
            if (!is_null($value)) {
                $form[$apiKey] = $value;
            }
        }

        return $form;
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function isPaymentSignature(array $params = array())
    {
        return strtolower($this->msppayanyway->getPaymentSignature($params)) ==
        strtolower($this->getOption('MNT_SIGNATURE', $params));
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function isPaymentParams(array $params = array())
    {
        return $this->msppayanyway->isPaymentParams($params);
    }

    /**
     * @return string
     */
    public function getPaymentSuccessAnswer()
    {
        return $this->msppayanyway->getPaymentSuccessAnswer();
    }

    /**
     * @return string
     */
    public function getPaymentFailureAnswer()
    {
        return $this->msppayanyway->getPaymentFailureAnswer();
    }

    /** @inheritdoc} */
    public function send(msOrder $order)
    {
        $link = $this->getPaymentLink($order);
        return $this->success('', array('redirect' => $link));
    }

    /** @inheritdoc} */
    public function getPaymentLink(msOrder $order)
    {
        $params = $this->loadParams($order);
        $form = $this->getPaymentForm($params);

        $options = array(
            'msorder' => $this->_getPaymentMntTransactionId($params)
        );

        return $this->msppayanyway->getPaymentLink($form, $options);
    }

    /**
     * @param msOrder $order
     * @param array   $params
     *
     * @return mixed|null|string
     */
    public function receive(msOrder $order, array $params = array())
    {
        $redirect = '';
        $action = strtolower($this->getOption('ACTION', $params, '', true));
        switch (true) {
            case $action == '' AND $this->isPaymentSignature($params);
                $this->changeOrderStatus($order, 2);
                break;
            case $action == 'fail' AND $this->isPaymentSignature($params);
                $this->changeOrderStatus($order, 4);
                $redirect = $this->getOption('FAIL_URL', $params, '', true);
                break;
            case $action == 'return' AND $this->isPaymentSignature($params);
                $this->changeOrderStatus($order, 4);
                $redirect = $this->getOption('RETURN_URL', $params, '', true);
                break;
            default:
                break;
        }

        return $redirect;
    }

    /**
     * @param msOrder $order
     * @param         $status
     */
    protected function changeOrderStatus(msOrder $order, $status)
    {
        if (!$this->ms2) {
            $this->ms2 = $this->modx->getService('miniShop2');
        }
        $this->ms2->changeOrderStatus($order->get('id'), $status);
    }
}
