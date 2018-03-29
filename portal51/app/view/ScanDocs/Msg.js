/*
 * File: app/view/ScanDocs/Msg.js
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

Ext.define('Portal.view.ScanDocs.Msg', {
    extend: 'Ext.window.Window',
    alias: 'widget.scandocs.msg',

    requires: [
        'Portal.view.ScanDocs.MsgViewModel',
        'Ext.toolbar.Toolbar',
        'Ext.button.Button'
    ],

    viewModel: {
        type: 'scandocs.msg'
    },
    modal: true,
    height: 250,
    width: 503,
    layout: 'border',
    title: 'My Window',

    dockedItems: [
        {
            xtype: 'toolbar',
            dock: 'bottom',
            items: [
                {
                    xtype: 'container',
                    flex: 1,
                    padding: 10,
                    items: [
                        {
                            xtype: 'button',
                            text: 'Button 1'
                        },
                        {
                            xtype: 'button',
                            iconCls: 'help',
                            text: 'MyButton'
                        }
                    ]
                }
            ]
        }
    ],
    items: [
        {
            xtype: 'container',
            region: 'west',
            reference: 'cImg',
            width: 60
        },
        {
            xtype: 'container',
            region: 'center',
            reference: 'msg'
        }
    ]

});