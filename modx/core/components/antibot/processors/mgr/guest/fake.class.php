<?php

class antiBotGuestFakeProcessor extends modObjectProcessor
{
    public $objectType = 'antiBotGuest';
    public $classKey = 'antiBotGuest';
    public $languageTopics = ['antibot:manager'];
    //public $permission = 'save';


    public $hostInfo = [];

    /**
     * Проверка фэйковых ботов яндекса
     * @param $ip
     * @param $bot
     * @return bool
     */
    public function isFake($ipbota, $bot)
    {
        $hostname = gethostbyaddr($ipbota);

        /* @var antiBot $antiBot */
        $antiBot = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');
        $rule = $antiBot->rules[$bot];
        $params = array(
            'hostname' => $hostname,
            'ipbota' => $ipbota,
            'bot' => $bot
        );


        $ip = gethostbyname($hostname);
        if (!$ip) {
            return $this->modx->lexicon('antibot_api_get_could_not_ip', $params);
        }

        $hostname = gethostbyaddr($ip);

        $this->hostInfo['bot'] = $bot;
        $this->hostInfo['hostname'] = $hostname;
        $this->hostInfo['ip'] = $ipbota;
        $this->hostInfo['real_ip'] = $ip;


        if (!$hostname) {
            return $this->modx->lexicon('antibot_api_get_empty_hostname', $params);
        }
        if (!preg_match($rule, $hostname)) {
            return $this->modx->lexicon('antibot_api_get_could_not_found', $params);
        }


        if (!$ip) {
            return $this->modx->lexicon('antibot_api_get_could_not_ip', $params);
        }
        if ($ipbota != $ip) {
            return $this->modx->lexicon('antibot_api_get_could_not_ip_ip', $params);
        }


        return true;
    }


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $bot = $this->getProperty('bot');
        if (empty($bot)) {
            return $this->failure($this->modx->lexicon('Не указан бот' . $bot));
        }

        $ip = $this->getProperty('ip');
        if (empty($ip)) {
            return $this->failure($this->modx->lexicon('Не указан IP' . $ip));
        }

        /* @var antiBot $antiBot */
        $antiBot = $this->modx->getService('antibot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');


        $response = $antiBot->isFakeGuest($ip, $bot);
        $hostInfo = $antiBot->hostInfo;

        if ($response !== true) {
            $response = '<div class="blocked_service">' . $response . '</div>';
            foreach ($hostInfo as $k => $item) {
                $table .= '<tr><td><b>' . $k . '</b>:</td> <td>' . $item . '</td></tr>';
            }
            $response .= '<br><table>' . $table . '</table>';
            return $this->success($response, ['status' => false, $hostInfo]);
        }
        return $this->success('', ['status' => true]);
    }

}

return 'antiBotGuestFakeProcessor';
