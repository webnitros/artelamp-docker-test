<?php

class msPromoCode2
{
    public $version = '1.1.5';
    public $config = [];
    public $initialized = [];
    /**
     * @var modX $modx
     */
    public $modx;
    /**
     * @var mspc2Tools $tools
     */
    public $tools;
    /**
     * @var pdoTools $pdoTools
     */
    public $pdoTools;
    /**
     * @var pdoFetch $pdoFetch
     */
    public $pdoFetch;
    /**
     * @var miniShop2 $miniShop2
     */
    public $miniShop2;
    /**
     * @var msOptionsPrice $msOptionsPrice
     */
    public $msOptionsPrice;
    /**
     * @var mspc2Randexp $randexp
     */
    public $randexp;
    /**
     * @var mspc2Manager $manager
     */
    public $manager;


    /**
     * @param modX  $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx = &$modx;

        $corePath = $this->modx->getOption('mspc2_core_path', $config, MODX_CORE_PATH . 'components/mspromocode2/');
        $assetsUrl = $this->modx->getOption('mspc2_assets_url', $config, MODX_ASSETS_URL . 'components/mspromocode2/');
        $assetsPath = $this->modx->getOption('mspc2_assets_path', $config, MODX_ASSETS_PATH . 'components/mspromocode2/');

        $this->config = array_merge([
            'assetsUrl' => $assetsUrl,
            'assetsPath' => $assetsPath,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
            'imagesUrl' => $assetsUrl . 'images/',
            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php',

            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'vendorPath' => $corePath . 'vendor/',
            'pluginsPath' => $corePath . 'plugins/',
            'handlersPath' => $corePath . 'handlers/',
            'templatesPath' => $corePath . 'elements/templates/',
            'processorsPath' => $corePath . 'processors/',

            'prepareResponse' => false,
            'jsonResponse' => false,
        ], $config);

        $this->modx->addPackage('mspromocode2', $this->config['modelPath']);
        $this->modx->lexicon->load('mspromocode2:default');
    }

    /**
     * @param string $ctx
     * @param array  $sp
     *
     * @return boolean
     */
    public function initialize($ctx = 'web', $sp = [])
    {
        $this->config = array_merge($this->config, $sp, ['ctx' => $ctx]);

        $this->getTools();
        $pdoTools = $this->getPdoTools();
        $pdoFetch = $this->getPdoFetch();

        if (empty($this->initialized[$ctx])) {
            switch ($ctx) {
                case 'mgr':
                    break;
                default:
                    // if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
                    //     // $this->loadFrontendScripts();
                    // }
                    break;
            }
        }

        return ($this->initialized[$ctx] = true);
    }

    /**
     * @param array $services
     *
     * @return bool
     */
    public function loadFrontendScripts(array $services = [])
    {
        $flag = false;
        if (empty($services)) {
            $services = [
                'main' => [],
            ];
        }

        $version = time(); // $this->version;
        foreach ([
            'main',
            'generate',
        ] as $service_key) {
            if (!isset($services[$service_key])) {
                continue;
            }
            $service_name = 'msPromoCode2' . ucfirst($service_key);
            $service_properties = is_array($services[$service_key]) ? $services[$service_key] : [];

            if (empty($this->modx->loadedjscripts[$service_name]) && (!defined('MODX_API_MODE') || !MODX_API_MODE)) {
                $pls = $this->tools->makePlaceholders($this->config);
                if ($css = trim($this->modx->getOption('mspc2_frontend_' . $service_key . '_css'))) {
                    $this->modx->regClientCSS(str_replace($pls['pl'], $pls['vl'], $css . '?v=' . $version));
                }
                if ($js = trim($this->modx->getOption('mspc2_frontend_' . $service_key . '_js'))) {
                    $this->modx->regClientScript(str_replace($pls['pl'], $pls['vl'], $js . '?v=' . $version));
                }
                unset($pls, $css, $js);

                $params = $this->modx->toJSON(array_merge([
                    'assetsUrl' => $this->config['assetsUrl'],
                    'actionUrl' => $this->config['actionUrl'],
                    'ctx' => $this->config['ctx'],
                ], $service_properties));

                $this->modx->regClientScript('<script>
                    if (typeof(' . $service_name . 'Cls) === "undefined") {
                        var ' . $service_name . 'Cls = new ' . $service_name . '(' . $params . ');
                    }
                </script>', true);

                $this->modx->loadedjscripts[$service_name] = true;
            }

            $flag = empty($flag) ? !empty($this->modx->loadedjscripts[$service_name]) : $flag;
        }

        return $flag;
    }

    /**
     * @param modManagerController $controller
     * @param null|array $services
     *
     * @return bool
     */
    public function loadManagerScripts(
        modManagerController $controller = null,
        $services = [
            'css/main',
            'js/vendor',
            'js/misc',
            'js/main',
            'js/minishop2',
        ]
    ) {
        $version = time(); // $this->version;

        $controller = is_null($controller) ? $this->modx->controller : $controller;
        if (!(is_object($controller) && ($controller instanceof modManagerController))) {
            return false;
        }

        // //
        // if (isset($controller->resource) && is_object($controller->resource)) {
        //     $available_templates = $this->modx->getOption('ym2_resource_templates', null, '');
        //     if ($available_templates == '-') {
        //         // Не отображать нигде
        //         return true;
        //     } elseif ($available_templates != '') {
        //         // Отображать на конкретных шаблонах
        //         if ($available_templates = array_map('trim', explode(',', $available_templates))) {
        //             if (!in_array($controller->resource->get('template'), $available_templates)) {
        //                 return true;
        //             }
        //         }
        //     }
        // }

        // Lexicon
        $controller->addLexiconTopic('mspromocode2:default');

        // CSS
        if (in_array('css/main', $services)) {
            $controller->head['css'][] = $this->config['cssUrl'] . 'mgr/main.css?v=' . $version;
            $controller->head['css'][] = $this->config['cssUrl'] . 'mgr/bootstrap.buttons.css?v=' . $version;
        }

        // JS / Vendors
        if (in_array('js/vendor', $services)) {
            $controller->head['js'][] = $this->config['jsUrl'] . 'vendor/clipboard.min.js';
            $controller->head['js'][] = $this->config['jsUrl'] . 'vendor/strftime.min.js';
            $controller->head['js'][] = $this->config['jsUrl'] . 'vendor/randexp.min.js';
        }

        // JS / Class
        $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/mspromocode2.js?v=' . $version;

        // JS / Config
        $controller->addHtml('
            <script>
                msPromoCode2.config = ' . json_encode($this->config) . ';
                msPromoCode2.config[\'connector_url\'] = "' . $this->config['connectorUrl'] . '";
            </script>
        ');

        // JS / Other
        if (in_array('js/misc', $services)) {
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/ux.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/utils.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/renderer.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/combo.js?v=' . $version;

            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/default/grid.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/default/window.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/default/formpanel.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/misc/default/panel.js?v=' . $version;
        }

        // JS / Main
        if (in_array('js/main', $services)) {
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/widgets/joins/grid.js?v=' . $version;

            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/widgets/coupons/grid.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/widgets/coupons/window.js?v=' . $version;

            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/widgets/home.panel.js?v=' . $version;
            $controller->head['js'][] = $this->config['jsUrl'] . 'mgr/sections/home.js?v=' . $version;

            // Config
            $controller->addHtml('
                <script>
                    Ext.onReady(function() {
                        MODx.load({
                            xtype: "mspromocode2-page-home",
                        });
                    });
                </script>
            ');
        }

        // JS / miniShop2
        if (in_array('js/minishop2', $services)) {
            $controller->head['lastjs'][] = $this->config['jsUrl'] . 'mgr/extends/ms2/orders.grid.logs.js?v=' . $version;
            $controller->head['lastjs'][] = $this->config['jsUrl'] . 'mgr/extends/ms2/orders.window.js?v=' . $version;
        }

        //
        // /** @var modUser $user */
        // /** @var modResource $resource */
        // foreach (
        //     array(
        //         'resource' => 'modResource',
        //         'user' => 'modUser',
        //     ) as $k => $v
        // ) {
        //     if (!isset($controller->{$k}) || !is_object($controller->{$k})) {
        //         continue;
        //     }
        //     $this->config['default'] = array(
        //         'parent' => $controller->{$k}->get('id'),
        //         'class' => $controller->{$k}->get('class_key'),
        //         'list' => 'default',
        //     );
        //     $controller->head['lastjs'][] = $this->config['jsUrl'] . 'mgr/inject/' . $k . '.tab.js';
        // }

        return true;
    }

    /**
     * @param array $config
     *
     * @return mspc2Tools
     */
    public function getTools(array $config = [])
    {
        if (!is_object($this->tools)) {
            if ($class = $this->modx->loadClass('tools.mspc2Tools', $this->config['handlersPath'], true, true)) {
                $this->tools = new $class($this, $config);
            }
        }

        return $this->tools;
    }

    /**
     * @return pdoTools
     */
    public function getPdoTools()
    {
        if (class_exists('pdoTools') && !is_object($this->pdoTools)) {
            $this->pdoTools = $this->modx->getService('pdoTools');
        }

        return $this->pdoTools;
    }

    /**
     * @return pdoFetch
     */
    public function getPdoFetch()
    {
        if (class_exists('pdoFetch') && !is_object($this->pdoFetch)) {
            $this->pdoFetch = $this->modx->getService('pdoFetch');
        }

        return $this->pdoFetch;
    }

    /**
     * @return miniShop2
     */
    public function getMiniShop2()
    {
        if (!is_object($this->miniShop2)) {
            $this->miniShop2 = $this->modx->getService('miniShop2');
        }
        if (is_object($this->miniShop2)) {
            $this->modx->lexicon->load('minishop2:default');
        }

        return $this->miniShop2;
    }

    /**
     * @return msOptionsPrice
     */
    public function getMsOptionsPrice()
    {
        $path = MODX_CORE_PATH . 'components/msoptionsprice/model/msoptionsprice/';
        if (file_exists($path) && !is_object($this->msOptionsPrice)) {
            $this->msOptionsPrice = $this->modx->getService('msoptionsprice', 'msOptionsPrice', $path);
        }

        return $this->msOptionsPrice;
    }

    /**
     * @return mspc2Randexp
     */
    public function getRandexp()
    {
        if (!is_object($this->randexp)) {
            if ($class = $this->modx->loadClass('randexp.mspc2Randexp', $this->config['handlersPath'], true, true)) {
                $this->randexp = new $class($this);
            }
        }

        return $this->randexp;
    }

    /**
     * @param array $config
     *
     * @return mspc2Manager
     */
    public function getManager(array $config = [])
    {
        if (!is_object($this->manager)) {
            if ($class = $this->modx->loadClass('manager.mspc2Manager', $this->config['handlersPath'], true, true)) {
                $this->manager = new $class($this, $config);
            }
        }

        if (is_object($this->manager)) {
            // Load logs lexicon
            $cultureKey = $this->modx->getOption('cultureKey', null, 'ru');
            $this->modx->lexicon->load($cultureKey . ':mspromocode2:default');

            //
            $this->manager->initialize($this->config['ctx'] ?: 'web');
        }

        return $this->manager;
    }
}