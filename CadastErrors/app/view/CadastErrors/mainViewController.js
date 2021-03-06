/*
 * File: app/view/CadastErrors/mainViewController.js
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

Ext.define('Portal.view.CadastErrors.mainViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.cadasterrors.main',

  onDBChange: function(field, newValue, oldValue, eOpts) {
    var r=this.getReferences();
    if(newValue && r.dateE.getValue()){r.btnSay.setDisabled(false);r.btnExcel.setDisabled(false);}
    else{r.btnSay.setDisabled(true);r.btnExcel.setDisabled(true);}
    r.dateE.setMinValue(newValue);
  },

  onDEChange: function(field, newValue, oldValue, eOpts) {
    var r=this.getReferences();
    if(newValue && r.dateB.getValue()){r.btnSay.setDisabled(false);r.btnExcel.setDisabled(false);}
    else{r.btnSay.setDisabled(true);r.btnExcel.setDisabled(true);}
    r.dateB.setMaxValue(newValue);
  },

  onSay: function(button, e, eOpts) {
    var ref=this.getReferences(),wd=60,
      gg=ref.grid,
      db=ref.dateB.getValue(),
      de=ref.dateE.getValue();
    //console.log(db);console.log(de);
    var tt='',o=[],t=[],ct=0,
      newcolumns=[
      {xtype: 'rownumberer'},
      {dataIndex:'fio',header:'Кадастровый инженер',width:200,locked:true},
      {dataIndex:'AttNumber',header:'№ аттестата',locked:true},
      ];
    // Список значений таблицы
    var Gstore = Ext.getStore('CadastErrors.main');
    // Список полей таблицы
    var stEngin = this.getViewModel().getStore('FieldsVar');
    stEngin.load();
    stEngin.on('load', function(storeRef, records, successful){
      //создаем поля каждой группе
      stEngin.each(function(record){
        console.log(record.data);
        var h=record.get('FieldRefer'),tempvar={
          dataIndex:record.get('FieldName'),
          header:h,
          width:wd,
          tooltip:h,
          align:'right'
        };
        t.push(record.get('name'));
        if(record.get('name')===''){
          newcolumns.push(tempvar);
        }else{
          if(ct!==0){
            if(t[ct-1]!==record.get('name')){o=[];}
            o.push(tempvar);
            if(t[ct-1]!==record.get('name')){
              newcolumns.push({
                xtype:'gridcolumn',
                header:record.get('name'),
                columns:o
              });
            }
          }
        }ct++;
      });

      gg.reconfigure(Gstore, newcolumns);
    }, this);
    gg.setStore(Gstore);
    Gstore.load({params:{db:db,de:de}});
    /*
    Ext.Ajax.request({
    url: 'data/EvalEffectives/getQrab.php',
    params:{
    db:db,de:de
    },
    success: function(r,o) {
    //var obj =
    this.getReferences().Q_rab.setValue(
    Ext.decode(r.responseText).data.Q_rab);
    //console.dir(obj);
  },

  failure: function(response, opts) {
    console.log('server-side failure with status code ' + response.status);
  },
  scope:this
});*/
  },

  onDataInput: function(button, e, eOpts) {
    var ed=Ext.create('Portal.view.CadastErrors.DataIn',{scope:this}).show();
  },

  onSetEngineers: function(button, e, eOpts) {
    var ed=Ext.create('Portal.view.CadastErrors.Engineers').show();
  }

});
