<?php


//ini_set('display_errors', 1);
//ini_set('error_reporting', -1);

if (!function_exists("array_column")) {
    function array_column($array, $column_name)
    {
        return array_map(function ($element) use ($column_name) {
            return $element[$column_name];
        }, $array);
    }

}


class UserLocation
{

    /* @var modX $modx */
    public $modx;
    /** @var string $version */
    public $version = '1.0.3-beta';
    /** @var mixed|null $namespace */
    public $namespace = 'userlocation';
    /** @var array $config */
    public $config = [];
    /** @var array $initialized */
    public $initialized = [];


    /**
     * @param  modX   $modx
     * @param  array  $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

        $this->modx->addPackage('userlocation', MODX_CORE_PATH.'components/userlocation/model/');
        $this->setConfig($config);
        $this->checkStat();
    }

    function __call($n, array $p)
    {
        echo __METHOD__.' says: '.$n;
    }

    public function setConfig(array $config = [], $reset = true)
    {
        $corePath = MODX_CORE_PATH.'components/userlocation/';
        $assetsUrl = MODX_ASSETS_URL.'components/userlocation/';

        if ($reset) {
            $config = array_merge([
                'assetsUrl'      => $assetsUrl,
                'cssUrl'         => $assetsUrl.'css/',
                'jsUrl'          => $assetsUrl.'js/',
                'connectorUrl'   => $assetsUrl.'connector.php',
                'corePath'       => $corePath,
                'modelPath'      => $corePath.'model/',
                'customPath'     => $corePath.'custom/',
                'processorsPath' => $corePath.'processors/',
            ], $config);
        } else {
            $config = array_merge($this->config, $config);
        }

        if (empty($config['cssUrl'])) {
            $config['cssUrl'] = $assetsUrl.'css/';
        }
        if (empty($config['jsUrl'])) {
            $config['jsUrl'] = $assetsUrl.'js/';
        }
        if (empty($config['connectorUrl'])) {
            $config['connectorUrl'] = $assetsUrl.'connector.php';
        }
        if (empty($config['customPath'])) {
            $config['customPath'] = $corePath.'custom/';
        }

        $this->config = $config;
    }


    public function translatePath($path)
    {
        return str_replace([
            '{core_path}',
            '{base_path}',
            '{assets_path}',
        ], [
            $this->modx->getOption('core_path', null, MODX_CORE_PATH),
            $this->modx->getOption('base_path', null, MODX_BASE_PATH),
            $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH),
        ], $path);
    }


    public function getOption($key, $config = [], $default = null, $skipEmpty = false)
    {
        $option = $default;
        if (!empty($key) AND is_string($key)) {
            if ($config != null AND array_key_exists($key, $config)) {
                $option = $config[$key];
            } else {
                if (array_key_exists($key, $this->config)) {
                    $option = $this->config[$key];
                } else {
                    if (array_key_exists($this->namespace.'_'.$key, $this->modx->config)) {
                        $option = $this->modx->getOption($this->namespace.'_'.$key);
                    }
                }
            }
        }
        if ($skipEmpty AND empty($option)) {
            $option = $default;
        }

        return $option;
    }


    public function checkLocation($location)
    {
        /** @var ulLocation $location */
        if (!empty($location) AND is_object($location) AND ($location instanceof ulLocation) AND $location->isWork()) {
            return true;
        }

        return false;
    }

    public function getEmptyLocation()
    {
        if ($defaultId = $this->getOption('default_location', null)) {
            $location = $this->modx->getObject('ulLocation', ['id' => $defaultId]);
        }

        if (!$this->checkLocation($location)) {
            /** @var ulLocation $location */
            $location = $this->modx->newObject('ulLocation');
            $location->fromArray([
                'id'   => 0,
                'name' => $this->getOption('default_location_name', '', '(location)', true),
            ], '', true);

        }

        return $location;
    }

    public function detectLocation($ip = '', $detect = false)
    {
        if ($this->getOption('detect_location', null) OR $detect) {
            $location = $this->processMethod(['method' => 'detectLocation', 'ip' => $ip]);
        }

        return $location;
    }

    public function getLocation($id = '')
    {
        /** @var ulLocation $location */
        $location = $this->modx->newObject('ulLocation');
        $response = $this->invokeEvent('ulOnBeforeGetLocation', [
            'object' => &$location,
            'id'     => $id,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

        if (!$this->checkLocation($location)) {
            if ($id !== '') {
                $location = $this->modx->getObject('ulLocation', ['id' => $id]);
            }
        }
        if (!$this->checkLocation($location)) {
            if (isset($_SESSION['userlocation.id'])) {
                $location = $this->modx->getObject('ulLocation', ['id' => $_SESSION['userlocation.id']]);
            }
        }
        if (!$this->checkLocation($location)) {
            $location = $this->detectLocation();
        }
        if (!$this->checkLocation($location)) {
            $location = $this->getEmptyLocation();
        }

        $response = $this->invokeEvent('ulOnGetLocation', [
            'object' => &$location,
            'id'     => $id,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

        if (!$this->checkLocation($location)) {
            $location = $this->getEmptyLocation();
        }

        return $location;
    }

    public function setLocation($id = '')
    {
        /** @var ulLocation $location */
        $location = $this->modx->newObject('ulLocation');
        $response = $this->invokeEvent('ulOnBeforeSetLocation', [
            'object' => &$location,
            'id'     => $id,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

        if (!$this->checkLocation($location)) {
            if ($id !== '') {
                $location = $this->modx->getObject('ulLocation', ['id' => $id]);
            }
        }
        if (!$this->checkLocation($location)) {
            $location = $this->getEmptyLocation();
        }
        if ($this->checkLocation($location)) {
            $_SESSION['userlocation.id'] = $location->get('id');
        }

        $response = $this->invokeEvent('ulOnSetLocation', [
            'object' => &$location,
            'id'     => $id,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

        if (!$this->checkLocation($location)) {
            $location = $this->getEmptyLocation();
        }

        return $location;
    }

    public function processLocation(ulLocation $location)
    {
        $response = $this->invokeEvent('ulOnProcessLocation', [
            'object' => &$location,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }
        if ($this->checkLocation($location)) {
            return $location->toArray();
        }

        return $location;
    }


    /**
     * @param          $array
     * @param  string  $delimiter
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
     * @param          $array
     * @param  string  $delimiter
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


    public function formatPlaceholders(array $array = [], $s = '.')
    {
        $save = $array;
        $exclude = [];
        if ($tmp = preg_grep('/(['.$s.'])/usi', array_keys($array))) {
            $exclude = array_merge($exclude, $tmp);
        }
        // clear data
        $array = array_diff_key($array, array_flip($exclude));
        // add ns
        foreach ($exclude as $k) {
            list($ns, $key) = explode($s, $k);
            if (!isset($array[$ns])) {
                $array[$ns] = [];
            }
            $array[$ns][$key] = $save[$k];
        }

        return $array;
    }


    /**
     * Transform array to placeholders
     *
     * @param  array   $array
     * @param  string  $plPrefix
     * @param  string  $prefix
     * @param  string  $suffix
     * @param  bool    $uncacheable
     *
     * @return array
     */
    public function makePlaceholders(array $array = [], $plPrefix = '', $prefix = '[[+', $suffix = ']]', $uncacheable = true)
    {
        $result = ['pl' => [], 'vl' => []];

        $uncached_prefix = str_replace('[[', '[[!', $prefix);
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result = array_merge_recursive($result, $this->makePlaceholders($v, $plPrefix.$k.'.', $prefix, $suffix, $uncacheable));
            } else {
                $pl = $plPrefix.$k;
                $result['pl'][$pl] = $prefix.$pl.$suffix;
                $result['vl'][$pl] = $v;
                if ($uncacheable) {
                    $result['pl']['!'.$pl] = $uncached_prefix.$pl.$suffix;
                    $result['vl']['!'.$pl] = $v;
                }
            }
        }

        return $result;
    }

    public function initialize($ctx = 'web', $config = [])
    {
        if (isset($this->initialized[$ctx])) {
            return $this->initialized[$ctx];
        }

        $this->setConfig(array_merge($config, ['ctx' => $ctx]), false);

        $lang = $this->modx->getOption('cultureKey', null, 'en');
        $this->modx->lexicon->load($lang.':userlocation:default');
        $this->modx->lexicon->load($lang.':userlocation:manager');
        $this->modx->lexicon->load($lang.':userlocation:errors');

        if (!defined('MODX_API_MODE') OR !MODX_API_MODE) {


        }

        $load = true;//$this->loadServices($ctx);
        $this->initialized[$ctx] = $load;

        return $load;
    }

    public function regClientStartupScript($src, $plaintext)
    {
        $src = trim($src);
        if (!empty($src)) {
            $this->modx->regClientStartupScript($src, $plaintext);
        }
    }


    public function regClientScript($src, $version = '')
    {
        $src = trim($src);
        if (!empty($src)) {
            if (!empty($version)) {
                $version = '?v='.dechex(crc32($version));
            } else {
                $version = '';
            }

            // check is load
            if (empty($version)) {
                $tmp = preg_replace('/\[\[.*?\]\]/', '', $src);
                foreach ($this->modx->loadedjscripts as $script => $v) {
                    if (strpos($script, $tmp) != false) {
                        return;
                    }
                }
            }

            $pls = $this->makePlaceholders($this->config);
            $src = str_replace($pls['pl'], $pls['vl'], $src);
            $this->modx->regClientScript($src.$version, false);
        }
    }


    public function regClientCSS($src, $version = '')
    {
        $src = trim($src);
        if (!empty($src)) {
            if (!empty($version)) {
                $version = '?v='.dechex(crc32($version));
            } else {
                $version = '';
            }

            // check is load
            if (empty($version)) {
                $tmp = preg_replace('/\[\[.*?\]\]/', '', $src);
                foreach ($this->modx->loadedjscripts as $script => $v) {
                    if (strpos($script, $tmp) != false) {
                        return;
                    }
                }
            }

            $pls = $this->makePlaceholders($this->config);
            $src = str_replace($pls['pl'], $pls['vl'], $src);
            $this->modx->regClientCSS($src.$version, null);
        }
    }


    public function success($msg = '', $data = [], $total = null)
    {
        return $this->response(true, $msg, $data, $total);
    }

    public function response(
        $success = false, $msg = '', $data = [], $total = null
    ) {
        $data = $this->modx->error->toArray($data);
        $success = (boolean)$success;
        $msg = $msg ? $this->lexicon($msg, $data) : $msg;

        $response = [
            'success' => $success, 'message' => $msg, 'data' => $data,
        ];

        return json_encode($response);
    }

    /**
     * return lexicon message if possibly
     *
     * @param  string  $message
     *
     * @return string $message
     */
    public function lexicon($message, $placeholders = [])
    {
        $key = '';
        if ($this->modx->lexicon->exists($message)) {
            $key = $message;
        } else {
            if ($this->modx->lexicon->exists($this->namespace.'_'.$message)) {
                $key = $this->namespace.'_'.$message;
            }
        }
        if ($key !== '') {
            $message = $this->modx->lexicon->process($key, $placeholders);
        }

        return $message;
    }

    public function failure($msg = '', $data = [], $total = null)
    {

        return $this->response(false, $msg, $data, $total);
    }

    public function loadCustomClasses($type)
    {
        $type = strtolower($type);
        if (file_exists($this->config['customPath'].$type)) {
            $files = scandir($this->config['customPath'].$type, true);
            if ($files) {
                foreach ($files as $file) {
                    if (preg_match('/.*?\.class\.php$/i', $file)) {
                        /** @noinspection PhpIncludeInspection */
                        include_once($this->config['customPath'].$type.'/'.$file);
                    }
                }
            }
        }
    }

    public function getHandler($type, array $config = [])
    {
        $type = strtolower($type);

        // Default type classes
        if (!class_exists('UserLocation'.ucfirst($type).'Handler')) {
            if (file_exists(__DIR__.'/userlocation'.$type.'handler.class.php')) {
                require_once __DIR__.'/userlocation'.$type.'handler.class.php';
            }
        }

        // Custom type class
        $class = $this->getOption($type.'_handler_class', null, 'UserLocation'.ucfirst($type).'Handler');
        if ($class !== 'UserLocation'.ucfirst($type).'Handler') {
            $this->loadCustomClasses($type);
        }

        if (!class_exists($class)) {
            $class = 'UserLocation'.ucfirst($type).'Handler';
        }

        if (!class_exists($class)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not get "'.$type.'" handler class: "'.$class.'"');
        } else {
            /** @var UserLocationHandler $service */
            $service = new $class($this, $config);
            if (!$service OR $service->initialize() !== true) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not initialize "'.$type.'" handler class: "'.$class.'"');

                return false;
            }

            return $service;
        }

        return false;
    }

    public function processEvent(modSystemEvent $event, array $props = [])
    {
        $event_class = 'ulEvent'.$event->name;
        if (!class_exists($event_class)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not get event class: "'.$event_class.'"');

            return false;
        }

        // Custom class
        $custom_class = $this->getOption('_'.$event_class, null, $event_class, true);
        if ($custom_class != $event_class) {
            $this->loadCustomClasses('event');
            if (!class_exists($custom_class)) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not get custom event class: "'.$custom_class.'"');
            } else {
                $event_class = $custom_class;
            }
        }

        /** @var ulEvent $handler */
        $handler = new $event_class($this, $event, $props);

        return $handler->process();
    }

    public function processContext($ctx = 'web')
    {
        if ($this->modx->context->key != $ctx AND $this->modx->getCount('modContext', ['key' => $ctx])) {
            $this->modx->switchContext($ctx);
            $this->modx->user = null;
            $this->modx->getUser($ctx);
        }
    }

    public function processMethod(array $props = [])
    {
        $process_class = 'ulMethod'.ucfirst($props['method']);
        if (empty($props['method']) OR !class_exists($process_class)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not get method class: "'.$process_class.'"');

            return false;
        }

        // Custom class
        $custom_class = $this->getOption($process_class, null, $process_class, true);
        if ($custom_class != $process_class) {
            $this->loadCustomClasses('method');
            if (!class_exists($custom_class)) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not get custom method class: "'.$custom_class.'"');
            } else {
                $process_class = $custom_class;
            }
        }

        /** @var ulMethod $handler */
        $handler = new $process_class($this, $props);

        return $handler->process();
    }


    /** @return array Grid Location Fields */
    public function getGridLocationFields()
    {
        $fields = $this->getOption('grid_location_fields', null,
            'id,type,name,postal,active', true);
        $fields .= ',id,name,actions,properties';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    /** @return array Window Location Fields */
    public function getWindowLocationFields()
    {
        $fields = $this->getOption('window_location_fields', null,
            'id,name,name,parent,type,resource,active', true);
        $fields .= ',id,name,actions';
        $fields = $this->explodeAndClean($fields);

        return $fields;
    }

    public function injectScript()
    {
        $pls = $this->makePlaceholders($this->config);
        $actionUrl = str_replace($pls['pl'], $pls['vl'], $this->getOption('actionUrl', null, '[[+assetsUrl]]action.php'));
        $this->modx->regClientStartupHTMLBlock(preg_replace(['/^\n/', '/[ ]{2,}|[\t]/'], '', '
            <meta name="userlocation:version" content="'.$this->version.'">
            <meta name="userlocation:ctx" content="'.$this->config['ctx'].'">
            <meta name="userlocation:actionUrl" content="'.$actionUrl.'">
            '));

        $css = $this->getOption('frontCss', $this->config, $this->modx->getOption('userlocation_front_css', null), true);
        $this->regClientCSS($css, $this->version);
        $js = $this->getOption('frontJs', $this->config, $this->modx->getOption('userlocation_front_js', null), true);
        $this->regClientScript($js, $this->version);
    }

    public function injectControllerScript($page, array $block = [])
    {
        /** @var modManagerController|msResourceUpdateController|ResourceUpdateManagerController $controller */
        if (!$controller = &$this->modx->controller) {
            return false;
        }

        $controller->addLexiconTopic('userlocation:default');
        $controller->addLexiconTopic('userlocation:manager');
        $controller->addLexiconTopic('userlocation:errors');

        switch ($page) {
            case 'widget':
                $block = ['config', 'base', 'widget', 'location'];
                break;
        }

        $config = [
            'connector_url'          => $this->config['connectorUrl'],
            'grid_location_fields'   => $this->getGridLocationFields(),
            'window_location_fields' => $this->getWindowLocationFields(),
        ];

        if (in_array('config', $block)) {
            $controller->addHtml('<script type="text/javascript">userlocation.config='.json_encode($config, JSON_UNESCAPED_UNICODE).';</script>');
        }

        if (in_array('base', $block)) {
            $controller->addCss($this->config['cssUrl'].'mgr/main.css?v='.$this->version);
            $controller->addCss($this->config['cssUrl'].'mgr/bootstrap.buttons.css');
            $controller->addJavascript($this->config['jsUrl'].'mgr/userlocation.js?v='.$this->version);
            $controller->addJavascript($this->config['jsUrl'].'mgr/misc/tools.js?v='.$this->version);
            $controller->addJavascript($this->config['jsUrl'].'mgr/misc/combo.js?v='.$this->version);
        }

        if (in_array('location', $block)) {
            $controller->addLastJavascript($this->config['jsUrl'].'mgr/location/location.grid.js?v='.$this->version);
            $controller->addLastJavascript($this->config['jsUrl'].'mgr/location/location.window.js?v='.$this->version);
        }

    }


    /**
     * Sets data to cache
     *
     * @param  mixed  $data
     * @param  mixed  $options
     *
     * @return string $cacheKey
     */
    public function setCache($data = [], $options = [])
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        if (!empty($cacheKey) AND !empty($cacheOptions) AND $this->modx->getCacheManager()) {
            $this->modx->cacheManager->set($cacheKey, $data, $cacheOptions[xPDO::OPT_CACHE_EXPIRES], $cacheOptions);
        }

        return $cacheKey;
    }

    /**
     * Returns key for cache of specified options
     *
     * @return bool|string
     * @var mixed $options
     */
    public function getCacheKey($options = [])
    {
        if (empty($options)) {
            $options = $this->config;
        }
        if (!empty($options['cache_key'])) {
            return $options['cache_key'];
        }
        $key = !empty($this->modx->resource) ? $this->modx->resource->getCacheKey() : '';

        return $key.'/'.sha1(serialize($options));
    }

    /**
     * Returns array with options for cache
     *
     * @param $options
     *
     * @return array
     */
    public function getCacheOptions($options = [])
    {
        if (empty($options)) {
            $options = $this->config;
        }
        $cacheOptions = [
            xPDO::OPT_CACHE_KEY     => empty($options['cache_key']) ? 'default' : 'default/'.$this->namespace.'/',
            xPDO::OPT_CACHE_HANDLER => !empty($options['cache_handler']) ? $options['cache_handler'] : $this->modx->getOption('cache_resource_handler', null, 'xPDOFileCache'),
            xPDO::OPT_CACHE_EXPIRES => $options['cacheTime'] !== '' ? (integer)$options['cacheTime'] : (integer)$this->modx->getOption('cache_resource_expires', null, 0),
        ];

        return $cacheOptions;
    }

    /**
     * Returns data from cache
     *
     * @param  mixed  $options
     *
     * @return mixed
     */
    public function getCache($options = [])
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
     * @param  array  $options
     *
     * @return bool
     */
    public function clearCache($options = [])
    {
        $cacheKey = $this->getCacheKey($options);
        $cacheOptions = $this->getCacheOptions($options);
        $cacheOptions['cache_key'] .= $cacheKey;
        if (!empty($cacheOptions) AND $this->modx->getCacheManager()) {
            return $this->modx->cacheManager->clean($cacheOptions);
        }

        return false;
    }


    public function invokeEvent($eventName, array $props = [], $glue = '<br/>')
    {
        if (isset($this->modx->event->returnedValues)) {
            $this->modx->event->returnedValues = null;
        }

        $response = $this->modx->invokeEvent($eventName, $props);
        if (is_array($response) AND count($response) > 1) {
            foreach ($response as $k => $v) {
                if (empty($v)) {
                    unset($response[$k]);
                }
            }
        }

        $message = is_array($response) ? implode($glue, $response) : trim((string)$response);
        if (isset($this->modx->event->returnedValues) AND is_array($this->modx->event->returnedValues)) {
            $props = array_merge($props, $this->modx->event->returnedValues);
        }

        return [
            'success' => empty($message),
            'message' => $message,
            'data'    => $props,
        ];
    }


    protected function checkStat()
    {
        $key = strtolower(__CLASS__);
        /** @var modDbRegister $registry */
        $registry = $this->modx->getService('registry', 'registry.modRegistry')->getRegister('user', 'registry.modDbRegister');
        $registry->connect();
        $registry->subscribe('/modstore/'.md5($key));
        if ($res = $registry->read(['poll_limit' => 1, 'remove_read' => false])) {
            return;
        }
        $c = $this->modx->newQuery('transport.modTransportProvider', ['service_url:LIKE' => '%modstore%']);
        $c->select('username,api_key');
        /** @var modRest $rest */
        $rest = $this->modx->getService('modRest', 'rest.modRest', '', [
            'baseUrl'        => 'https://modstore.pro/extras',
            'suppressSuffix' => true,
            'timeout'        => 1,
            'connectTimeout' => 1,
        ]);

        if ($rest) {
            $level = $this->modx->getLogLevel();
            $this->modx->setLogLevel(modX::LOG_LEVEL_FATAL);
            $rest->post('stat', [
                'package'            => $key,
                'version'            => $this->version,
                'keys'               => ($c->prepare() AND $c->stmt->execute()) ? $c->stmt->fetchAll(PDO::FETCH_ASSOC) : [],
                'uuid'               => $this->modx->uuid,
                'database'           => $this->modx->config['dbtype'],
                'revolution_version' => $this->modx->version['code_name'].'-'.$this->modx->version['full_version'],
                'supports'           => $this->modx->version['code_name'].'-'.$this->modx->version['full_version'],
                'http_host'          => $this->modx->getOption('http_host'),
                'php_version'        => XPDO_PHP_VERSION,
                'language'           => $this->modx->getOption('manager_language'),
            ]);
            $this->modx->setLogLevel($level);
        }
        $registry->subscribe('/modstore/');
        $registry->send('/modstore/', [md5($key) => true], ['ttl' => 3600 * 24]);
    }

}


abstract class ulEvent
{

    /** @var modX $modx */
    public $modx;
    /** @var UserLocation $UserLocation */
    public $UserLocation;
    /** @var string */
    public $prefix;
    /** @var array $props */
    protected $props;
    /** @var bool $anon */
    protected $ctx;

    /** @var bool $anon */
    protected $ajax;


    public function __construct(UserLocation &$UserLocation, modSystemEvent $event, &$props)
    {
        $this->UserLocation = &$UserLocation;
        $this->modx = &$UserLocation->modx;
        $this->props =& $props;

        $this->ctx = !empty($props['ctx']) ? (string)$props['ctx'] : $this->modx->context->key;
        $this->UserLocation->initialize($this->ctx);

        $this->ajax = (!empty($_REQUEST['service']) AND $_REQUEST['service'] == 'userlocation') AND
        (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) AND $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');

    }

    public function process()
    {
        return $this->run();
    }

    abstract public function run();


    public function getProps()
    {
        return $this->props;
    }


    public function getProp($key, $default = null)
    {
        if (isset($this->props[$key])) {
            return $this->props[$key];
        }

        return $default;
    }


    public function getCtx()
    {
        return $this->ctx;
    }

    public function isAjax()
    {
        return $this->ajax;
    }

}


class ulEventOnHandleRequest extends ulEvent
{
    public function run()
    {
        if ($this->modx->context->key === 'mgr') {
            return;
        }

        if ($location = $this->UserLocation->getLocation()) {
            $pls = $this->UserLocation->processLocation($location);
            $this->modx->toPlaceholders($pls, 'userlocation');
        }

        if ($this->isAjax()) {
            $this->UserLocation->processContext($this->getCtx());
            $response = $this->UserLocation->processMethod($_REQUEST);
            $response = is_string($response) ? $response : json_encode($response);
            @session_write_close();
            exit($response);
        }
    }
}


class ulEventPdoToolsOnFenomInit extends ulEvent
{
    public function run()
    {
        /** @var Fenom $fenom */
        if ($fenom = $this->getProp('fenom')) {
            $UserLocation = $this->UserLocation;
            $fenom->addModifier('getUserLocation', function ($id = '') use ($UserLocation) {
                $location = $UserLocation->getLocation($id);
                if ($UserLocation->checkLocation($location)) {
                    return $this->UserLocation->processLocation($location);
                }

                return false;
            });
            $fenom->addModifier('detectUserLocation', function ($ip = '') use ($UserLocation) {
                $location = $UserLocation->detectLocation($ip, true);
                if (!$UserLocation->checkLocation($location)) {
                    $location = $UserLocation->getEmptyLocation();
                }
                if ($UserLocation->checkLocation($location)) {
                    return $this->UserLocation->processLocation($location);
                }

                return false;
            });
        }
    }
}


abstract class ulMethod
{

    /** @var modX $modx */
    public $modx;
    /** @var UserLocation $UserLocation */
    public $UserLocation;

    /** @var string $beforeSaveEvent The name of the event to fire before run */
    public $beforeRunEvent = '';
    /** @var string $afterSaveEvent The name of the event to fire after run */
    public $afterRunEvent = '';

    /** @var array $props */
    protected $props;
    /** @var string $method */
    protected $method;

    public function __construct(UserLocation &$UserLocation, $props)
    {
        $this->UserLocation = &$UserLocation;
        $this->modx = &$UserLocation->modx;
        $this->props =& $props;

        $this->method = $this->getProp('method');
    }


    abstract public function run();


    public function process()
    {
        /* run the before run event and allow stoppage */
        $before = $this->fireBeforeRunEvent();
        if (!empty($before)) {
            return $this->failure($before);
        }

        $output = $this->run();

        /* run the before run event and allow stoppage */
        $after = $this->fireAfterRunEvent();
        if (!empty($after)) {
            return $this->failure($after);
        }

        return $output;
    }


    public function fireAfterRunEvent()
    {
        if (!empty($this->afterRunEvent)) {
            $response = $this->UserLocation->invokeEvent($this->afterRunEvent, [
                'method' => $this->getMethod(),
                'props'  => $this->getProps(),
                'self'   => &$this,
            ]);

            if (!$response['success']) {
                return $response['message'];
            }
        }

        return false;
    }


    public function fireBeforeRunEvent()
    {
        if (!empty($this->beforeRunEvent)) {
            $response = $this->UserLocation->invokeEvent($this->beforeRunEvent, [
                'method' => $this->getMethod(),
                'props'  => $this->getProps(),
                'self'   => &$this,
            ]);

            if (!$response['success']) {
                return $response['message'];
            }
        }

        return false;
    }


    public function success($msg = '', $data = [], $total = null)
    {
        return $this->UserLocation->response(true, $msg, $data, $total);
    }


    public function failure($msg = '', $data = [], $total = null)
    {

        return $this->UserLocation->response(false, $msg, $data, $total);
    }


    public function getMethod()
    {
        return $this->method;
    }


    public function getProps()
    {
        return $this->props;
    }


    public function getProp($key, $default = null)
    {
        if (isset($this->props[$key])) {
            return $this->props[$key];
        }

        return $default;
    }


    public function getResult()
    {
        return [];
    }


    public function getData($data = [])
    {
        $data = array_merge($data, [
            'props'  => $this->getProps(),
            'result' => $this->getResult(),
        ]);

        return $data;
    }

}


class ulMethodGetLocation extends ulMethod
{

    public function getResult()
    {
        $limit = (int)$this->getProp('limit', 10);
        $limit = ($limit < 100) ? $limit : 100;

        $c = $this->modx->newQuery('ulLocation');
        $c->leftJoin('ulLocation', 'ParentLocation', 'ulLocation.parent = ParentLocation.id');

        $c->where([
            'active' => true,
        ]);
        $query = trim($this->getProp('query'));
        if (!empty($query)) {
            $c->where([
                'id:LIKE'             => "{$query}%",
                'OR:name:LIKE'        => "{$query}%",
                'OR:postal:LIKE'      => "{$query}%",
                'OR:gninmb:LIKE'      => "%{$query}%",
                'OR:okato:LIKE'       => "%{$query}%",
                'OR:oktmo:LIKE'       => "%{$query}%",
                'OR:fias:LIKE'        => "%{$query}%",
                'OR:description:LIKE' => "%{$query}%",
            ]);
        }

        if ($parent = $this->getProp('parent')) {
            $c->where([
                'parent' => $parent,
            ]);
        }
        if ($type = $this->getProp('type')) {
            $c->where([
                'type' => $type,
            ]);
        }
        $id = $this->getProp('id');
        if (!empty($id) AND $this->getProp('combo')) {
            $c->sortby("FIELD (ulLocation.id, {$id})", "DESC");
        }
        $c->groupby('ulLocation.id');
        $c->limit($limit);

        $c->select($this->modx->getSelectColumns('ulLocation', 'ulLocation', '', [], true));
        $c->select($this->modx->getSelectColumns('ulLocation', 'ParentLocation', 'parent_', ['name'], false));

        $result = [];
        if ($c->prepare() AND $c->stmt->execute()) {
            if ($rows = $c->stmt->fetchAll(PDO::FETCH_ASSOC)) {
                $result = $rows;
            }
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($c->stmt->errorInfo(), 1));
        }

        return $result;
    }

    public function run()
    {
        return $this->success('', $this->getData());
    }

}


class ulMethodSetLocation extends ulMethod
{
    public function getResult()
    {
        $id = trim($this->getProp('id'));
        $result = $this->UserLocation->setLocation($id);

        return $result;
    }

    public function run()
    {
        return $this->success('', $this->getData());
    }

}


class ulMethodDetectLocation extends ulMethod
{
    public function getUserIp($key = 'ip')
    {
        if ($this->modx->getRequest()) {
            $data = $this->modx->request->getClientIp();

            return !empty($key) ? $data[$key] : $data;
        }

        return false;
    }

    public function getUserBotByUserAgent()
    {
        $pattern = $this->UserLocation->getOption('bot_pattern', null, "~(Google|Yahoo|Rambler|Bot|Yandex|Spider|Snoopy|Crawler|Finder|Mail|curl)~i", true);

        return preg_match($pattern, $_SERVER['HTTP_USER_AGENT']);
    }

    public function getLocation(ulLocation $location, $data = [])
    {
        $response = $this->UserLocation->invokeEvent('ulOnDetectLocation', [
            'object' => &$location,
            'data'   => $data,
            'self'   => $this,
        ]);
        if (!$response['success']) {
            return $response['message'];
        }

        return $location;
    }

    public function run()
    {
        return;
    }
}




