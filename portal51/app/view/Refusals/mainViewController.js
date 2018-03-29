/*
 * File: app/view/Refusals/mainViewController.js
 *
 * This file was generated by Sencha Architect version 3.2.0.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 5.1.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 5.1.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Portal.view.Refusals.mainViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.refusalsmain',

    onBtnRefusalsAddClick: function(button, e, eOpts) {
        var edit = Ext.create('Portal.view.Refusals.Edit').show();
        edit.getReferences().Form.getForm().setValues({id:0});
    },

    onBtnRefusalsDelClick: function(button, e, eOpts) {
        Portal.util.Util.deleteRecord2(
        this.getReferences().grid.getSelection()[0],
        Ext.getStore('Refusals.main'),
        'data/Refusals/table-delete.php');
    },

    onBtnRefusalsRefreshClick: function(button, e, eOpts) {
        Ext.getStore('Refusals.main').reload();
    },

    onGridpanelSelectionChange: function(model, selected, eOpts) {
        this.getReferences().btnRefusalsDel.setDisabled(selected.length === 0);
    },

    onAppRefusalsAfterRender: function(component, eOpts) {
        var AccessEdit = Portal.util.Util.appAccessEdit(this.getView().xtype);
        console.log('AccessEdit='+AccessEdit);

        var refs = this.getReferences();
        if(AccessEdit===false){
            console.log('setDisabled');
            refs.btnRefusalsAdd.setDisabled(true);
            refs.btnRefusalsDel.setDisabled(true);
        }

        Ext.getStore('Refusals.main').load();
    }

});