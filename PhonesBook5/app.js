/*
 * File: app.js
 *
 * This file was generated by Sencha Architect version 4.0.1.
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

// @require @packageOverrides
Ext.Loader.setConfig({
    paths: {
        Ext: '/ext5/build',
        'Ext.ux': '/ext5/build/ux'
    }
});


Ext.application({

    requires: [
        'Ext.Loader'
    ],
    id: 'appPhonesBook',
    controllers: [
        'PhonesBook.PhonesBook'
    ],
    name: 'Portal',

    requires: [
        'Ext.Loader'
    ],

    launch: function() {
        Ext.create('Portal.view.PhonesBook.PhonesBook', {renderTo: Ext.getBody()});
    }

});
