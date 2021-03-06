/*
 * File: app/view/EGRP/fns.js
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

Ext.define('Portal.view.EGRP.fns', {
   extend: 'Ext.panel.Panel',
   alias: 'widget.egrp.fns',

   requires: [
      'Portal.view.EGRP.fnsViewModel',
      'Portal.view.EGRP.fnsViewController',
      'Ext.form.field.ComboBox',
      'Ext.grid.Panel',
      'Ext.grid.column.Template',
      'Ext.XTemplate',
      'Ext.grid.filters.filter.List',
      'Ext.grid.filters.filter.String',
      'Ext.view.Table',
      'Ext.toolbar.Paging',
      'Ext.grid.filters.Filters',
      'Ext.form.Panel',
      'Ext.button.Button',
      'Ext.form.field.TextArea',
      'Ext.form.FieldSet',
      'Ext.form.field.Radio',
      'Ext.form.field.HtmlEditor',
      'Ext.form.field.Hidden'
   ],

   controller: 'egrp.fns',
   viewModel: {
      type: 'egrp.fns'
   },
   height: 483,
   html: '<h2>Пока в разработке</h2>',
   width: 790,
   layout: 'border',
   title: 'My Panel',

   dockedItems: [
      {
         xtype: 'toolbar',
         dock: 'top',
         items: [
            {
               xtype: 'combobox',
               reference: 'cbProtocol',
               width: 303,
               editable: false,
               emptyText: 'Период',
               displayField: 'period',
               valueField: 'session',
               bind: {
                  store: '{Protocol}'
               },
               listeners: {
                  change: 'onProtocolChange1'
               }
            },
            {
               xtype: 'combobox',
               reference: 'cbOtdel',
               width: 202,
               editable: false,
               emptyText: 'Отдел',
               displayField: 'NAME',
               valueField: 'ID',
               bind: {
                  store: '{Otdel}'
               },
               listeners: {
                  change: 'onOtdelChange1'
               }
            }
         ]
      }
   ],
   items: [
      {
         xtype: 'gridpanel',
         region: 'center',
         split: true,
         reference: 'grid',
         title: 'Список ошибок',
         bind: {
            store: '{Main}'
         },
         columns: [
            {
               xtype: 'gridcolumn',
               width: 36,
               dataIndex: 'RW',
               text: '№'
            },
            {
               xtype: 'templatecolumn',
               tpl: [
                  '{PICTURE}'
               ],
               width: 30,
               dataIndex: 'STATUS_ERROR',
               text: 'Статус',
               filter: {
                  type: 'list'
               }
            },
            {
               xtype: 'gridcolumn',
               width: 73,
               dataIndex: 'OTD_NAME',
               text: 'Отдел',
               filter: {
                  type: 'list'
               }
            },
            {
               xtype: 'gridcolumn',
               flex: 1,
               dataIndex: 'CONCADNUM',
               text: 'Кад/Усл номер',
               filter: {
                  type: 'string'
               }
            },
            {
               xtype: 'gridcolumn',
               flex: 2,
               dataIndex: 'DESCRIPTION',
               text: 'Описание',
               filter: {
                  type: 'list'
               }
            }
         ],
         dockedItems: [
            {
               xtype: 'pagingtoolbar',
               dock: 'bottom',
               displayInfo: true,
               bind: {
                  store: '{Main}'
               },
               listeners: {
                  change: 'onPtbarChange1'
               }
            },
            {
               xtype: 'toolbar',
               dock: 'top'
            }
         ],
         listeners: {
            selectionchange: 'onMSelectionChange1'
         },
         plugins: [
            {
               ptype: 'gridfilters'
            }
         ]
      },
      {
         xtype: 'form',
         region: 'east',
         split: true,
         reference: 'form',
         scrollable: 'both',
         width: 430,
         bodyPadding: 10,
         title: 'Сведения об исправлении ошибки',
         url: 'data/EGRP/tir/tir-error.SAVE-fns2.php',
         dockedItems: [
            {
               xtype: 'toolbar',
               dock: 'top',
               items: [
                  {
                     xtype: 'button',
                     reference: 'btSave',
                     disabled: true,
                     iconCls: 'dialog-save',
                     text: 'Сохранить',
                     listeners: {
                        click: 'onSaveClick1'
                     }
                  }
               ]
            }
         ],
         items: [
            {
               xtype: 'textfield',
               anchor: '100%',
               fieldLabel: 'Терр.отдел',
               name: 'OTD_NAME'
            },
            {
               xtype: 'textfield',
               anchor: '100%',
               fieldLabel: 'OBJ External_ID',
               name: 'EXTERNAL_ID'
            },
            {
               xtype: 'textfield',
               anchor: '100%',
               fieldLabel: 'Кад/Усл. номер',
               name: 'CONCADNUM'
            },
            {
               xtype: 'textareafield',
               anchor: '100%',
               fieldLabel: 'Описание',
               name: 'DESCRIPTION'
            },
            {
               xtype: 'textareafield',
               anchor: '100%',
               fieldLabel: 'Путь',
               name: 'ERROR_PATH'
            },
            {
               xtype: 'textareafield',
               anchor: '100%',
               fieldLabel: 'Значение аттрибута',
               name: 'ATTRIBUTE_VALUE'
            },
            {
               xtype: 'fieldset',
               title: 'Пользователь',
               items: [
                  {
                     xtype: 'fieldcontainer',
                     height: 27,
                     layout: 'hbox',
                     items: [
                        {
                           xtype: 'textfield',
                           fieldLabel: 'Имя',
                           labelAlign: 'right',
                           labelWidth: 40,
                           name: 'USER_NAME'
                        },
                        {
                           xtype: 'textfield',
                           fieldLabel: 'Дата изменения',
                           name: 'USER_DATE'
                        }
                     ]
                  }
               ]
            },
            {
               xtype: 'fieldset',
               height: 77,
               title: 'Ошибка',
               layout: {
                  type: 'hbox',
                  align: 'stretch'
               },
               items: [
                  {
                     xtype: 'fieldcontainer',
                     height: 52,
                     layout: 'vbox',
                     items: [
                        {
                           xtype: 'radiofield',
                           labelCls: 'icon-flag-red',
                           labelWidth: 20,
                           name: 'STATUS_ERROR',
                           boxLabel: 'Ошибка',
                           inputValue: '0'
                        },
                        {
                           xtype: 'radiofield',
                           labelCls: 'icon-flag-green',
                           labelWidth: 20,
                           name: 'STATUS_ERROR',
                           boxLabel: 'Исправлена',
                           inputValue: '1'
                        }
                     ]
                  },
                  {
                     xtype: 'fieldcontainer',
                     height: 26,
                     padding: '0 15 0 15',
                     layout: 'vbox',
                     items: [
                        {
                           xtype: 'radiofield',
                           labelCls: 'icon-flag-yellow',
                           labelWidth: 20,
                           name: 'STATUS_ERROR',
                           boxLabel: 'Не обнаружена',
                           inputValue: '3'
                        },
                        {
                           xtype: 'radiofield',
                           labelCls: 'icon-flag-blue',
                           labelWidth: 20,
                           name: 'STATUS_ERROR',
                           boxLabel: 'Не возможно исправить',
                           inputValue: '2'
                        }
                     ]
                  }
               ]
            },
            {
               xtype: 'htmleditor',
               anchor: '100%',
               height: 150,
               fieldLabel: 'Комментарий',
               name: 'USER_COMMENT'
            },
            {
               xtype: 'hiddenfield',
               anchor: '100%',
               fieldLabel: 'Label',
               name: 'USERGID'
            },
            {
               xtype: 'hiddenfield',
               anchor: '100%',
               reference: 'REC_GUID',
               fieldLabel: 'Label',
               name: 'REC_GUID'
            },
            {
               xtype: 'hiddenfield',
               anchor: '100%',
               fieldLabel: 'Label',
               name: 'DOC_GUID'
            }
         ]
      }
   ]

});