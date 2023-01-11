<?php

class antiBotCurl
{
    /** @var modX $modx */
    public $modx;

    /* @var modRestCurlClient|null $curlClient */
    protected $curlClient = null;
    protected $apiURL = 'http://antibot.bustep.ru/api/';

    /**
     * @param antiBot $antiBot
     * @param array $config
     */
    function __construct(antiBot &$antiBot, array $config = [])
    {
        $this->modx =& $antiBot->modx;
        $this->curlClient = $this->modx->getService('rest.modRestCurlClient');
    }

    public function url($service)
    {
        return $this->apiURL . $service;
    }

    /**
     * @param $service
     * @param array $params
     * @param array $options
     * @return mixed|null
     */
    public function request($service, $params = array(), $options = array())
    {
        $options = array_merge(array(
            'contentType' => 'json',
        ), $options);


        $params = $this->loadProtection('antiBot', $params);
        $params['language'] = $this->modx->cultureKey;
        $params['hostname'] = $this->modx->getOption('site_url');
        $result = $this->curlClient->request($this->url($service), '', 'GET', $params, $options);
        return !empty($result) ? $this->modx->fromJSON($result) : null;
    }

    /**
     * @param $package_name
     * @param array $params
     * @return array
     */
    public function loadProtection($package_name, $params = array())
    {
        $key = '';
        $version = '';

        /* @var transport.modTransportPackage $object */
        $q = $this->modx->newQuery('transport.modTransportPackage');
        $q->where(array(
            'package_name' => strtolower($package_name),
        ));
        $q->sortby('installed', 'DESC');
        if ($transport = $this->modx->getObject('transport.modTransportPackage', $q)) {
            $version = $transport->get(array('version_major', 'version_minor', 'version_patch', 'release'));
            $version = implode('.', $version);;

            /** @var modTransportProvider $provider */
            if ($provider = $this->modx->getObject('transport.modTransportProvider', [
                'service_url:LIKE' => '%modstore.pro%',
            ])) {

                $provider->xpdo->setOption('contentType', 'default');
                $request = array(
                    'package' => $package_name,
                    'version' => $version,
                    'username' => $provider->username,
                    'api_key' => $provider->api_key,
                    'vehicle_version' => '2.0.0',
                    'http_host' => $this->modx->getOption('site_url'),
                );

                $response = $provider->request('package/decode/install', 'POST', $request);
                if ($response->isError()) {
                    $msg = $response->getError();
                    $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, $msg);
                } else {
                    $data = $response->toXml();
                    if (!empty($data->key)) {
                        $key = (string)$data->key;
                        if (strlen($key) != 40) {
                            $key = '';
                        }
                    } elseif (!empty($data->message)) {
                        $transport->xpdo->log(xPDO::LOG_LEVEL_ERROR, $data->message);
                    }
                }

            }
        }
        return $params = array_merge($params, array(
            'key' => $key,
            'version' => $version,
        ));
    }

}