/*
 * File: app/view/PVDstat/main.js
 *
 * This file was generated by Sencha Architect
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

Ext.define('Portal.view.PVDstat.main', {
    extend: 'Ext.form.Panel',
    alias: 'widget.PVDstat',

    requires: [
        'Portal.view.PVDstat.mainViewModel',
        'Portal.view.PVDstat.mainViewController',
        'Ext.toolbar.Toolbar',
        'Ext.container.ButtonGroup',
        'Ext.form.field.ComboBox',
        'Ext.form.Label',
        'Ext.form.field.Date',
        'Ext.tab.Panel',
        'Ext.grid.Panel',
        'Ext.view.Table',
        'Ext.grid.column.RowNumberer',
        'Ext.tab.Tab'
    ],

    controller: 'pvdstatmain',
    viewModel: {
        type: 'pvdstatmain'
    },
    autoShow: true,
    height: 453,
    width: 646,
    layout: 'fit',
    title: 'ПВД статистика',
    defaultListenerScope: true,

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'top',
            items: [
                {
                    xtype: 'buttongroup',
                    height: 74,
                    title: 'Тип отчета',
                    columns: 1,
                    items: [
                        {
                            xtype: 'combobox',
                            reference: 'TypeReport',
                            width: 205,
                            name: 'TypeReport',
                            allowBlank: false,
                            displayField: 'name',
                            store: 'PVDstat.TypeReport',
                            valueField: 'id',
                            valueNotFoundText: 'тип отчета'
                        },
                        {
                            xtype: 'label',
                            html: '<a href="http://sp.rosreestr.ru:8082/uit/Lists/2016/EditForm.aspx?ID=110&Source=http%3A%2F%2Fsp%2Erosreestr%2Eru%3A8082%2Fuit%2FLists%2F2016%2FAllItems%2Easpx%3FView%3D%7BC4D2CB42%2D4BA0%2D49E1%2D91C4%2D28E230C2D84C%7D%26FilterField1%3DLinkTitle%26FilterValue1%3D51" target="blank">Ссылка на отчет</a>'
                        }
                    ]
                },
                {
                    xtype: 'buttongroup',
                    height: 74,
                    width: 220,
                    collapsed: false,
                    title: 'Период',
                    columns: 1,
                    items: [
                        {
                            xtype: 'datefield',
                            reference: 'DateBegin',
                            padding: '0 5 0 5',
                            fieldLabel: 'Начало',
                            labelWidth: 50,
                            name: 'DateBegin',
                            allowBlank: false,
                            format: 'd/m/Y',
                            startDay: 1,
                            listeners: {
                                change: {
                                    fn: 'onDateBeginChange',
                                    scope: 'controller'
                                }
                            }
                        },
                        {
                            xtype: 'datefield',
                            reference: 'DateEnd',
                            padding: '0 5 0 5',
                            fieldLabel: 'Конец',
                            labelWidth: 50,
                            name: 'DateEnd',
                            allowBlank: false,
                            format: 'd/m/Y'
                        }
                    ]
                },
                {
                    xtype: 'buttongroup',
                    formBind: true,
                    height: 74,
                    width: 103,
                    title: 'Действия',
                    columns: 1,
                    items: [
                        {
                            xtype: 'button',
                            reference: 'btnPVDstatReport',
                            id: 'btnPVDstatReport',
                            width: 80,
                            iconCls: 'icon-report',
                            text: 'Отчетик',
                            listeners: {
                                click: 'onDoReportClick'
                            }
                        },
                        {
                            xtype: 'button',
                            reference: 'btnPVDstatExcel',
                            hidden: true,
                            id: 'btnPVDstatExcel',
                            text: 'Вывести в Excel'
                        },
                        {
                            xtype: 'button',
                            reference: 'btnPVDstatPrint',
                            id: 'btnPVDstatPrint',
                            width: 80,
                            iconCls: 'icon-printer',
                            text: 'Печать',
                            listeners: {
                                click: {
                                    fn: 'onBtnPVDstatPrintClick',
                                    scope: 'controller'
                                }
                            }
                        }
                    ]
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'tabpanel',
            reference: 'mainTab',
            activeTab: 0,
            items: [
                {
                    xtype: 'panel',
                    layout: 'fit',
                    title: 'Отчет',
                    items: [
                        {
                            xtype: 'gridpanel',
                            reference: 'mGrid',
                            title: 'Таблица отчета',
                            store: 'PVDstat.report01',
                            columns: [
                                {
                                    xtype: 'rownumberer'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    hidden: true,
                                    width: 69,
                                    dataIndex: 'ID_PROC',
                                    text: 'ID_PROC'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'TEXT',
                                    text: 'Наименование',
                                    flex: 1
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 67,
                                    dataIndex: 'CALC',
                                    text: 'Кол-во'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 77,
                                    dataIndex: 'DB',
                                    text: 'С'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 77,
                                    dataIndex: 'DE',
                                    text: 'По'
                                }
                            ]
                        }
                    ]
                },
                {
                    xtype: 'panel',
                    reference: 'tabServers',
                    loader: {
                        url: 'data/PVDstat/pvd_get_data.html',
                        contentType: 'html'
                    },
                    scrollable: true,
                    title: 'Отчет о загрузке данных с серверов ПВД',
                    listeners: {
                        activate: 'onPanelActivate'
                    }
                }
            ]
        }
    ],

    onDoReportClick: function(button, e, eOpts) {
        var refs=this.getReferences(),
        TypeReport = refs.TypeReport.getValue(),
        DateBegin = refs.DateBegin.getValue(),
        DateEnd = refs.DateEnd.getValue();
        //console.log(TypeReport+' '+Ext.Date.format(DateBegin,'d.m.Y')+' '+Ext.Date.format(DateEnd,'d.m.Y'));

        if(TypeReport && DateBegin && DateEnd){

            switch (TypeReport) {
            case '0':
                refs.mainTab.setActiveTab(0);
                Ext.getStore('PVDstat.report01').load({
                    params: {
                        db: Ext.Date.format(DateBegin,'d.m.Y'),
                        de: Ext.Date.format(DateEnd,'d.m.Y')
                    }
                });
                break;
            case '1':
                window.open('data/PVDstat/PVDstat_excel01.php?db='+Ext.Date.format(DateBegin,'d.m.Y')+'&de='+Ext.Date.format(DateEnd,'d.m.Y'));
                break;
            }
        }
    },

    onPanelActivate: function(component, eOpts) {
        component.loader.load();
    }

});