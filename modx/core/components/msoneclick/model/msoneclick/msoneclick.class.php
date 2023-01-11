<?php


/**
 * The base class for msOneClick.
 */
class msOneClick
{
    /* @var modX $modx */
    public $modx;
    public $config;
    public $version = '1.3.0';
    public $initialized = array();
    public $controllers = array();
    /** @var pdoFetch $pdoTools */
    public $pdoTools;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = array())
    {
        $this->modx =& $modx;

        $actionUrl = $this->modx->getOption('msoneclick_assets_url', $config, $this->modx->getOption('assets_url') . 'components/msoneclick/action.php');
        $corePath = $this->modx->getOption('msoneclick_core_path', $config, $this->modx->getOption('core_path') . 'components/msoneclick/');
        $assetsUrl = $this->modx->getOption('msoneclick_assets_url', $config, $this->modx->getOption('assets_url') . 'components/msoneclick/');
        $connectorUrl = $assetsUrl . 'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $connectorUrl,
            'actionUrl' => $actionUrl,
            'corePath' => $corePath,
            'controllersPath' => $corePath . 'controllers/',
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'templatesPath' => $corePath . 'elements/templates/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'processorsPath' => $corePath . 'processors/',
            'captchaPath' => $assetsUrl . 'captcha/image.php',
            'base64_encode' => (boolean)$this->modx->getOption('msoneclick_base64_encode',$config, false)
        ), $config);


        $this->modx->addPackage('msoneclick', $this->config['modelPath']);
        $this->modx->lexicon->load('msoneclick:default');

        if ($this->pdoTools = $this->modx->getService('pdoFetch')) {
            $this->pdoTools->setConfig($this->config);
        }
    }


    /**
     * Initializes component into different contexts.
     *
     * @param string $ctx The context to load. Defaults to web.
     * @param array $scriptProperties Properties for initialization.
     *
     * @return bool
     */
    public function initialize($ctx = 'web', $scriptProperties = array())
    {
        $this->config = array_merge($this->config, $scriptProperties);

        $this->config['ctx'] = $ctx;
        $this->config['pageId'] = $this->modx->resource->id;
        $this->config['close_all_message'] = $this->modx->lexicon('msoc_message_close_all');


        if (!empty($this->initialized[$ctx])) {
            return true;
        }
        switch ($ctx) {
            case 'mgr':
                break;
            default:
                if (!defined('MODX_API_MODE') || !MODX_API_MODE) {

                    $config = $this->makePlaceholders($this->config);
                    if ($css = $this->modx->getOption('msoneclick_frontend_css')) {
                        $css = $this->addVersion($css);
                        $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
                    }

                    $this->config['framework'] = $this->modx->getOption('msoneclick_framework');


                    if ($mask_phone = $this->modx->getOption('msoneclick_mask_phone')) {
                        $this->config['mask_phone'] = (bool)str_replace($config['pl'], $config['vl'], $mask_phone);
                    }

                    if ($mask_phone_format = $this->modx->getOption('msoneclick_mask_phone_format')) {
                        $this->config['mask_phone_format'] = str_replace($config['pl'], $config['vl'], $mask_phone_format);
                    }


                    $this->config['copy_count'] = (boolean)$this->modx->getOption('msoneclick_copy_count', null, true);

                    $config_js = preg_replace(array('/^\n/', '/\t{5}/'), '', '
							msOneClick = {};
							msOneClickConfig = ' . $this->modx->toJSON($this->config) . ';
					');

                    $this->modx->regClientStartupScript("<script type=\"text/javascript\">\n" . $config_js . "\n</script>", true);
                    if ($js = trim($this->modx->getOption('msoneclick_frontend_js'))) {

                        if (!empty($js) && preg_match('/\.js/i', $js)) {
                            $this->modx->regClientScript(preg_replace(array('/^\n/', '/\t{7}/'), '', '
							<script type="text/javascript">
								if(typeof jQuery == "undefined") {
									document.write("<script src=\"' . $this->config['jsUrl'] . 'web/lib/jquery.min.js\" type=\"text/javascript\"><\/script>");
								}
							</script>
							'), true);
                            
                            //version
                            $js = $this->addVersion($js);
                            $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));

                            // TODO как то включить библиотеку
                            #$this->modx->regClientScript(str_replace($config['pl'], $config['vl'],  $this->config['jsUrl'].'web/bootstrap/js/transition.js'));
                        }
                    }

                    // lib bootstrap
                    if ($this->modx->getOption('msoneclick_bootstrap_enabled', null, false)) {
                        $cssBootstrap = $this->config['cssUrl'] . 'web/bootstrap/bootstrap.min.css';
                        $this->modx->regClientCSS($cssBootstrap);
                    }

                }
                $this->initialized[$ctx] = true;
                break;
        }
        return true;
    }

    /**
     * Добавляем версию для нормализации кэширования браузером
     * @param $path
     * @return string
     */
    public function addVersion($path)
    {
        return $path.'?v='.dechex(crc32($this->version));
    }

    /**
     * Регистрация конфика с настройками для одной кнопки
     *
     * @var string $hash - кэш формы
     * @var array $scriptProperties - конфигурация сниппета
     *
     */
    protected function setConfig($hash = '', $scriptProperties = array())
    {

        if (empty($scriptProperties)) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "[" . __CLASS__ . "][" . __LINE__ . "] " . __FUNCTION__ . ": Error set config cache");
        } else if (!isset($_SESSION['msOneClickConfig'][$hash])) {
            $_SESSION['msOneClickConfig'][$hash] = $scriptProperties;
        }
    }

    /**
     * Генерация уникального индитификатора для конфига
     *
     * @param array $scriptProperties
     * @return string
     */
    public function getHastBtn($scriptProperties = array())
    {
        unset($scriptProperties['id']);
        $hash = $this->getHash($scriptProperties);
        $this->setConfig($hash, $scriptProperties);
        return $hash;
    }

    /**
     * Method for transform array to placeholders
     *
     * @var array $array With keys and values
     * @var string $prefix Placeholders prefix
     *
     * @return array $array Two nested arrays With placeholders and values
     */
    public function makePlaceholders(array $array = array(), $prefix = '')
    {
        $result = array('pl' => array(), 'vl' => array());
        foreach ($array as $k => $v) {
            if (is_array($v)) {
                $result = array_merge_recursive($result, $this->makePlaceholders($v, $k . '.'));
            } else {
                $result['pl'][$prefix . $k] = '[[+' . $prefix . $k . ']]';
                $result['vl'][$prefix . $k] = $v;
            }
        }
        return $result;
    }

    /**
     * Method loads custom controllers
     *
     * @var string $dir Directory for load controllers
     *
     * @return void
     */
    public function loadController($name)
    {
        require_once 'controller.class.php';

        $name = strtolower(trim($name));

        $file = $this->config['controllersPath'] . $name . '/' . $name . '.class.php';

        if (!file_exists($file)) {
            $file = $this->config['controllersPath'] . $name . '.class.php';
        }

        if (file_exists($file)) {

            $class = include_once($file);
            if (!class_exists($class)) {
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[msOneClick] Wrong controller at ' . $file);
            } /* @var msoneclickDefaultController $controller */
            else if ($controller = new $class($this, $this->config)) {
                if ($controller instanceof msoneclickDefaultController && $controller->initialize()) {
                    $this->controllers[strtolower($name)] = $controller;
                } else {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[msOneClick] Could not load controller ' . $file);
                }
            }
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, '[msOneClick] Could not find controller ' . $file);
        }

    }

    /**
     * Loads given action, if exists, and transfers work to it
     *
     * @param $action
     * @param array $scriptProperties
     *
     * @return bool
     */
    public function loadAction($action, $scriptProperties = array())
    {
        if (!empty($action)) {
            @list($name, $action) = explode('/', strtolower(trim($action)));


            if (!isset($this->controllers[$name])) {
                $this->loadController($name);
            }

            if (isset($this->controllers[$name])) {
                /* @var officeDefaultController $controller */
                $controller = $this->controllers[$name];
                $controller->setDefault($scriptProperties);

                if (empty($action)) {
                    $action = $controller->getDefaultAction();
                }
                if (method_exists($controller, $action)) {
                    return $controller->$action($scriptProperties);
                }
            } else {
                return 'Could not load controller "' . $name . '"';
            }
        }

        return false;
    }

    /**
     * Shorthand for load and run an processor in this component
     *
     * @param string $action
     * @param array $scriptProperties
     *
     * @return mixed
     */
    public function runProcessor($action = '', $scriptProperties = array())
    {
        $this->modx->error->errors = $this->modx->error->message = null;
        return $this->modx->runProcessor($action, $scriptProperties, array(
                'processors_path' => $this->config['processorsPath']
            )
        );
    }


    /**
     * Вернет массив с префиксами телефонного номера
     * @return array|null
     */
    public function getPrefixPhone()
    {
        $enabled = $this->modx->getOption('msoneclick_prefix_enabled');
        $prefix_phone = $this->modx->getOption('msoneclick_prefix_phone');
        if ($enabled and !empty($prefix_phone)) {
            $prefix_data = null;
            $prefix_phone = explode(',', $prefix_phone);
            if (is_array($prefix_phone)) {
                if (count($prefix_phone) > 0) {
                    foreach ($prefix_phone as $p) {
                        $arr = explode(':', $p);
                        if (count($arr) == 2) {
                            $prefix_data[] = array(
                                'prefix' => $arr[0],
                                'prefix_len' => strlen($arr[0]),  // Длина первой цифры телефона
                                'phone_len' => $arr[1],  // Длина первой цифры телефона
                            );
                        }
                    }

                }
            }
            if (is_array($prefix_data) and count($prefix_data) > 0) {
                return $prefix_data;
            }
        }
        return null;
    }


    /**
     * @param array $params
     *
     * @return string
     */
    public function getHash(array $params)
    {
        $keys = array_keys($params);
        $keys = $this->natsort($keys);

        $values = array_values($params);
        foreach ($values as $k => $v) {
            if (is_array($v)) {
                unset($values[$k]);
            }
        }
        $values = $this->natsort($values);
        $str = implode($keys) . implode($values);

        return md5(strtolower($str));
    }


    /**
     * @param array $array
     *
     * @return array
     */
    protected function natsort(array $array)
    {
        $ints = $strings = array();
        foreach ($array as $v) {
            if (is_numeric($v) || is_bool($v)) {
                $ints[] = (int)$v;
            } elseif (is_array($v)) {
                // Exclude arrays
            } else {
                $strings[] = (string)$v;
            }
        }
        sort($ints);
        sort($strings);

        $res = array();
        foreach ($ints as $v) {
            $res[] = (string)$v;
        }
        foreach ($strings as $v) {
            $res[] = $v;
        }

        return $res;
    }


    /**
     * This method returns an error of the order
     *
     * @param string $message A lexicon key for error message
     * @param array $data .Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function error($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => false,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response'] ? $this->modx->toJSON($response) : $response;
    }


    /**
     * This method returns an success of the order
     *
     * @param string $message A lexicon key for success message
     * @param array $data .Additional data, for example cart status
     * @param array $placeholders Array with placeholders for lexicon entry
     *
     * @return array|string $response
     */
    public function success($message = '', $data = array(), $placeholders = array())
    {
        $response = array(
            'success' => true,
            'message' => $this->modx->lexicon($message, $placeholders),
            'data' => $data,
        );

        return $this->config['json_response'] ? $this->modx->toJSON($response) : $response;
    }

    /**
     * Function for sending email
     *
     * @param string $email
     * @param string $subject
     * @param string $body
     *
     * @return void
     */
    public function sendEmail($email, $subject, $body = '')
    {
        $this->modx->getParser()->processElementTags('', $body, true, false, '[[', ']]', array(), 10);
        $this->modx->getParser()->processElementTags('', $body, true, true, '[[', ']]', array(), 10);

        /** @var modPHPMailer $mail */
        $mail = $this->modx->getService('mail', 'mail.modPHPMailer');
        $mail->setHTML(true);

        $mail->address('to', trim($email));
        $mail->set(modMail::MAIL_SUBJECT, trim($subject));
        $mail->set(modMail::MAIL_BODY, $body);
        $mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender'));
        $mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
        if (!$mail->send()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR,
                'An error occurred while trying to send the email: ' . $mail->mailer->ErrorInfo
            );
        }
        $mail->reset();
    }

}