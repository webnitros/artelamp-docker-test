<?php
if (!class_exists('msManagerController')) {
    require_once dirname(dirname(__FILE__)) . '/index.class.php';
}

/**
 * The home manager controller for mspre.
 *
 */
class mspreHelpManagerController extends mspreMainController {

    public $nameController = 'help';
    public $grid_id = 'mspre-grid-help';


    /**
     * @return void
     */
    public function initialize()
    {
        $this->mspre = $this->modx->getService('mspre', 'mspre', MODX_CORE_PATH . 'components/mspre/model/');
        $this->mspre->classKey = $this->classKey;
        $this->mspre->controller = $this->mspre;


        $this->addCss($this->mspre->config['cssUrl'] . 'mgr/main.css?version=' . $this->version);
        $this->addCss($this->mspre->config['cssUrl'] . 'mgr/bootstrap.buttons.css?version=' . $this->version);
        $this->addJavascript($this->mspre->config['jsUrl'] . 'mgr/mspre.js?version=' . $this->version);

        $this->addHtml('
            <script type="text/javascript">
                mspre.config = ' . $this->modx->toJSON($this->mspre->config) . ';
                mspre.config.controller = "' . $this->config['controller'] . '";
                mspre.config.connector_url = "' . $this->mspre->config['connectorUrl'] . '";
            </script>
            ');
    }

    
    /**
     * @return null|string
     */
    public function getPageTitle() {
        return $this->modx->lexicon('mspre_help');
    }

    /**
     * @return void
     */
    public function loadCustomCssJs() {

        $this->addJavascript($this->mspre->config['jsUrl'] . 'mgr/product/product.grid.js');
        $this->addJavascript($this->mspre->config['jsUrl'] . 'mgr/help.js');

        $script = 'Ext.onReady(function() {
			MODx.add({ xtype: "mspre-panel-all"});
		});';
        $this->addHtml("<script type='text/javascript'>{$script}</script>");
    }


    /**
     * @return string
     */
    public function getTemplateFile() {
        return $this->mspre->config['templatesPath'] . 'help.tpl';
    }
}