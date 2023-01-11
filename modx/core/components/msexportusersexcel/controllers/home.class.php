<?php

/**
 * The home manager controller for msExportUsersExcel.
 *
 */
class msExportUsersExcelHomeManagerController extends modExtraManagerController
{
    /** @var msExportUsersExcel $msExportUsersExcel */
    public $msExportUsersExcel;


    /**
     *
     */
    public function initialize()
    {
        $this->msExportUsersExcel = $this->modx->getService('msExportUsersExcel', 'msExportUsersExcel', MODX_CORE_PATH . 'components/msexportusersexcel/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['msexportusersexcel:manager'];
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
        return $this->modx->lexicon('msexportusersexcel');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->msExportUsersExcel->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/msexportusersexcel.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/misc/export.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/widgets/profiles/grid.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/widgets/profiles/windows.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/widgets/profiles/windows.testing.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/widgets/table/grid.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/widgets/table/windows.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->msExportUsersExcel->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        msExportUsersExcel.config = ' . json_encode($this->msExportUsersExcel->config) . ';
        msExportUsersExcel.config.connector_url = "' . $this->msExportUsersExcel->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "msexportusersexcel-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="msexportusersexcel-panel-home-div"></div>';
        return '';
    }
}