/*
 * File: app/view/GKUstop/KPedit.js
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

Ext.define('Portal.view.GKUstop.KPedit', {
  extend: 'Ext.window.Window',
  alias: 'widget.gkustop.kpedit',

  requires: [
    'Portal.view.GKUstop.KPeditViewModel',
    'Portal.view.GKUstop.KPeditViewController',
    'Ext.form.Panel',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.FieldSet',
    'Ext.form.field.Date',
    'Ext.form.field.ComboBox',
    'Ext.form.field.Hidden',
    'Ext.form.field.TextArea'
  ],

  controller: 'gkustop.kpedit',
  viewModel: {
    type: 'gkustop.kpedit'
  },
  modal: true,
  height: 451,
  width: 613,
  layout: 'fit',
  title: 'Новая запись',

  items: [
    {
      xtype: 'form',
      reference: 'form',
      border: false,
      scrollable: true,
      bodyPadding: 10,
      url: 'data/GKUstop/KPedit.php',
      dockedItems: [
        {
          xtype: 'toolbar',
          dock: 'top',
          items: [
            {
              xtype: 'button',
              formBind: true,
              iconCls: 'dialog-save',
              text: 'Сохранить',
              listeners: {
                click: 'onSave'
              }
            },
            {
              xtype: 'button',
              hidden: true,
              text: 'Список кадастровых инженеров'
            }
          ]
        }
      ],
      items: [
        {
          xtype: 'fieldset',
          title: 'Заявление',
          items: [
            {
              xtype: 'textfield',
              anchor: '100%',
              fieldLabel: '* №',
              name: 'zayav',
              allowBlank: false
            },
            {
              xtype: 'container',
              layout: {
                type: 'hbox',
                align: 'stretchmax'
              },
              items: [
                {
                  xtype: 'datefield',
                  padding: '0 0 5 0',
                  fieldLabel: '* Дата подачи',
                  name: 'zayav_date',
                  allowBlank: false,
                  editable: false,
                  format: 'Y-m-d'
                },
                {
                  xtype: 'combobox',
                  padding: '0 0 5 10',
                  fieldLabel: '* Тип',
                  labelWidth: 50,
                  name: 'zayav_type',
                  allowBlank: false,
                  editable: false,
                  autoLoadOnValue: true,
                  displayField: 'name',
                  valueField: 'id',
                  bind: {
                    store: '{zayav_type}'
                  }
                }
              ]
            }
          ]
        },
        {
          xtype: 'fieldset',
          title: 'Объект недвижимости',
          layout: {
            type: 'hbox',
            align: 'stretchmax'
          },
          items: [
            {
              xtype: 'combobox',
              padding: '0 0 5 0',
              fieldLabel: '* Вид',
              labelWidth: 50,
              name: 'ONvid',
              allowBlank: false,
              editable: false,
              autoLoadOnValue: true,
              displayField: 'name',
              valueField: 'id',
              bind: {
                store: '{ONvid}'
              }
            },
            {
              xtype: 'textfield',
              flex: 1,
              padding: '0 0 5 10',
              fieldLabel: '* Характеристика',
              labelWidth: 110,
              name: 'charc',
              allowBlank: false
            }
          ]
        },
        {
          xtype: 'container',
          layout: 'column',
          items: [
            {
              xtype: 'datefield',
              padding: '0 5 5 0 ',
              width: 160,
              fieldLabel: 'Учет',
              labelAlign: 'top',
              labelWidth: 50,
              name: 'KU_date_uchet',
              editable: false,
              format: 'Y-m-d'
            },
            {
              xtype: 'datefield',
              padding: '0 5 0 5',
              width: 160,
              fieldLabel: 'Приостановление',
              labelAlign: 'top',
              name: 'KU_date_stop',
              editable: false,
              format: 'Y-m-d'
            },
            {
              xtype: 'datefield',
              padding: '0 0 0 5',
              width: 160,
              fieldLabel: 'Отказ',
              labelAlign: 'top',
              labelWidth: 50,
              name: 'KU_date_none',
              editable: false,
              format: 'Y-m-d'
            }
          ]
        },
        {
          xtype: 'textfield',
          anchor: '100%',
          fieldLabel: '218-ФЗ',
          name: 'KU_FZ'
        },
        {
          xtype: 'textfield',
          anchor: '100%',
          fieldLabel: 'Требования',
          name: 'KU_treb'
        },
        {
          xtype: 'hiddenfield',
          anchor: '100%',
          fieldLabel: 'Label',
          name: 'id'
        },
        {
          xtype: 'container',
          layout: {
            type: 'hbox',
            align: 'stretchmax'
          },
          items: [
            {
              xtype: 'combobox',
              flex: 1,
              fieldLabel: '* Кадастровый инженер',
              labelWidth: 150,
              name: 'CadEngineer',
              allowBlank: false,
              editable: false,
              anyMatch: true,
              autoLoadOnValue: true,
              displayField: 'fio',
              valueField: 'id',
              bind: {
                store: '{engineers}'
              }
            },
            {
              xtype: 'button',
              iconCls: 'icon-add',
              listeners: {
                click: 'onAddEng'
              }
            }
          ]
        },
        {
          xtype: 'textareafield',
          anchor: '100%',
          height: 99,
          fieldLabel: 'Примечание',
          labelAlign: 'top',
          name: 'KU_refer'
        }
      ]
    }
  ]

});