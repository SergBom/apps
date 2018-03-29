/*
 * File: app/view/OpisDel/mainViewController.js
 *
 * This file was generated by Sencha Architect version 4.0.1.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 6.2.x Classic library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 6.2.x Classic. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Portal.view.OpisDel.mainViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.opisdel.main',

    onTableItemDblClick: function(dataview, record, item, index, e, eOpts) {
        var edit=Ext.create('Portal.view.OpisDel.Edit'),dd=new Date();
        edit.getReferences().form.getForm().loadRecord(record);
        edit.show();
    },

    onTableSelectionChange: function(model, selected, eOpts) {
        this.getReferences().btnDelete.setDisabled(selected.length === 0);
    },

    onAddClick: function(button, e, eOpts) {
        var edit=Ext.create('Portal.view.OpisDel.Edit'),
            dd=this.getReferences().Year.getValue();
        //,dd=new Date();
        edit.getReferences().form.getForm().setValues({id:0,
        Year:dd});
        edit.show();
    },

    onDelClick: function(button, e, eOpts) {
        Portal.util.Util.deleteRecord2(
        this.getReferences().grid.getSelection()[0],
        Ext.getStore('OpisDel.main'),
        'data/OpisDel/Opis-delete.php');
    },

    onExportClick: function(button, e, eOpts) {
        var ed=Ext.create('Portal.view.OpisDel.Export'),
            dd=this.getReferences().Year.getValue();
        ed.getReferences().form.getForm().setValues({Year:dd});
        ed.show();


    },

    onGetYearChange: function(field, newValue, oldValue, eOpts) {
        Ext.getStore("OpisDel.main").load({params:{Year:newValue}});
        this.getReferences().btnRefresh.setDisabled(false);
        this.getReferences().btnAdd.setDisabled(false);
        this.getReferences().btnExport.setDisabled(false);
    },

    onRefreshClick: function(button, e, eOpts) {
        Ext.getStore("OpisDel.main").reload();
    },

    onWindowAfterRender: function(component, eOpts) {
        //var AccessEdit = Portal.util.Util.appAccessEdit(this.getView().xtype);
        //Ext.getStore("OpisDel.main").load();
        //this.getViewModel().getStore('getYear').load();
    }

});
