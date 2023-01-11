<?php

/**
 * The home manager controller for ReadLogJson.
 *
 */
class ReadLogJsonHomeManagerController extends modExtraManagerController
{
    /** @var ReadLogJson $ReadLogJson */
    public $ReadLogJson;


    /**
     *
     */
    public function initialize()
    {
        $this->ReadLogJson = $this->modx->getService('ReadLogJson', 'ReadLogJson', MODX_CORE_PATH . 'components/readlogjson/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['readlogjson:manager', 'readlogjson:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('readlogjson');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->ReadLogJson->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/readlogjson.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/widgets/requests/grid.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/widgets/requests/windows.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->ReadLogJson->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addJavascript(MODX_MANAGER_URL . 'assets/modext/util/datetime.js');

        $this->ReadLogJson->config['date_format'] = $this->modx->getOption('readlogjson_date_format', null, '%d.%m.%y <span class="gray">%H:%M</span>');
        $this->ReadLogJson->config['help_buttons'] = ($buttons = $this->getButtons()) ? $buttons : '';

        $this->addHtml('<script type="text/javascript">
        ReadLogJson.config = ' . json_encode($this->ReadLogJson->config) . ';
        ReadLogJson.config.connector_url = "' . $this->ReadLogJson->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "readlogjson-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .=  '<div id="readlogjson-panel-home-div"></div>';
        return '';
    }

    /**
     * @return string
     */
    public function getButtons()
    {
        $buttons = null;
        $name = 'ReadLogJson';
        $path = "Extras/{$name}/_build/build.php";
        if (file_exists(MODX_BASE_PATH . $path)) {
            $site_url = $this->modx->getOption('site_url').$path;
            $buttons[] = [
                'url' => $site_url,
                'text' => $this->modx->lexicon('readlogjson_button_install'),
            ];
            $buttons[] = [
                'url' => $site_url.'?download=1&encryption_disabled=1',
                'text' => $this->modx->lexicon('readlogjson_button_download'),
            ];
            $buttons[] = [
                'url' => $site_url.'?download=1',
                'text' => $this->modx->lexicon('readlogjson_button_download_encryption'),
            ];
        }
        return $buttons;
    }
}
