/*
 * File: app/view/benzine/Tour_Edit.js
 *
 * This file was generated by Sencha Architect version 4.2.2.
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

Ext.define('Portal.view.benzine.Tour_Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.benzinetour_edit',

    requires: [
        'Portal.view.benzine.Tour_EditViewModel',
        'Portal.view.benzine.Tour_EditViewController',
        'Ext.form.Panel',
        'Ext.form.FieldContainer',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Number',
        'Ext.form.field.Hidden',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.toolbar.Separator'
    ],

    controller: 'benzinetour_edit',
    viewModel: {
        type: 'benzinetour_edit'
    },
    modal: true,
    maxWidth: 400,
    minHeight: 175,
    minWidth: 400,
    width: 400,
    title: 'Поездка',

    layout: {
        type: 'vbox',
        align: 'stretch',
        pack: 'end'
    },
    items: [
        {
            xtype: 'form',
            flex: 1,
            reference: 'formTourEdit',
            bodyPadding: 10,
            items: [
                {
                    xtype: 'fieldcontainer',
                    height: 24,
                    layout: {
                        type: 'hbox',
                        align: 'middle'
                    },
                    items: [
                        {
                            xtype: 'combobox',
                            flex: 1,
                            id: 'fldBenzineTourTourFrom',
                            fieldLabel: 'Откуда',
                            labelWidth: 50,
                            name: 'tour_from',
                            allowBlank: false,
                            editable: false,
                            displayField: 'address_full',
                            store: 'benzine.address',
                            valueField: 'address_full',
                            listeners: {
                                change: 'onFldBenzineTourTourFromChange'
                            }
                        }
                    ]
                },
                {
                    xtype: 'fieldcontainer',
                    height: 29,
                    layout: {
                        type: 'hbox',
                        align: 'middle'
                    },
                    items: [
                        {
                            xtype: 'combobox',
                            flex: 1,
                            id: 'fldBenzineTourTourTo',
                            fieldLabel: 'Куда',
                            labelWidth: 50,
                            name: 'tour_to',
                            allowBlank: false,
                            editable: false,
                            displayField: 'address_full',
                            store: 'benzine.address',
                            valueField: 'address_full',
                            listeners: {
                                change: 'onFldBenzineTourTourToChange'
                            }
                        }
                    ]
                },
                {
                    xtype: 'numberfield',
                    anchor: '100%',
                    fieldLabel: 'Расстояние, км',
                    name: 'distance'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'id'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'tour_id'
                }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            flex: 1,
            dock: 'bottom',
            layout: {
                type: 'hbox',
                align: 'middle',
                padding: 5
            },
            items: [
                {
                    xtype: 'container',
                    layout: {
                        type: 'hbox',
                        align: 'middle',
                        pack: 'end',
                        padding: 5
                    },
                    items: [
                        {
                            xtype: 'button',
                            id: 'btnBenzineTourEditSave',
                            iconCls: 'dialog-save',
                            text: 'Сохранить',
                            listeners: {
                                click: 'onBtnBenzineTourEditSaveClick'
                            }
                        },
                        {
                            xtype: 'button',
                            id: 'btnBenzineTourEditCancel',
                            iconCls: 'dialog-cancel',
                            text: 'Отмена',
                            listeners: {
                                click: 'onBtnBenzineTourEditCancelClick'
                            }
                        }
                    ]
                },
                {
                    xtype: 'tbseparator'
                },
                {
                    xtype: 'container',
                    items: [
                        {
                            xtype: 'button',
                            id: 'btnBenzineTourEditAddressAdd',
                            iconCls: 'icon-add',
                            text: 'Добавить адрес',
                            listeners: {
                                click: 'onBtnBenzineTourEditAddressAddClick'
                            }
                        }
                    ]
                },
                {
                    xtype: 'container',
                    hidden: true,
                    html: '<i>Если нет нужного адреса, зайдите в <u>Настройки</u> и добавьте</i>',
                    layout: 'column'
                }
            ]
        }
    ]

});