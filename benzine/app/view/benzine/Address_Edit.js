/*
 * File: app/view/benzine/Address_Edit.js
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

Ext.define('Portal.view.benzine.Address_Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.benzineaddress_edit',

    requires: [
        'Portal.view.benzine.Address_EditViewModel',
        'Portal.view.benzine.Address_EditViewController',
        'Ext.form.Panel',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Hidden',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.toolbar.Separator'
    ],

    controller: 'benzineaddress_edit',
    viewModel: {
        type: 'benzineaddress_edit'
    },
    height: 150,
    width: 400,
    title: 'Адрес',
    modal: true,

    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    items: [
        {
            xtype: 'form',
            reference: 'formAddressEdit',
            height: 73,
            width: 150,
            bodyPadding: 10,
            layout: {
                type: 'vbox',
                align: 'stretch'
            },
            items: [
                {
                    xtype: 'combobox',
                    fieldLabel: 'Населенный пункт',
                    labelWidth: 120,
                    name: 'city_id',
                    allowBlank: false,
                    editable: false,
                    displayField: 'name',
                    store: 'benzine.city',
                    valueField: 'id'
                },
                {
                    xtype: 'textfield',
                    flex: 1,
                    fieldLabel: 'Адрес',
                    labelWidth: 50,
                    name: 'address',
                    allowBlank: false
                },
                {
                    xtype: 'hiddenfield',
                    flex: 1,
                    fieldLabel: 'Label',
                    name: 'id'
                },
                {
                    xtype: 'hiddenfield',
                    flex: 1,
                    fieldLabel: 'Label',
                    name: 'new'
                }
            ]
        }
    ],
    dockedItems: [
        {
            xtype: 'toolbar',
            flex: 1,
            dock: 'bottom',
            items: [
                {
                    xtype: 'container',
                    height: 43,
                    layout: {
                        type: 'hbox',
                        align: 'middle',
                        pack: 'end',
                        padding: 10
                    },
                    items: [
                        {
                            xtype: 'button',
                            id: 'btnAddressEditSave',
                            iconCls: 'dialog-save',
                            text: 'Сохранить',
                            listeners: {
                                click: 'onBtnAddressEditSaveClick'
                            }
                        },
                        {
                            xtype: 'button',
                            id: 'btnAddressEditDelete',
                            iconCls: 'dialog-cancel',
                            text: 'Отмена',
                            listeners: {
                                click: 'onBtnAddressEditDeleteClick'
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
                            id: 'btnBenzineAddressCityAdd',
                            iconCls: 'icon-add',
                            text: 'Добавить населенный пункт',
                            listeners: {
                                click: 'onBtnBenzineAddressCityAddClick'
                            }
                        }
                    ]
                }
            ]
        }
    ]

});