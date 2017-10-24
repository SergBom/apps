/*
 * File: app/store/ScanDocs/MainData.js
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

Ext.define('Portal.store.ScanDocs.MainData', {
  extend: 'Ext.data.Store',
  alias: 'store.MainData',

  requires: [
    'Portal.model.ScanDocs.MainData',
    'Ext.data.proxy.Ajax',
    'Ext.data.reader.Json',
    'Ext.data.writer.Json'
  ],

  constructor: function(cfg) {
    var me = this;
    cfg = cfg || {};
    me.callParent([Ext.apply({
      storeId: 'ScanDocs.MainData',
      autoLoad: false,
      model: 'Portal.model.ScanDocs.MainData',
      proxy: {
        type: 'ajax',
        api: {
          create: 'data/ScanDocs/MainData-edit.php',
          read: 'data/ScanDocs/MainData.json.php',
          update: 'data/ScanDocs/MainData-edit.php',
          destroy: 'data/ScanDocs/MainData-delete.php'
        },
        extraParams: {
          dateBegin: '',
          dateEnd: '',
          Otdel: '',
          cyear: '',
          opis: '',
          retro: ''
        },
        url: 'data/ScanDocs/MainData.json.php',
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
    }, cfg)]);
  }
});