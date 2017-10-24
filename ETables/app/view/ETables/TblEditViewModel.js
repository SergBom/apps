/*
 * File: app/view/ETables/TblEditViewModel.js
 *
 * This file was generated by Sencha Architect
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

Ext.define('Portal.view.ETables.TblEditViewModel', {
  extend: 'Ext.app.ViewModel',
  alias: 'viewmodel.etables.tbledit',

  requires: [
    'Ext.data.Store',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json',
    'Ext.data.writer.Json',
    'Ext.data.field.Field'
  ],

  stores: {
    fields: {
      autoSync: true,
      model: 'Portal.model.ETables.TblFileds',
      proxy: {
        type: 'ajax',
        api: {
          create: 'data/ETables/structFieldNew.php',
          read: 'data/ETables/structFieldLoad.php',
          update: 'data/ETables/structFieldUpdate.php',
          destroy: 'data/ETables/structFieldDestroy.php'
        },
        url: 'data/ETables/structField.php',
        reader: {
          type: 'json',
          rootProperty: 'data'
        },
        writer: {
          type: 'json',
          writeAllFields: true,
          encode: true,
          rootProperty: 'data'
        }
      }
    },
    f_type: {
      data: [
        {
          id: 'VARCHAR',
          name: 'Текст'
        },
        {
          id: 'INT',
          name: 'Целое число'
        },
        {
          id: 'FLOAT',
          name: 'Дробное число'
        },
        {
          id: 'DATE',
          name: 'Дата'
        },
        {
          id: 'TIME',
          name: 'Время'
        }
      ],
      fields: [
        {
          name: 'id'
        },
        {
          name: 'name'
        }
      ]
    }
  }

});