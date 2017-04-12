/*
 * File: app/store/ScanDocs/District.js
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

Ext.define('Portal.store.ScanDocs.District', {
  extend: 'Ext.data.Store',
  alias: 'store.District',

  requires: [
    'Portal.model.ScanDocs.District',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json'
  ],

  constructor: function(cfg) {
    var me = this;
    cfg = cfg || {};
    me.callParent([Ext.apply({
      storeId: 'ScanDocs.District',
      autoLoad: false,
      model: 'Portal.model.ScanDocs.District',
      proxy: {
        type: 'ajax',
        url: 'data/ScanDocs/district.json.php',
        reader: {
          type: 'json',
          rootProperty: 'data'
        }
      }
    }, cfg)]);
  }
});