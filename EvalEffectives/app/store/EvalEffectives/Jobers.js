/*
 * File: app/store/EvalEffectives/Jobers.js
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

Ext.define('Portal.store.EvalEffectives.Jobers', {
    extend: 'Ext.data.Store',

    requires: [
        'Portal.model.EvalEffectives.Jobers',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json'
    ],

    constructor: function(cfg) {
        var me = this;
        cfg = cfg || {};
        me.callParent([Ext.apply({
            storeId: 'EvalEffectives.Jobers',
            model: 'Portal.model.EvalEffectives.Jobers',
            proxy: {
                type: 'ajax',
                url: 'data/EvalEffectives/Jobers-read.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            }
        }, cfg)]);
    }
});