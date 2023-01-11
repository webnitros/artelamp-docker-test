<?php

class fdkKassa
{
    /** @var modX $modx */
    public $modx;

    /** @var pdoFetch $pdoTools */
    public $pdoTools;

    /** @var array() $config */
    public $config = array();

    /** @var array $initialized */
    public $initialized = array();

    /** @var modError|null $error = */
    public $error = null;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/fdkkassa/';
        $assetsUrl = MODX_ASSETS_URL . 'components/fdkkassa/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'customPath' => $corePath . 'custom/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        if ($this->pdoTools = $this->modx->getService('pdoFetch')) {
            $this->pdoTools->setConfig($this->config);
        }

        $this->delivery_id = 2;
        $this->payment_id = 11;
        $this->user_id = 1511;
        $this->status_order_payment = 2;
        $this->status_order_send_1c = 3;
    }

    /**
     * Initializes component into different contexts.
     *
     * @param string $ctx The context to load. Defaults to web.
     * @param array $scriptProperties Properties for initialization.
     *
     * @return bool
     */
    public function initialize($ctx = 'web', $scriptProperties = array())
    {
        $this->config = array_merge($this->config, $scriptProperties);

        $this->config['pageId'] = $this->modx->resource->id;

        switch ($ctx) {
            case 'mgr':
                break;
            default:
                if (!defined('MODX_API_MODE') || !MODX_API_MODE) {

                    $config = $this->makePlaceholders($this->config);
                    if ($css = $this->modx->getOption('fdkkassa_frontend_css')) {
                        $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
                    }

                    $config_js = preg_replace(array('/^\n/', '/\t{5}/'), '', '
							fdkKassa = {};
							fdkKassaConfig = ' . $this->modx->toJSON($this->config) . ';
					');


                    $this->modx->regClientStartupScript("<script type=\"text/javascript\">\n" . $config_js . "\n</script>", true);
                    if ($js = trim($this->modx->getOption('fdkkassa_frontend_js'))) {

                        if (!empty($js) && preg_match('/\.js/i', $js)) {
                            $this->modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
							<script type="text/javascript">
								if(typeof jQuery == "undefined") {
									document.write("<script src=\"' . $this->config['jsUrl'] . 'web/lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
								}
							</script>
							'), true);
                            $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));

                        }
                    }

                }

                break;
        }
        return true;
    }


    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array $data Data to be transmitted to the processor
     *
     * @return mixed The result of the processor
     */
    public function runProcessor($action = '', $data = array())
    {
        if (empty($action)) {
            return false;
        }
        #$this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/fdkkassa/processors/';

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
    }


    /**
     * Method loads custom classes from specified directory
     *
     * @return void
     * @var string $dir Directory for load classes
     *
     */
    public function loadCustomClasses($dir)
    {
        $files = scandir($this->config['customPath'] . $dir);
        foreach ($files as $file) {
            if (preg_match('/.*?\.class\.php$/i', $file)) {
                include_once($this->config['customPath'] . $dir . '/' . $file);
            }
        }
    }

    /**
     * Обработчик для событий
     * @param modSystemEvent $event
     * @param array $scriptProperties
     */
    public function loadHandlerEvent(modSystemEvent $event, $scriptProperties = array())
    {
        switch ($event->name) {
            case 'OnHandleRequest':
                /*  if (!empty($_REQUEST['q'])) {
                      switch ($_REQUEST['q']) {
                          case 'rest/1c/order/create':
                              $response = $this->createOrder();
                              session_write_close();
                              exit($this->modx->toJSON($response));
                          default:
                              break;
                      }
                  }*/
                break;
            case 'OnLoadWebDocument':
                break;
            case 'msOnCreateOrder':
                $order = $scriptProperties['msOrder'];
                if ($order instanceof msOrder) {
                    if ($order->get('id') != $order->get('num')) {
                        $order->set('num', $order->get('id'));
                        $order->set('user_id', 1511);
                        $order->save();
                    }

                    /** @var msPayment $payment */
                    if ($Payment = $order->getOne('Payment')) {
                        $Payment->set('active', 0);
                        $Payment->save();
                    }

                }

                break;
            case 'msOnBeforeCreateOrder':
                /* @var  msOrder $msOrder */
                $msOrder = $scriptProperties['msOrder'];
                /* @var msOrderHandler $order */
                $order = $scriptProperties['order'];
                if ($msOrder instanceof msOrder) {
                    $data = $order->get();
                    if (array_key_exists('is_create_1c', $data)) {
                        $msOrder->set('is_create_1c', $data['is_create_1c']);
                        $msOrder->set('order_1c_id', $data['order_1c_id']);
                    }
                }
                break;
        }
    }


    /**
     * Properly get request parameters for various HTTP methods and content types
     * @return array
     */
    protected function _collectRequestParameters()
    {
        $filehandle = fopen('php://input', "r");
        $params = array();
        $contentType = isset($_SERVER['CONTENT_TYPE']) ? $_SERVER['CONTENT_TYPE'] : '';
        $spPos = strpos($contentType, ';');
        if ($spPos !== false) {
            $contentType = substr($contentType, 0, $spPos);
        }
        switch ($contentType) {
            case 'image/jpeg':
            case 'image/png':
            case 'image/gif':
                $params['filehandle'] = $filehandle;
                break;
            case 'application/json':
            case 'text/json':
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                $params = $this->modx->fromJSON($data);
                $params = (!is_array($params)) ? array() : $params;
                break;
            case 'application/x-www-form-urlencoded':
            default:
                $data = stream_get_contents($filehandle);
                fclose($filehandle);
                parse_str($data, $params);
                break;
        }
        /* if ($this->service->getOption('trimParameters', false)) {
             array_walk_recursive($this->parameters, array('modRestServiceRequest', '_trimString'));
         }*/
        return $params;
    }


    /**
     * @param array $data
     * @return array|string
     */
    public function createOrder($data = [])
    {
        $error = null;

        if (!isset($data['order_1c_id'])) {
            $error[] = 'missing "order_1c_id"';
        }
        if (!isset($data['application_id'])) {
            $error[] = 'missing "application_id"';
        }
        $order_1c_id = (string)$data['order_1c_id'];
        if (strlen($order_1c_id) != 36) {
            return $this->error('invalid data', ['order_1c_id' => 'not equal to 36 characters']);
        }


        if (!isset($data['sum'])) {
            $error[] = 'missing "sum"';
        }

        #if (!isset($data['contact'])) {
        #    $error[] = 'Укажите номер телефона или e-mail';
        #}


        $sum = (float)$data['sum'];
        if ($error) {
            return $this->error('invalid data', $error);
        }


        if ($sum == 0) {
            return $this->error('invalid data', ['sum' => 'amount cannot be 0']);
        }

        $request = [
            "order_1c_id" => $order_1c_id,
            "application_id" => $data['application_id'],
            "sum" => $sum,
            "payment" => 'rbs',
        ];

        $response = $this->msOrderCreate($request);
        $response = is_array($response) ? $response : json_decode($response, 1);
        if (!$response) {
            return $this->error('Вернулся пустой массив');
        }


        if ($response['success'] === false) {
            return $this->error($response['message'], $response['data']);
        }

        if (empty($response['data']['redirect'])) {
            return $this->error('Failed to get payment link');
        }

        $operation_uuid = $order_1c_id;

        /** @var msOrder $obj */
        if ($obj = $this->modx->getObject('msOrder', ['operation_uuid' => $operation_uuid])) {
            $order_ms_id = $obj->get('num') . $this->modx->getOption('fdkkassa_prefix_order');
        } else {
            return $this->error('Failed to get the order from the site database order_1c_id ' . $operation_uuid);
        }

        $redirect = $response['data']['redirect'];
        $redirect_array = parse_url($redirect);
        parse_str($redirect_array['query'], $redirect_arr);

        if (!empty($redirect_arr['error'])) {
            $error = (boolean)$redirect_arr['error'];
            if ($error) {
                return $this->error($redirect_arr['message'], $redirect_arr);
            }
        }


        $time = 259200 / 3600; // 3 дня
        $expirationDate = date(DATE_ATOM, strtotime('+' . $time . ' hours', time()));
        $status_order = '';
        if ($order = $this->order) {
            if ($Status = $order->getOne('Status')) {
                $status_order = $Status->get('name');
            }
        }

        return $this->success('', [
            'order_ms_id' => $order_ms_id,
            'url' => $response['data']['redirect'],
            'test_mode' => false,
            'expirationDate' => $expirationDate,
            'hours' => $time,
            'status' => $this->order->get('status'),
            'status_name' => $status_order,
        ]);
    }

    /* @var msOrder order */
    public $order;

    /**
     * @param $data
     * @return array|string
     */
    private function msOrderCreate($data)
    {
        $order_1c_id = $data['application_id'];
        $operation_uuid = $data['order_1c_id'];
        $sum = $data['sum'];


        /* @var miniShop2 $miniShop2 */
        $miniShop2 = $this->modx->getService('miniShop2');

        if (!$miniShop2 instanceof miniShop2) {
            return $this->error('Не удалось загрузить minishop2');
        }

        if (!$miniShop2->initialize('web', ['json_response' => true])) {
            return $this->error('Не удалось загрузить minishop2');
        }


        /* @var modUser $User */
        if (!$User = $this->modx->getObject('modUser', $this->user_id)) {
            return $this->error('Ошибка, не найден пользователь обратиться к администратору');
        }
        /* @var msOrder $Order */
        $criteria = [
            'order_1c_id' => $order_1c_id,
            'operation_uuid' => $operation_uuid,
        ];

        if ($Order = $this->modx->getObject('msOrder', $criteria)) {
            $this->order = $Order;
            $criteria = [
                'order_id' => $Order->get('id'),
                'action' => $Order->get('id'),
                'entry' => $this->status_order_payment
            ];

            /* @var msOrderLog $object */
            $isCount = (boolean)$this->modx->getCount('msOrderLog', $criteria);

            // Если заказ оплачен то не даем создавать ссылку второй раз
            if ($isCount) {
                $status_order = '';
                $date_payment = '';
                if ($order = $this->order) {
                    if ($Status = $order->getOne('Status')) {
                        $status_order = $Status->get('name');
                    }
                    $order_id = $Order->get('id');
                    if ($Log = $order->getOne('Log', ['order_id' => $order_id, 'action' => 'status', 'entry' => 2])) {
                        $date_payment = date(DATE_ATOM, strtotime($Log->get('timestamp')));
                    }
                }
                return $this->error('Заказ уже был оплачен. Повторное создание ссылки невозможно', [
                    'status' => $Order->get('status'),
                    'date_payment' => $date_payment,
                    'status_name' => $status_order,
                    'order_1c_id' => $Order->get('operation_uuid'),
                    'application_id' => $Order->get('order_1c_id'),
                ]);
            }

            // Создаем новую ссылку
            $Order->set('user_id', $User->get('id'));
            $Order->set('cost', $sum);
            $Order->save();
            $this->order = $Order;
            return $this->msOrderGerUrl($Order);
        }


        // Отчищаем корзину
        $miniShop2->cart->clean();
        $key = md5('order_1' . $sum);
        $miniShop2->cart->set([
            $key => [
                'id' => 144718,
                'name' => 'Произвольная оплата',
                'price' => $sum,
                'count' => 1,
                'ctx' => 'web',
            ]
        ]);


        /** @var msOrder $order */
        $createdon = date('Y-m-d H:i:s');


        /* @var msOrder $Order */
        $Order = $this->modx->newObject('msOrder');
        $Order->fromArray(array(
            'user_id' => $User->get('id'),
            'createdon' => $createdon,
            'num' => $miniShop2->order->getNum(),
            'delivery' => $this->delivery_id,
            'payment' => $this->payment_id, // Онлайн
            'cart_cost' => $sum,
            'weight' => 0,
            'delivery_cost' => 0,
            'cost' => $sum,
            'status' => 1,
            'context' => 'web',
            'operation_uuid' => $operation_uuid,
            'order_1c_id' => $order_1c_id,
            'create_payment_url_link' => true,
            'is_send_admin' => true,
            'track_order' => true, // Ставим что нужно отслеживать этот заказ
        ));
        $this->order = $Order;

        // Adding address
        /** @var msOrderAddress $address */
        $address = $this->modx->newObject('msOrderAddress');
        $address->fromArray(array_merge($this->order->toArray(), array(
            'user_id' => $this->user_id,
            'createdon' => $createdon,
        )));
        $Order->addOne($address);


        /* @var msOrderLog $Log */
        // Логируем что наступил статус Новый
        $Log = $this->modx->newObject('msOrderLog');
        $Log->set('user_id', $User->get('id'));
        $Log->set('timestamp', $createdon);
        $Log->set('action', 'status');
        $Log->set('entry', 1);
        $Order->addMany($Log);

        if (!$Order->save()) {
            return $this->error('Произошла ошибка во время сохранения');
        }

        return $this->msOrderGerUrl($Order);

    }


    /**
     * Вернет ссылку на заказ для которого уже была выдана ссылка
     * @param msOrder $order
     * @return array|mixed[]|string
     */
    public function msOrderGerUrl(msOrder $order)
    {
        /* @var msPayment $Payment */
        $Payment = $order->getOne('Payment');
        if (!$PaymentHandler = $Payment->loadHandler()) {
            return 'Не удалось загрузить class' . $Payment->get('class');
        }

        $url = $this->modx->getOption('site_url') . 'rest/1c/order/payment';
        $url .= '/' . $order->get('operation_uuid');

        return $this->success('', ['redirect' => $url]);
    }

    public function msOrderGerUrlPayment(msOrder $order)
    {
        /* @var msPayment $Payment */
        $Payment = $order->getOne('Payment');
        if (!$PaymentHandler = $Payment->loadHandler()) {
            return $this->error('Не удалось загрузить class' . $Payment->get('class'));
        }
        /* @var mspPayAnyWayPaymentHandler $handler */
        $handler = $Payment->handler;
        $response = $handler->send($order);
        if (!empty($response['errorCode'])) {
            return $this->error($response['errorMessage'], $response);
        }
        return $response;
    }


    /**
     * This method returns an error of the order
     *
     * @param string $message A lexicon key for error message
     * @param array $data .Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function error($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }


    /**
     * This method returns an success of the order
     *
     * @param string $message A lexicon key for success message
     * @param array $data .Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response']
            ? json_encode($response)
            : $response;
    }

}
