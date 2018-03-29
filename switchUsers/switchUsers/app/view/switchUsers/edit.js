/*
 * File: app/view/switchUsers/edit.js
 *
 * This file was generated by Sencha Architect version 4.1.2.
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

Ext.define('Portal.view.switchUsers.edit', {
  extend: 'Ext.window.Window',
  alias: 'widget.switchusers.edit',

  requires: [
    'Portal.view.switchUsers.editViewModel',
    'Portal.view.switchUsers.editViewController',
    'Ext.form.Panel',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.FieldSet',
    'Ext.form.field.Hidden',
    'Ext.form.field.ComboBox',
    'Ext.form.field.Checkbox',
    'Ext.form.field.Date',
    'Ext.form.field.HtmlEditor'
  ],

  controller: 'switchusers.edit',
  viewModel: {
    type: 'switchusers.edit'
  },
  modal: true,
  height: 482,
  width: 542,
  layout: 'fit',
  title: 'Данные о пользователе',
  defaultListenerScope: true,

  items: [
    {
      xtype: 'form',
      reference: 'form',
      border: false,
      bodyPadding: 10,
      url: 'data/switchUsers/save.php',
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
                  iconCls: 'dialog-save',
                  text: 'Сохранить',
                  listeners: {
                    click: {
                      fn: 'onSaveClick',
                      scope: 'controller'
                    }
                  }
                },
                {
                  xtype: 'button',
                  iconCls: 'dialog-cancel',
                  text: 'Отмена',
                  listeners: {
                    click: {
                      fn: 'onCancelClick',
                      scope: 'controller'
                    }
                  }
                }
              ]
            },
            {
              xtype: 'container',
              flex: 1,
              layout: {
                type: 'hbox',
                align: 'stretch',
                pack: 'end'
              },
              items: [
                {
                  xtype: 'button',
                  disabled: true,
                  text: 'Данные учетной записи АИС ЕГРП'
                }
              ]
            },
            {
              xtype: 'container',
              userCls: 'b-001',
              layout: {
                type: 'hbox',
                align: 'middle',
                pack: 'center',
                padding: 5
              },
              items: [
                {
                  xtype: 'button',
                  iconCls: 'icon-user-del',
                  text: '',
                  tooltip: 'Удалить пользователя',
                  listeners: {
                    click: {
                      fn: 'onDelUserClick',
                      scope: 'controller'
                    }
                  }
                }
              ]
            }
          ]
        }
      ],
      items: [
        {
          xtype: 'fieldset',
          title: 'Ф.И.О.',
          items: [
            {
              xtype: 'fieldcontainer',
              layout: 'column',
              items: [
                {
                  xtype: 'textfield',
                  width: 227,
                  fieldLabel: 'Ф.',
                  labelWidth: 20,
                  name: 'userFm'
                },
                {
                  xtype: 'textfield',
                  padding: '0 0 0 10',
                  width: 220,
                  fieldLabel: 'И.',
                  labelWidth: 20,
                  name: 'userIm'
                }
              ]
            },
            {
              xtype: 'textfield',
              anchor: '100%',
              fieldLabel: 'О.',
              labelWidth: 20,
              name: 'userOt'
            }
          ]
        },
        {
          xtype: 'hiddenfield',
          anchor: '100%',
          fieldLabel: 'Label',
          name: 'id',
          listeners: {
            change: {
              fn: 'onIDChange',
              scope: 'controller'
            }
          }
        },
        {
          xtype: 'fieldset',
          title: 'Отдел / Должность',
          items: [
            {
              xtype: 'combobox',
              anchor: '100%',
              name: 'otdel_id',
              editable: false,
              emptyText: 'Отдел',
              autoLoadOnValue: true,
              displayField: 'name',
              valueField: 'id',
              bind: {
                store: '{otdels}'
              }
            },
            {
              xtype: 'combobox',
              anchor: '100%',
              name: 'dolzhnost_id',
              editable: false,
              emptyText: 'Должность',
              autoLoadOnValue: true,
              displayField: 'name',
              valueField: 'id',
              bind: {
                store: '{dolzhnost}'
              }
            }
          ]
        },
        {
          xtype: 'fieldset',
          title: 'Планировщик',
          items: [
            {
              xtype: 'fieldcontainer',
              layout: {
                type: 'hbox',
                align: 'stretchmax'
              },
              items: [
                {
                  xtype: 'checkboxfield',
                  disabled: true,
                  disabledCls: 'x-item',
                  fieldLabel: 'В данный момент "Выключен"',
                  labelClsExtra: 'user-off',
                  labelWidth: 180,
                  name: 'off'
                },
                {
                  xtype: 'checkboxfield',
                  padding: '0 0 0 50',
                  fieldLabel: '"Видимый"',
                  labelClsExtra: 'user-nosay',
                  labelWidth: 80,
                  name: 'say'
                }
              ]
            },
            {
              xtype: 'fieldcontainer',
              layout: 'column',
              items: [
                {
                  xtype: 'datefield',
                  reference: 'dateOff',
                  fieldLabel: 'Выключить с',
                  labelWidth: 80,
                  name: 'dateOff',
                  format: 'Y-m-d',
                  listeners: {
                    change: 'onDatefieldChange'
                  }
                },
                {
                  xtype: 'datefield',
                  reference: 'dateOn',
                  padding: '0 0 0 10',
                  fieldLabel: 'Включить с',
                  labelWidth: 80,
                  name: 'dateOn',
                  format: 'Y-m-d',
                  listeners: {
                    change: 'onDatefieldChange1'
                  }
                }
              ]
            }
          ]
        },
        {
          xtype: 'htmleditor',
          anchor: '100%',
          height: 133,
          name: 'refer'
        }
      ]
    }
  ],

  onDatefieldChange: function(field, newValue, oldValue, eOpts) {
    this.getReferences().dateOn.setMinValue(newValue);
  },

  onDatefieldChange1: function(field, newValue, oldValue, eOpts) {
    this.getReferences().dateOff.setMaxValue(newValue);
  }

});