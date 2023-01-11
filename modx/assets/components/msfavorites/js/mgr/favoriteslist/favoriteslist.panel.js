msFavorites.page.FavoritesList = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        components: [{
            xtype: 'msfavorites-panel-favoriteslist'
            ,renderTo: 'msfavorites-panel-favoriteslist-div'
        }]
    });
    msFavorites.page.FavoritesList.superclass.constructor.call(this,config);
};
Ext.extend(msFavorites.page.FavoritesList,MODx.Component);
Ext.reg('msfavorites-page-favoriteslist',msFavorites.page.FavoritesList);

msFavorites.panel.FavoritesList = function(config) {
    config = config || {};
    Ext.apply(config,{
        border: false
        ,deferredRender: true
        ,baseCls: 'modx-formpanel'
        ,items: [{
            html: '<h2>'+_('msfavorites') + ' :: ' + _('msfavorites_favoriteslist')+'</h2>'
            ,border: false
            ,cls: 'modx-page-header container'
        },{
            xtype: 'modx-tabs'
            ,id: 'msfavorites-favoriteslist-tabs'
            ,bodyStyle: 'padding: 10px'
            ,defaults: { border: false ,autoHeight: true }
            ,border: true
            ,hideMode: 'offsets'
            //,stateful: true
            //,stateId: 'msfavorites-favoriteslist-tabpanel'
            //,stateEvents: ['tabchange']
            //,getState:function() {return { activeTab:this.items.indexOf(this.getActiveTab())};}
            ,items: [{
                title: _('msfavorites_favoriteslist')
                ,items: [{
                    html: '<p>'+_('msfavorites_favoriteslist_intro')+'</p>'
                    ,border: false
                    ,bodyCssClass: 'panel-desc'
                    ,bodyStyle: 'margin-bottom: 10px'
                },{
                    xtype: 'msfavorites-grid-favoriteslist'
                }]
                ,listeners: {/*
                 afterrender : function() {
                 this.on('show', function() {
                 Ext.getCmp('msfavorites-grid-favoriteslist').refresh();
                 });
                 }
                 */}
            }]
        }]
    });
    msFavorites.panel.FavoritesList.superclass.constructor.call(this,config);
};
Ext.extend(msFavorites.panel.FavoritesList,MODx.Panel);
Ext.reg('msfavorites-panel-favoriteslist',msFavorites.panel.FavoritesList);