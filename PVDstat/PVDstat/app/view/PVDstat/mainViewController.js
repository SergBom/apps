/*
 * File: app/view/PVDstat/mainViewController.js
 *
 * This file was generated by Sencha Architect version 4.2.2.
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

Ext.define('Portal.view.PVDstat.mainViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.pvdstatmain',

    onDateBeginChange: function(field, newValue, oldValue, eOpts) {
        /* //console.log(field.getItemId());
        var btnPrint = Ext.getCmp('print');
        var btnReport = Ext.getCmp('doReport');

        //if(field.getItemId()=='TypeReport'){
        var db = newValue;
        var de = Ext.getCmp( 'DateEnd' ).getValue();
        var tr = Ext.getCmp( 'TypeReport' ).getValue();
        if(db===null||de===null||tr===null){
            btnPrint.disable();
            btnReport.disable();
        } else {
            btnPrint.enable();
            btnReport.enable();
        }
        //} */
    },

    onBtnPVDstatPrintClick: function(button, e, eOpts) {
        var refs=this.getReferences(),
            db = Ext.Date.format(refs.DateBegin.getValue(),'d.m.Y');
        de = Ext.Date.format(refs.DateEnd.getValue(),'d.m.Y');

        Ext.ux.grid.Printer.printAutomatically = false;
        Ext.ux.grid.Printer.mainTitle = 'Статистика ПК ПВД за период\nс '+db+' по '+de;
        Ext.ux.grid.Printer.print(refs.mGrid);
    }

});
