/*
 * File: app/view/EGRP/fnsViewModel.js
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

Ext.define('Portal.view.EGRP.fnsViewModel', {
   extend: 'Ext.app.ViewModel',
   alias: 'viewmodel.egrp.fns',

   requires: [
      'Ext.data.Store',
      'Ext.data.reader.Json',
      'Ext.data.proxy.Rest',
      'Ext.data.writer.Json',
      'Ext.data.field.Integer'
   ],

   stores: {
      Protocol: {
         proxy: {
            type: 'ajax',
            url: 'data/EGRP/tir/tir-period.LIST-fns2.php',
            reader: {
               type: 'json',
               rootProperty: 'data'
            }
         },
         fields: [
            {
               name: 'session'
            },
            {
               name: 'period'
            }
         ]
      },
      Otdel: {
         proxy: {
            type: 'ajax',
            url: 'data/EGRP/tir/tir-otdel.LIST3.php',
            reader: {
               type: 'json',
               rootProperty: 'data'
            }
         },
         fields: [
            {
               name: 'ID'
            },
            {
               name: 'NAME'
            }
         ]
      },
      Main: {
         proxy: {
            type: 'rest',
            extraParams: {
               session: 0,
               otdel: 0,
               stype: '',
               desc: ''
            },
            url: 'data/EGRP/tir/tir-errors.REST-fns2.php',
            reader: {
               type: 'json',
               rootProperty: 'data'
            },
            writer: {
               type: 'json',
               rootProperty: 'data'
            }
         },
         fields: [
            {
               type: 'int',
               name: 'RW'
            },
            {
               type: 'int',
               name: 'RN'
            },
            {
               name: 'REC_GUID'
            },
            {
               type: 'int',
               name: 'OTD_ID'
            },
            {
               name: 'NAME'
            },
            {
               name: 'ERROR_PATH'
            },
            {
               name: 'ATTRIBUTE_VALUE'
            },
            {
               name: 'CONCADNUM'
            },
            {
               name: 'USER_NAME'
            },
            {
               name: 'USER_DATE'
            },
            {
               name: 'USER_COMMENT'
            },
            {
               name: 'STATUS_ERROR'
            },
            {
               name: 'DOC_GUID'
            },
            {
               name: 'DESCRIPTION'
            },
            {
               name: 'OTD_NAME'
            },
            {
               name: 'EXTERNAL_ID'
            }
         ]
      }
   }

});