/*
 * File: app/view/EvalEffectives/GZIcount.js
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

Ext.define('Portal.view.EvalEffectives.GZIcount', {
    extend: 'Ext.window.Window',
    alias: 'widget.evaleffectivesgzicount',

    requires: [
        'Portal.view.EvalEffectives.GZIcountViewModel',
        'Portal.view.EvalEffectives.GZIcountViewController',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.Panel',
        'Ext.form.field.ComboBox',
        'Ext.form.field.Number',
        'Ext.form.field.Hidden',
        'Ext.form.field.Date'
    ],

    controller: 'evaleffectivesgzicount',
    viewModel: {
        type: 'evaleffectivesgzicount'
    },
    modal: true,
    height: 185,
    width: 464,
    layout: 'fit',
    title: 'Численность зем.инспекторов в отделе',
    defaultListenerScope: true,

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'bottom',
            padding: 5,
            items: [
                {
                    xtype: 'container',
                    padding: 5,
                    items: [
                        {
                            xtype: 'button',
                            iconCls: 'dialog-save',
                            text: 'Сохранить',
                            listeners: {
                                click: 'onButtonClick1'
                            }
                        },
                        {
                            xtype: 'button',
                            iconCls: 'dialog-cancel',
                            text: 'Отмена',
                            listeners: {
                                click: 'onButtonClick'
                            }
                        }
                    ]
                }
            ]
        }
    ],
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
                    name: 'otdel_id',
                    allowBlank: false,
                    autoLoadOnValue: true,
                    displayField: 'name2',
                    valueField: 'id',
                    bind: {
                        store: '{otdels}'
                    }
                },
                {
                    xtype: 'numberfield',
                    anchor: '100%',
                    fieldLabel: 'Количество человек',
                    labelWidth: 250,
                    name: 'n_gzi',
                    allowBlank: false
                },
                {
                    xtype: 'hiddenfield',
                    fieldLabel: 'Label',
                    name: 'id',
                    listeners: {
                        change: {
                            fn: 'onHiddenfieldChange',
                            scope: 'controller'
                        }
                    }
                },
                {
                    xtype: 'datefield',
                    anchor: '100%',
                    reference: 'dateCount',
                    fieldLabel: 'С какой даты действует',
                    labelWidth: 250,
                    name: 'dateCount',
                    allowBlank: false,
                    format: 'Y-m-d'
                }
            ]
        }
    ],

    onButtonClick1: function(button, e, eOpts) {
        var me = this,
            form = me.down('form'),
            values = form.getValues(),
            store = Ext.getStore('EvalEffectives.GZIcount');

        if (form.isValid()) {
            me.mask('Подождите...');

            Ext.Ajax.request({
                url: 'data/EvalEffectives/CountGZI-edit.php',
                params: values,
                success: function(r) {
                    store.reload();
                }
            });
            me.unmask();
            me.close();
        }
    },

    onButtonClick: function(button, e, eOpts) {
        this.close();
    }

});