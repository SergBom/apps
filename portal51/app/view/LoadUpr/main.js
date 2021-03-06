/*
 * File: app/view/LoadUpr/main.js
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

Ext.define('Portal.view.LoadUpr.main', {
  extend: 'Ext.panel.Panel',
  alias: 'widget.loaduprmain',

  requires: [
    'Portal.view.LoadUpr.mainViewModel',
    'Portal.view.LoadUpr.mainViewController',
    'Ext.toolbar.Toolbar',
    'Ext.form.field.ComboBox',
    'Ext.button.Button',
    'Ext.grid.Panel',
    'Ext.grid.column.RowNumberer',
    'Ext.view.Table',
    'Ext.grid.column.Number'
  ],

  controller: 'loadupr.main',
  viewModel: {
    type: 'loadupr.main'
  },
  height: 513,
  width: 1332,
  layout: 'fit',
  title: 'My Panel',

  dockedItems: [
    {
      xtype: 'toolbar',
      dock: 'top',
      items: [
        {
          xtype: 'combobox',
          reference: 'cbYear',
          width: 110,
          editable: false,
          emptyText: 'Год',
          allQuery: 'A',
          displayField: 'years',
          store: 'LoadUpr.Years',
          valueField: 'years',
          listeners: {
            change: 'onYearChange'
          }
        },
        {
          xtype: 'combobox',
          reference: 'cbMonth',
          disabled: true,
          editable: false,
          emptyText: 'Месяц',
          allQuery: 'A',
          autoLoadOnValue: true,
          displayField: 'name',
          queryParam: 'mode',
          store: 'LoadUpr.Months',
          valueField: 'id',
          listeners: {
            change: 'onMonthChange'
          }
        },
        {
          xtype: 'button',
          reference: 'btSay',
          disabled: true,
          iconCls: 'icon-say',
          text: 'Покажись',
          listeners: {
            click: 'onSayClick'
          }
        },
        {
          xtype: 'button',
          iconCls: 'icon-db-add',
          text: 'Внести данные',
          listeners: {
            click: 'onMainDataClick'
          }
        },
        {
          xtype: 'button',
          iconCls: 'icon-info',
          text: 'Справочные данные',
          listeners: {
            click: 'onReferClick'
          }
        },
        {
          xtype: 'button',
          reference: 'btTimes',
          iconCls: 'icon-clock',
          text: 'Временной показатель',
          listeners: {
            click: 'onTimesClick'
          }
        }
      ]
    }
  ],
  items: [
    {
      xtype: 'gridpanel',
      reference: 'grid',
      disabled: true,
      defaults: {
        columns: {
          defaults: {
            align: 'end'
          }
        }
      },
      store: 'LoadUpr.main',
      columns: [
        {
          xtype: 'rownumberer',
          align: 'end',
          locked: true,
          text: '№<br>п/п'
        },
        {
          xtype: 'gridcolumn',
          width: 200,
          dataIndex: 'name_full',
          locked: true,
          text: 'Наименование<br>отдела<br><br>[А]'
        },
        {
          xtype: 'gridcolumn',
          width: 86,
          align: 'end',
          dataIndex: 'time_fact',
          tdCls: 'x-panel-style1',
          text: 'Количество<br>фактически<br>отработанного<br>времени<br>сотрудниками<br>отдела<br><br>[Б]',
          tooltip: 'Количество<br>фактически<br>отработанного<br>времени<br>сотрудниками<br>отдела<br><br>[Б]'
        },
        {
          xtype: 'gridcolumn',
          width: 72,
          cellWrap: true,
          dataIndex: 'beginDocs2regs',
          text: 'Подготовка<br>документов<br>к рег-ции<br>(ИШ, корр.<br>данных)<br><br>[1]',
          tooltip: 'Подготовка<br>документов<br>к рег-ции<br>(ИШ, корр.<br>данных)<br><br>[1]'
        },
        {
          xtype: 'gridcolumn',
          text: 'Осуществление государственной регистрации недвижимости',
          columns: [
            {
              xtype: 'gridcolumn',
              text: 'КУ (003)',
              columns: [
                {
                  xtype: 'gridcolumn',
                  baseCls: 'x-component x-panel-style1',
                  width: 49,
                  dataIndex: 'KU_regs',
                  text: 'Регис-<br>трация<br><br>[2]'
                },
                {
                  xtype: 'gridcolumn',
                  width: 48,
                  dataIndex: 'KU_MakeDocs',
                  text: 'Форм-е<br>док-ов<br><br>[3]',
                  tooltip: 'Формирование документов'
                }
              ]
            },
            {
              xtype: 'gridcolumn',
              text: 'РП+КУ (002)',
              columns: [
                {
                  xtype: 'gridcolumn',
                  width: 49,
                  dataIndex: 'RPKU_regs',
                  text: 'Регис-<br>трация<br><br>[4]'
                },
                {
                  xtype: 'gridcolumn',
                  width: 49,
                  dataIndex: 'RPKU_MakeDocs',
                  text: 'Форм-е<br>док-ов<br><br>[5]',
                  tooltip: 'Формирование документов'
                }
              ]
            },
            {
              xtype: 'gridcolumn',
              text: 'РП (001)',
              columns: [
                {
                  xtype: 'gridcolumn',
                  width: 49,
                  dataIndex: 'RP_regs',
                  text: 'Регис-<br>трация<br><br>[6]'
                },
                {
                  xtype: 'gridcolumn',
                  width: 49,
                  dataIndex: 'RP_MakeDocs',
                  text: 'Форм-е<br>док-ов<br><br>[7]',
                  tooltip: 'Формирование документов'
                }
              ]
            },
            {
              xtype: 'gridcolumn',
              text: 'Электронная<br>регистрация',
              columns: [
                {
                  xtype: 'gridcolumn',
                  width: 49,
                  dataIndex: 'EP_regs',
                  text: 'Регис-<br>трация<br><br>[8]'
                },
                {
                  xtype: 'gridcolumn',
                  width: 49,
                  dataIndex: 'EP_MakeDocs',
                  text: 'Форм-е<br>док-ов<br><br>[9]',
                  tooltip: 'Формирование документов'
                }
              ]
            },
            {
              xtype: 'gridcolumn',
              width: 54,
              dataIndex: 'Arest',
              text: 'Аресты<br>(наложение,<br>снятие)<br><br>[10]',
              tooltip: 'Аресты<br>(наложение,<br>снятие)<br><br>[10]'
            }
          ]
        },
        {
          xtype: 'gridcolumn',
          width: 62,
          dataIndex: 'InsertOldObj',
          text: 'Внесение<br>раннее<br>учтенных<br>объектов<br>(присвоение<br>кад. номера)<br><br>[11]',
          tooltip: 'Внесение раннее учтенных<br>объектов<br>(присвоение<br>кад. номера)<br><br>[11]'
        },
        {
          xtype: 'gridcolumn',
          width: 66,
          dataIndex: 'IsprErrorGKN',
          text: 'Исправление<br>технической<br>ошибки<br>в ГКН<br><br>[12]',
          tooltip: 'Исправление<br>технической<br>ошибки<br>в ГКН<br><br>[12]'
        },
        {
          xtype: 'gridcolumn',
          width: 60,
          dataIndex: 'MVedGKN',
          text: 'Межвед.<br>ГКН<br><br>[13]'
        },
        {
          xtype: 'gridcolumn',
          text: 'Предоставление сведений из ЕГРП',
          columns: [
            {
              xtype: 'gridcolumn',
              width: 60,
              dataIndex: 'GRP_MakeCopyRightDocs',
              text: 'Подготовка<br>копий<br>договоров<br>и прав.уст.<br>док-ов<br><br>[14]',
              tooltip: 'Подготовка<br>копий<br>договоров<br>и прав.уст.<br>док-ов<br><br>[14]'
            },
            {
              xtype: 'gridcolumn',
              width: 50,
              dataIndex: 'GRP_StatKUVI',
              text: 'Статистика<br>по КУВИ<br><br>[15]',
              tooltip: 'Статистика<br>по КУВИ<br><br>[15]'
            },
            {
              xtype: 'gridcolumn',
              width: 57,
              dataIndex: 'GRP_StatPORTAL',
              text: 'Статистика<br>с портала<br>(portal.rosreestr.ru)<br><br>[16]',
              tooltip: 'Статистика<br>с портала<br>(portal.rosreestr.ru)<br><br>[16]'
            }
          ]
        },
        {
          xtype: 'gridcolumn',
          text: 'Госземнадзор',
          columns: [
            {
              xtype: 'gridcolumn',
              width: 76,
              dataIndex: 'GZN_Proverok',
              text: 'Кол-во<br>проведенных<br>проверок<br><br>[17]'
            }
          ]
        },
        {
          xtype: 'gridcolumn',
          text: 'Ведение ГФД',
          columns: [
            {
              xtype: 'gridcolumn',
              width: 80,
              dataIndex: 'GFD_Human',
              text: 'Кол-во<br>обработанных<br>обращений<br>граждан<br><br>[18]'
            }
          ]
        },
        {
          xtype: 'gridcolumn',
          text: 'Сканирова-<br>ние ГФД',
          columns: [
            {
              xtype: 'gridcolumn',
              width: 80,
              dataIndex: 'GFD_Lists',
              text: 'Кол-во<br>ДПД<br>в месяц<br><br>[19]'
            }
          ]
        },
        {
          xtype: 'gridcolumn',
          text: 'Перекомплек-<br>тование ДПД',
          columns: [
            {
              xtype: 'gridcolumn',
              defaultWidth: 80,
              dataIndex: 'DPD',
              text: 'Кол-во<br>ДПД<br>в месяц<br><br>[20]'
            }
          ]
        },
        {
          xtype: 'gridcolumn',
          text: 'Делопроизводство',
          columns: [
            {
              xtype: 'gridcolumn',
              dataIndex: 'DP_InputDocs',
              text: 'Кол-во<br>входящих<br>док-ов<br><br>[21]'
            },
            {
              xtype: 'gridcolumn',
              dataIndex: 'DP_OutHuman',
              text: 'Исходящих<br>по обращ.<br>гргаждан<br><br>[22]'
            },
            {
              xtype: 'gridcolumn',
              width: 84,
              dataIndex: 'DP_OutOther',
              text: 'Иные<br>исх.<br>док-ты<br><br>[23]'
            }
          ]
        },
        {
          xtype: 'numbercolumn',
          width: 80,
          dataIndex: 'WorksHourInDay',
          tdCls: 'f-bold-red',
          text: 'Отработано<br>часов<br>сотрудниками<br>отдела<br>в день<br><br>[24]'
        },
        {
          xtype: 'numbercolumn',
          width: 80,
          dataIndex: 'SotrudnikHour',
          tdCls: 'f-bold-red',
          text: 'ЧЕЛ/ЧАСЫ<p>[25]</p>'
        },
        {
          xtype: 'numbercolumn',
          width: 80,
          dataIndex: 'SotrudnikDay',
          tdCls: 'f-bold-red',
          text: 'Работало<br>человек<br>в день<br>в среднем<br><br>[26]'
        },
        {
          xtype: 'numbercolumn',
          width: 80,
          dataIndex: 'LoadSotrudnikDay',
          tdCls: 'f-bold-red',
          text: 'Нагрузка<br>на одного<br>сотрудника<br>в день,<br>часы<br><br>[27]'
        }
      ]
    }
  ],
  listeners: {
    afterrender: 'onAfterRender'
  }

});