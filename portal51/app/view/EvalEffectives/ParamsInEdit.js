/*
 * File: app/view/EvalEffectives/ParamsInEdit.js
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

Ext.define('Portal.view.EvalEffectives.ParamsInEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.evaleffectivesparamsinedit',

    requires: [
        'Portal.view.EvalEffectives.ParamsInEditViewModel',
        'Ext.form.Panel',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.field.Text',
        'Ext.form.field.HtmlEditor',
        'Ext.form.field.Hidden'
    ],

    viewModel: {
        type: 'evaleffectivesparamsinedit'
    },
    modal: true,
    height: 283,
    width: 400,
    layout: 'fit',
    title: 'Редактор входных параметров',
    maximizable: true,
    defaultListenerScope: true,

    items: [
        {
            xtype: 'form',
            reference: 'form',
            bodyPadding: 10,
            dockedItems: [
                {
                    xtype: 'toolbar',
                    dock: 'bottom',
                    layout: {
                        type: 'hbox',
                        padding: 10
                    },
                    items: [
                        {
                            xtype: 'container',
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
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Параметр (аббревиатура)',
                    labelWidth: 200,
                    name: 'R1',
                    allowBlank: false
                },
                {
                    xtype: 'htmleditor',
                    anchor: '100%',
                    formBind: true,
                    height: 150,
                    fieldLabel: 'Описание/Расшифровка',
                    labelAlign: 'top',
                    name: 'R2'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'id'
                }
            ]
        }
    ],

    onButtonClick1: function(button, e, eOpts) {
        var me = this,
            form = me.down('form'),
            values = form.getValues(),
            store = Ext.getStore('EvalEffectives.ParamsIn');

        if (form.isValid()) {
            me.mask('Подождите...');

            Ext.Ajax.request({
                url: 'data/EvalEffectives/ParamsIn-post.php',
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