/*
 * File: app/controller/PVDstat.js
 *
 * This file was generated by Sencha Architect
 * http://www.sencha.com/products/architect/
 *
 * This file requires use of the Ext JS 5.0.x library, under independent license.
 * License of Sencha Architect does not include license for Ext JS 5.0.x. For more
 * details see http://www.sencha.com/license or contact license@sencha.com.
 *
 * This file will be auto-generated each and everytime you save your project.
 *
 * Do NOT hand edit this file.
 */

Ext.define('ExtDesktop.controller.PVDstat', {
    extend: 'Ext.app.Controller',

    requires: [
        'Ext.ux.grid.Printer'
    ],

    models: [
        'portal.PVDstat_TypeReport',
        'portal.PVDstat_report01'
    ],
    stores: [
        'portal.PVDstat_TypeReport',
        'module.PVDstat_report01'
    ],
    views: [
        'portal.PVDstat'
    ],

    control: {
        "button": {
            click: 'onButtonClick'
        },
        "combobox": {
            change: 'onComboboxChange'
        },
        "datefield": {
            change: 'onDatefieldChange'
        },
        "tabpanel": {
            tabchange: 'onTabpanelTabChange'
        }
    },

    onButtonClick: function(button, e, eOpts) {
        //console.log( 'Button ID:' + button.getItemId() );
        if(button.getItemId()=='print'){
            var grid = Ext.getCmp( 'mGrid' );
            var db = Ext.Date.format(Ext.getCmp( 'DateBegin' ).getValue(),'d.m.Y');
            var de = Ext.Date.format(Ext.getCmp( 'DateEnd' ).getValue(),'d.m.Y');

            Ext.ux.grid.Printer.printAutomatically = false;
            Ext.ux.grid.Printer.mainTitle = 'Статистика ПК ПВД за период\nс '+db+' по '+de;
            Ext.ux.grid.Printer.print(grid);
        }
    },

    onComboboxChange: function(field, newValue, oldValue, eOpts) {
        //console.log(field.getItemId());
        var btnPrint = Ext.getCmp('print');
        var btnReport = Ext.getCmp('doReport');

        if(field.getItemId()=='TypeReport'){
            var db = Ext.getCmp( 'DateBegin' ).getValue();
            var de = Ext.getCmp( 'DateEnd' ).getValue();
            var tr = Ext.getCmp( 'TypeReport' ).getValue();
            if(db===null||de===null||tr===null){
                btnPrint.disable();
                btnReport.disable();
            } else {
                btnPrint.enable();
                btnReport.enable();
            }
        }
    },

    onDatefieldChange: function(field, newValue, oldValue, eOpts) {
        //console.log(field.getItemId());
        var btnPrint = Ext.getCmp('print');
        var btnReport = Ext.getCmp('doReport');

        //if(field.getItemId()=='TypeReport'){
            var db = Ext.getCmp( 'DateBegin' ).getValue();
            var de = Ext.getCmp( 'DateEnd' ).getValue();
            var tr = Ext.getCmp( 'TypeReport' ).getValue();
            if(db===null||de===null||tr===null){
                btnPrint.disable();
                btnReport.disable();
            } else {
                btnPrint.enable();
                btnReport.enable();
            }
        //}
    },

    onTabpanelTabChange: function(tabPanel, newCard, oldCard, eOpts) {
        //console.log(tabPanel);
        //console.log(newCard);
        if(tabPanel.id=='mainTab'){
            if(newCard.id=='tabServers'){
                var tab = Ext.getCmp('tabServers').loader.load();
                //tab.loader.load();
            }
        }
    }

});
