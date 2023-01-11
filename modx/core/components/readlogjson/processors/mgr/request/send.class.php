<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include_once dirname(__FILE__, 4) . '/vendor/autoload.php';

/**
 * Disable an Mailing
 */
class ReadLogJsonRequestSendProcessor extends modProcessor
{
    public $objectType = 'ReadLogJsonRequest';
    public $classKey = 'ReadLogJsonRequest';
    public $languageTopics = array('readlogjsonrequest');


    /** {inheritDoc} */
    public function process()
    {
        $id = $this->getProperty('id');
        $url = $this->getProperty('url');
        $method = $this->getProperty('method_name');
        $request = $this->getProperty('request');
        $timeout = (int)$this->getProperty('timeout', 5);


        if (substr($request, 0, 1) !== '{') {
            return $this->failure('Не удалось преобразовать в массив');
        }

        $request = $this->modx->fromJSON($request);


        /* @var ReadLogJsonRequest $Request */
        if (!$Request = $this->modx->getObject($this->classKey, $id)) {
            return $this->failure('Не удалось получить запрос');
        }


        $Client = new \Readlogjson\Request();
        $response = $Client->send($url, $method, $request, $timeout);


        # $response = $Request->clear($response);
        if (!is_array($response) && $response[0] !== '{') {
            #return $this->failure('Произошла ошибка не удалось преобразовать ответ');
            $res = [
                'is_error' => true,
                'msg' => 'Вернулась строка',
                'response' => $response
            ];
        } else {
            $res = is_array($response) ? $response : $this->modx->fromJSON($response);
        }

        return $this->success('', [
            'response' => $response,
            'response_raw' => $Request->clear($res)
        ]);
    }


}

return 'ReadLogJsonRequestSendProcessor';
