<?php
/**
 * Created by Andrey Stepanenko.
 * User: webnitros
 * Date: 16.07.2019
 * Time: 12:18
 */
if (!class_exists('exchangeException')) {
    class exchangeException extends Exception
    {
    }
}

class Fandeco1cSync
{
    public $isPrint = false;

    /* @var modX $modx */
    private $modx;

    protected $config = array();

    private $data = null;
    private $method = null;
    private $params = null;

    function __construct(modX &$modx, array $config = array())
    {
        $this->modx = $modx;
        $this->config = array_merge(array(
            'request_url' => 'http://fandeco1c.ddns.net:7780/br/hs/fandeco/',
            'set_cache' => true,
            'get_cache' => true,
            'options' => array(
                #'format' => 'json',
                'suppressSuffix' => true, // иначе при использовании format=json в конец url добавить http://fandeco.ru/method.json
                'timeout' => 120,
                'username' => $this->modx->getOption('Ic_username',null,'WebUserArtelamp'),
                'password' => $this->modx->getOption('Ic_password',null,'VXDTB9lg4Uz4vkKsASAx2'),
                /*'headers' => array(
                       'content-type' => 'application/json'
                ),*/
            ),
        ), $config);
    }


    /**
     * Вернет полный код ответа
     */
    public function fullInfo()
    {
        $this->isPrint = true;
    }


    private function reset()
    {
        $this->request = null;
        $this->method = null;
        $this->params = null;
        $this->hash = null;
        $this->data = null;
    }

    /**
     * Запись параметров по умолчанию
     * @param $method
     * @param array $params
     * @throws exchangeException
     */
    private function setDefault($method, $params = array())
    {
        $this->reset();
        if (empty($method)) {
            throw new exchangeException('[FandecoSync1C] Не указан метод отправки запроса');
        }
        $this->params = $params;
        $this->method = $method;
        $this->request_url = $this->config['request_url'] . $method;
    }


    /**
     * Проверяем что данные были записаны
     * @return bool
     */
    private function isWrite()
    {
        return is_null($this->data) ? false : true;
    }

    /**
     * Запись опций в CURL
     * @param $key
     * @param $value
     */
    public function setOption($key, $value)
    {
        $this->config['options'][$key] = $value;
    }

    /**
     * @param modRest $client
     */
    private function setOptionDefault($client)
    {
        $options = $this->config['options'];
        foreach ($options as $option => $value) {
            $client->setOption($option, $value);
        }
    }

    public $request_url = null;

    /**
     * @param $method
     * @return mixed|null
     */
    public function findUrlRequest($method)
    {
        $fdk_url_sync_1c = [
            'region' => [
                'name' => 'Регион',
                'url' => 'http://fandeco1c.ddns.net:7780/bb/hs/fandeco/',
                'methods' => [
                    'property_reference' => 'Справочник свойств',
                    'products' => 'Получение свойств',
                    'submit_to_site' => 'Получение свойства "Передавать на сайт"',
                ]
            ],
            'retail' => [
                'name' => 'Розница',
                'url' => 'http://fandeco1c.ddns.net:7780/br/hs/fandeco/',
                'methods' => [
                    'prices' => 'Получение Цен',
                    'stocks' => 'Получение Остатков',
                    'get_vendors' => 'Получение производителей',
                ]
            ],
        ];
        foreach ($fdk_url_sync_1c as $base_name => $item) {
            $url = $item['url'];
            $methods = $item['methods'];
            foreach ($methods as $key => $name) {
                if ($key == $method) {
                    return $url;
                }
            }
        }
        return null;
    }

    /**
     * @param $method
     * @param array $params
     * @return array
     */
    public function send($method, $params = array(), $request_url = null)
    {
	    $params = array_merge([
		    'site_name'=>MODX_HTTP_HOST,
	    ],$params);

        $request_url_method = null;
        if ($newUlr = $this->findUrlRequest($method)) {
            $newUlr = rtrim($newUlr, '/');
            $request_url_method = $newUlr . '/' . $method;
        }
        $this->setDefault($method, $params);

        if (!$result = $this->isWrite()) {
            /* @var modRest $client */
            $client = $this->modx->getService('rest', 'rest.modRest');

            if (!empty($params)) {
                $this->setOption('format', 'json');
            }
            $this->setOptionDefault($client);
            if ($request_url_method) {
                $this->request_url = $request_url_method;
            }
	        $request_url = 'https://rest.massive.ru/';
            if ($request_url) {
                $request_url = rtrim($request_url, '/');
                $this->request_url = $request_url . '/' . $method;
            }

            if (strripos($this->request_url, 'fandeco.ru') !== false) {
                $response = $client->get($this->request_url, $params);
            } else {
                $response = $client->post($this->request_url, $params);
            }

            $this->setResponse($method, $response);
        }
        return $this->getResponse();
    }

    /**
     * @return array
     */
    public function setHeaders($key, $value)
    {
        $headers = $this->config['options']['headers'];
        $headers[$key] = $value;
        $this->setOption('headers', $headers);
        return $this->data;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return $this->data;
    }

    public $full_info = array();

    /**
     * @param $url
     */
    public function setRequestUrl($url)
    {
        $this->request_url = $url;
    }


    /**
     * @return array
     */
    public function getFullInfo()
    {
        return $this->full_info;
    }


    /**
     * @param $response
     * @return bool
     */
    public function saveResponse($method, $response, $params)
    {
        $sync_method_save_response = $this->modx->getOption('sync_method_save_response', null, []);
        if (array_key_exists($method, $sync_method_save_response)) {
            // Постоянная фиксация ответов из 1С
            /* @var syncResponse $object */
            $object = $this->modx->newObject('syncResponse');
            $object->set('method', $method);
            $object->set('response', $response);
            $object->set('params', $this->modx->toJSON($params));
            return $object->save();
        }
        return true;
    }


    /**
     * Запись ответа от сервера
     *
     * @param RestClientResponse $response
     * @throws exchangeException
     */
    protected function setResponse($method, RestClientResponse $response)
    {
        $this->full_info = array(
            'request_url' => $this->request_url,
            'params' => $this->params,
            'headers' => $response->responseHeaders,
            'body' => $response->responseBody,
            'error' => $response->responseError,
            'info' => $response->responseInfo,
            'options' => $this->config['options'],
        );

        $this->saveResponse($method, $response->responseBody, $this->params);

        if ($this->isPrint) {
            echo '<pre>';
            print_r($this->full_info);
            die;
        }


        if ($response->responseError) {
            throw new exchangeException('[FandecoSync1C] ' . $response->responseError);
        }

        if (property_exists($response->responseInfo, 'scalar')) {
            $code = intval($response->responseInfo->scalar);
            if ($code != 0 and $code != 200) {
                throw new exchangeException('[FandecoSync1C] Error code:' . $code);
            }
        }

        // Проверям что ответ от сервера получен без ошибок
        $this->setData($response->process());
        if (!$this->isWrite()) {
            throw new exchangeException('[FandecoSync1C] Error writing data, the answer should be in the format JSON');
        } else {
            // Проверяем ошибки полученные из тела
            if (array_key_exists('errCode', $this->data) && !empty($this->data['errCode']) && (int)$this->data['errCode'] !== 0 && $this->data['errMsg'] !== 'order exists creation is impossible') {
				$msg = '[FandecoSync1C] 1c return body errCode :' . print_r($this->data['errMsg']?:$this->data['errors'], 1);
                throw new exchangeException($msg);
            }
        }
    }


    /**
     * Записываем данные
     */
    private function setData($data)
    {
        if (!empty($data) and is_array($data)) {
            $this->data = $data;
        } else {
            $this->data = array();
        }
    }


}
