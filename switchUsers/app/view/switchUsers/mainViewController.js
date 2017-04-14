/*
 * File: app/view/switchUsers/mainViewController.js
 *
 * This file was generated by Sencha Architect version 4.1.1.
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

Ext.define('Portal.view.switchUsers.mainViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.switchusers.main',

  onRefreshClick: function(button, e, eOpts) {
    Ext.getStore("switchUsers.tree").load();
  },

  onTreeSelectionChange: function(model, selected, eOpts) {
    Ext.getStore('switchUsers.users').load({params:{id:selected[0].id}});
  },

  onGridItemDblClick: function(dataview, record, item, index, e, eOpts) {
    var e=Ext.create('Portal.view.switchUsers.edit').show();
    e.down('form').loadRecord(record);
  },

  onAfterRender: function(component, eOpts) {
    var r=this.getReferences();

  }

});
