<?php

/**
 * The home manager controller for antiBot.
 *
 */
class antiBotHomeManagerController extends modExtraManagerController
{
    /** @var antiBot $antiBot */
    public $antiBot;


    /**
     *
     */
    public function initialize()
    {
        $this->antiBot = $this->modx->getService('antiBot', 'antiBot', MODX_CORE_PATH . 'components/antibot/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['antibot:manager','antibot:default'];
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
        return $this->modx->lexicon('antibot');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->antiBot->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/antibot.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/misc/default.combo.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/hits/grid.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/guests/grid.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/guests/windows.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/rules/grid.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/rules/windows.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/rules/window/ips.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/stoplists/grid.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/stoplists/windows.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->antiBot->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        antiBot.config = ' . json_encode($this->antiBot->config) . ';
        antiBot.config.connector_url = "' . $this->antiBot->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "antibot-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="antibot-panel-home-div"></div>';

        return '';
    }
}
