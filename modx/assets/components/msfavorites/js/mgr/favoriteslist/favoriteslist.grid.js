msFavorites.grid.FavoritesList = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        id: 'msfavorites-grid-favoriteslist'
        ,url: msFavorites.config.connector_url
        ,baseParams: {
            action: 'mgr/favoriteslist/getlist'
        }
        ,fields: ['msf_id','name','total']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: false
        ,columns: [
            {header: _('msfavorites_id'), dataIndex: 'msf_id', width: 50}
            ,{header: _('msfavorites_name'), dataIndex: 'name'}
            ,{header: _('msfavorites_total_in_favorites'), dataIndex: 'total'}
        ]
        ,listeners: {
            rowclick: function(grid, rowIndex, e) {
                record = grid.store.getAt(rowIndex).data;
                this.showUsers({},{},record);
            }
        }
    });
    msFavorites.grid.FavoritesList.superclass.constructor.call(this,config);
};

Ext.extend(msFavorites.grid.FavoritesList,MODx.grid.Grid,{
    windows: {}

    ,getMenu: function() {
        var m = [];
        m.push({
            text: _('msfavorites_show_users')
            ,handler: this.showUsers
        });
        this.addContextMenuItem(m);
    }

    ,showUsers: function(btn,e,record) {

        if(record) {
            this.record = record;
        }else{
            this.record = this.menu.record;
        }

        if (!this.record || !this.record.msf_id) return false;
        var r = this.record;

        if (this.windows.showUsers) {
            try {
                this.windows.showUsers.close();
                this.windows.showUsers.destroy();
                this.windows.showUsers = undefined;
            } catch (e) {}
        }

        if (!this.windows.showUsers) {
            this.windows.showUsers = MODx.load({
                xtype: 'msfavorites-window-favoriteslist-showusers'
                ,items: [{
                    xtype: 'msfavorites-grid-userslist'
                    ,baseParams: {
                        action: 'mgr/users/getlist'
                        ,msf_id: r.msf_id
                    }
                }]
            });
        }
        this.windows.showUsers.show(e.target);
    }
});
Ext.reg('msfavorites-grid-favoriteslist',msFavorites.grid.FavoritesList);


msFavorites.window.showUsers = function(config) {
    config = config || {};
    this.ident = config.ident || 'meuitem'+Ext.id();
    Ext.applyIf(config,{
        title: _('msfavorites_userlist')
        ,id: this.ident
        ,width: 800
        ,autoHeight: true
        ,buttons: []
    });
    msFavorites.window.showUsers.superclass.constructor.call(this,config);
};

Ext.extend(msFavorites.window.showUsers,Ext.Window);
Ext.reg('msfavorites-window-favoriteslist-showusers',msFavorites.window.showUsers);