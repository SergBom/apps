/*
 * File: app/store/admin_group/clearstore.js
 *
 * This file was generated by Sencha Architect version 3.5.1.
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 5.1.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 5.1.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('Portal.store.admin_group.clearstore', {
    extend: 'Ext.data.Store',

    requires: [
        'Portal.model.admin_group.clearmodel',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.data.writer.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'admin_group.clearstore',
            autoSync: true,
            model: 'Portal.model.admin_group.clearmodel',
            proxy: {
                type: 'ajax',
                url: 'data/admin_group/read.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                },
                writer: {
                    type: 'json',
                    encode: true,
                    rootProperty: 'data'
                }
            }
        }, cfg)]);
    }
});