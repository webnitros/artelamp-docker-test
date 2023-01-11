<?


class ulDetectLocationByIpGeoBase extends ulMethodDetectLocation
{

    public function getUserAddressByIp($ip = '')
    {
        $ip = !empty($ip) ? $ip : $this->getUserIp();
        if (empty($ip) OR $this->getUserBotByUserAgent()) {
            $ip = '62.105.128.0';
        }

        $tmp = [
            'cache_key' => '/address/'.strtolower(__CLASS__.'/'.$ip),
            'cacheTime' => 3600,
        ];

        if (!$data = $this->UserLocation->getCache($tmp)) {
            try {
                $client = new ulIpGeoBase($this->UserLocation);
                $data = $client->detectAddressByIp($ip);
            } catch (Exception $exception) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, $exception->getMessage());
            }
            if (!empty($data)) {
                $this->UserLocation->setCache($data, $tmp);
            }
        }

        return $data;
    }


    public function run()
    {
        /** @var ulLocation $location */
        $location = $this->modx->newObject('ulLocation');
        $data = $this->getUserAddressByIp($this->getProp('ip'));

        if (!empty($data) AND !empty($data['ip'])) {
            $c = $this->modx->newQuery('ulLocation');
            $c->where([
                'name:='    => $data['ip']['city'],
                'OR:name:=' => $data['ip']['city'],
            ]);
            if ($o = $this->modx->getObject('ulLocation', $c)) {
                $location = $o;
            }
        }

        $location = $this->getLocation($location, $data);
        if ($location AND $location->isWork()) {
            return $location;
        }

        return false;
    }
}

class ulIpGeoBase
{
    /* @var modX $modx */
    public $modx;
    /* @var UserLocation $UserLocation */
    public $UserLocation;
    /**
     * http://ipgeobase.ru:7020/geo?ip=94.25.161.17
     */

    private $apiUrl;

    public function __construct(UserLocation $UserLocation)
    {
        $this->UserLocation = $UserLocation;
        $this->modx = $UserLocation->modx;

        $this->apiUrl = $this->UserLocation->getOption('ipgeobase_api_url', null, 'http://ipgeobase.ru:7020', true);
    }

    public function detectAddressByIp($ip = '')
    {
        $opts = [
            'http' => [
                'method'  => 'GET',
                'header'  => "Content-Type: text/xml\r\n",
                'timeout' => $this->UserLocation->getOption('curl_timeout', null, 5, true),
            ],
        ];
        $context = stream_context_create($opts);
        $url = trim($this->apiUrl, '/').'/geo?ip='.$ip;
        if (!$response = file_get_contents($url, false, $context) OR !$xml = simplexml_load_string($response)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($response, 1));
        }

        $data = json_encode($xml);
        $data = json_decode($data, true);

        return $data ? $data : [];
    }

}