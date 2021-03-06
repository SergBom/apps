/*
 * File: app/view/benzine/Type_Tour_EditViewController.js
 *
 * This file was generated by Sencha Architect version 3.2.0.
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

Ext.define('Portal.view.benzine.Type_Tour_EditViewController', {
    extend: 'Ext.app.ViewController',
    alias: 'controller.benzinetype_tour_edit',

    onBtnTypeTourSaveClick: function(button, e, eOpts) {
        var me = this,
            form = me.lookupReference('formTypeTour'),
            values = form.getValues(),
            store = Ext.getStore('benzine.type_tour');

        if (form.isValid()) {
            me.getView().mask('Подождите...');

            Ext.Ajax.request({
                url: 'data/benzine/type_tour-edit.php',
                params: values,
                success: function(r) {
                    store.load();
                }
            });

            me.getView().unmask();
            me.getView().close();
        }
    },

    onBtnTypeTourCancelClick: function(button, e, eOpts) {
        this.getView().close();
    }

});
