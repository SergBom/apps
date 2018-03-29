/*
 * File: app/model/admin_group/lavels.js
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

Ext.define('Portal.model.admin_group.lavels', {
    extend: 'Ext.data.Model',

    requires: [
        'Ext.data.field.Field'
    ],

    fields: [
        {
            name: 'id_app'
        },
        {
            name: 'name'
        },
        {
            name: 'zapret'
        },
        {
            name: 'read'
        },
        {
            name: 'write'
        },
        {
            name: 'id_group'
        }
    ]
});