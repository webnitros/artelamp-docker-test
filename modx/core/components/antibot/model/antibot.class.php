<?php

class antiBot
{
    /** @var modX $modx */
    public $modx;

    /** @var array $config */
    public $config = array();


    /* @var antiBotStopList $rule */
    protected $stopList = false;
    /* @var boolean $isBlock */
    protected $isBlock = false;
    /* @var boolean $isSave */
    protected $isSave = false;

    // Правила проверки адреса
    public $rules = array(
        'yandex' => '/\.yandex\.(net|ru|com)/',
        'mail' => '/\.mail\.(ru)/',
        'google' => '/\.googlebot\.(com)/',
        'bing' => '/\.search\.msn\.(com)/'
    );


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = MODX_CORE_PATH . 'components/antibot/';
        $assetsUrl = $this->modx->getOption('antibot_assets_url', $config, $this->modx->getOption('assets_url') . 'components/antibot/');
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
        $this->modx->addPackage('antibot', $this->config['modelPath']);
        $this->isSave = $this->modx->getOption('antibot_keep_statistics', $this->config, true);
    }


    /**
     * Shorthand for the call of processor
     *
     * @access public
     *
     * @param string $action Path to processor
     * @param array $data Data to be transmitted to the processor
     *
     * @return mixed|modProcessorResponse The result of the processor
     */
    public function runProcessor($action = '', $data = array())
    {
        if (empty($action)) {
            return false;
        }
        #$this->modx->error->reset();
        $processorsPath = !empty($this->config['processorsPath'])
            ? $this->config['processorsPath']
            : MODX_CORE_PATH . 'components/antibot/processors/';

        return $this->modx->runProcessor($action, $data, array(
            'processors_path' => $processorsPath,
        ));
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
                $enable_statistics = $this->modx->getOption('antibot_enable_statistics', $this->config, true);
                if ($enable_statistics) {
                    $keep_statistics_context = $this->modx->getOption('antibot_keep_statistics_context', $this->config, true);
                    $save = true;
                    if (!$keep_statistics_context) {
                        if ($this->modx->context->key == 'mgr') {
                            $save = false;
                        }
                    }
                    if ($save) {
                        $this->OnPageStartHandler();
                        $this->verificationRequest();
                    }
                }
                break;
            case 'OnPageNotFound':
                $enable_statistics = $this->modx->getOption('antibot_enable_statistics', $this->config, true);
                if ($enable_statistics) {
                    $keep_statistics_context = $this->modx->getOption('antibot_keep_statistics_context', $this->config, true);
                    $save = true;
                    if (!$keep_statistics_context) {
                        if ($this->modx->context->key == 'mgr') {
                            $save = false;
                        }
                    }

                    if ($save && $this->hit instanceof antiBotHits) {
                        // Если получили код 404
                        $this->hit->set('code_response', 404);
                        $this->hit->save();
                    }
                }
                break;
        }

    }

    /* @var antiBotHits|null $hit */
    protected $hit = null;

    /* @var antiBotCurl|NULL $curlClient */
    protected $curlClient = null;

    /**
     * Load client curl
     * @return antiBotCurl|NULL
     */
    public function loadRequest()
    {
        if (is_null($this->curlClient)) {
            if (!class_exists('antiBotCurl')) {
                include dirname(__FILE__) . '/request/antibotcurl.class.php';
                $this->curlClient = new antiBotCurl($this);
            }
        }
        return $this->curlClient;
    }

    /**
     * Поиск правил блокировки для IP адреса
     */
    public function OnPageStartHandler()
    {
        // Отключение отчистки статистики по дням
        $remove_statistics = $this->modx->getOption('antibot_disable_remove_statistics', $this->config, false);
        if (!$remove_statistics) {
            $this->removeStatistic();
        }


        $ip = $this->GetIpArray();
        $user_agent = $this->GetUserAgent();
        $context = $this->GetContext();

        $ip_1 = intval($ip[0]);
        $ip_2 = intval($ip[1]);
        $ip_3 = intval($ip[2]);
        $ip_4 = intval($ip[3]);


        /* @var antiBotStopList $rule */
        $q = $this->modx->newQuery('antiBotStopList');


        $sql = "SELECT {$this->modx->getSelectColumns('antiBotStopList','antiBotStopList')} FROM `{$this->modx->getTableName('antiBotStopList')}` WHERE
  (active = 1 AND  (context = '{$context}' OR context = ''))
  AND (
    (ip_1 = '{$ip_1}' AND ip_2 = '{$ip_2}' AND ip_3 = '{$ip_3}' AND ip_4 = '{$ip_4}') OR ((ip_1 = '{$ip_1}' OR ip_1 = '') AND (ip_2 = '{$ip_2}' OR ip_2 = '') AND (ip_3 = '{$ip_3}' OR ip_3 = '') AND (ip_4 = '{$ip_4}' OR ip_4 = ''))
  )
  AND (user_agent = '' OR user_agent LIKE concat('%{$user_agent}%'))";


        $sql2 = "((ip_1 = '{$ip_1}' AND ip_2 = '{$ip_2}' AND ip_3 = '{$ip_3}' AND ip_4 = '{$ip_4}') OR ((ip_1 = '{$ip_1}' OR ip_1 = '') AND (ip_2 = '{$ip_2}' OR ip_2 = '') AND (ip_3 = '{$ip_3}' OR ip_3 = '') AND (ip_4 = '{$ip_4}' OR ip_4 = ''))) AND (user_agent = '' OR user_agent LIKE concat('%{$user_agent}%'))";


        $q->select($this->modx->getSelectColumns('antiBotStopList', 'antiBotStopList'));
        $q->where(array(
            array(
                'active:=' => true,
            ),
            array(
                'AND:context:=' => $context,
                'OR:context:=' => '',
            ),
        ));


        $q->andCondition($sql2);

        #$q->prepare(); echo '<pre>'; print_r($q->toSQL()); die;


        $isSaveBlocke = $this->modx->getOption('antibot_keep_block_user', $this->config, false);
        $stopList = $this->modx->getObject('antiBotStopList', $q);
        if ($stopList and $stopList->get('active')) {
            // Получаем одно из правил под которое попада IP адрес
            $this->isBlock = true;
            $this->stopList = $stopList;
            if ($isSaveBlocke) {
                $this->isSave = true;
            } else {
                $this->isSave = false;
            }

        } else {
            $this->isBlock = false;
        }
    }

    /**
     * Проверка запроса
     */
    public function verificationRequest()
    {
        $userId = $this->modx->user->id;
        $context = $this->GetContext();
        $authorized = $this->modx->user->isAuthenticated($context);
        $userAgent = $this->GetUserAgent();
        // Отключение ведения статистики для авторизованных пользователей
        $keep_statistics_authorized_users = $this->modx->getOption('antibot_keep_statistics_authorized_users', $this->config, true);
        if (!$keep_statistics_authorized_users and $authorized) {
            $this->isSave = false;
        }

        if ($this->isSave) {
            $guestId = $this->GetParamByName('APIKIT_BOTSTOP_GUEST_ID');
            $guestMd5 = md5($_SERVER["HTTP_USER_AGENT"] . $_SERVER["REMOTE_ADDR"] . $_SERVER["HTTP_X_FORWARDED_FOR"]);

            if ($guest = $this->GetGuestDb($guestId, $guestMd5)) {
                if (!$authorized) {
                    $userId = $guest->get('user_id');
                }
                $guestId = $guest->get('id');
                $this->UpdateGuest($guest, $userId, $userAgent, $authorized);
            } else {
                $guestId = $this->AddGuest($guestMd5, $userId, $userAgent, $authorized, $context);
            }

            $this->AddHits($guestId, $userId, $userAgent, $authorized, $context);
        }

        // Сообщение о блокировки
        if ($this->isBlock) {
            $message = trim($this->stopList->get('message'));
            $redirect_url_manual = trim($this->stopList->get('redirect_url'));


            $current_page = $this->modx->getOption('site_url');
            $site_unavailable_page = $this->modx->getOption('site_unavailable_page');
            $redirect_url = $this->modx->makeUrl($site_unavailable_page, $this->GetContext(), '', 'full');

            // Текущая страница
            $request_uri = $current_page . substr($_SERVER['REQUEST_URI'], 1);
            if (!empty($redirect_url_manual)) {
                if ($request_uri != $redirect_url_manual) {
                    $this->modx->sendRedirect($redirect_url_manual, array('responseCode' => 'HTTP/1.1 503 Service Temporarily Unavailable'));
                }
            } else {
                if (!empty($message)) {
                    //header("HTTP/1.1 403 Forbidden");
                    header('HTTP/1.1 503 Service Temporarily Unavailable');
                    header('Status: 503 Service Temporarily Unavailable');
                    header('Retry-After: 86400');//300 seconds

                    die("<center>{$message}</center>");
                } else {
                    if (!empty($message) && (!empty($redirect_url) and $request_uri != $redirect_url)) {
                        $this->modx->sendRedirect($redirect_url, array('responseCode' => 'HTTP/1.1 403 Forbidden'));
                    }
                }
            }
        }
    }


    /**
     * Вернет текущий контекст
     */
    public function reCaptcha()
    {
        if (!class_exists('ReCaptcha')) {
            include_once dirname(__FILE__) . '/request/recaptchalib.php';
        }

        // ваш секретный ключ
        $secret = $this->modx->getOption('antibot_recaptcha_secret', $this->config, "6LeKepQUAAAAALmaGDm-0IP98rsHFGeJCs541cEX");
        $public = $this->modx->getOption('antibot_recaptcha_public', $this->config, "6LeKepQUAAAAAPw2B4Rij-HEJPLbLGATbkxbBkFh");


        //ответ
        $message = 'Необходимо пройти проверку на человеко подобие';
        $response = null;

        //проверка секретного ключа
        $reCaptcha = new ReCaptcha($secret);

        if (!empty($_POST)) {
            if (isset($_POST["g-recaptcha-response"])) {
                $response = $reCaptcha->verifyResponse(
                    $_SERVER["REMOTE_ADDR"],
                    $_POST["g-recaptcha-response"]
                );
            }
            if ($response != null && $response->success) {
                $message = "Все хорошо.";
            } else {
                $message = "Вы точно человек?";
            }
        }


        $_GET['antibot_css'] = $this->config['cssUrl'] . 'web/button.css';
        $_GET['google_message'] = $message;
        $_GET['google_public'] = $public;


    }

    /**
     * Вернет текущий контекст
     * @return string
     */
    public function GetContext()
    {
        return $this->modx->context->key;
    }


    /**
     * Вернет user agent
     * @return string
     */
    private function GetUserAgent()
    {
        return !empty($_SERVER["HTTP_USER_AGENT"]) ? $this->modx->stripTags(trim($_SERVER["HTTP_USER_AGENT"])) : '';
    }

    /**
     * @return array|mixed|null
     */
    private function GetIpArray()
    {
        $ip = null;
        $ips = $this->getClientIp();
        if (isset($ips['ip'])) {
            $ip = $ips['ip'];
        }
        return $ip ? explode(".", $ip) : $ip;
    }


    public function getClientIp()
    {
        $ip = '';
        $ipAll = array(); // networks IP
        $ipSus = array(); // suspected IP

        $varable = trim($this->modx->getOption('antibot_ip_definition', $this->config, 'REMOTE_ADDR'));
        if (empty($varable)) {
            $serverVariables = array(
                'HTTP_X_FORWARDED_FOR',
                'HTTP_X_FORWARDED',
                'HTTP_X_CLUSTER_CLIENT_IP',
                'HTTP_X_COMING_FROM',
                'HTTP_FORWARDED_FOR',
                'HTTP_FORWARDED',
                'HTTP_COMING_FROM',
                'HTTP_CLIENT_IP',
                'HTTP_FROM',
                'HTTP_VIA',
                'REMOTE_ADDR',
            );
            foreach ($serverVariables as $serverVariable) {
                $value = '';
                if (isset($_SERVER[$serverVariable])) {
                    $value = $_SERVER[$serverVariable];
                } elseif (getenv($serverVariable)) {
                    $value = getenv($serverVariable);
                }
                if (!empty($value)) {
                    $tmp = explode(',', $value);
                    $ipSus[] = $tmp[0];
                    $ipAll = array_merge($ipAll, $tmp);
                }
            }
            $ipSus = array_unique($ipSus);
            $ipAll = array_unique($ipAll);
            $ip = (sizeof($ipSus) > 0) ? $ipSus[0] : $ip;

        } else {
            $ip = isset($_SERVER[$varable]) ? $_SERVER[$varable] : $_SERVER['REMOTE_ADDR'];
        }

        return array(
            'ip' => $ip,
            'suspected' => $ipSus,
            'network' => $ipAll,
        );
    }

    /**
     * Вернет индитификатор пользователя по сессии или кукам
     * @param $name
     * @return int
     */
    private function GetParamByName($name)
    {
        $sessionValue = intval($_SESSION[$name]);
        if ($sessionValue <= 0) {
            $cookieValue = intval($_COOKIE[$name]);
            if ($cookieValue <= 0) {
                return 0;
            }
            $_SESSION[$name] = $cookieValue;
            return $cookieValue;
        }
        return $sessionValue;
    }

    /**
     * Вернет индитификатор гостя
     * @param $guestId
     * @param $guestMd5
     * @return null|object|antiBotGuest
     */
    private function GetGuestDb($guestId, $guestMd5)
    {
        $guest = null;
        if ($guestId > 0) {
            $guest = $this->modx->getObject('antiBotGuest', $guestId);
        }
        if (!$guest) {
            $guest = $this->modx->getObject('antiBotGuest', array(
                'session_hash' => $guestMd5
            ));
        }
        return $guest;
    }

    /**
     * Вернет отформатированные IP адреса
     * @return string
     */
    public function getIp()
    {
        $ips = $this->GetIpArray();
        $ips = array_map('intval', $ips);
        return implode('.', $ips);
    }

    /**
     * Добавление хита
     *
     * @param int $guestId
     * @param int $userId
     * @param string $userAgent
     * @param boolean $authorized
     * @return antiBotHits|null
     */
    public function AddHits($guestId, $userId, $userAgent, $authorized, $context)
    {
        $data = array(
            'url' => $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
            'url_from' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
            'context' => $context,
            'method' => $_SERVER['REQUEST_METHOD'],
            'cookies' => isset($_SERVER['HTTP_COOKIE']) ? $_SERVER['HTTP_COOKIE'] : '',
            'user_agent' => $userAgent,
            'ip' => $this->getIp(),
            'user_id' => $userId,
            'guest_id' => $guestId,
            'authorized' => $authorized,
            'blocked' => $this->isBlock ? 1 : 0,
            'createdon' => time(),
            'updatedon' => time(),
            'code_response' => 200,
        );

        /* @var antiBotHits $object */
        $object = $this->modx->newObject('antiBotHits');
        $object->fromArray($data);
        if (!$object->save()) {
            return null;
        }
        $this->hit = $object;
        return $object;
    }


    /**
     * Добавление пользоватял в базу данных
     * @param int $guestMd5
     * @param int $userId
     * @param string $userAgent
     * @param boolean $authorized
     * @return int
     */
    private function AddGuest($guestMd5, $userId, $userAgent, $authorized, $context)
    {
        $data = array(
            'session_hash' => $guestMd5,
            'user_id' => $userId,
            'context' => $context,
            'ip' => $this->getIp(),
            'day' => date('Y-m-d', time()),
            'user_agent' => $userAgent,
            'authorized' => $authorized,
            'createdon' => time(),
            'updatedon' => time()
        );

        /* @var antiBotGuest $Guest */
        $Guest = $this->modx->newObject('antiBotGuest');
        $Guest->fromArray($data);

        if ($Guest->save()) {
            $guestId = $Guest->get('id');
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Не удалось создать гостевого пользователя", '', __METHOD__, __FILE__, __LINE__);
            $guestId = 0;
        }

        if ($guestId > 0) {
            // Сохранение в куки индитификатора
            setcookie("APIKIT_BOTSTOP_GUEST_ID", $guestId, time() + (60 * 60 * 24 * 30), '/');
            $_SESSION["APIKIT_BOTSTOP_GUEST_ID"] = $guestId;
        }
        return $guestId;
    }

    /**
     * Обновление количества хитов для пользователя
     * @param antiBotGuest $guest
     * @param int $userId
     * @param string $userAgent
     * @param boolean $authorized
     * @return bool
     */
    private function UpdateGuest($guest, $userId, $userAgent, $authorized)
    {
        $guest->fromArray(
            array(
                'user_id' => $userId,
                'user_agent' => $userAgent,
                'authorized' => $authorized,
                'hits' => intval($guest->get('hits')) + 1,
                'updatedon' => time(),
            )
        );
        if (!$guest->save()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Error update guest" . $guest->get('id'), '', __METHOD__, __FILE__, __LINE__);
            return false;
        }
        return true;
    }

    /**
     * Удаление устаревшей статистики
     */
    private function removeStatistic()
    {
        $max_day = (int)trim($this->modx->getOption('antibot_max_day', $this->config, 3));
        if ($max_day == 0) {
            $max_day = 1;
        }

        $current_day = date('Y-m-d H:i:s', time());
        if (!$object = $this->modx->getObject('modSystemSetting', array('key' => 'antibot_last_date_remove'))) {
            /* @var modSystemSetting $object */
            $object = $this->modx->newObject('modSystemSetting');
            $object->set('key', 'antibot_last_date_remove');
            $object->set('value', date('Y-m-d', strtotime($current_day)));
            $object->set('editedon', time());
            $object->save();
        }
        $last_date_remove = $object->get('value');


        // Текущая дата
        $remove = false;
        if (empty($last_date_remove)) {
            $remove = true;
        } else {
            $today = strtotime(date('Y-m-d', strtotime($current_day)));
            $lastday = strtotime(date('Y-m-d', strtotime('+' . $max_day . ' days', strtotime($last_date_remove))));
            if ($today >= $lastday) {
                $remove = true;
            }
        }

        if ($remove) {
            $this->removeRecords($current_day);
            // Установка новой даты обновления
            $object->set('value', date('Y-m-d', strtotime($current_day)));
            $object->save();
        }

    }

    /**
     * Удаление устаревшх записей по наступлению дня максимального хранения записей
     * @param $current_day
     */
    public function removeRecords($current_day)
    {
        /* @var antiBotHits $object */
        $q = $this->modx->newQuery('antiBotHits');
        $q->where(array(
            'updatedon:<' => $current_day
        ));
        if ($objectList = $this->modx->getIterator('antiBotHits', $q)) {
            foreach ($objectList as $object) {
                $object->remove();
            }
        }

        /* @var antiBotHits $object */
        $q = $this->modx->newQuery('antiBotGuest');
        $q->where(array(
            'updatedon:<' => $current_day
        ));
        if ($objectList = $this->modx->getIterator('antiBotGuest', $q)) {
            foreach ($objectList as $object) {
                $object->remove();
            }
        }
    }


    public $hostInfo = [];

    /**
     * Определяет имя поисковой системы
     * @param $userAgent
     * @return null|array
     */
    public function getCollectionIp(antiBotRule $Rule)
    {

        $ips = null;
        $arrays = $Rule->getCollectionIp();
        if (!empty($arrays)) {
            foreach ($arrays as $k => $row) {
                $total = $row['total'];
                $user_id = $row['user_id'];
                $userAgent = $row['user_agent'];
                $guest_id = $row['id'];
                $ip = $row['ip'];
                $isSearchSystem = false;
                if ($searchSystem = $this->isSearchSystemBot($userAgent)) {
                    // Получает результаты проверки обратных DNS
                    $result = $this->isFakeGuest($ip, $searchSystem);
                    if ($result === true) {
                        $isSearchSystem = true;
                    }
                }
                $ips[] = [
                    'guest_id' => $guest_id,
                    'user_id' => $user_id,
                    'userAgent' => $userAgent,
                    'ip' => $ip,
                    'search_system' => $isSearchSystem,
                    'total_hits' => $total
                ];
            }
        }
        return $ips;
    }

    /**
     * Определяет по UserAgent что это возможно поисковая система
     * @param $userAgent
     * @return string|null
     */
    public function isSearchSystemBot($userAgent)
    {
        foreach ($this->rules as $name => $rule) {
            if (strripos($userAgent, $name) !== false) {
                return $name;
            }
        }
        return null;
    }

    public function isFakeGuest($ipbota, $bot)
    {
        $bot_hostname = gethostbyaddr($ipbota);
        $ip = gethostbyname($bot_hostname);
        $hostname = gethostbyaddr($ip);

        /* @var antiBot $antiBot */
        $rule = $this->rules[$bot];
        $params = array(
            'bot_hostname' => $bot_hostname,
            'hostname' => $hostname,
            'ipbota' => $ipbota,
            'bot' => $bot
        );


        if (!$ip) {
            return $this->modx->lexicon('antibot_api_get_could_not_ip', $params);
        }

        $this->hostInfo['bot'] = $bot;
        $this->hostInfo['bot_hostname'] = $bot_hostname;
        $this->hostInfo['bot_ip'] = $ipbota;

        $this->hostInfo['hostname'] = $hostname;
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

}
