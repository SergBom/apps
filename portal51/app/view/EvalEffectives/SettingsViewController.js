/*
 * File: app/view/EvalEffectives/SettingsViewController.js
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

Ext.define('Portal.view.EvalEffectives.SettingsViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.evaleffectivessettings',

    onPanelActivate: function(component, eOpts) {
        Ext.getStore('EvalEffectives.ParamsIn').load();
        Ext.getStore('EvalEffectives.ParamsOut').load();
        Ext.getStore('EvalEffectives.ParamsEffectives').load();
    },

    onEvalEffectivesWinSettingsAfterRender: function(component, eOpts) {
        Ext.getStore('EvalEffectives.JobersGroup').load();
        Ext.getStore('EvalEffectives.Jobers').load();
        Ext.getStore('EvalEffectives.GZIcount').load();
    }

});
