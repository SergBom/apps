/*
 * File: app/view/DCV/ObjEditViewModel.js
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

Ext.define('Portal.view.DCV.ObjEditViewModel', {
    extend: 'Ext.app.ViewModel',
    alias: 'viewmodel.dcv.objedit',

    requires: [
        'Ext.data.Store',
        'Ext.data.proxy.Ajax',
        'Ext.data.reader.Json',
        'Ext.data.field.Field'
    ],

    stores: {
        DocsTypeApplicant: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/DocsTypeApplicant-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        ObjectNaznachenie: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/ObjectNaznachenie-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        ObjectVid: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/ObjectVid-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                },
                {
                    name: 'name_s'
                }
            ]
        },
        OKSVid: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/OKSVid-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        ZemlyaKategory: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/ZemlyaKategory-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        ApplicantType: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/ApplicantType-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        DocsUtvCadStoim: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/DocsUtvCadStoim-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        SposobOprCadStiom: {
            proxy: {
                type: 'ajax',
                url: 'data/DCV/SposobOprCadStiom-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        },
        OsnovaniaOpredKadStoim: {
            proxy: {
                type: 'ajax',
                url: 'OsnovaniaOpredKadSt-list.php',
                reader: {
                    type: 'json',
                    rootProperty: 'data'
                }
            },
            fields: [
                {
                    name: 'id'
                },
                {
                    name: 'name'
                }
            ]
        }
    }

});