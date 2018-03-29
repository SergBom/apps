/*
 * File: app/view/LoadUpr/InputDataViewModel.js
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

Ext.define('Portal.view.LoadUpr.InputDataViewModel', {
  extend: 'Ext.app.ViewModel',
  alias: 'viewmodel.loadupr.inputdata',

  requires: [
    'Ext.data.Store',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json',
    'Ext.data.field.Field'
  ],

  stores: {
    data: {
      proxy: {
        type: 'ajax',
        url: 'data/LoadUpr/Data-read.php',
        reader: {
          type: 'json',
          rootProperty: 'data'
        }
      },
      fields: [
        {
          name: 'id'
        },
        {
          name: 'var_data'
        },
        {
          name: 'name_full'
        }
      ]
    },
    otdels: {
      proxy: {
        type: 'ajax',
        url: 'data/LoadUpr/Otdels-list.php',
        reader: {
          type: 'json',
          rootProperty: 'data'
        }
      },
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