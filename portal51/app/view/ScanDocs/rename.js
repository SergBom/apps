/*
 * File: app/view/ScanDocs/rename.js
 *
 * This file was generated by Sencha Architect version 4.2.2.
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

Ext.define('Portal.view.ScanDocs.rename', {
  extend: 'Ext.window.Window',
  alias: 'widget.scandocs.rename',

  requires: [
    'Portal.view.ScanDocs.renameViewModel',
    'Portal.view.ScanDocs.renameViewController',
    'Ext.form.Panel',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.field.Hidden',
    'Ext.form.field.Date',
    'Ext.form.field.ComboBox'
  ],

  controller: 'scandocs.rename',
  viewModel: {
    type: 'scandocs.rename'
  },
  modal: true,
  height: 218,
  width: 400,
  layout: 'fit',
  iconCls: 'icon-edit',
  title: 'Переименовать ДПД',

  items: [
    {
      xtype: 'form',
      reference: 'form',
      border: false,
      bodyPadding: 10,
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
                  formBind: true,
                  iconCls: 'dialog-save',
                  text: 'Переименовать',
                  listeners: {
                    click: 'onRenameClick'
                  }
                },
                {
                  xtype: 'button',
                  iconCls: 'dialog-cancel',
                  text: 'Отмена',
                  listeners: {
                    click: 'onCancelClick'
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
          name: 'name',
          allowBlank: false,
          regexText: 'Шаблон номера: 51 00 0000000 000'
        },
        {
          xtype: 'hiddenfield',
          anchor: '100%',
          fieldLabel: 'Label',
          name: 'path'
        },
        {
          xtype: 'hiddenfield',
          anchor: '100%',
          fieldLabel: 'Label',
          name: 'cyear'
        },
        {
          xtype: 'hiddenfield',
          anchor: '100%',
          fieldLabel: 'Label',
          name: 'id'
        },
        {
          xtype: 'datefield',
          anchor: '100%',
          fieldLabel: 'Дата учета',
          name: 'cdate',
          editable: false,
          format: 'Y-m-d',
          submitFormat: 'Y-m-d'
        },
        {
          xtype: 'combobox',
          anchor: '100%',
          fieldLabel: 'Отдел',
          name: 'n1',
          allowBlank: false,
          editable: false,
          autoLoadOnValue: true,
          displayField: 'name',
          store: 'ScanDocs.Otdel',
          valueField: 'id'
        },
        {
          xtype: 'combobox',
          anchor: '100%',
          fieldLabel: 'Год, за который учитываем дело',
          labelWidth: 200,
          name: 'cyear',
          autoLoadOnValue: true,
          displayField: 'name',
          store: 'ScanDocs.cyears',
          valueField: 'id'
        }
      ]
    }
  ]

});