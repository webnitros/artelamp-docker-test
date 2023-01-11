<?php

/**
 * The home manager controller for userlocation.
 *
 */
class userlocationHomeManagerController extends modExtraManagerController
{
    /** @var userlocation $userlocation */
    public $userlocation;


    /**
     *
     */
    public function initialize()
    {
        $this->userlocation = $this->modx->getService('userlocation', 'userlocation', MODX_CORE_PATH.'components/userlocation/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['userlocation:default'];
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
        return $this->modx->lexicon('userlocation');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->userlocation->config['cssUrl'].'mgr/main.css');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/userlocation.js');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/misc/utils.js');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/misc/combo.js');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/widgets/items.grid.js');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/widgets/items.windows.js');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/widgets/home.panel.js');
        $this->addJavascript($this->userlocation->config['jsUrl'].'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        userlocation.config = '.json_encode($this->userlocation->config).';
        userlocation.config.connector_url = "'.$this->userlocation->config['connectorUrl'].'";
        Ext.onReady(function() {MODx.load({ xtype: "userlocation-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="userlocation-panel-home-div"></div>';

        return '';
    }
}