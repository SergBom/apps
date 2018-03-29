/*
 * File: app/view/EvalEffectives/JobersGroupEdit.js
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

Ext.define('Portal.view.EvalEffectives.JobersGroupEdit', {
    extend: 'Ext.window.Window',
    alias: 'widget.evaleffectivesjobersgroupedit',

    requires: [
        'Portal.view.EvalEffectives.JobersGroupEditViewModel',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.form.field.Hidden'
    ],

    viewModel: {
        type: 'evaleffectivesjobersgroupedit'
    },
    modal: true,
    height: 155,
    width: 463,
    layout: 'fit',
    title: 'Группа доступа',
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
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Наименование',
                    name: 'groupname',
                    allowBlank: false
                },
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Описание',
                    name: 'info'
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
            store = Ext.getStore('EvalEffectives.JobersGroup');

        if (form.isValid()) {
            me.mask('Подождите...');

            Ext.Ajax.request({
                url: 'data/EvalEffectives/JobersGroup-edit.php',
                params: values,
                success: function(r) {
                    store.load();
                }
            });
            me.unmask();
            me.close();
        }
        //this.close();
    },

    onButtonClick: function(button, e, eOpts) {
        this.close();
    }

});