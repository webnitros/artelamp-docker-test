/** *********************************************** **/
Ext.onReady(function () {

    var tabPanel = Ext.getCmp('modx-leftbar-tabpanel');

    if (tabPanel) {
        var at = tabPanel.getActiveTab();
        if (null === at) {
            tabPanel.setActiveTab(0)
        }
    }
})
