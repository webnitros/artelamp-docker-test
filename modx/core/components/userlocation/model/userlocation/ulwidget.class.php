<?php

class ulWidget extends modDashboardWidgetInterface
{
    /** @var UserLocation $UserLocation */
    public $UserLocation;

    function __construct(xPDO &$modx, modDashboardWidget &$widget, modManagerController &$controller)
    {
        parent::__construct($modx, $widget, $controller);

        if (!$this->UserLocation = $modx->getService('userlocation.UserLocation', '', MODX_CORE_PATH.'components/userlocation/model/')) {
            return;
        }
        $this->cssBlockClass = 'dashboard-block-userlocation';
    }

    public function render()
    {
        $this->UserLocation->injectControllerScript('widget');
        $script = 'Ext.onReady(function() {
			MODx.load({ xtype: "userlocation-grid-location", "compact": true, renderTo: "userlocation-panel-widget-div"});
		});';
        $this->controller->addHtml("<script type='text/javascript'>{$script}</script>");

        return '<div id="userlocation-panel-widget-div"></div>';
    }
}

return 'ulWidget';