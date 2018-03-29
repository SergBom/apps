/*
 * File: app/view/ETables/mainViewController.js
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

Ext.define('Portal.view.ETables.mainViewController', {
  extend: 'Ext.app.ViewController',
  alias: 'controller.etables.main',

  onTableSelectionChange: function(model, selected, eOpts) {
    var r=this.getReferences();//,db=selected[0].data,mp=r.MainPanel;
    if(selected[0]){r.pInfo.setHtml(selected[0].data.info);}
    r.btnTblEdit.setDisabled(false);
    r.btnTEdit.setDisabled(false);
    r.btnDelete.setDisabled(false);
  },

  onTableItemDblClick: function(dataview, record, item, index, e, eOpts) {
    var ref=this.getReferences(),db=record.data,P=ref.MainPanel,
      newcols=[{xtype:'rownumberer'}],mc=[],
      SM=Ext.getStore('ETables.main'),M=SM.getModel(),
      SF=this.getStore('tbFields');

    console.log('a_id='+db.id);
    P.setMinWidth(db.id);
    P.setTitle(db.title);

    SF.load({params:{a_id:db.id},scope:this,callback:function(records,o,success){

    SF.each(function(rec){

      var tvar={},r=rec.data;
      console.log(r);

      if(r.field_name!='id'){


        tvar={
          dataIndex:r.field_name,
          tooltip:r.field_name,
          header:r.field_title,
          name:r.field_name,
          xtype:r.xtype,
          editor:{xtype:r.editor,allowBlank:r.allowBlank}
        };

        tvar.format=r.format;
        tvar.editor.format=r.format;
        if(r.width){tvar.width=Number(r.width);}
        if(r.filter_type){
          //if(r.xtype!='datecolumn'){
        tvar.filter=r.filter_type;//}
      }//else{tvar.filter='string';}

        newcols.push(tvar);

        //Set Model data
        if(r.xtype=='datecolumn'){
          mc.push({
            name:r.field_name,
            type:'date',
            dateFormat:'Y-m-d'
          });

        } else {
          mc.push({
            name:r.field_name  //type:r.xtype
          });
        }


      }else{
        mc.push({name:'id'});
      }

    });

    mc.push({name:'a_id',defaultValue:db.id});
    M.addFields(mc);
    P.reconfigure(SM,newcols);
    //console.log(P.getColumns());

  }});
  SM.load({params:{a_id:db.id}});
  ref.tbAdd.setDisabled(false);
  ref.tbRefresh.setDisabled(false);
  },

  onTAddClick: function(button, e, eOpts) {
    var w=Ext.create('Portal.view.ETables.TblNew').show();
    w.down("form").getForm().setValues({id:0});
  },

  onRefreshClick: function(button, e, eOpts) {
    Ext.getStore('ETables.tables').reload();
  },

  onEditClick: function(button, e, eOpts) {
    var w=Ext.create('Portal.view.ETables.TblNew');
    w.down("form").getForm().setValues(this.getReferences().tblMain.getSelection()[0].data);
    w.show();
  },

  onEditDBClick: function(button, e, eOpts) {
    var d=this.getReferences().tblMain.getSelection()[0].data,
      w=Ext.create('Portal.view.ETables.TblEdit',{minWidth:d.id,title:d.title}).show();
  },

  onReferChange: function(field, newValue, oldValue, eOpts) {
    Ext.getStore('ETables.tables').load({params:{refer:newValue}});
  },

  onTbAddClick: function(button, e, eOpts) {
    var P=this.getReferences().MainPanel,S=Ext.getStore('ETables.main'),
      rec=new Portal.model.ETables.main({id:0,a_id:P.getMinWidth()});
    S.insert(0,rec);
    P.findPlugin('rowediting').startEdit(rec,0);
  },

  onTbDelClick: function(button, e, eOpts) {

  },

  onTbRefreshClick: function(button, e, eOpts) {
    Ext.getStore('ETables.main').reload();
  },

  onMainFilterMinus: function(button, e, eOpts) {
    this.getReferences().MainPanel.getPlugin('mainFilter').clearFilters();
  },

  onMPSelectionChange: function(model, selected, eOpts) {
    this.getReferences().tbDel.setDisabled(false);
  },

  onPanelAfterRender: function(component, eOpts) {
    Ext.getStore('ETables.tables').load();
  }

});
