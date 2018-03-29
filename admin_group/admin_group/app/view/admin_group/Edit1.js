/*
 * File: app/view/admin_group/Edit1.js
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

Ext.define('Portal.view.admin_group.Edit1', {
    extend: 'Ext.window.Window',
    alias: 'widget.admin_groupedit1',

    requires: [
        'Portal.view.admin_group.Edit1ViewModel',
        'Portal.view.admin_group.Edit1ViewController',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button',
        'Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.form.field.Hidden'
    ],

    controller: 'admin_groupedit1',
    viewModel: {
        type: 'admin_groupedit1'
    },
    height: 176,
    width: 394,
    layout: 'fit',
    title: 'Редактор',
    modal: true,

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'bottom',
            items: [
                {
                    xtype: 'container',
                    padding: 10,
                    items: [
                        {
                            xtype: 'button',
                            id: 'btnAdminGroupEdit1Save',
                            text: 'Сохранить',
                            listeners: {
                                click: 'onBtnAdminGroupEdit1SaveClick'
                            }
                        },
                        {
                            xtype: 'button',
                            id: 'btnAdminGroupEdit1Cancel',
                            text: 'Отмена',
                            listeners: {
                                click: 'onBtnAdminGroupEdit1CancelClick'
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
            reference: 'formEdit',
            bodyPadding: 5,
            items: [
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Группа',
                    name: 'name',
                    allowBlank: false
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'id'
                },
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    fieldLabel: 'Описание',
                    name: 'reference'
                },
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    disabled: true,
                    fieldLabel: 'Ник',
                    name: 'app_nik'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'par_id'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'url'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'store'
                }
            ]
        }
    ]

});