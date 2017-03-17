/*
 * File: app/view/EGRP/fnsViewController.js
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

Ext.define('Portal.view.EGRP.fnsViewController', {
   extend: 'Ext.app.ViewController',
   alias: 'controller.egrp.fns',

   LoadData: function() {
      var r=this.getReferences(),
          s=r.cbProtocol.getValue(),
          o=r.cbOtdel.getValue();
      if(s&&o){
      this.getStore('Main').loadPage(1,{params:{session:s,otdel:o}});
      r.form.getForm().reset();}
   },

   cE: function(v) {
      switch(v){
      case "0":return '<img src="/resources/images/diagona/02/10/21.png" alt="Bad">';
      case "1":return '<img src="/resources/images/diagona/02/10/22.png" alt="Ok">';
      case "2":return '<img src="/resources/images/diagona/02/10/24.png" alt="Not">';
      case "3":return '<img src="/resources/images/diagona/02/10/23.png" alt="No Err">';
      case "4":return '<img src="/resources/images/flag/png/ru.png">';
      }
      return v;
   },

   onProtocolChange1: function(field, newValue, oldValue, eOpts) {
      this.LoadData();
   },

   onOtdelChange1: function(field, newValue, oldValue, eOpts) {
      this.LoadData();
   },

   onPtbarChange1: function(pagingtoolbar, pageData, eOpts) {
      var r=this.getReferences(),s=r.cbProtocol.getValue(),
         o=r.cbOtdel.getValue();
      this.getStore('Main').getProxy().setConfig({extraParams:{
      session:s,otdel:o}});
      r.btSave.setDisabled(true);
   },

   onMSelectionChange1: function(model, selected, eOpts) {
      var r=this.getReferences();
      r.form.getForm().loadRecord(selected[0]);
      r.btSave.setDisabled(false);
   },

   onSaveClick1: function(button, e, eOpts) {
      var m=this,r=m.getReferences(),
         f=r.form.getForm(),s=m.getStore('Main'),
         gR=r.grid.getSelectionModel().getSelection(),
         rguid=r.REC_GUID.getValue();
      if (rguid) {
         f.submit({
            success:function(fr,a){
               var fv=fr.getFieldValues();
               gR[0].set('PICTURE',m.cE(fv.STATUS_ERROR));
               gR[0].set('STATUS_ERROR',fv.STATUS_ERROR);
               s.sync();
               r.grid.getView().refresh();
            },
            failure:function(f,a){
               Ext.Msg.alert('Failed',a.result?a.result.message:'No response');
            }
         });
      }
   }

});