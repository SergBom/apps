/*
 * File: app/view/CadastErrors/mainViewModel.js
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

Ext.define('Portal.view.CadastErrors.mainViewModel', {
  extend: 'Ext.app.ViewModel',
  alias: 'viewmodel.cadasterrors.main',

  requires: [
    'Ext.data.Store',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json',
    'Ext.data.field.Field'
  ],

  stores: {
    FieldsVar: {
      proxy: {
        type: 'ajax',
        url: 'data/CadastErrors/Fields-var.php',
        reader: {
          type: 'json',
          rootProperty: 'data'
        }
      },
      fields: [
        {
          name: 'id'
        }
      ]
    }
  }

});