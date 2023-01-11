<?


class ulDetectLocationByDaData extends ulMethodDetectLocation
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
                $client = new ulDadataApi($this->UserLocation);
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

        if (!empty($data) AND !empty($data['location']) AND !empty($data['location']['data'])) {
            $c = $this->modx->newQuery('ulLocation');
            $c->where([
                'name:='  => $data['location']['data']['city'],
                'OR:id:=' => $data['location']['data']['city_kladr_id'],
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

class ulDadataApi
{
    /* @var modX $modx */
    public $modx;
    /* @var UserLocation $UserLocation */
    public $UserLocation;
    /**
     * https://dadata.ru/api/detect_address_by_ip/
     */

    private $token;
    private $secret;
    private $apiUrl;
    private $apiSuggestionsUrl;
    private $partner = 'MODX.VGRISH';

    public function __construct(UserLocation $citySelect)
    {
        $this->UserLocation = $citySelect;
        $this->modx = $citySelect->modx;

        $this->apiUrl = $this->UserLocation->getOption('dadata_api_url', null, 'https://dadata.ru/api/v2');
        $this->apiSuggestionsUrl = $this->UserLocation->getOption('dadata_api_suggestions_url', null, 'https://suggestions.dadata.ru/suggestions/api/4_1/rs');
        $this->token = $this->UserLocation->getOption('dadata_api_token', null);
        $this->secret = $this->UserLocation->getOption('dadata_api_secret', null);
    }

    public function detectAddressByIp($ip = '', array $params = [])
    {
        $url = trim($this->apiSuggestionsUrl, '/').'/detectAddressByIp?ip='.$ip;
        $data = $this->request($url, $params, false);

        return isset($data['location']) ? $data : [];
    }

    public function findByIdAddress($query = '', $params = [])
    {
        $url = trim($this->apiSuggestionsUrl, '/').'/findById/address';
        $data = $this->request($url, array_merge($params, ['query' => $query]), true);

        return isset($data['suggestions']) ? $data : [];
    }

    private function request($url, array $params = [], $isPost = true, array $headers = [])
    {
        if (empty($url)) {
            $url = $this->apiUrl;
        }

        $timeout = $this->UserLocation->getOption('curl_timeout', null, 15, true);

        if ($isPost) {
            $headers = array_merge($headers, [
                "Content-Type: application/json",
                "Accept: application/json",
                "Authorization: Token {$this->token}",
                "X-Secret: {$this->secret}",
                "X-Partner: {$this->partner}",
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
                "Authorization: Token {$this->token}",
                "X-Partner: {$this->partner}",
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

        return $result;
    }

}