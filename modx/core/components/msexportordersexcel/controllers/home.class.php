<?php

/**
 * The home manager controller for msExportOrdersExcel.
 *
 */
class msExportOrdersExcelHomeManagerController extends modExtraManagerController
{
    /** @var msExportOrdersExcel $msExportOrdersExcel */
    public $msExportOrdersExcel;


    /**
     *
     */
    public function initialize()
    {
        $this->msExportOrdersExcel = $this->modx->getService('msExportOrdersExcel', 'msExportOrdersExcel', MODX_CORE_PATH . 'components/msexportordersexcel/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['msexportordersexcel:manager'];
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
        return $this->modx->lexicon('msexportordersexcel');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->msExportOrdersExcel->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/msexportordersexcel.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/misc/export.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/misc/default.grid.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/misc/default.window.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/widgets/profiles/grid.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/widgets/profiles/windows.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/widgets/profiles/windows.testing.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/widgets/table/grid.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/widgets/table/windows.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->msExportOrdersExcel->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        msExportOrdersExcel.config = ' . json_encode($this->msExportOrdersExcel->config) . ';
        msExportOrdersExcel.config.connector_url = "' . $this->msExportOrdersExcel->config['connectorUrl'] . '";
        Ext.onReady(function() {MODx.load({ xtype: "msexportordersexcel-page-home"});});
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="msexportordersexcel-panel-home-div"></div>';
        return '';
    }
}