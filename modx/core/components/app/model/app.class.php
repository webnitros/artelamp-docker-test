<?php

class App
{
    /** @var modX $modx */
    public $modx;

    /** @var array() $config */
    public $config = array();

    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        $corePath = $this->modx->getOption('app_core_path', $config, MODX_CORE_PATH . 'components/app/');
        $assetsPath = $this->modx->getOption('app_assets_path', $config, MODX_ASSETS_PATH . 'components/app/');
        $assetsUrl = $this->modx->getOption('app_assets_url', $config, MODX_ASSETS_URL . 'components/app/');

        $this->config = array_merge([
            'assetsPath' => $assetsPath,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
            'customPath' => $corePath . 'custom/',
            'mapExtension' => MODX_CORE_PATH . 'components/minishop2/plugins/fandeco/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'actionUrl' => $assetsUrl . 'action.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->modx->addPackage('app', $this->config['modelPath']);
        $this->modx->lexicon->load('app:default');

    }




}