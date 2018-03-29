/*
 * File: app/view/LoadUpr/InputData.js
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

Ext.define('Portal.view.LoadUpr.InputData', {
  extend: 'Ext.window.Window',
  alias: 'widget.loadupr.inputdata',

  requires: [
    'Portal.view.LoadUpr.InputDataViewModel',
    'Portal.view.LoadUpr.InputDataViewController',
    'Ext.toolbar.Toolbar',
    'Ext.form.field.ComboBox',
    'Ext.button.Button',
    'Ext.grid.Panel',
    'Ext.grid.column.RowNumberer',
    'Ext.grid.column.Number',
    'Ext.form.field.Number',
    'Ext.view.Table',
    'Ext.grid.plugin.CellEditing'
  ],

  controller: 'loadupr.inputdata',
  viewModel: {
    type: 'loadupr.inputdata'
  },
  modal: true,
  height: 624,
  width: 876,
  layout: 'fit',
  title: 'Вносим данные за период',
  maximizable: true,
  defaultListenerScope: true,

  dockedItems: [
    {
      xtype: 'toolbar',
      dock: 'top',
      items: [
        {
          xtype: 'combobox',
          flex: 2,
          reference: 'cbOtdel',
          editable: false,
          emptyText: 'Отдел',
          autoLoadOnValue: true,
          displayField: 'name',
          valueField: 'id',
          bind: {
            store: '{otdels}'
          },
          listeners: {
            change: {
              fn: 'onOtdelChange',
              scope: 'controller'
            }
          }
        },
        {
          xtype: 'combobox',
          reference: 'cbYear',
          disabled: true,
          editable: false,
          emptyText: 'Год',
          allQuery: '0',
          displayField: 'years',
          store: 'LoadUpr.Years',
          valueField: 'years',
          listeners: {
            change: {
              fn: 'onYearChange',
              scope: 'controller'
            }
          }
        },
        {
          xtype: 'combobox',
          reference: 'cbMonth',
          disabled: true,
          editable: false,
          emptyText: 'Месяц',
          allQuery: '0',
          displayField: 'name',
          queryParam: 'mode',
          store: 'LoadUpr.Months',
          valueField: 'id',
          listeners: {
            change: {
              fn: 'onMonthChange',
              scope: 'controller'
            }
          }
        },
        {
          xtype: 'button',
          reference: 'btSay',
          disabled: true,
          iconCls: 'icon-say',
          text: 'Покажись',
          listeners: {
            click: {
              fn: 'onSayClick',
              scope: 'controller'
            }
          }
        }
      ]
    }
  ],
  items: [
    {
      xtype: 'gridpanel',
      reference: 'tbGrid',
      bind: {
        store: '{data}'
      },
      columns: [
        {
          xtype: 'rownumberer',
          width: 37
        },
        {
          xtype: 'gridcolumn',
          flex: 1,
          dataIndex: 'name_full',
          text: 'Показатель'
        },
        {
          xtype: 'numbercolumn',
          align: 'end',
          dataIndex: 'var_data',
          text: 'Значение',
          format: '0',
          editor: {
            xtype: 'numberfield'
          }
        }
      ],
      plugins: [
        {
          ptype: 'cellediting',
          listeners: {
            edit: 'onCellEditingEdit'
          }
        }
      ]
    }
  ],
  listeners: {
    afterrender: {
      fn: 'onWindowAfterRender',
      scope: 'controller'
    }
  },

  onCellEditingEdit: function(editor, context, eOpts) {
    var //d=this.getReferences().dateParams.getValue(),
    s=this.getViewModel().getStore('data');
    //console.log(context.record.data);
    Ext.Ajax.request({
      url: 'data/LoadUpr/Data-edit.php',
      params: {
        action:"edit",
        id:context.record.data.id,
        var_data:context.record.data.var_data
      },
      success: function(r) {
        s.reload();
      }
    });
  }

});