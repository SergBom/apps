/*
 * File: app/store/CadastErrors/main.js
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

Ext.define('Portal.store.CadastErrors.main', {
  extend: 'Ext.data.Store',

  requires: [
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json',
    'Ext.data.field.Field'
  ],

  constructor: function(cfg) {
    var me = this;
    cfg = cfg || {};
    me.callParent([Ext.apply({
      storeId: 'CadastErrors.main',
      proxy: {
        type: 'ajax',
        url: 'data/CadastErrors/main-list.php',
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
    }, cfg)]);
  }
});