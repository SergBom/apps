/*
 * File: app/view/FarmGZN/EditViewController.js
 * Date: Wed Jan 31 2018 09:45:43 GMT+0300 (RTZ 2 (зима))
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

Ext.define('Portal.view.FarmGZN.EditViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.farmgzn.edit',

  onSave: function(button, e, eOpts) {
    var me=this.getView(),f=me.down('form'),st=Ext.getStore('FarmGZN.main');
    if(f.isValid()){
      f.submit({
        success:function(fp,o) {
          console.log(o.result);
          st.reload();
          me.close();
        },
        failure:function(fp,o) {
          alert(o.result);
        }
      });
    }
  }

});