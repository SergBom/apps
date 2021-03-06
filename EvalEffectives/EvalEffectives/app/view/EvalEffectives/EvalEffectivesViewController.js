/*
 * File: app/view/EvalEffectives/EvalEffectivesViewController.js
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

Ext.define('Portal.view.EvalEffectives.EvalEffectivesViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.evaleffectivesevaleffectives',

    onButtonClick: function(button, e, eOpts) {
        var ref=this.getReferences(),
            gg=ref.mainGrid,
            db=ref.DateCur0.getValue(),
            de=ref.DateCur.getValue();
        var fields_temp=[],
            newcolumns=[
            {xtype: 'rownumberer'},
            {dataIndex:'R1',header:'Параметр',name:'R1',locked:true},
            {dataIndex:'summ',header:'Сумма',name:'summ',
                tooltip:'Сумма по всем отделам',
                locked:true,
                tdCls:'f-blue',
                align:'right',
                renderer:function(v,mD,r,row,col,s,view){
                    if(v>0){return "<b>"+v+"</b>";}else{return v;}
                }
            },
            ];
        var otd=[],
        // Список значений таблицы
            Gstore=Ext.getStore('EvalEffectives.ParamsFromOtdels'),
            // Список полей таблицы
            storeOtdels=Ext.getStore('EvalEffectives.OtdelsList');
        storeOtdels.load();
        storeOtdels.on('load',function(storeRef,records,successful){
            //создаем поля каждой группе
            storeOtdels.each(function(rec){
                //console.log(record.get('cn'));
                if(rec.get('cn')!=='summ'){
                    var tmpvar;
                    tmpvar={
                        dataIndex:rec.get('cn'),
                        header:rec.get('sname'),
                        tooltip:rec.get('name'),
                        name:rec.get('cn'),
                        align:'right',
                        //xtype:'numbercolumn',
                        //width:70,
                        //disabled:true
                        renderer:function(v,mD,r,row,col,s,view){
                            if(v>0){return "<b>"+v+"</b>";}else{return v;}
                        }
                    };
                    otd.push(tmpvar);
                }
            });
            newcolumns.push({
                xtype:'gridcolumn',
                text:'По отделам',
            columns:otd});
            gg.reconfigure(Gstore,newcolumns);
        },this);
        gg.setStore(Gstore);
        Gstore.load({params:{db:db,de:de}});
    },

    onEvalEffectivesAddEvalClick: function(button, e, eOpts) {
        //var edit = Ext.create('Portal.view.EvalEffectives.JobersGroupEdit').show();
        //edit.getReferences().formAddressEdit.getForm().setValues({
        //    new: 1
        //});
        var ed = Ext.create('Portal.view.EvalEffectives.ParamsEditor').show();
        //ed.down('form').getForm().setValues({id:0});
    },

    onEvalEffectivesSettingsClick: function(button, e, eOpts) {
        var edit = Ext.create('Portal.view.EvalEffectives.Settings').show();
        //edit.getReferences().formAddressEdit.getForm().setValues({
        //    new: 1
        //});
    },

    onButtonClick3: function(button, e, eOpts) {
        Ext.create('Portal.view.EvalEffectives.DataOtdels').show();
    },

    onAppEvalEffectivesAfterRender: function(component, eOpts) {
        var AccessEdit = Portal.util.Util.appAccessEdit(this.getView().xtype),
            ref=this.getReferences(),
            dt=new Date(),
            d0=new Date(dt.getFullYear(),dt.getMonth(),1);
        ref.DateCur0.setValue(d0);
        ref.DateCur.setValue(dt);
        ref.EvalEffectivesSettings.setDisabled(!AccessEdit);
        //ref.btnEditors.setDisabled(!AccessEdit);
        console.log('AccessEdit='+AccessEdit);
        this.onButtonClick();
    }

});
