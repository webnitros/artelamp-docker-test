<?php

/**
 * The base class for msppayanyway.
 */
class msppayanyway
{
    /* @var modX $modx */
    public $modx;

    /** @var mixed|null $namespace */
    public $namespace = 'msppayanyway';
    /** @var string $partner */
    public $partner = 'MODX.VGRISH';
    /** @var array $config */
    public $config = array();
    /** @var array $initialized */
    public $initialized = array();

    /** @var modRestCurlClient $curlClient */
    public $curlClient;

    /** @var miniShop2 $miniShop2 */
    public $miniShop2;

    /**
     * @param modX  $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $corePath = $this->getOption('core_path', $config,
            $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/msppayanyway/');
        $assetsPath = $this->getOption('assets_path', $config,
            $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/msppayanyway/');
        $assetsUrl = $this->getOption('assets_url', $config,
            $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/msppayanyway/');
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'namespace'       => $this->namespace,
            'connectorUrl'    => $connectorUrl,
            'assetsBasePath'  => MODX_ASSETS_PATH,
            'assetsBaseUrl'   => MODX_ASSETS_URL,
            'assetsPath'      => $assetsPath,
            'assetsUrl'       => $assetsUrl,
            'actionUrl'       => $assetsUrl . 'action.php',
            'cssUrl'          => $assetsUrl . 'css/',
            'jsUrl'           => $assetsUrl . 'js/',
            'corePath'        => $corePath,
            'modelPath'       => $corePath . 'model/',
            'handlersPath'    => $corePath . 'handlers/',
            'processorsPath'  => $corePath . 'processors/',
            'templatesPath'   => $corePath . 'elements/templates/mgr/',
            'jsonResponse'    => true,
            'prepareResponse' => true,
            'showLog'         => false,
            'replacePattern'  => "#[\r\n\t]+#is",

        ), $config);

        $this->modx->addPackage('msppayanyway', $this->getOption('modelPath'));
        $this->modx->lexicon->load('msppayanyway:default');
        $this->namespace = $this->getOption('namespace', $config, 'msppayanyway');

        $level = $modx->getLogLevel();
        $modx->setLogLevel(xPDO::LOG_LEVEL_FATAL);
        if (!$this->curlClient = $modx->getService('rest.modRestCurlClient')) {
            return false;
        }
        if ($this->miniShop2 = $modx->getService('miniShop2')) {
            if (!($this->miniShop2 instanceof miniShop2)) {
                $this->miniShop2 = false;
            }
        }
        $modx->setLogLevel($level);
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
     *
     * @return mixed|null
     */
    public function getOption($key, $config = array(), $default = null, $skipEmpty = false)
    {
        $option = $default;
        if (!empty($key) AND is_string($key)) {
            if ($config != null AND array_key_exists($key, $config)) {
                $option = $config[$key];
            } elseif (array_key_exists($key, $this->config)) {
                $option = $this->config[$key];
            } elseif (array_key_exists("{$this->namespace}_{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}_{$key}");
            }
        }
        if ($skipEmpty AND empty($option)) {
            $option = $default;
        }

        return $option;
    }

    /**
     * Initializes component into different contexts.
     *
     * @param string $ctx The context to load. Defaults to web.
     * @param array  $scriptProperties
     *
     * @return boolean
     */
    public function initialize($ctx = 'web', $scriptProperties = array())
    {
        $this->config = array_merge($this->config, $scriptProperties, array('ctx' => $ctx));

        if (!empty($this->initialized[$ctx])) {
            return true;
        }

        switch ($ctx) {
            case 'mgr':
                break;
            default:
                if (!defined('MODX_API_MODE') OR !MODX_API_MODE) {

                    $this->initialized[$ctx] = true;
                }
                break;
        }

        return true;
    }

    /**
     * @param string $action
     * @param array  $data
     *
     * @return array|modProcessorResponse|string
     */
    public function runProcessor($action = '', $data = array())
    {
        if ($error = $this->modx->getService('error', 'error.modError')) {
            $error->reset();
        }
        $processorsPath = $this->getOption('processorsPath', null, MODX_CORE_PATH, true);
        $prepareResponse = $this->getOption('prepareResponse', null, false, true);
        /* @var modProcessorResponse $response */
        $response = $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath
        ));

        return $prepareResponse ? $this->prepareResponse($response) : $response;
    }

    /**
     * This method returns prepared response
     *
     * @param mixed $response
     *
     * @return array|string $response
     */
    public function prepareResponse($response)
    {
        if ($response instanceof modProcessorResponse) {
            $output = $response->getResponse();
        } else {
            $message = $response;
            if (empty($message)) {
                $message = $this->lexicon('err_unknown');
            }
            $output = $this->failure($message);
        }
        if ($this->config['jsonResponse'] AND is_array($output)) {
            $output = $this->modx->toJSON($output);
        } elseif (!$this->config['jsonResponse'] AND !is_array($output)) {
            $output = $this->modx->fromJSON($output);
        }

        return $output;
    }

    /**
     * return lexicon message if possibly
     *
     * @param string $message
     *
     * @return string $message
     */
    public function lexicon($message, $placeholders = array())
    {
        $key = '';
        if ($this->modx->lexicon->exists($message)) {
            $key = $message;
        } elseif ($this->modx->lexicon->exists($this->namespace . '_' . $message)) {
            $key = $this->namespace . '_' . $message;
        }
        if ($key !== '') {
            $message = $this->modx->lexicon->process($key, $placeholders);
        }

        return $message;
    }

    /**
     * @param string $message
     * @param array  $data
     * @param array  $placeholders
     *
     * @return array|string
     */
    public function failure($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->lexicon($message, $placeholders),
            'data'    => $data,
        );

        return $this->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
    }

    /**
     * @param string $message
     * @param array  $data
     * @param array  $placeholders
     *
     * @return array|string
     */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->lexicon($message, $placeholders),
            'data'    => $data,
        );

        return $this->config['jsonResponse'] ? $this->modx->toJSON($response) : $response;
    }

    /**
     * @param string $message
     * @param array  $data
     * @param bool   $showLog
     * @param bool   $writeLog
     */
    public function log($message = '', $data = array(), $showLog = false)
    {
        if ($this->getOption('showLog', null, $showLog, true)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $message);
            if (!empty($data)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($data, 1));
            }
        }
    }

    /**
     * @param        $array
     * @param string $delimiter
     *
     * @return array
     */
    public function explodeAndClean($array, $delimiter = ',')
    {
        $array = explode($delimiter, $array);     // Explode fields to array
        $array = array_map('trim', $array);       // Trim array's values
        $array = array_keys(array_flip($array));  // Remove duplicate fields
        $array = array_filter($array);            // Remove empty values from array
        return $array;
    }

    /**
     * @param        $array
     * @param string $delimiter
     *
     * @return array|string
     */
    public function cleanAndImplode($array, $delimiter = ',')
    {
        $array = array_map('trim', $array);       // Trim array's values
        $array = array_keys(array_flip($array));  // Remove duplicate fields
        $array = array_filter($array);            // Remove empty values from array
        $array = implode($delimiter, $array);

        return $array;
    }

    /**
     * Sets data to cache
     *
     * @param mixed $data
     * @param mixed $options
     *
     * @return string $cacheKey
     */
    public function setCache($data = array(), $options = array())
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        if (!empty($cacheKey) AND !empty($cacheOptions) AND $this->modx->getCacheManager()) {
            $this->modx->cacheManager->set(
                $cacheKey,
                $data,
                $cacheOptions[xPDO::OPT_CACHE_EXPIRES],
                $cacheOptions
            );
        }

        return $cacheKey;
    }

    /**
     * Returns data from cache
     *
     * @param mixed $options
     *
     * @return mixed
     */
    public function getCache($options = array())
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        $cached = '';
        if (!empty($cacheOptions) AND !empty($cacheKey) AND $this->modx->getCacheManager()) {
            $cached = $this->modx->cacheManager->get($cacheKey, $cacheOptions);
        }

        return $cached;
    }


    /**
     * @param array $options
     *
     * @return bool
     */
    public function clearCache($options = array())
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        $cacheOptions['cache_key'] .= $cacheKey;
        if (!empty($cacheOptions) AND $this->modx->getCacheManager()) {
            return $this->modx->cacheManager->clean($cacheOptions);
        }

        return false;
    }

    /**
     * Returns array with options for cache
     *
     * @param $options
     *
     * @return array
     */
    public function getCacheOptions($options = array())
    {
        if (empty($options)) {
            $options = $this->config;
        }
        $cacheOptions = array(
            xPDO::OPT_CACHE_KEY     => empty($options['cache_key'])
                ? 'default' : 'default/' . $this->namespace . '/',
            xPDO::OPT_CACHE_HANDLER => !empty($options['cache_handler'])
                ? $options['cache_handler'] : $this->modx->getOption('cache_resource_handler', null, 'xPDOFileCache'),
            xPDO::OPT_CACHE_EXPIRES => $options['cacheTime'] !== ''
                ? (integer)$options['cacheTime'] : (integer)$this->modx->getOption('cache_resource_expires', null, 0),
        );

        return $cacheOptions;
    }

    /**
     * Returns key for cache of specified options
     *
     * @var mixed $options
     * @return bool|string
     */
    public function getCacheKey($options = array())
    {
        if (empty($options)) {
            $options = $this->config;
        }
        if (!empty($options['cache_key'])) {
            return $options['cache_key'];
        }
        $key = !empty($this->modx->resource) ? $this->modx->resource->getCacheKey() : '';

        return $key . '/' . sha1(serialize($options));
    }

    /**
     * @return string
     */
    public function getVersionMiniShop2()
    {
        return isset($this->miniShop2->version) ? $this->miniShop2->version : '2.2.0';
    }

    /**
     * @return array|mixed
     */
    public function getPaymentIds($class = '')
    {
        if (empty($class)) {
            $class = $this->getPaymentClass();
        }
        if (!is_array($class)) {
            $class = array($class);
        }

        $mode = '/payment/ids/';
        $options = array(
            'cache_key' => $this->namespace . $mode . sha1(serialize($class)),
            'cacheTime' => 0,
        );
        if (!$data = $this->getCache($options)) {
            $data = array();
            $q = $this->modx->newQuery('msPayment');
            $q->where(array('class:IN' => $class));
            $q->select('id');
            $q->limit(0);
            if ($q->prepare() && $q->stmt->execute()) {
                $data = $q->stmt->fetchAll(PDO::FETCH_COLUMN);
            }

            $this->setCache($data, $options);
        }

        return $data;
    }

    /**
     * @return mixed|null
     */
    public function getPaymentClass()
    {
        return $this->explodeAndClean($this->getOption('payment_class', null, 'mspPayAnyWayPaymentHandler', true));
    }

    /** @return array Inject Payment Tabs */
    public function getInjectPaymentTabs()
    {
        $fields = $this->getOption('inject_payment_tabs', null,
            'add', true);
        $fields .= ',add';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    /**
     * @param modManagerController $controller
     * @param array                $setting
     */
    public function loadControllerJsCss(modManagerController &$controller, array $setting = array())
    {
        $controller->addLexiconTopic('msppayanyway:default');

        $config = $this->config;
        foreach (array('controller') as $key) {
            if (isset($config[$key])) {
                unset($config[$key]);
            }
        }

        $config['inject_payment_tabs'] = $this->getInjectPaymentTabs();

        $config['miniShop2']['version'] = $this->getVersionMiniShop2();
        $config['miniShop2']['payment']['ids'] = $this->getPaymentIds();
        $config['miniShop2']['payment']['class'] = $this->getPaymentClass();

        if (!empty($setting['config'])) {
            $controller->addHtml("<script type='text/javascript'>msppayanyway.config={$this->modx->toJSON($config)}</script>");
        }

        if (!empty($setting['tools'])) {
            $controller->addJavascript($this->config['jsUrl'] . 'mgr/msppayanyway.js');
            $controller->addJavascript($this->config['jsUrl'] . 'mgr/misc/tools.js');
        }

        if (!empty($setting['payment/inject'])) {
            $controller->addLastJavascript($this->config['jsUrl'] . 'mgr/payment/inject/inject.tab.js');
        }
    }


    /**
     * https://www.walletone.com/ru/merchant/documentation/
     *
     * MNT_MERCHANT_ID    Идентификатор интернет-магазина, полученный при регистрации.
     * MNT_PAYMENT_AMOUNT    Сумма заказа — число округленное до 2-х знаков после «запятой», в качестве разделителя .
     *
     *
     * MNT_TRANSACTION_ID   Внутренний идентификатор заказа, однозначно определяющий заказ в магазине.
     * MNT_CURRENCY_CODE    ISO код валюты, в которой производится оплата заказа в магазине. (RUB, USD, EUR)
     * MNT_TEST_MODE    Указание, что запрос происходит в тестовом режиме
     * MNT_DESCRIPTION  Описание оплаты. Максимальная длина 500 символов
     * MNT_SUBSCRIBER_ID    Внутренний идентификатор пользователя, однозначно определяющий получателя в магазине
     * MNT_SUCCESS_URL      URL страницы магазина, куда должен попасть покупатель после успешной оплаты.
     * MNT_FAIL_URL         URL страницы магазина, куда должен попасть покупатель после неуспешной оплаты.
     * MNT_RETURN_URL   URL страницы магазина, куда должен вернуться покупатель при добровольном отказе
     * MNT_SIGNATURE    Код для идентификации отправителя и проверки целостности данных
     *
     * @return array
     */
    public function getPaymentKeysPaymentForm()
    {
        return array(
            'MNT_ID'                 => 'payment_mnt_id',//
            'MNT_AMOUNT'             => 'payment_amount',
            'MNT_TRANSACTION_ID'     => 'payment_transaction_id',
            'MNT_CURRENCY_CODE'      => 'payment_currency_code',//
            'MNT_TEST_MODE'          => 'payment_test_mode',
            'MNT_DESCRIPTION'        => 'payment_description',
            'MNT_SUBSCRIBER_ID'      => 'payment_subscriber_id',
            'MNT_SUCCESS_URL'        => 'payment_success_url',
            'MNT_FAIL_URL'           => 'payment_failure_url',
            'MNT_RETURN_URL'         => 'payment_return_url',
            'moneta.locale'          => 'payment_culture_code',
            'paymentSystem.unitId'   => '',
            'paymentSystem.limitIds' => '',
            'MNT_DATAINTEGRITY_CODE' => '',
            'MNT_SIGNATURE'          => '',
        );
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function getPaymentSecretKey(array $params = array(), array $form = array())
    {
        return $this->getOption('payment_secret_key', null, '', true);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return string
     */
    public function getPaymentPaymentUrl(array $params = array(), array $form = array())
    {
        return trim($this->getOption('payment_url', null, 'https://www.payanyway.ru/assistant.htm', true), '/');
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentMntId(array $params = array(), array $form = array())
    {
        return $this->getOption('payment_mnt_id', null, '123456', true);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return null
     */
    protected function _getPaymentMntAmount(array $params = array(), array $form = array())
    {
        return null;
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return null
     */
    protected function _getPaymentMntTransactionId(array $params = array(), array $form = array())
    {
        return null;
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentMntCurrencyCode(array $params = array(), array $form = array())
    {
        return $this->getOption('payment_currency_code', null, 'RUB', true);
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
     * @return null
     */
    protected function _getPaymentMntSubscriberId(array $params = array(), array $form = array())
    {
        return null;
    }

    /**
     * @param array $params
     * @param array $form
     * @param array $options
     *
     * @return string
     */
    protected function _getPaymentMntSuccessUrl(
        array $params = array(),
        array $form = array(),
        array $options = array()
    ) {
        $id = $this->getOption('payment_success_id', null, $this->modx->getOption('site_start'), true);
        $options = array_merge($options, array('action' => 'success'));

        return $this->modx->makeUrl($id, '', $options, 'full', array('xhtml_urls' => false));
    }

    /**
     * @param array $params
     * @param array $form
     * @param array $options
     *
     * @return string
     */
    protected function _getPaymentMntFailUrl(
        array $params = array(),
        array $form = array(),
        array $options = array()
    ) {
        $id = $this->getOption('payment_failure_id', null, $this->modx->getOption('site_start'), true);
        $options = array_merge($options, array('action' => 'failure'));

        return $this->modx->makeUrl($id, '', $options, 'full', array('xhtml_urls' => false));
    }

    /**
     * @param array $params
     * @param array $form
     * @param array $options
     *
     * @return string
     */
    protected function _getPaymentMntReturnUrl(
        array $params = array(),
        array $form = array(),
        array $options = array()
    ) {
        $id = $this->getOption('payment_return_id', null, $this->modx->getOption('site_start'), true);
        $options = array_merge($options, array('action' => 'return'));

        return $this->modx->makeUrl($id, '', $options, 'full', array('xhtml_urls' => false));
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return mixed|null
     */
    protected function _getPaymentMntDataIntegrityCode(array $params = array(), array $form = array())
    {
        return $this->getPaymentSecretKey($params, $form);
    }

    /**
     * @param array $params
     * @param array $form
     *
     * @return array|string
     */
    protected function _getPaymentMntSignature(array $params = array(), array $form = array())
    {
        $keys = array(
            'MNT_ID',
            'MNT_TRANSACTION_ID',
            'MNT_AMOUNT',
            'MNT_CURRENCY_CODE',
            'MNT_SUBSCRIBER_ID',
            'MNT_TEST_MODE',
        );
        if (isset($params['MNT_SIGNATURE'])) {
            $keys = array(
                'MNT_ID',
                'MNT_TRANSACTION_ID',
                'MNT_OPERATION_ID',
                'MNT_AMOUNT',
                'MNT_CURRENCY_CODE',
                'MNT_SUBSCRIBER_ID',
                'MNT_TEST_MODE',
            );
        }

        $signature = array();
        foreach ($keys as $key) {
            $signature[] = $params[$key];
        }

        $signature[] = $this->getPaymentSecretKey();
        $signature = md5(implode('', $signature));

        return $signature;
    }

    /**
     * @param string $name
     *
     * @return string
     */
    public function getMethodName($name = '')
    {
        $name = '_getPayment' . ucfirst(str_replace(array('_', '.'), array('', ''), $name));

        return $name;
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
        $paymentKeys = $this->getPaymentKeysPaymentForm();
        foreach ($paymentKeys as $apiKey => $dataKey) {
            if (isset($params[$apiKey])) {
                $value = $params[$apiKey];
            } elseif (!$value = $this->getOption($dataKey, $params)) {
                $getMethod = $this->getMethodName($apiKey);
                if (method_exists($this, $getMethod)) {
                    $value = $this->$getMethod($params, $form, $options);
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
     * @param array $options
     *
     * @return string
     */
    public function getPaymentLink(array $params = array(), array $options = array())
    {
        $form = $this->getPaymentForm($params, $options);
        $url = $this->getPaymentPaymentUrl() . '?' . http_build_query($form);

        return $url;
    }

    /**
     * @param array $params
     *
     * @return array|string
     */
    public function getPaymentSignature(array $params = array())
    {
        return $this->_getPaymentMntSignature($params);
    }

    /**
     * @param array $params
     *
     * @return bool
     */
    public function isPaymentParams(array $params = array())
    {
        return isset(
            $params['MNT_ID'],
            $params['MNT_TRANSACTION_ID'],
            $params['MNT_SIGNATURE']
        );
    }

    /**
     * @return string
     */
    public function getPaymentSuccessAnswer()
    {
        return 'SUCCESS';
    }

    /**
     * @return string
     */
    public function getPaymentFailureAnswer()
    {
        return 'FAIL';
    }
}
