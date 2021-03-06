/*
 * File: app/view/ETables/TblNewViewController.js
 *
 * This file was generated by Sencha Architect
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

Ext.define('Portal.view.ETables.TblNewViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.etables.tblnew',

  onTitleChange: function(field, newValue, oldValue, eOpts) {
    this.getView().setTitle('Таблица "'+newValue+'"');
  },

  onSaveClick: function(button, e, eOpts) {
    var m=this;
    m.getReferences().form.getForm().submit({
      clientValidation:true,
      url:'data/ETables/saveNewTable.php',
      success:function(form,action) {
        Ext.getStore('ETables.tables').reload();
        m.getView().close();
      },
      failure: function(form, action) {
        switch (action.failureType) {
          case Ext.form.action.Action.CLIENT_INVALID:
          Ext.Msg.alert('Ошибка', 'Form fields may not be submitted with invalid values');
          break;
          case Ext.form.action.Action.CONNECT_FAILURE:
          Ext.Msg.alert('Ошибка', 'Ajax communication failed');
          break;
          case Ext.form.action.Action.SERVER_INVALID:
          Ext.Msg.alert('Ошибка', action.result.msg);
        }
        m.getView().close();
      }});
  },

  onCancelClick: function(button, e, eOpts) {
    this.getView().close();
  }

});
