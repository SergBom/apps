/*
 * File: app/view/CadastErrors/Engineers.js
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

Ext.define('Portal.view.CadastErrors.Engineers', {
  extend: 'Ext.window.Window',
  alias: 'widget.cadasterrors.engineers',

  requires: [
    'Portal.view.CadastErrors.EngineersViewModel',
    'Portal.view.CadastErrors.EngineersViewController',
    'Ext.grid.Panel',
    'Ext.grid.column.RowNumberer',
    'Ext.view.Table',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.field.Text'
  ],

  controller: 'cadasterrors.engineers',
  viewModel: {
    type: 'cadasterrors.engineers'
  },
  modal: true,
  height: 511,
  width: 651,
  layout: 'fit',
  title: 'Список кадастровых инженеров',

  items: [
    {
      xtype: 'gridpanel',
      reference: 'grid',
      bind: {
        store: '{engineers}'
      },
      columns: [
        {
          xtype: 'rownumberer'
        },
        {
          xtype: 'gridcolumn',
          flex: 1,
          dataIndex: 'fio',
          text: 'Ф.И.О.'
        },
        {
          xtype: 'gridcolumn',
          width: 131,
          dataIndex: 'AttNumber',
          text: '№ аттестата'
        }
      ],
      dockedItems: [
        {
          xtype: 'toolbar',
          dock: 'top',
          items: [
            {
              xtype: 'button',
              iconCls: 'icon-add',
              text: 'Добавить',
              listeners: {
                click: 'onAdd'
              }
            },
            {
              xtype: 'button',
              reference: 'btnDel',
              disabled: true,
              iconCls: 'icon-delete',
              text: 'Удалить',
              listeners: {
                click: 'onDel'
              }
            },
            {
              xtype: 'button',
              iconCls: 'icon-refresh',
              text: 'Обновить',
              listeners: {
                click: 'onRefresh'
              }
            }
          ]
        },
        {
          xtype: 'toolbar',
          dock: 'top',
          items: [
            {
              xtype: 'textfield',
              reference: 'seek',
              flex: 1,
              emptyText: 'Поиск по ФИО',
              enableKeyEvents: true,
              listeners: {
                change: 'onSeek'
              }
            }
          ]
        }
      ],
      listeners: {
        itemdblclick: 'onEdit',
        selectionchange: 'onSelectionChange'
      }
    }
  ],
  listeners: {
    afterrender: 'onAfterRender'
  }

});