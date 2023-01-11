msFavorites.grid.UsersList = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        id: 'msfavorites-grid-userslist'
        ,url: msFavorites.config.connector_url
        ,baseParams: {
            action: 'mgr/users/getlist'
        }
        ,fields: ['user_id','user_username', 'profile_fullname', 'profile_email', 'profile_mobilephone']
        ,autoHeight: true
        ,paging: true
        ,remoteSort: false
        ,columns: [
            {header: _('msfavorites_id'), dataIndex: 'user_id', width: 50}
            ,{header: _('msfavorites_username'), dataIndex: 'user_username'}
            ,{header: _('msfavorites_fullname'), dataIndex: 'profile_fullname'}
            ,{header: _('msfavorites_email'), dataIndex: 'profile_email'}
            ,{header: _('msfavorites_mobilephone'), dataIndex: 'profile_mobilephone'}
        ]
        ,listeners: {}
    });
    msFavorites.grid.UsersList.superclass.constructor.call(this,config);
};

Ext.extend(msFavorites.grid.UsersList,MODx.grid.Grid);
Ext.reg('msfavorites-grid-userslist',msFavorites.grid.UsersList);