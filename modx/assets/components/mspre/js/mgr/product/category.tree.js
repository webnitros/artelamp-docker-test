mspre.tree.OptionCategories = function (config) {
    config = config || {}
    this.grid = null
    this.form = null

    var parents = Ext.util.JSON.encode(mspre.store.get('categories'))
    var context = mspre.store.get('context')
    this.form = Ext.getCmp('mspre-form-panel')
    this.grid = Ext.getCmp(mspre.config.grid_id)

    Ext.applyIf(config, {
        url: mspre.config.connector_url
        , id: 'mspre-tree-option-categories-id'
        , title: ''
        , anchor: '100%'
        , rootVisible: false
        , expandFirst: true
        , enableDD: false
        , ddGroup: 'modx-treedrop-dd'
        , remoteToolbar: false
        , action: mspre.config.controllerPath + 'getcategorynodes'
        , tbarCfg: {id: config.id ? config.id + '-tbar' : 'modx-tree-resource-tbar'}
        , baseParams: {
            action: 'mgr/system/category/getcategorynodes'
            , currentResource: MODx.request.id || 0
            , currentAction: MODx.request.a || 0
            , context: context
            , categories: parents
        }
        , listeners: {
            checkchange: function (node, checked) {

                if (typeof this.optionGrid === 'undefined') return
                var checkedNodes = this.getChecked()
                var categories = []

                for (var i = 0; i < checkedNodes.length; i++) {
                    categories.push(checkedNodes[i].attributes.pk)
                }
                this.setFilterArray('categories', categories)
            },
            load: function (node) {
                // TODO отключили событие по причине сброса отмеченых категорий
                //this.fireEvent('checkchange', node)

            }
            , afterrender: function () {
                this.mask = new Ext.LoadMask(this.getEl())
            }
            , loadCreateMenus: function () {

            }
        }
    })
    mspre.tree.OptionCategories.superclass.constructor.call(this, config)
}
Ext.extend(mspre.tree.OptionCategories, MODx.tree.Resource, {

    setFilterArray: function (field, value) {
        value = this.getFilterArray(value)
        this.form._filterSet(field, value)
    },
    getFilterArray: function (value) {
        if (value !== undefined) {
            value = Object.assign({}, value)
        } else {
            value = {}
        }
        return value
    }

    /**
     * Gets a default toolbar setup
     */
    , getToolbar: function () {

        var parent = mspre.tree.ModalCategories.superclass.getToolbar.call(this)

        parent.push({
            //icon: iu + 'refresh.png'
            cls: 'x-btn x-btn-small x-btn-icon-small-left x-grid3-row-checker x-btn-noicon'
            , tooltip: {text: _('mspre_unchecked_categories')}
            , id: 'mspre-unchecked-mspre'
            , handler: this.unСheckedCategories
            , scope: this
        })


        parent.push('->')

        parent.push({
            //icon: iu + 'refresh.png'
            cls: 'x-btn x-btn-small x-btn-icon-small-left tree-trash x-btn-noicon x-item-disabled'
            , tooltip: {text: _('empty_recycle_bin')}
            , id: 'emptifier-mspre'
            , handler: this.emptyRecycleBin
            , scope: this
        })

        return parent
    },
    unСheckedCategories: function () {

        var form = Ext.getCmp('mspre-form-panel')
        var grid_ = Ext.getCmp(mspre.config.grid_id)

        form.saveState = true;
        form.setState('categories', {})
        this.refresh()
        grid_.refresh()


       /* this.baseParams.categories = Ext.util.JSON.encode({})

        this.refresh()
        mspre.store.start()
        mspre.store.dirty = true;
        mspre.store.queue = mspre.store.state
        mspre.store.set('categories', {})
        mspre.store.submitState()*/

    },
    emptyRecycleBin: function () {
        MODx.msg.confirm({
            title: _('empty_recycle_bin')
            , text: _('empty_recycle_bin_confirm')
            , url: MODx.config.connector_url
            , params: {
                action: 'resource/emptyRecycleBin'
            }
            , listeners: {
                'success': {
                    fn: function () {
                        Ext.select('div.deleted', this.getRootNode()).remove()
                        MODx.msg.status({
                            title: _('success')
                            , message: _('empty_recycle_bin_emptied')
                        })

                        this.refresh()
                        Ext.getCmp(mspre.config.grid_id).refresh()
                        //var trashButton = this.getTopToolbar().findById('emptifier-mspre');
                        //trashButton.disable();
                        //trashButton.setTooltip(_('empty_recycle_bin') + ' (0)');
                        this.fireEvent('emptyTrash')
                    }, scope: this
                }
            }
        })
    }
})
Ext.reg('mspre-tree-option-categories', mspre.tree.OptionCategories)

mspre.window.AssignCategorys = function (config) {

    config = config || {}
    this.ident = config.ident || 'meuitem' + Ext.id()
    this.grid = Ext.getCmp(mspre.config.grid_id)
    this.grid.enabledMask()
    Ext.applyIf(config, {
        title: _('mspre_action_assign')
        , id: this.ident
        , width: 700
        , labelAlign: 'left'
        , labelWidth: 180
        // , autoHeight: true
        , maxHeight: 450
        , height: 450
        , url: mspre.config.connector_url
        , action: mspre.config.controllerPath + 'additional/add_categories'
        , fields: [{
            xtype: 'mspre-tree-modal-categories',
            id: 'mspre-tree-modal-categorys-assign-window',
            categories: 'mspre-categories-ids',
            baseParams: {
                action: 'settings/category/getcategorynodes'
                , currentResource: MODx.request.id || 0
                , currentAction: MODx.request.a || 0
                , contextKey: mspre.store.get('context')
            }
        }, {
            xtype: 'hidden', name: 'categorys', id: 'mspre-categorys-ids'
        }, {
            xtype: 'hidden', name: 'categories', id: 'mspre-categories-ids'
        }]
        , keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
                this.grid.disabledMask()
            }, scope: this
        }]
    })
    mspre.window.AssignCategorys.superclass.constructor.call(this, config)
}
Ext.extend(mspre.window.AssignCategorys, MODx.Window)
Ext.reg('mspre-window-categorys-assign', mspre.window.AssignCategorys)

mspre.tree.ModalCategories = function (config) {
    config = config || {}

    Ext.applyIf(config, {
        url: mspre.config.connector_url
        , id: 'mspre-modal-categories-tree'
        , title: ''
        , anchor: '100%'
        , rootVisible: false
        , autoLoad: false
        , expandFirst: true
        , enableDD: false
        , autoHeight: true
        , maxHeight: 350
        , height: 350
        , ddGroup: 'modx-treedrop-dd'
        , remoteToolbar: false
        , action: 'mgr/system/category/getcategorynodes'
        , tbarCfg: {id: config.id ? config.id + '-tbar' : 'modx-tree-resource-tbar'}
        , listeners: {
            checkchange: function (node, checked) {
                var checkedNodes = this.getChecked()
                var categories = []

                for (var i = 0; i < checkedNodes.length; i++) {
                    categories.push(checkedNodes[i].attributes.pk)
                }

                var catField = Ext.getCmp(this.categories)
                if (!catField) return false
                catField.setValue(Ext.util.JSON.encode(categories))
            }
            , afterrender: function () {
                this.mask = new Ext.LoadMask(this.getEl())
            }
        }
    })
    mspre.tree.ModalCategories.superclass.constructor.call(this, config)
}
Ext.extend(mspre.tree.ModalCategories, MODx.tree.Tree, {
    _showContextMenu: function (n, e) {
        n.select()
        this.cm.activeNode = n
        this.cm.removeAll()
        var m = []
        m.push({
            text: _('directory_refresh'), handler: function () {
                this.refreshNode(this.cm.activeNode.id, true)
            }
        })
        this.addContextMenuItem(m)
        this.cm.showAt(e.xy)
        e.stopEvent()
    }

})
Ext.reg('mspre-tree-modal-categories', mspre.tree.ModalCategories)