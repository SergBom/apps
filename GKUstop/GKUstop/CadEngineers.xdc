{
    "xdsVersion": "4.2.2",
    "frameworkVersion": "ext62",
    "internals": {
        "type": "Ext.window.Window",
        "reference": {
            "name": "items",
            "type": "array"
        },
        "codeClass": null,
        "userConfig": {
            "designer|userAlias": "cadasterrors.engineers",
            "designer|userClassName": "CadastErrors.Engineers",
            "height": 511,
            "layout": "fit",
            "modal": true,
            "title": "Список кадастровых инженеров",
            "width": 651
        },
        "configAlternates": {
            "designer|userAlias": "string",
            "designer|userClassName": "string",
            "height": "auto",
            "layout": "string",
            "modal": "boolean",
            "title": "string",
            "width": "auto"
        },
        "name": "MyWindow",
        "cn": [
            {
                "type": "Ext.grid.Panel",
                "reference": {
                    "name": "items",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "reference": "grid",
                    "store": [
                        "{engineers}"
                    ]
                },
                "configAlternates": {
                    "store": "binding",
                    "reference": "string"
                },
                "name": "MyGridPanel1",
                "cn": [
                    {
                        "type": "Ext.grid.column.RowNumberer",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "name": "MyRowNumberer1"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "fio",
                            "flex": 1,
                            "text": "Ф.И.О."
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "flex": "number",
                            "text": "string"
                        },
                        "name": "MyColumn2"
                    },
                    {
                        "type": "Ext.grid.column.Column",
                        "reference": {
                            "name": "columns",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dataIndex": "AttNumber",
                            "text": "№ аттестата",
                            "width": 131
                        },
                        "configAlternates": {
                            "dataIndex": "datafield",
                            "text": "string",
                            "width": "auto"
                        },
                        "name": "MyColumn3"
                    },
                    {
                        "type": "Ext.view.Table",
                        "reference": {
                            "name": "viewConfig",
                            "type": "object"
                        },
                        "codeClass": null,
                        "name": "MyTable1"
                    },
                    {
                        "type": "Ext.toolbar.Toolbar",
                        "reference": {
                            "name": "dockedItems",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dock": "top"
                        },
                        "configAlternates": {
                            "dock": "string"
                        },
                        "name": "MyToolbar1",
                        "cn": [
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "iconCls": "icon-add",
                                    "layout|flex": null,
                                    "text": "Добавить"
                                },
                                "configAlternates": {
                                    "iconCls": "string",
                                    "layout|flex": "number",
                                    "text": "string"
                                },
                                "name": "MyButton5",
                                "cn": [
                                    {
                                        "type": "viewcontrollereventbinding",
                                        "reference": {
                                            "name": "listeners",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "fn": "onAdd",
                                            "implHandler": [
                                                "var ed=Ext.create('Portal.view.CadastErrors.EngEdit',{scope:this}).show();",
                                                "ed.down('form').getForm().setValues({id:0});"
                                            ],
                                            "name": "click",
                                            "scope": "me"
                                        },
                                        "configAlternates": {
                                            "fn": "string",
                                            "implHandler": "code",
                                            "name": "string",
                                            "scope": "string"
                                        },
                                        "name": "onAdd"
                                    }
                                ]
                            },
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "disabled": true,
                                    "iconCls": "icon-delete",
                                    "layout|flex": null,
                                    "reference": "btnDel",
                                    "text": "Удалить"
                                },
                                "configAlternates": {
                                    "disabled": "boolean",
                                    "iconCls": "string",
                                    "layout|flex": "number",
                                    "reference": "string",
                                    "text": "string"
                                },
                                "name": "MyButton6",
                                "cn": [
                                    {
                                        "type": "viewcontrollereventbinding",
                                        "reference": {
                                            "name": "listeners",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "fn": "onDel",
                                            "implHandler": [
                                                "var id=this.getReferences().grid.getSelection()[0].id,",
                                                "s=this.getViewModel().getStore('engineers');",
                                                "console.log(id);",
                                                "Portal.util.Util.deleteRecord3(id,s,'data/CadastErrors/Engineer-del.php');"
                                            ],
                                            "name": "click",
                                            "scope": "me"
                                        },
                                        "configAlternates": {
                                            "fn": "string",
                                            "implHandler": "code",
                                            "name": "string",
                                            "scope": "string"
                                        },
                                        "name": "onDel"
                                    }
                                ]
                            },
                            {
                                "type": "Ext.button.Button",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "iconCls": "icon-refresh",
                                    "layout|flex": null,
                                    "text": "Обновить"
                                },
                                "configAlternates": {
                                    "iconCls": "string",
                                    "layout|flex": "number",
                                    "text": "string"
                                },
                                "name": "MyButton10",
                                "cn": [
                                    {
                                        "type": "viewcontrollereventbinding",
                                        "reference": {
                                            "name": "listeners",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "fn": "onRefresh",
                                            "implHandler": [
                                                "this.getViewModel().getStore(\"engineers\").reload();"
                                            ],
                                            "name": "click",
                                            "scope": "me"
                                        },
                                        "configAlternates": {
                                            "fn": "string",
                                            "implHandler": "code",
                                            "name": "string",
                                            "scope": "string"
                                        },
                                        "name": "onRefresh"
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "type": "Ext.toolbar.Toolbar",
                        "reference": {
                            "name": "dockedItems",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "dock": "top"
                        },
                        "configAlternates": {
                            "dock": "string"
                        },
                        "name": "MyToolbar3",
                        "cn": [
                            {
                                "type": "Ext.form.field.Text",
                                "reference": {
                                    "name": "items",
                                    "type": "array"
                                },
                                "codeClass": null,
                                "userConfig": {
                                    "emptyText": "Поиск по ФИО",
                                    "enableKeyEvents": true,
                                    "flex": 1,
                                    "layout|flex": null,
                                    "reference": "seek"
                                },
                                "configAlternates": {
                                    "emptyText": "string",
                                    "enableKeyEvents": "boolean",
                                    "flex": "number",
                                    "layout|flex": "number",
                                    "reference": "string"
                                },
                                "name": "MyTextField4",
                                "cn": [
                                    {
                                        "type": "viewcontrollereventbinding",
                                        "reference": {
                                            "name": "listeners",
                                            "type": "array"
                                        },
                                        "codeClass": null,
                                        "userConfig": {
                                            "fn": "onSeek",
                                            "implHandler": [
                                                "console.log(newValue);",
                                                "this.getViewModel().getStore(\"engineers\").load({params:{fio:newValue}});"
                                            ],
                                            "name": "change",
                                            "scope": "me"
                                        },
                                        "configAlternates": {
                                            "fn": "string",
                                            "implHandler": "code",
                                            "name": "string",
                                            "scope": "string"
                                        },
                                        "name": "onSeek"
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        "type": "viewcontrollereventbinding",
                        "reference": {
                            "name": "listeners",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fn": "onEdit",
                            "implHandler": [
                                "var ed=Ext.create('Portal.view.CadastErrors.EngEdit',{scope:this}).show();",
                                "ed.down('form').getForm().setValues(record.data);"
                            ],
                            "name": "itemdblclick",
                            "scope": "me"
                        },
                        "configAlternates": {
                            "fn": "string",
                            "implHandler": "code",
                            "name": "string",
                            "scope": "string"
                        },
                        "name": "onEdit"
                    },
                    {
                        "type": "viewcontrollereventbinding",
                        "reference": {
                            "name": "listeners",
                            "type": "array"
                        },
                        "codeClass": null,
                        "userConfig": {
                            "fn": "onSelectionChange",
                            "implHandler": [
                                "this.getReferences().btnDel.setDisabled(selected.length===0);"
                            ],
                            "name": "selectionchange",
                            "scope": "me"
                        },
                        "configAlternates": {
                            "fn": "string",
                            "implHandler": "code",
                            "name": "string",
                            "scope": "string"
                        },
                        "name": "onSelectionChange"
                    }
                ]
            },
            {
                "type": "viewcontrollereventbinding",
                "reference": {
                    "name": "listeners",
                    "type": "array"
                },
                "codeClass": null,
                "userConfig": {
                    "fn": "onAfterRender",
                    "implHandler": [
                        "this.getViewModel().getStore(\"engineers\").load();"
                    ],
                    "name": "afterrender",
                    "scope": "me"
                },
                "configAlternates": {
                    "fn": "string",
                    "implHandler": "code",
                    "name": "string",
                    "scope": "string"
                },
                "name": "onAfterRender"
            }
        ]
    },
    "linkedNodes": {},
    "boundStores": {},
    "boundModels": {},
    "viewController": {
        "xdsVersion": "4.2.2",
        "frameworkVersion": "ext62",
        "internals": {
            "type": "Ext.app.ViewController",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "cadasterrors.engineers",
                "designer|userClassName": "CadastErrors.EngineersViewController"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            }
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    },
    "viewModel": {
        "xdsVersion": "4.2.2",
        "frameworkVersion": "ext62",
        "internals": {
            "type": "Ext.app.ViewModel",
            "reference": {
                "name": "items",
                "type": "array"
            },
            "codeClass": null,
            "userConfig": {
                "designer|userAlias": "cadasterrors.engineers",
                "designer|userClassName": "CadastErrors.EngineersViewModel"
            },
            "configAlternates": {
                "designer|userAlias": "string",
                "designer|userClassName": "string"
            },
            "cn": [
                {
                    "type": "jsonstore",
                    "reference": {
                        "name": "stores",
                        "type": "array"
                    },
                    "codeClass": null,
                    "userConfig": {
                        "name": "engineers"
                    },
                    "configAlternates": {
                        "name": "string"
                    },
                    "name": "MyJsonStore",
                    "cn": [
                        {
                            "type": "Ext.data.proxy.Ajax",
                            "reference": {
                                "name": "proxy",
                                "type": "object"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "url": "data/CadastErrors/Engineer-read.php"
                            },
                            "configAlternates": {
                                "url": "string"
                            },
                            "name": "MyAjaxProxy",
                            "cn": [
                                {
                                    "type": "Ext.data.reader.Json",
                                    "reference": {
                                        "name": "reader",
                                        "type": "object"
                                    },
                                    "codeClass": null,
                                    "userConfig": {
                                        "rootProperty": "data"
                                    },
                                    "configAlternates": {
                                        "rootProperty": "string"
                                    },
                                    "name": "MyJsonReader"
                                }
                            ]
                        },
                        {
                            "type": "Ext.data.field.Field",
                            "reference": {
                                "name": "fields",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "name": "id"
                            },
                            "configAlternates": {
                                "name": "string"
                            },
                            "name": "MyField"
                        },
                        {
                            "type": "Ext.data.field.Field",
                            "reference": {
                                "name": "fields",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "name": "fio"
                            },
                            "configAlternates": {
                                "name": "string"
                            },
                            "name": "MyField1"
                        },
                        {
                            "type": "Ext.data.field.Field",
                            "reference": {
                                "name": "fields",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "name": "Fm"
                            },
                            "configAlternates": {
                                "name": "string"
                            },
                            "name": "MyField2"
                        },
                        {
                            "type": "Ext.data.field.Field",
                            "reference": {
                                "name": "fields",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "name": "Im"
                            },
                            "configAlternates": {
                                "name": "string"
                            },
                            "name": "MyField3"
                        },
                        {
                            "type": "Ext.data.field.Field",
                            "reference": {
                                "name": "fields",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "name": "Ot"
                            },
                            "configAlternates": {
                                "name": "string"
                            },
                            "name": "MyField4"
                        },
                        {
                            "type": "Ext.data.field.Field",
                            "reference": {
                                "name": "fields",
                                "type": "array"
                            },
                            "codeClass": null,
                            "userConfig": {
                                "name": "AttNumber"
                            },
                            "configAlternates": {
                                "name": "string"
                            },
                            "name": "MyField5"
                        }
                    ]
                }
            ]
        },
        "linkedNodes": {},
        "boundStores": {},
        "boundModels": {}
    }
}