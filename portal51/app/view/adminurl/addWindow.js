/*
 * File: app/view/adminurl/addWindow.js
 *
 * This file was generated by Sencha Architect version 4.1.1.
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

Ext.define('Portal.view.adminurl.addWindow', {
  extend: 'Ext.window.Window',
  alias: 'widget.adminurladdwindow',

  requires: [
    'Portal.view.adminurl.addWindowViewModel',
    'Portal.view.adminurl.addWindowViewController',
    'Ext.form.Panel',
    'Ext.form.field.TextArea',
    'Ext.form.field.Checkbox',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.field.Hidden'
  ],

  controller: 'adminurladdwindow',
  viewModel: {
    type: 'adminurladdwindow'
  },
  modal: true,
  height: 250,
  id: 'adminurladdwindow',
  width: 400,
  layout: 'fit',
  title: 'Редактор ссылки',

  items: [
    {
      xtype: 'form',
      reference: 'form',
      height: 218,
      id: 'adminurlform',
      itemId: 'adminurlform',
      bodyPadding: 10,
      title: '',
      layout: {
        type: 'vbox',
        align: 'stretch'
      },
      items: [
        {
          xtype: 'textfield',
          fieldLabel: 'Наименование',
          name: 'name'
        },
        {
          xtype: 'textfield',
          fieldLabel: 'Url',
          name: 'url'
        },
        {
          xtype: 'textareafield',
          fieldLabel: 'Комментарий',
          name: 'coment'
        },
        {
          xtype: 'checkboxfield',
          fieldLabel: 'только админу',
          name: 'admin',
          boxLabel: ''
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
          name: 'flag'
        }
      ],
      dockedItems: [
        {
          xtype: 'toolbar',
          dock: 'bottom',
          items: [
            {
              xtype: 'container',
              height: 48,
              padding: 10,
              layout: {
                type: 'hbox',
                align: 'middle',
                pack: 'center'
              },
              items: [
                {
                  xtype: 'button',
                  flex: 1,
                  formBind: true,
                  itemId: 'saveButton',
                  margin: 5,
                  iconCls: 'dialog-save',
                  text: 'Сохранить',
                  listeners: {
                    click: 'onSave'
                  }
                },
                {
                  xtype: 'button',
                  flex: 1,
                  itemId: 'cancelButton',
                  margin: 5,
                  iconCls: 'dialog-cancel',
                  text: 'Отмена',
                  listeners: {
                    click: 'onCancel'
                  }
                }
              ]
            }
          ]
        }
      ]
    }
  ]

});