/*
 * File: app/view/switchUsers/editViewController.js
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

Ext.define('Portal.view.switchUsers.editViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.switchusers.edit',

  onSaveClick: function(button, e, eOpts) {
    this.getReferences().form.getForm().submit({
      success:function(f,a) {
        this.getView().close();
      },
      failure:function(f,a) {
        Ext.Msg.alert('Failed',a.result.msg);
      }
    });
  },

  onCancelClick: function(button, e, eOpts) {
    this.getView().close();
  },

  onIDChange: function(field, newValue, oldValue, eOpts) {
    /*var p=this.getReferences().form.getForm().getValues();
    console.log(p);
    Ext.Ajax.request({
    url:'data/switchUsers/getEGRPdata.php',
    params:{id:newValue},
    method:"GET",
    success: function(r,o) {
    var obj = Ext.decode(r.responseText);
    console.dir(obj);
    },
    failure: function(r,o) {
    console.log('server-side failure with status code ' + r.status);
    }
    });*/
  }

});
