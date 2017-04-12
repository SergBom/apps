/*
 * File: app/view/ScanDocs/dpd.js
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

Ext.define('Portal.view.ScanDocs.dpd', {
  extend: 'Ext.window.Window',
  alias: 'widget.scandocs.dpd',

  requires: [
    'Portal.view.ScanDocs.dpdViewModel',
    'Portal.view.ScanDocs.dpdViewController',
    'Ext.form.Panel',
    'Ext.form.field.Date',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.field.TextArea'
  ],

  controller: 'scandocs.dpd',
  viewModel: {
    type: 'scandocs.dpd'
  },
  modal: true,
  height: 331,
  width: 450,
  layout: 'fit',
  title: 'Вносим ДПД - Только Отсканированные и Загруженные в Платформу',

  items: [
    {
      xtype: 'form',
      reference: 'form',
      border: false,
      bodyPadding: 10,
      items: [
        {
          xtype: 'datefield',
          fieldLabel: 'За какую дату вносим',
          labelWidth: 150,
          name: 'dpd_date',
          allowBlank: false,
          format: 'Y-m-d'
        },
        {
          xtype: 'textareafield',
          anchor: '100%',
          height: 207,
          scrollable: true,
          fieldLabel: 'Список кадастровых номеров',
          labelAlign: 'top',
          name: 'dpd_list',
          allowBlank: false,
          emptyText: 'Обязательно:  1. Каждый номер отдельной строкой.    2. Можно с пробелами или двоеточиями. Другие символы не допускаются. 3. Внимательно смотрите отчет после внесения данных.'
        }
      ],
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
                  text: 'Сохранить',
                  listeners: {
                    click: 'onSaveClick'
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
      ]
    }
  ]

});