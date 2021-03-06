/*
 * File: app/view/benzine/Type_Tour_Edit.js
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

Ext.define('Portal.view.benzine.Type_Tour_Edit', {
    extend: 'Ext.window.Window',
    alias: 'widget.benzinetype_tour_edit',

    requires: [
        'Portal.view.benzine.Type_Tour_EditViewModel',
        'Portal.view.benzine.Type_Tour_EditViewController',
        'Ext.form.Panel',
        'Ext.form.field.Text',
        'Ext.form.field.Hidden',
        'Ext.button.Button'
    ],

    controller: 'benzinetype_tour_edit',
    viewModel: {
        type: 'benzinetype_tour_edit'
    },
    height: 140,
    width: 400,
    title: 'Тип поездки',
    modal: true,

    layout: {
        type: 'vbox',
        align: 'stretch'
    },
    items: [
        {
            xtype: 'form',
            flex: 1,
            reference: 'formTypeTour',
            itemId: 'formTypeTour',
            defaultFocus: 'name',
            bodyPadding: 10,
            header: false,
            title: 'My Form',
            items: [
                {
                    xtype: 'textfield',
                    anchor: '100%',
                    name: 'name',
                    allowBlank: false,
                    blankText: 'Обязательное поле'
                },
                {
                    xtype: 'hiddenfield',
                    anchor: '100%',
                    fieldLabel: 'Label',
                    name: 'id'
                }
            ]
        },
        {
            xtype: 'container',
            height: 45,
            padding: 10,
            layout: {
                type: 'hbox',
                align: 'middle',
                pack: 'end'
            },
            items: [
                {
                    xtype: 'button',
                    itemId: 'btnTypeTourSave',
                    iconCls: 'dialog-save',
                    params: 'rffrrr',
                    text: 'Сохранить',
                    listeners: {
                        click: 'onBtnTypeTourSaveClick'
                    }
                },
                {
                    xtype: 'button',
                    itemId: 'btnTypeTourCancel',
                    iconCls: 'dialog-cancel',
                    text: 'Отмена',
                    listeners: {
                        click: 'onBtnTypeTourCancelClick'
                    }
                }
            ]
        }
    ]

});