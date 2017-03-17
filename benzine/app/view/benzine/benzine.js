/*
 * File: app/view/benzine/benzine.js
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

Ext.define('Portal.view.benzine.benzine', {
    extend: 'Ext.panel.Panel',
    alias: 'widget.benzine',

    requires: [
        'Portal.view.benzine.benzineViewModel',
        'Portal.view.benzine.benzineViewController',
        'Ext.tab.Panel',
        'Ext.toolbar.Toolbar',
        'Ext.container.ButtonGroup',
        'Ext.grid.Panel',
        'Ext.grid.column.RowNumberer',
        'Ext.grid.column.Action',
        'Ext.view.Table',
        'Ext.grid.plugin.RowExpander',
        'Ext.XTemplate',
        'Ext.grid.feature.Grouping',
        'Ext.tab.Tab',
        'Ext.form.field.Hidden'
    ],

    controller: 'benzinebenzine',
    viewModel: {
        type: 'benzinebenzine'
    },
    autoShow: true,
    height: 441,
    id: 'appBenzine',
    width: 546,
    layout: 'fit',
    title: 'Планировщик поездок',

    items: [
        {
            xtype: 'tabpanel',
            activeTab: 0,
            items: [
                {
                    xtype: 'panel',
                    layout: 'fit',
                    header: false,
                    title: 'Планировщик',
                    dockedItems: [
                        {
                            xtype: 'toolbar',
                            dock: 'top',
                            items: [
                                {
                                    xtype: 'buttongroup',
                                    title: 'Редактор',
                                    columns: 2,
                                    items: [
                                        {
                                            xtype: 'button',
                                            reference: 'btnPlanerAdd',
                                            itemId: 'btnPlanerAdd',
                                            iconCls: 'icon-db-add',
                                            text: 'Добавить',
                                            listeners: {
                                                click: 'onBtnPlanerAddClick'
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            reference: 'btnPlanerDel',
                                            disabled: true,
                                            itemId: 'btnPlanerDel',
                                            iconCls: 'icon-db-del',
                                            text: 'Удалить',
                                            listeners: {
                                                click: 'onBtnPlanerDelClick'
                                            }
                                        }
                                    ]
                                },
                                {
                                    xtype: 'buttongroup',
                                    reference: 'btngrpPlanerWeek',
                                    title: '2015-08-05 ... 2015-08-06',
                                    columns: 3,
                                    items: [
                                        {
                                            xtype: 'button',
                                            id: 'btnBenzinePlanerBackward',
                                            width: 70,
                                            iconCls: 'icon-arrow-left',
                                            text: 'назад',
                                            listeners: {
                                                click: 'onBtnBenzinePlanerBackwardClick'
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            id: 'btnBenzinePlanerNow',
                                            text: 'сегодня',
                                            listeners: {
                                                click: 'onBtnBenzinePlanerNowClick'
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            id: 'btnBenzinePlanerForward',
                                            width: 70,
                                            iconAlign: 'right',
                                            iconCls: 'icon-arrow-right',
                                            text: 'вперед',
                                            listeners: {
                                                click: 'onBtnBenzinePlanerForwardClick'
                                            }
                                        }
                                    ]
                                },
                                {
                                    xtype: 'buttongroup',
                                    title: 'Отчеты',
                                    columns: 2,
                                    items: [
                                        {
                                            xtype: 'button',
                                            id: 'btnBenzinePlanerPrint',
                                            iconCls: 'icon-printer',
                                            text: 'Печать',
                                            listeners: {
                                                click: 'onBtnBenzinePlanerPrintClick'
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            hidden: true,
                                            iconCls: 'icon-report',
                                            text: 'Button 2'
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    items: [
                        {
                            xtype: 'gridpanel',
                            reference: 'gridMain',
                            itemId: 'gridMain',
                            header: false,
                            store: 'benzine.planer',
                            columns: [
                                {
                                    xtype: 'rownumberer',
                                    dataIndex: 'number'
                                },
                                {
                                    xtype: 'actioncolumn',
                                    width: 25,
                                    menuDisabled: true,
                                    items: [
                                        {
                                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                                var edit = Ext.create('Portal.view.benzine.planer_edit');
                                                edit.getReferences().formPlaner.getForm().setValues(record.data);
                                                edit.getReferences().formPlaner.getForm().setValues({
                                                    new: 0
                                                });
                                                edit.show();
                                            },
                                            iconCls: 'icon-edit'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'gridcolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        //console.log(record);
                                        return '<span style="color:red";>'+value+'</span>: <span style="color:green";>'+record.data.week_day+'</span>';
                                    },
                                    dataIndex: 'begin_date',
                                    draggable: false,
                                    groupable: true,
                                    text: 'Дата'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    width: 48,
                                    align: 'center',
                                    dataIndex: 'begin_time',
                                    hideable: false,
                                    menuDisabled: true,
                                    text: 'Время'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        return value+' ч.';
                                    },
                                    width: 42,
                                    align: 'center',
                                    dataIndex: 'duration',
                                    menuDisabled: true,
                                    text: 'Продолжительность',
                                    tooltip: 'Продолжительность поездки'
                                },
                                {
                                    xtype: 'actioncolumn',
                                    renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
                                        switch(value){
                                            case '0':this.items[0].iconCls='icon-ahtung';
                                            this.items[0].tooltip='Поездка запланирована';
                                            break;
                                            case '1':this.items[0].iconCls='icon-tick-3';
                                            this.items[0].tooltip='Поездка завершена';
                                            break;
                                            case '2':this.items[0].iconCls='icon-ahtung-red';
                                            this.items[0].tooltip='Поездка просрочена!!!';
                                            break;
                                        }
                                    },
                                    dataIndex: 'tour_success',
                                    width: 43,
                                    align: 'center',
                                    text: 'Завершен',
                                    tooltip: 'Поездка Завершена',
                                    items: [
                                        {
                                            handler: function(view, rowIndex, colIndex, item, e, record, row) {
                                                switch(record.data.tour_success){
                                                    case '0':
                                                    case '2':
                                                    Ext.Msg.show({
                                                        title:'Внимание',
                                                        message: 'Данным действием Вы подтверждаете, что данная поездка состоялась в полном объеме!',
                                                        buttons: Ext.Msg.YESNO,
                                                        icon: Ext.Msg.QUESTION,
                                                        fn: function(btn) {
                                                            if (btn === 'yes') {
                                                                //            v.send_c(view,record.data.id,1);
                                                                view.mask('Подождите...');
                                                                Ext.Ajax.request({
                                                                    url: 'data/benzine/planer-confirm.php',
                                                                    params: {
                                                                        id: record.data.id,
                                                                        confirm: 1
                                                                    },
                                                                    success: function(response){
                                                                        Ext.getStore('benzine.planer').reload();
                                                                    }
                                                                });
                                                                view.unmask();
                                                            }
                                                        }
                                                    });
                                                    break;
                                                    case '1':
                                                    Ext.Msg.show({
                                                        title:'Внимание',
                                                        message: 'Вы действительно желаете вернуть статус поездки на НЕЗАВЕРШЕННЫЙ?',
                                                        buttons: Ext.Msg.YESNO,
                                                        icon: Ext.Msg.QUESTION,
                                                        fn: function(btn) {
                                                            if (btn === 'yes') {
                                                                //            v.send_c(view,record.data.id,0);
                                                                view.mask('Подождите...');
                                                                Ext.Ajax.request({
                                                                    url: 'data/benzine/planer-confirm.php',
                                                                    params: {
                                                                        id: record.data.id,
                                                                        confirm: 0
                                                                    },
                                                                    success: function(response){
                                                                        Ext.getStore('benzine.planer').reload();
                                                                    }
                                                                });
                                                                view.unmask();
                                                            }
                                                        }
                                                    });
                                                    break;
                                                }
                                            },
                                            iconCls: 'icon-ahtung'
                                        }
                                    ]
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'otdel',
                                    text: 'Отдел',
                                    flex: 1
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'type_tour',
                                    menuDisabled: true,
                                    text: 'Тип поездки',
                                    flex: 1
                                }
                            ],
                            viewConfig: {
                                listeners: {
                                    selectionchange: 'onTableSelectionChange'
                                }
                            },
                            plugins: [
                                {
                                    ptype: 'rowexpander',
                                    rowBodyTpl: Ext.create('Ext.XTemplate', 
                                        '{tour}',
                                        '<p><b>Пояснение:</b> {reference}</p>',
                                        {
                                            tpl: function() {
                                                return '';
                                            }
                                        }
                                    )
                                }
                            ],
                            features: [
                                {
                                    ftype: 'grouping',
                                    showSummaryRow: true,
                                    collapsible: false,
                                    groupHeaderTpl: [
                                        '{name}'
                                    ],
                                    hideGroupedHeader: true
                                }
                            ]
                        },
                        {
                            xtype: 'hiddenfield',
                            reference: 'CurrentWeek'
                        }
                    ],
                    tabConfig: {
                        xtype: 'tab',
                        iconCls: 'icon-date',
                        listeners: {
                            activate: 'onTabActivate'
                        }
                    }
                },
                {
                    xtype: 'panel',
                    width: 532,
                    layout: 'border',
                    title: 'Настройки',
                    items: [
                        {
                            xtype: 'container',
                            region: 'west',
                            split: true,
                            width: 250,
                            layout: 'border',
                            items: [
                                {
                                    xtype: 'gridpanel',
                                    region: 'north',
                                    split: true,
                                    reference: 'gridTypeTour',
                                    height: 250,
                                    itemId: 'gridTypeTour',
                                    title: 'Типы поездок',
                                    store: 'benzine.type_tour',
                                    viewConfig: {
                                        id: 'tableTypeTour',
                                        listeners: {
                                            itemdblclick: 'onTableTypeTourItemDblClick',
                                            selectionchange: 'onTableTypeTourSelectionChange'
                                        }
                                    },
                                    dockedItems: [
                                        {
                                            xtype: 'toolbar',
                                            dock: 'top',
                                            items: [
                                                {
                                                    xtype: 'button',
                                                    reference: 'btnTypeTourAdd',
                                                    itemId: 'btnTypeTourAdd',
                                                    iconCls: 'icon-db-add',
                                                    text: 'Добавить',
                                                    listeners: {
                                                        click: 'onBtnTypeTourAddClick'
                                                    }
                                                },
                                                {
                                                    xtype: 'button',
                                                    reference: 'btnTypeTourDelete',
                                                    disabled: true,
                                                    itemId: 'btnTypeTourDelete',
                                                    iconCls: 'icon-db-del',
                                                    text: 'Удалить',
                                                    listeners: {
                                                        click: 'onBtnTypeTourDeleteClick'
                                                    }
                                                },
                                                {
                                                    xtype: 'button',
                                                    id: 'btnBenzineGridTypeTour',
                                                    iconCls: 'icon-refresh',
                                                    text: 'Обновить',
                                                    listeners: {
                                                        click: 'onBtnBenzineGridTypeTourClick'
                                                    }
                                                }
                                            ]
                                        }
                                    ],
                                    columns: [
                                        {
                                            xtype: 'rownumberer'
                                        },
                                        {
                                            xtype: 'gridcolumn',
                                            dataIndex: 'name',
                                            text: 'Наименование',
                                            flex: 1
                                        }
                                    ]
                                },
                                {
                                    xtype: 'gridpanel',
                                    region: 'center',
                                    split: true,
                                    reference: 'gridCity',
                                    itemId: 'gridCity',
                                    title: 'Города',
                                    store: 'benzine.city',
                                    columns: [
                                        {
                                            xtype: 'rownumberer',
                                            dataIndex: 'number',
                                            text: 'Number'
                                        },
                                        {
                                            xtype: 'gridcolumn',
                                            dataIndex: 'name',
                                            text: 'Наименование',
                                            flex: 1
                                        }
                                    ],
                                    viewConfig: {
                                        id: 'benzine_tableCity',
                                        listeners: {
                                            selectionchange: 'onBenzine_tableCitySelectionChange',
                                            itemdblclick: 'onBenzine_tableCityItemDblClick'
                                        }
                                    },
                                    dockedItems: [
                                        {
                                            xtype: 'toolbar',
                                            dock: 'top',
                                            items: [
                                                {
                                                    xtype: 'button',
                                                    reference: 'btnCityAdd',
                                                    id: 'btnCityAdd',
                                                    iconCls: 'icon-db-add',
                                                    text: 'Добавить',
                                                    listeners: {
                                                        click: 'onBtnCityAddClick'
                                                    }
                                                },
                                                {
                                                    xtype: 'button',
                                                    reference: 'btnCityDelete',
                                                    disabled: true,
                                                    id: 'btnCityDelete',
                                                    iconCls: 'icon-db-del',
                                                    text: 'Удалить',
                                                    listeners: {
                                                        click: 'onBtnCityDeleteClick'
                                                    }
                                                },
                                                {
                                                    xtype: 'button',
                                                    id: 'btnBenzineGridCity',
                                                    iconCls: 'icon-refresh',
                                                    text: 'Обновить',
                                                    listeners: {
                                                        click: 'onBtnBenzineGridCityClick'
                                                    }
                                                }
                                            ]
                                        }
                                    ]
                                }
                            ]
                        },
                        {
                            xtype: 'gridpanel',
                            region: 'center',
                            split: true,
                            reference: 'gridAddress',
                            itemId: 'gridAddress',
                            title: 'Адреса',
                            store: 'benzine.address',
                            columns: [
                                {
                                    xtype: 'rownumberer'
                                },
                                {
                                    xtype: 'gridcolumn',
                                    dataIndex: 'address_full',
                                    text: 'Адрес',
                                    flex: 1
                                }
                            ],
                            viewConfig: {
                                id: 'benzine_tableAddress',
                                listeners: {
                                    selectionchange: 'onBenzine_tableAddressSelectionChange',
                                    itemdblclick: 'onBenzine_tableAddressItemDblClick'
                                }
                            },
                            dockedItems: [
                                {
                                    xtype: 'toolbar',
                                    dock: 'top',
                                    items: [
                                        {
                                            xtype: 'button',
                                            reference: 'btnAddressAdd',
                                            id: 'btnAddressAdd',
                                            iconCls: 'icon-db-add',
                                            text: 'Добавить',
                                            listeners: {
                                                click: 'onBtnAddressAddClick'
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            reference: 'btnAddressDelete',
                                            disabled: true,
                                            id: 'btnAddressDelete',
                                            iconCls: 'icon-db-del',
                                            text: 'Удалить',
                                            listeners: {
                                                click: 'onBtnAddressDeleteClick'
                                            }
                                        },
                                        {
                                            xtype: 'button',
                                            id: 'btnBenzineGridAddressRefresh',
                                            iconCls: 'icon-refresh',
                                            text: 'Обновить',
                                            listeners: {
                                                click: 'onBtnBenzineGridAddressRefreshClick'
                                            }
                                        }
                                    ]
                                }
                            ]
                        }
                    ],
                    tabConfig: {
                        xtype: 'tab',
                        iconCls: 'icon-setting',
                        listeners: {
                            activate: 'onTabActivate1'
                        }
                    }
                },
                {
                    xtype: 'panel',
                    hidden: true,
                    title: 'Tab 3',
                    tabConfig: {
                        xtype: 'tab',
                        iconCls: 'icon-setting'
                    }
                }
            ]
        }
    ],
    listeners: {
        afterrender: 'onAppBenzineAfterRender'
    },

    send_c: function(v, id, c) {
        v.mask('Подождите...');
        Ext.Ajax.request({
            url: 'data/benzine/planer-confirm.php',
            params: {
                id: id,
                confirm: c
            },
            success: function(response){
                Ext.getStore('benzine.planer').reload();
        }
        });
        v.unmask();
    }

});