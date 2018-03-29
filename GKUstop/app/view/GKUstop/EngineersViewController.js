/*
 * File: app/view/GKUstop/EngineersViewController.js
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

Ext.define('Portal.view.GKUstop.EngineersViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.gkustop.engineers',

  onAdd: function(button, e, eOpts) {
    var ed=Ext.create('Portal.view.GKUstop.EngEdit',{scope:this}).show();
    ed.down('form').getForm().setValues({id:0});
  },

  onDel: function(button, e, eOpts) {
    var id=this.getReferences().grid.getSelection()[0].id,
      s=this.getViewModel().getStore('engineers');
    console.log(id);
    Portal.util.Util.deleteRecord3(id,s,'data/GKUstop/Engineer-del.php');
  },

  onRefresh: function(button, e, eOpts) {
    this.getViewModel().getStore("engineers").reload();
  },

  onSeek: function(field, newValue, oldValue, eOpts) {
    console.log(newValue);
    this.getViewModel().getStore("engineers").load({params:{fio:newValue}});
  },

  onEdit: function(dataview, record, item, index, e, eOpts) {
    var ed=Ext.create('Portal.view.GKUstop.EngEdit',{scope:this}).show();
    ed.down('form').getForm().setValues(record.data);
  },

  onSelectionChange: function(model, selected, eOpts) {
    this.getReferences().btnDel.setDisabled(selected.length===0);
  },

  onAfterRender: function(component, eOpts) {
    this.getViewModel().getStore("engineers").load();
  }

});