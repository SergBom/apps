/*
 * File: app/view/admin_group/admin_groupViewController.js
 *
 * This file was generated by Sencha Architect version 3.5.1.
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

Ext.define('Portal.view.admin_group.admin_groupViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.admin_groupadmin_group',

    AppAccessTable: function(groupMainId) {
        var fields_temp=[],
        gg=this.getReferences().admingrid1,
        newcolumns=[
        {xtype: 'rownumberer'},
        {dataIndex:'name',header:'Пользователь',name:'username',flex:1}
        ];

        var Gstore = Ext.getStore('admin_group.clearstore'),
        UStore = Ext.getStore('admin_group.group_app');
        UStore.load({params:{
            admin:0,
            groupMainId:groupMainId
        }});
        UStore.on('load', function(storeRef, records, successful){
            //создаем поля каждой группе
            UStore.each(function(record){
            //console.log(record);
            var tempvar;
            if(record.get('id')==='2'){
                tempvar={
                    dataIndex: record.get('id'),
                    header: record.get('name'),
                    tooltip: record.get('name'),
                    name: record.get('name'),
                    xtype: 'checkcolumn',
                    width:70,
                    disabled:false
                };
            }else{
                tempvar={
                    dataIndex: record.get('id'),
                    tooltip: record.get('name'),
                    header: record.get('name'),
                    name: record.get('name'),
                    xtype: 'checkcolumn'
        //            width:100
                };
            }
            newcolumns.push(tempvar);
            });

            gg.reconfigure(Gstore, newcolumns);
        }, this);
        Gstore.load({params:{groupMainId:groupMainId}});
    },

    onTabAdminGroupTab1Activate: function(component, eOpts) {
        Ext.getStore('admin_group.group_main').load();
    },

    onCmbAdminGroupAppAccessChange: function(field, newValue, oldValue, eOpts) {

    },

    onTabAdminGroupAppAccessActivate: function(component, eOpts) {
        this.AppAccessTable(2);
    },

    onAdmin_group_groupsSelect: function(rowmodel, record, index, eOpts) {
        //console.log('Select');
        Ext.getStore('admin_group.lavels').load({ params: {
            in_id_group: record.data.id
        } });
    },

    onBtnAdminGroupGroupAddClick: function(button, e, eOpts) {
        var edit1=Ext.create('Portal.view.admin_group.Edit1').show();
        edit1.getReferences().formEdit.getForm().setValues({
            id:0,
            //    par_id:this.getReferences().cmbAdminGroupMainGroup.getValue(),
            url:'data/admin_group/group_app.php',
            store:'admin_group.group_app'
        });

    },

    onBtnAdminGroupGroupDelClick: function(button, e, eOpts) {
        Portal.util.Util.deleteRecord(
        this.getReferences().admin_group_groups_app.getSelectionModel().getSelection(),
        Ext.getStore('admin_group.group_app')
        );
    },

    onAdmin_group_groupsItemDblClick: function(dataview, record, item, index, e, eOpts) {
        var edit1=Ext.create('Portal.view.admin_group.Edit1').show();
        edit1.getReferences().formEdit.getForm().setValues(record.data);
        edit1.getReferences().formEdit.getForm().setValues({
            //    id:record.data.id,
            url:'data/admin_group/group_app.php',
            store:'admin_group.group_app'
        });
    }

});
