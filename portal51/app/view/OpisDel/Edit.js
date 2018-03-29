/*
 * File: app/view/OpisDel/Edit.js
 *
 * This file was generated by Sencha Architect version 4.0.1.
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

Ext.define('Portal.view.OpisDel.Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.opisdel.edit',

    requires: [
        'Portal.view.OpisDel.EditViewModel',
        'Portal.view.OpisDel.EditViewController',
        'Ext.form.Panel',
        'Ext.form.field.ComboBox',
        'Ext.form.FieldSet',
        'Ext.form.field.Number',
        'Ext.form.field.Date',
        'Ext.toolbar.Toolbar',
        'Ext.form.FieldContainer',
        'Ext.button.Button',
        'Ext.form.field.Hidden',
        'Ext.form.field.TextArea'
    ],

    controller: 'opisdel.edit',
    viewModel: {
        type: 'opisdel.edit'
    },
    modal: true,
    height: 410,
    width: 525,
    layout: 'fit',
    title: 'Опись',

    items: [
        {
            xtype: 'form',
            reference: 'form',
            bodyPadding: 10,
            items: [
                {
                    xtype: 'combobox',
                    anchor: '100%',
                    fieldLabel: 'Отдел',
                    name: 'otdel',
                    editable: false,
                    autoLoadOnValue: true,
                    displayField: 'name',
                    valueField: 'id',
                    bind: {
                        store: '{otdels}'
                    }
                },
                {
                    xtype: 'fieldset',
                    title: 'Заголовок',
                    items: [
                        {
                            xtype: 'combobox',
                            anchor: '100%',
                            fieldLabel: 'Книга',
                            name: 'TitleBook',
                            allowBlank: false,
                            editable: false,
                            autoLoadOnValue: true,
                            displayField: 'name',
                            valueField: 'name',
                            bind: {
                                store: '{books}'
                            }
                        },
                        {
                            xtype: 'textfield',
                            anchor: '100%',
                            fieldLabel: 'Номер',
                            name: 'TitleNumber'
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    layout: {
                        type: 'hbox',
                        align: 'middle',
                        pack: 'center',
                        padding: '0 0 5 0'
                    },
                    items: [
                        {
                            xtype: 'numberfield',
                            width: 160,
                            fieldLabel: 'За какой год',
                            labelAlign: 'top',
                            labelWidth: 50,
                            name: 'Year',
                            allowBlank: false
                        },
                        {
                            xtype: 'textfield',
                            padding: '0 10 0 10',
                            width: 130,
                            fieldLabel: 'Индекс дела',
                            labelAlign: 'top',
                            name: 'Index',
                            allowBlank: false
                        },
                        {
                            xtype: 'numberfield',
                            fieldLabel: 'Количество листов',
                            labelAlign: 'top',
                            name: 'Listov',
                            allowBlank: false
                        }
                    ]
                },
                {
                    xtype: 'fieldset',
                    title: 'Крайние даты',
                    layout: {
                        type: 'hbox',
                        align: 'middle',
                        pack: 'center',
                        padding: '0 0 5 0'
                    },
                    items: [
                        {
                            xtype: 'datefield',
                            reference: 'DateBegin',
                            width: 180,
                            fieldLabel: 'Начало',
                            labelWidth: 50,
                            name: 'DateBegin',
                            allowBlank: false,
                            format: 'Y-m-d',
                            listeners: {
                                change: 'onDateBeginChange'
                            }
                        },
                        {
                            xtype: 'datefield',
                            reference: 'DateEnd',
                            padding: '0 0 0 10',
                            width: 180,
                            fieldLabel: 'Конец',
                            labelWidth: 50,
                            name: 'DateEnd',
                            allowBlank: false,
                            format: 'Y-m-d',
                            listeners: {
                                change: 'onDateEndChange'
                            }
                        }
                    ]
                },
                {
                    xtype: 'textareafield',
                    anchor: '100%',
                    scrollable: 'vertical',
                    fieldLabel: 'Примечание',
                    labelAlign: 'top',
                    name: 'Refer'
                }
            ],
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    modal: true,
                    layout: {
                        type: 'hbox',
                        padding: 10
                    },
                    items: [
                        {
                            xtype: 'fieldcontainer',
                            flex: 1,
                            items: [
                                {
                                    xtype: 'button',
                                    formBind: true,
                                    iconCls: 'dialog-save',
                                    text: 'Сохранить',
                                    listeners: {
                                        click: 'onSaveClick'
                                    }
                                },
                                {
                                    xtype: 'button',
                                    iconCls: 'dialog-cancel',
                                    text: 'Отменить',
                                    listeners: {
                                        click: 'onCancelClick'
                                    }
                                }
                            ]
                        },
                        {
                            xtype: 'fieldcontainer',
                            items: [
                                {
                                    xtype: 'hiddenfield',
                                    modal: true,
                                    width: 86,
                                    fieldLabel: 'id',
                                    labelWidth: 20,
                                    name: 'id'
                                }
                            ]
                        },
                        {
                            xtype: 'fieldcontainer',
                            items: [
                                {
                                    xtype: 'hiddenfield',
                                    width: 139,
                                    fieldLabel: '№ п/п',
                                    labelWidth: 50,
                                    name: 'Npp'
                                }
                            ]
                        }
                    ]
                }
            ]
        }
    ],
    listeners: {
        afterrender: 'onWindowAfterRender'
    }

});