/*
 * File: app/view/CadastErrors/EngineersViewModel.js
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

Ext.define('Portal.view.CadastErrors.EngineersViewModel', {
  extend: 'Ext.app.ViewModel',
  alias: 'viewmodel.cadasterrors.engineers',

  requires: [
    'Ext.data.Store',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json',
    'Ext.data.field.Field'
  ],

  stores: {
    engineers: {
      proxy: {
        type: 'ajax',
        url: 'data/CadastErrors/Engineer-read.php',
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
          name: 'fio'
        },
        {
          name: 'Fm'
        },
        {
          name: 'Im'
        },
        {
          name: 'Ot'
        },
        {
          name: 'AttNumber'
        }
      ]
    }
  }

});