var msFavorites = function(config) {
    config = config || {};
    msFavorites.superclass.constructor.call(this,config);
};
Ext.extend(msFavorites,Ext.Component,{
    page:{},window:{},grid:{},tree:{},panel:{},combo:{},config:{},view:{},keymap:{}, plugin:{}
});
Ext.reg('msfavorites',msFavorites);

msFavorites = new msFavorites();

msFavorites.PanelSpacer = { html: '<br />' ,border: false, cls: 'msfavorites-panel-spacer' };

// DnD + SelectModel in grid
Ext.override(Ext.dd.DragSource, {
    handleMouseDown: function(e) {
        var t = e.getTarget();
        var classes = t.className.split(' ');
        if (classes.indexOf('x-grid3-row-checker') !== -1) {
            return false;
        }

        if (!this.dragging) {
            var data = this.getDragData(e);
            if (data && this.onBeforeDrag(data, e) !== false) {
                this.dragData = data;
                this.proxy.stop();
                Ext.dd.DragSource.superclass.handleMouseDown.apply(this, arguments);
            }
        }
        return true;
    }
});