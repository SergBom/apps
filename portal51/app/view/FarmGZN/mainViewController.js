/*
 * File: app/view/FarmGZN/mainViewController.js
 * Date: Wed Jan 31 2018 13:16:36 GMT+0300 (RTZ 2 (зима))
 *
 * This file was generated by Sencha Architect version 4.2.2.
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

Ext.define('Portal.view.FarmGZN.mainViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.farmgzn.main',

  onAddClick: function(button, e, eOpts) {
    var w=Ext.create('Portal.view.FarmGZN.Edit').show();
    w.down('form').getForm().setValues({id:0});
  },

  onEditClick: function(button, e, eOpts) {
    var w=Ext.create('Portal.view.FarmGZN.Edit').show();
    w.down('form').getForm().setValues(this.getReferences().grid.getSelection()[0].data);
  },

  onRefresh: function(button, e, eOpts) {
    Ext.getStore('FarmGZN.main').reload();
    this.getReferences().info.update({html:''});
  },

  onGridSelectionChange: function(model, selected, eOpts) {
    var r=this.getReferences(),s=selected[0].data;
    r.btnEdit.setDisabled(selected.length===0);
    r.info.update({
      //config:{bodyCls:'bg-001'},
    html:'<b>КН: <font color="blue">'+s.cad_num+'</font><br>Адрес: <font color="green">'+s.address+'</font></b>'});
    //if(s.error_type===false){r.info.setConfig({bodyCls:'bg-001'});}
    console.log(r.info);
    r.info.setCls('bg-001');
  },

  onAfterRender: function(component, eOpts) {
    var AccessEdit=Portal.util.Util.appAccessEdit(this.getView().xtype),
      ref=this.getReferences();
    ref.btnEdit.setHidden(!AccessEdit);
    //ref.btnEditors.setDisabled(!AccessEdit);
    console.log('AccessEdit='+AccessEdit);
    Ext.getStore('FarmGZN.main').load();

  }

});
