<?


class ulDetectLocationBySypexGeo extends ulMethodDetectLocation
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
                $client = new ulSypexGeoApi($this->UserLocation);
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

        if (!empty($data) AND !empty($data['city'])) {
            $c = $this->modx->newQuery('ulLocation');
            $c->where([
                'name:='    => $data['city']['name_ru'],
                'OR:name:=' => $data['city']['name_en'],
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

class ulSypexGeoApi
{
    /* @var modX $modx */
    public $modx;
    /* @var UserLocation $UserLocation */
    public $UserLocation;
    /**
     * https://sypexgeo.net/ru/api/
     */

    private $apiUrl;

    public function __construct(UserLocation $UserLocation)
    {
        $this->UserLocation = $UserLocation;
        $this->modx = $UserLocation->modx;

        $this->apiUrl = $this->UserLocation->getOption('sypexgeo_api_url', null, 'https://api.sypexgeo.net/json/', true);
    }

    public function detectAddressByIp($ip = '')
    {
        $url = trim($this->apiUrl, '/').'/'.$ip;
        $data = $this->request($url, [], false);

        return $data ? $data : [];
    }

    private function request($url, array $params = [], $isPost = true, array $headers = [])
    {
        if (empty($url)) {
            $url = $this->apiUrl;
        }

        $timeout = $this->UserLocation->getOption('curl_timeout', null, 5, true);

        if ($isPost) {
            $headers = array_merge($headers, [
                "Content-Type: application/json",
                "Accept: application/json",
            ]);
            $opts = [
                CURLOPT_RETURNTRANSFER => true,
                CURLINFO_HEADER_OUT    => true,
                CURLOPT_VERBOSE        => true,
                CURLOPT_HEADER         => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT        => $timeout,
                CURLOPT_POST           => true,
                CURLOPT_POSTFIELDS     => json_encode($params),
                CURLOPT_URL            => $url,
                CURLOPT_HTTPHEADER     => $headers,
            ];
        } else {
            $headers = array_merge($headers, [
                "Accept: application/json",
            ]);
            $opts = [
                CURLOPT_RETURNTRANSFER => true,
                CURLINFO_HEADER_OUT    => true,
                CURLOPT_VERBOSE        => true,
                CURLOPT_HEADER         => false,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_CONNECTTIMEOUT => $timeout,
                CURLOPT_TIMEOUT        => $timeout,
                CURLOPT_POST           => false,
                CURLOPT_URL            => $url,
                CURLOPT_HTTPHEADER     => $headers,
            ];
        }
        $curl = curl_init();
        curl_setopt_array($curl, $opts);
        $response = curl_exec($curl);
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        if ($statusCode >= 400) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($url, 1));
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($params, 1));
            $this->modx->log(modX::LOG_LEVEL_ERROR, $response);
        }
        $result = json_decode($response, true);

        if (!empty($result['error'])) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($result, 1));
        }

        return $result;
    }

}