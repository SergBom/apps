/*
 * File: app/view/benzine/benzineViewController.js
 *
 * This file was generated by Sencha Architect version 3.2.0.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 5.1.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 5.1.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Portal.view.benzine.benzineViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.benzinebenzine',

    fnBenzinePlanerGo: function(n) {
        var refs = this.getReferences();
        if(n!==0){
            refs.CurrentWeek.setValue( parseInt(refs.CurrentWeek.getValue())+n );
        }else{
            refs.CurrentWeek.setValue( parseInt(0) );
        }
        var startOfWeek = moment().startOf('week').add(1+refs.CurrentWeek.getValue()*7,'d'),
        endOfWeek = moment().endOf('week').add(1+refs.CurrentWeek.getValue()*7,'d');
        refs.btngrpPlanerWeek.setTitle(startOfWeek.format('YYYY-MM-DD')+' ... '+
                                       endOfWeek.format('YYYY-MM-DD'));
        Ext.getStore('benzine.planer').load({
            params:{
                startOfWeek:startOfWeek.format('YYYY-MM-DD')
            }
        });
    },

    onBtnPlanerAddClick: function(button, e, eOpts) {
        var me=this;
        me.getView().mask('Подождите...');

        Ext.Ajax.request({
            url: 'data/benzine/planer-edit1.php',
            params: {new: 1},
            success: function(response){
                var d = Portal.util.Util.decodeJSON(response.responseText);
                //        console.log(d);
                //        console.log('id='+d.data.id);
                me.getView().unmask();

                var edit = Ext.create('Portal.view.benzine.planer_edit');
                edit.getReferences().formPlaner.getForm().setValues({
                    new: 1,
                    id:d.data.id
                });
                edit.show();
            }
        });

    },

    onBtnPlanerDelClick: function(button, e, eOpts) {
        var selId = this.getReferences().gridMain.getSelection()[0].id,
            store=Ext.getStore('benzine.planer');
        Ext.Msg.show({
            title:'Удаление записи?',
            message: 'Вы действительно желаете удалить запись из планировщика?<br>Восстановление будет невозможно!',
            buttons: Ext.Msg.YESNO,
            icon: Ext.Msg.QUESTION,
            fn: function(btn) {
                if (btn === 'yes') {
                    Ext.Ajax.request({
                        url: 'data/benzine/planer-delete.php',
                        params: {id:selId},
                        success: function(response){
                            store.reload();
                        }
                    });
                }
            }
        });
    },

    onBtnBenzinePlanerBackwardClick: function(button, e, eOpts) {
        this.fnBenzinePlanerGo(-1);
    },

    onBtnBenzinePlanerNowClick: function(button, e, eOpts) {
        this.fnBenzinePlanerGo(0);
    },

    onBtnBenzinePlanerForwardClick: function(button, e, eOpts) {
        this.fnBenzinePlanerGo(1);
    },

    onBtnBenzinePlanerPrintClick: function(button, e, eOpts) {
        var refs=this.getReferences();
        //db = Ext.Date.format(refs.DateBegin.getValue(),'d.m.Y');
        //de = Ext.Date.format(refs.DateEnd.getValue(),'d.m.Y');

        var startOfWeek = moment().startOf('week').add(1+refs.CurrentWeek.getValue()*7,'d'),
            endOfWeek = moment().endOf('week').add(1+refs.CurrentWeek.getValue()*7,'d');

        window.open('data/benzine/planer-report01.php?startOfWeek='+startOfWeek.format('YYYY-MM-DD'));


        //Ext.ux.grid.Printer.printAutomatically = false;
        //        Ext.ux.grid.Printer.mainTitle = 'Статистика ПК ПВД за период\nс '+db+' по '+de;
        //Ext.ux.grid.Printer.print(refs.gridMain);

    },

    onTableSelectionChange: function(model, selected, eOpts) {
        var refs = this.getReferences();
        refs.btnPlanerDel.setDisabled(selected.length === 0);
    },

    onTabActivate: function(tab, eOpts) {

    },

    onTableTypeTourItemDblClick: function(dataview, record, item, index, e, eOpts) {
        var edit = Ext.create('Portal.view.benzine.Type_Tour_Edit').show();
        edit.getReferences().formTypeTour.getForm().loadRecord(record);
    },

    onTableTypeTourSelectionChange: function(model, selected, eOpts) {
        this.getReferences().btnTypeTourDelete.setDisabled(selected.length === 0);
    },

    onBtnTypeTourAddClick: function(button, e, eOpts) {
        var edit = Ext.create('Portal.view.benzine.Type_Tour_Edit').show();
        edit.getReferences().formTypeTour.getForm().setValues({id:0});
    },

    onBtnTypeTourDeleteClick: function(button, e, eOpts) {
        Portal.util.Util.deleteRecord2(
        this.getReferences().gridTypeTour.getSelection()[0],
        Ext.getStore('benzine.type_tour'),
        'data/benzine/type_tour-delete.php');
    },

    onBtnBenzineGridTypeTourClick: function(button, e, eOpts) {
        Ext.getStore('benzine.type_tour').load();
    },

    onBenzine_tableCitySelectionChange: function(model, selected, eOpts) {
        this.getReferences().btnCityDelete.setDisabled(selected.length === 0);
    },

    onBenzine_tableCityItemDblClick: function(dataview, record, item, index, e, eOpts) {
        var edit = Ext.create('Portal.view.benzine.City_Edit').show(),
            refs = edit.getReferences();
        refs.formCityEdit.getForm().loadRecord(record);
        refs.formCityEdit.getForm().setValues(record.getData());
    },

    onBtnCityAddClick: function(button, e, eOpts) {
        var edit = Ext.create('Portal.view.benzine.City_Edit').show();
        edit.getReferences().formCityEdit.getForm().setValues({
            new: 1
        });
    },

    onBtnCityDeleteClick: function(button, e, eOpts) {
        Portal.util.Util.deleteRecord2(
        this.getReferences().gridCity.getSelection()[0],
        Ext.getStore('benzine.city'),
        'data/benzine/city-delete.php');
    },

    onBtnBenzineGridCityClick: function(button, e, eOpts) {
        Ext.getStore('benzine.city').load();
    },

    onBenzine_tableAddressSelectionChange: function(model, selected, eOpts) {
        this.getReferences().btnAddressDelete.setDisabled(selected.length === 0);
    },

    onBenzine_tableAddressItemDblClick: function(dataview, record, item, index, e, eOpts) {
        var edit = Ext.create('Portal.view.benzine.Address_Edit').show(),
            refs = edit.getReferences();
        refs.formAddressEdit.getForm().loadRecord(record);
    },

    onBtnAddressAddClick: function(button, e, eOpts) {
        var edit = Ext.create('Portal.view.benzine.Address_Edit').show();
        edit.getReferences().formAddressEdit.getForm().setValues({
            new: 1
        });
    },

    onBtnAddressDeleteClick: function(button, e, eOpts) {
        Portal.util.Util.deleteRecord2(
        this.getReferences().gridAddress.getSelection()[0],
        Ext.getStore('benzine.address'),
        'data/benzine/address-delete.php');
    },

    onBtnBenzineGridAddressRefreshClick: function(button, e, eOpts) {
        Ext.getStore('benzine.address').load();
    },

    onTabActivate1: function(tab, eOpts) {
        Ext.getStore('benzine.type_tour').load();
        Ext.getStore('benzine.city').load();
        Ext.getStore('benzine.address').load();
    },

    onAppBenzineAfterRender: function(component, eOpts) {
        var refs = this.getReferences();
        refs.CurrentWeek.setValue(0);

        var startOfWeek = moment().startOf('week').add(1,'d'),
            endOfWeek = moment().endOf('week').add(1,'d');
        //console.log(startOfWeek.format('YYYY-MM-DD'));


        refs.btngrpPlanerWeek.setTitle(startOfWeek.format('YYYY-MM-DD')+' ... '+endOfWeek.format('YYYY-MM-DD')
        );

        Ext.getStore('benzine.planer').load({
            params:{
                startOfWeek:startOfWeek.format('YYYY-MM-DD')
            }
        });

        /*Ext.Ajax.request({
        url: 'data/benzine/planer-read-week.php',
        params: {new: 1},
        success: function(response){
        var d = Portal.util.Util.decodeJSON(response.responseText);
        console.log(d);
        console.log('id='+d.data.id);
        me.getView().unmask();
        //    me.getView().close();

        var edit = Ext.create('Portal.view.benzine.planer_edit');
        edit.getReferences().formPlaner.getForm().setValues({
            new: 1,
            id:d.data.id
        });
        edit.show();
    }
});
*/


var AccessEdit = Portal.util.Util.appAccessEdit(this.getView().xtype);

//,
//tabDataInput = refs.tabDataInput,
//fsspTabPanel = refs.fsspTabPanel;



//fsspTabPanel.setActiveTab(1);
//tabDataInput.setHidden( !AccessEdit );
    }

});
