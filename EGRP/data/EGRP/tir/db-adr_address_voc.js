Ext.Loader.setConfig({
    enabled: true
});
Ext.Loader.setPath('Ext.ux', '/extjs/ux');
Ext.require(['Ext.grid.*', 'Ext.data.*', 'Ext.ux.grid.FiltersFeature', 'Ext.toolbar.Paging', 'Ext.ux.ajax.JsonSimlet', 'Ext.ux.ajax.SimManager']);

/*Ext.require([
    'Ext.tab.*',
    'Ext.window.*',
    'Ext.tip.*',
    'Ext.layout.container.Border'
]);*/
Ext.onReady(function(){
    var win;
    /************************************/
    /************************************/  
    Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

    /************************************/  

    /************************************/  
    Ext.define('Errors',{
       extend: 'Ext.data.Model',
       fields: [
                     {name: 'id', type: 'int'},
                     {name: 'text', type: 'string'},
                     {name: 'picture', type: 'string'}
       ]
    });
    var storeErrors = Ext.create('Ext.data.Store', {
        autoLoad: true,
        model: 'Errors',
        proxy: {
            type: 'rest',
            url: '/php/tir2/tir-errors.LIST.php',
            reader: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            }
        }
    });
    /************************************/  
    Ext.define('RF',{
       extend: 'Ext.data.Model',
       fields: [
                     {name: 'id', type: 'int'},
                     {name: 'text', type: 'string'},
                     {name: 'picture', type: 'string'}
       ]
    });
    var storeRF = Ext.create('Ext.data.Store', {
        autoLoad: true,
        model: 'RF',
        proxy: {
            type: 'rest',
            url: '/php/tir2/tir-RF.LIST.php',
            reader: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            }
        }
    });
    /************************************/  
    Ext.define('Period',{
       extend: 'Ext.data.Model',
       fields: [
                     {name: 'session', type: 'string'},
                     {name: 'period', type: 'string'},
                     {name: 'periodV', type: 'string'},
                     {name: 'periodS', type: 'string'}
       ]
    });
    var storePeriod = Ext.create('Ext.data.Store', {
        autoLoad: true,
        model: 'Period',
        proxy: {
            type: 'rest',
            url: '/php/tir2/tir-period.LIST-flk.php',
            reader: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            }
        }
    });
    /************************************/  
    Ext.define('Otdel',{
       extend: 'Ext.data.Model',
       fields: [
                     {name: 'id', type: 'int'},
                     {name: 'name', type: 'string'}
       ]
    });
    var storeOtdel = Ext.create('Ext.data.Store', {
        autoLoad: true,
        model: 'Otdel',
        proxy: {
            type: 'rest',
            url: '/php/tir2/tir-otdel.LIST.php',
            reader: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            }
        }
    });
    /************************************/  
        var storeSysType = Ext.create('Ext.data.Store', {
            storeId: 'storeSysType',
            fields:['stype', 'name'],
            data:{'items':[
                { 'stype': 'R', 'name': 'ЕГРП'},
                { 'stype': 'C', 'name': 'ГКН' }
            ]},
            proxy: {
                type: 'memory',
                reader: {
                    type: 'json',
                    root: 'items'
                }
            }
        });
    /************************************/  
        var storeErrType = Ext.create('Ext.data.Store', {
            storeId: 'storeErrType',
            fields:['errtype', 'name'],
            data:{'items':[
                { 'errtype': 'FLK', 'name': 'ФЛК'},
                { 'errtype': 'FNS', 'name': 'ФНС' }
            ]},
            proxy: {
                type: 'memory',
                reader: {
                    type: 'json',
                    root: 'items'
                }
            }
        });
    /************************************/  
    var comboErrors = Ext.create('Ext.form.ComboBox', {       
//                   name: 'status',
//                   id: 'status',
                   width: 250,
                   store: storeErrors,
                   xtype: 'combobox',
//                   queryMode: 'local',
                   valueField: 'id',
                   displayField: 'name',
                   editable: false,
                   selectOnFocus: true,
                   autoSelect: 'true',
                   listConfig: {
                        getInnerTpl: function() {
                             var tpl = '<div class="x-combo-list-item">{picture}&nbsp;&nbsp;{name}</div>';
                             return tpl;
                        }
                   }
//            Ext.form.ComboBox.superclass.setValue.call(this, text);
     });

    /************************************/  


    var comboPeriod = Ext.create('Ext.form.ComboBox', {       
				name: 'Period',
                id: 'selPeriod',
                width: 300,
                store: storePeriod,
                xtype: 'combobox',
                valueField: 'session',
                displayField: 'period',
                emptyText: 'Период...',
                allowBlank: false,
                editable: false,
                selectOnFocus: true,
                autoSelect: 'true',
/*				tpl: new Ext.XTemplate(
						'<tpl for=".">',
							'<tpl if="periodS == \'_\'">',
								'<div class="x-combo-list-item"><font color="red">{period}</font></div>',
							'<tpl else>',
								'<div class="x-combo-list-item">{period}</div>',
							'</tpl>',
						'</tpl>',
						{
							compiled: true,
							disableFormats: true
						}
				),
  */              listeners: {
                    change: function(t,newVal){
                        LoadData(comboPeriod.getValue(), comboOtdel.getValue(),
                                        comboStype.getValue(), ''
                        );
                    }
                }
     });

    var comboOtdel = Ext.create('Ext.form.ComboBox', {       
                   name: 'Otdel',
                   id: 'selOtdel',
                   width: 250,
                   store: storeOtdel,
                   xtype: 'combobox',
                   valueField: 'id',
                   displayField: 'name',
                   emptyText: 'Отдел...',
                   allowBlank: false,
                   editable: false,
                   selectOnFocus: true,
                   autoSelect: 'true',
                   listeners: {
                       change: function(t,newVal){
                          LoadData(comboPeriod.getValue(), comboOtdel.getValue(),
                                        comboStype.getValue(), ''
                          );
                       }
                  }
     });
    var comboStype = Ext.create('Ext.form.ComboBox', {
                   name: 'SType',
                   id: 'selSType',
                   width: 100,
                   store: storeSysType,
                   xtype: 'combobox',
                   valueField: 'stype',
                   displayField: 'name',
                   editable: false,
                   allowBlank: false,
                   emptyText: 'Тип системы...',
                   selectOnFocus: true,
                   autoSelect: 'true',
                   listeners: {
                       change: function(t,newVal){
                          LoadData(comboPeriod.getValue(), comboOtdel.getValue(),
                                        comboStype.getValue(), ''
                          );
                       }
                  }
     });
    var comboErrType = Ext.create('Ext.form.ComboBox', {
                   name: 'ErrType',
                   id: 'selErrType',
                   store: storeErrType,
                   xtype: 'combobox',
                   valueField: 'errtype',
                   displayField: 'name',
                   editable: false,
                   allowBlank: false,
                   emptyText: 'Тип ошибок...',
                   selectOnFocus: true,
                   autoSelect: 'true',
                   listeners: {
                       change: function(t,newVal){
                       }
                  }
     });

    /************************************/  

    Ext.define('ErrorDescription',{
       extend: 'Ext.data.Model',
       fields: [
                     {name: 'id', type: 'int'},
                     {name: 'text', type: 'string'}
       ]
    });
    var storeErrorDescription = Ext.create('Ext.data.Store', {
//        autoLoad: true,
        model: 'Errors',
        proxy: {
            type: 'rest',
            url: '/php/tir2/tir-errorDescription.LIST.php',
            extraParams:{
                               session: comboPeriod.getValue(),
                               otdel: comboOtdel.getValue(),
                               stype: comboStype.getValue()
            },
            reader: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            }
        }
    });
    var comboErrDesc = Ext.create('Ext.form.ComboBox', {
                   name: 'ErrDesc',
                   id: 'selErrDesc',
                   width: 200,
                   store: storeErrorDescription,
                   xtype: 'combobox',
                   valueField: 'id',
                   displayField: 'text',
                   editable: false,
                   emptyText: 'Фильтр по ошибкам...',
                   selectOnFocus: true,
                   autoSelect: 'true',
                   listeners: {
                       change: function(t,newVal){
                          LoadData(comboPeriod.getValue(), comboOtdel.getValue(),
                                        comboStype.getValue(), newVal
                          );
                       }
                  }
     });

    /************************************/  

    Ext.define('listError',{
       extend: 'Ext.data.Model',
       fields: [
        {name: 'rn',type: 'int'},
        {name: 'rw',type: 'int'},
        {name: 'rec_guid',type: 'string'},
        {name: 'dept_id',type: 'int'},
        {name: 'name',type: 'string'},
        {name: 'status_error',type: 'string'},
        {name: 'doc_guid',type: 'string'},
        {name: 'description',type: 'string'},
        {name: 'attribute_name',type: 'string'},
        {name: 'attribute_value',type: 'string'},
        {name: 'tag_name',type: 'string'},
        {name: 'path',type: 'string'},
        {name: 'system_type',type: 'string'},
        {name: 'essence_type',type: 'string'},
        {name: 'external_id',type: 'string'},
        {name: 'user_id',type: 'string'},
        {name: 'user_name',type: 'string'},
        {name: 'user_date',type: 'string'},
        {name: 'user_comment',type: 'string'},
        {name: 'concadnum',type: 'string'},
        {name: 'kladr_n-y',type: 'string'},
        {name: 'kladr_need',type: 'int'},
        {name: 'kladr_yes',type: 'int'},
		{name: 'rf',type: 'int'}
       ]
    });

    var storeListError = Ext.create('Ext.data.Store', {
        autoSync: true,
        model: 'listError',
        proxy: {
            type: 'rest',
            url: '/php/tir2/tir-errors.REST-flk.php',
            extraParams:{
                               session: comboPeriod.getValue(),
                               otdel: comboOtdel.getValue(),
                               stype: comboStype.getValue(),
                               desc: comboErrDesc.getValue()
            },
            reader: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            },
            writer: {
                type: 'json',
                root: 'data',
                successProperty: 'success'
            }
        },
        listeners: {
             beforeload: function(store){
                              var cmpForm = Ext.getCmp('ErrorForm');
                              var form = cmpForm.getForm();
                              form.reset();
                              store.baseParams = {filter: filters}
             }
        }
    });
/***********************************************************************/
    /**
     * Custom function used for column renderer
     * @param {Object} val
     */
    function cError(val) {
        if (val == 0 || val == null) {
            return '<img src="/images/s/10/21.png" alt="Bad">';
        } else if (val ==1) {
            return '<img src="/images/s/10/22.png" alt="Ok">';
        } else if (val ==2) {
            return '<img src="/images/s/10/24.png" alt="Not">';
        } else if (val ==3) {
            return '<img src="/images/s/10/23.png" alt="No Err">';
        } else if (val ==4) {
            return '<img src="/images/flag/png/ru.png">';
        }
        return val;
    }
/***********/
    function b2v(val) {
        if (val ) { return '1';}
        else  { return '0';}
    }
/***********/
    function cRF(val) {
	//> prop_type=1     -------------Субъект РФ
	//> prop_type=2     -------------Субъект РФ (Мурманская область)
	//> prop_type=3     -------------Муниципальные образования (40 штук)
			   if (val == 0) {
			return '';
		} else if (val == 1) {
			return '<img src="/images/flag/png/ru.png">';
        } else if (val == 2) {
			return '<img src="/images/flag/png/ru_2.png">Мур';
		} else if (val == 3) {
			return '<img src="/images/flag/png/ru_2.png">МО';
		} else {
			return '<img src="/images/flag/png/ru_2.png">'+val;
		}
	}
/************ FILTERS *********/
    var essenceStore = Ext.create('Ext.data.Store',{
        fields: ['id','text'],
        data:{'items':[
                    { 'id': "'Объект'", 'text': 'Объект'}, //O
                    { 'id': "'Субъект'", 'text': 'Субъект' }, //S
                    { 'id': "'Право'", 'text': 'Право' }, //R
                    { 'id': "'Обременение'", 'text': 'Обременение' }, //E
                    { 'id': "'Неопределено'", 'text': 'Неопределено' } //U
        ]},
        proxy: {
             type: 'memory',
             reader: {
                 type: 'json',
                 root: 'items'
             }
        }
    });

    var kladrStore = Ext.create('Ext.data.Store',{
        fields: ['id','text'],
        data:{'items':[
                    { 'id': "1", 'text': 'Нужно исправить'}, //O
                    { 'id': "2", 'text': 'Исправлен' }, //S
        ]},
        proxy: {
             type: 'memory',
             reader: {
                 type: 'json',
                 root: 'items'
             }
        }
    });

    var filters = {
        ftype: 'filters',
        encode: true,
        local: false,
        filters: [{
            type: 'numeric',
            dataIndex: 'rw'
        },{
            type: 'string',
            dataIndex: 'concadnum',
        },{
            type: 'list',
//            type: 'boolean',
            dataIndex: 'rf',
//			defaultValue: null,
//			yesText: 'Yes',
//			noText: 'No'
			store: storeRF
        },{
            type: 'list',
            dataIndex: 'essence_type',
            store: essenceStore
        },{
            type: 'list',
            dataIndex: 'status_error',
            store: storeErrors
        },{
            type: 'list',
            dataIndex: 'kladr_n-y',
            store: kladrStore
        }]
    };



/***********************************************************************/
    var gridListErrors = Ext.create('Ext.grid.Panel',{
       id: 'gridListErrors',
       store: storeListError,
       stateful: true,
       stateId: 'gridListErrorsFLK',
       width: '100%',
       layout: 'fit',
       border: 0,
       columns: [
                     {text: '№ записи', dataIndex: 'rw', width: 40, filtrable: true},
                     {text: 'Статус', dataIndex: 'status_error', renderer: cError, width: 20 },
                     {text: 'КЛАДР', dataIndex: 'kladr_n-y', width: 20 },
                     {text: 'РФ', dataIndex: 'rf', renderer: cRF, width: 20 },
                     {text: 'Сущность', dataIndex: 'essence_type', width: 50 },
                     {text: 'Кад/Усл номер', dataIndex: 'concadnum', flex: 1 },
                     {text: 'Описание', dataIndex: 'description', width: 300, filter: 'string'}
       ],
       features: [filters],
       tbar: [{
                text: 'Сбросить фильтр',
                icon: '/images/fugue/funnel_minus.png',
                handler: function(){
                      gridListErrors.filters.clearFilters();
                }
       } //, comboErrDesc
       ],
       dockedItems:[
                 Ext.create('Ext.toolbar.Paging',{
                    dock: 'bottom',
                    store: storeListError,
                    displayInfo: true,
                    displayMsg: 'Показано  {0} - {1} из {2}',
                    listeners:{
                        change: function(){
                              storeListError.proxy.setExtraParam('session',comboPeriod.getValue());
                              storeListError.proxy.setExtraParam('otdel',comboOtdel.getValue());
                              storeListError.proxy.setExtraParam('stype',comboStype.getValue());
//                              storeListError.proxy.setExtraParam('desc',comboErrDesc.getValue());
//                              storeListError.proxy.setExtraParam('errtype',comboErrType.getValue());
                        }
                    }
                })
        ],
        listeners:{
            selectionchange: function(model, records) {
                    if (records[0]) {
                        ErrorForm.getForm().loadRecord(records[0]);
                        var temp = Ext.getCmp('userGID'); temp.setValue(UserGID);
                        var temp = Ext.getCmp('userName'); temp.setValue(UserName);
//                        comboErrors.select( records[0].get('status') );
                    }
                }
        }
         
    });

    /************************************/  

    var ErrorForm = Ext.create('Ext.form.Panel', { //Ext.widget('form',{ 
        bodyPadding: 5,
        id: 'ErrorForm',
        border: 0,
        url: '/php/tir2/tir-error.SAVE-flk.php',
        defaults: {
            anchor: '100%',
            labelAlign: 'right',
            readOnly: true
        },
        defaultType: 'textfield',
        items: [{
             fieldLabel: 'Ищем внутри ID',
             name: 'aav_id',
             id: 'aav_id'
        },{
             fieldLabel: 'Нужный DEPT_ID',
             name: 'dept_id',
             id: 'dept_id'
        },{
             fieldLabel: 'Описание',
             name: 'description',
             id: 'description',
             xtype: 'textareafield',
             rows: 2
        },{
             fieldLabel: 'REC_GUID',
             name: 'rec_guid',
             allowBlank: false,
             xtype: 'hiddenfield',
             id: 'rec_guid'
        },{
             fieldLabel: 'Путь',
             name: 'path',
             id: 'path',
             xtype: 'textareafield',
             rows: 2
        },{
             fieldLabel: 'Аттрибут',
             name: 'attribute_name',
             id: 'attribute_name'
        },{
             fieldLabel: 'Значение аттрибута',
             name: 'attribute_value',
             id: 'attribute_value',
             xtype: 'textareafield',
             rows: 2
        },{
             xtype: 'fieldset',
             title: 'Пользователь',
             layout: 'vbox',
             items: [{
                xtype: 'fieldcontainer',
                layout: 'hbox',
                combineErrors: true,
                defaultType: 'textfield',
                defaults: {
                    labelAlign: 'right',
                    anchor: '100%',
                    readOnly: true
                },
                items: [{
                    fieldLabel: 'Имя',
                    labelWidth: 40,
                    name: 'user_name',
                    id: 'user_name'
                },{
                    name: 'userGID',
                    id: 'userGID',
                    xtype: 'hiddenfield'
                },{
                    name: 'userName',
                    id: 'userName',
                    xtype: 'hiddenfield'
                },{
                    fieldLabel: 'Дата изменений',
                    labelWidth: 120,
                    name: 'user_date',
                    id: 'user_date'
                }]
            },{
                xtype: 'fieldcontainer',
                defaults: {
                    labelAlign: 'right',
                    //anchor: '100%',
                    width: 150,
                    flex: 2
                },
                defaultType: 'radiofield',
                layout: 'hbox',
                items: [{
                   boxLabel: cError(0) + 'Ошибка',
                   name: 'status_error',
                   inputValue: 0,
                   id: 'error0'
                },{
//                   fieldLabel: cError(1),
//				   labelWidth: 20,
				   boxLabel: cError(1) + 'Исправлена',
                   name: 'status_error',
                   inputValue: 1,
                   id: 'error1'
                },{
                   fieldLabel: 'КЛАДР',
                   labelWidth: 50,
                   width: 220,
                   xtype: 'checkbox',
                   boxLabel: 'Нужно исправить',
                   name: 'kladr_need',
                   id: 'kladr_need'
                }]
            },{
                xtype: 'fieldcontainer',
                defaults: {
                    labelAlign: 'right',
                    width: 150,
                    anchor: '100%',
                    flex: 1
                },
                defaultType: 'radiofield',
                layout: 'hbox',
                items: [{ //  comboErrors,  {
                   boxLabel: cError(2) + 'Не возм. исправить',
                   name: 'status_error',
                   inputValue: 2,
                   id: 'error2'
                },{
                   boxLabel: cError(3) + 'Не обнаружена',
                   name: 'status_error',
                   inputValue: 3,
                   id: 'error3'
                },{
                   fieldLabel: 'КЛАДР',
                   labelWidth: 50,
                   width: 220,
                   xtype: 'checkbox',
                   boxLabel: 'Исправлен',
                   name: 'kladr_yes',
                   id: 'kladr_yes'
                }]
/*            },{
                xtype: 'fieldcontainer',
                defaults: {
                    labelAlign: 'right',
                    width: 150,
                    anchor: '100%',
                    flex: 1
                },
                defaultType: 'radiofield',
                layout: 'hbox',
                items: [{ //  comboErrors,  {
                   boxLabel: cError(4) + 'Российская Федерация',
                   name: 'status_error',
                   inputValue: 4,
                   id: 'error4',
				   width: 250
//                },{
//					xtype: 'hidden'
//                },{
//					xtype: 'hidden' 
                }] */
            },{
                xtype: 'fieldcontainer',
                defaults: {
                    labelAlign: 'right',
                    anchor: '100%'
                },
                items: [{
                    fieldLabel: 'Комментарий',
                    name: 'user_comment',
                    id: 'user_comment',
                    xtype: 'htmleditor',
                    autoScroll: true,
                    enableSourceEdit: false,
                    enableLinks: false,
                    enableAlignments: false,
                    enableSourceEdit: false,
                }]
            }]
        }],
        dockedItems: [{
            xtype: 'toolbar',
            items: [{
                text: 'Сохранить',
                icon: '/images/diagona/02/16/45.png',
               
                handler: function(){
                    var form = this.up('form').getForm();
                    if (Ext.getCmp('rec_guid').getValue()) {
                        form.submit({
                             success: function(form, action) {
                                   var gridRecord = gridListErrors.getSelectionModel().getSelection();
                                   if(gridRecord){
                                       gridRecord[0].set( 'kladr_n-y',  b2v( Ext.getCmp('kladr_need').getValue() )
                                                                 +'/'+b2v( Ext.getCmp('kladr_yes').getValue() ) );
                                       if(Ext.getCmp('error0').getValue()){gridRecord[0].set( 'status_error',cError(0))}
                                       else if(Ext.getCmp('error1').getValue()){gridRecord[0].set( 'status_error',cError(1))}
                                       else if(Ext.getCmp('error2').getValue()){gridRecord[0].set( 'status_error',cError(2))}
                                       else if(Ext.getCmp('error3').getValue()){gridRecord[0].set( 'status_error',cError(3))};
                                  }
                                  gridRecord[0].set( 'user_comment',Ext.getCmp('user_comment').getValue());
                                  gridListErrors.getView().refresh();
                             },
                             failure: function(form, action) {
                                 Ext.Msg.alert('Failed', action.result ? action.result.message : 'No response');
                            }
                       });
                    }
                }
            },{
                text: 'Детали ошибки',
                icon: '/images/diagona/01/16/50.png',
               
                handler: function(){
                    if (Ext.getCmp('rec_guid').getValue()) {
                        var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading..."});
                        loadMask.show();
                        Ext.Ajax.request({
                             url: '/php/tir2/tir-error-detail.php',
                             params: {
                                 rec_guid: Ext.getCmp('rec_guid').getValue()
                             },
                             success: function(response){
                                 loadMask.hide();
                                 Ext.create('Ext.window.Window', {
                                      title: 'Детали ошибки',
                                      height: 200,
                                      width: 400,
                                      stateful: true,
                                      stateId: 'winDetailError',
                                      autoScroll: true,
                                      items: {
                                          html: response.responseText
                                      }
                                }).show();
                            }
                        });
                    }
                }
            },{
                text: 'Подробности',
                icon: '/images/flag/png/ru.png',
               
                handler: function(){
                    if (Ext.getCmp('rec_guid').getValue()) {
                        var loadMask = new Ext.LoadMask(Ext.getBody(), {msg:"Loading..."});
                        loadMask.show();
                        Ext.Ajax.request({
                             url: '/php/tir2/tir-RF-detail.php',
                             params: {
                                 ext_id: Ext.getCmp('external_id').getValue(),
								 essence_type: Ext.getCmp('essence_type').getValue()
                             },
                             success: function(response){
                                 loadMask.hide();
                                 Ext.create('Ext.window.Window', {
                                      title: '<img src="/images/flag/png/ru.png"> Подробности по Сущности из ССД!',
                                      height: 200,
                                      width: 400,
                                      stateful: true,
                                      stateId: 'winDetailRF',
                                      autoScroll: true,
                                      items: {
                                          html: response.responseText
                                      }
                                }).show();
                            }
                        });
                    }
                }
            }]
        }]
     });


    /************************************/  



    if(!win){
           win = Ext.create('widget.window', {
                title: 'Список ошибок ФЛК',
                id: 'mainWindow',
                closable: true,
                maximized: true,
                maximizable: true,
                stateful: true,
                stateId: 'winMainAdrAdrVoc',
                width: 700,
                minWidth: 500,
                height: 500,
                layout: {
                    type: 'border',
                    padding: 5
                },
                items: [{
                    region: 'west',
                    split: true,
                    width: 350,
                    minWidth: 350,
                    layout: 'fit',
                    id: 'listErr',
                    title: 'Список ошибок',
                    items: [ gridListErrors ],
                }, {
                    region: 'center',
                    autoScroll: true,
                    width: 350,
                    minWidth: 350,
                    title: 'Сведения об исправлении ошибки',
                    items: [ ErrorForm ],
                    id: 'SvedErr',
                    name: 'SvedErr'
                }],
                listeners: {
                },
                tbar: [
                        ]
            });
        win.show();
    }

   
    function LoadData(_session, _otdel, _stype, _desc){
                          if( !_session || !_otdel || !_stype ){
//                               Ext.Msg.alert('ВНИМАНИЕ!', 'Все поля должны быть заполнены!');
                          } else {
                               if(_desc=='') { comboErrDesc.setValue('');}
                               storeListError.loadPage(1,{ params: {
                                  session:  _session,
                                  otdel: _otdel,
                                  stype: _stype,
                                  desc: _desc
                               } });
/*                              storeErrorDescription.load({ params: {
                                  session:  _session,
                                  otdel: _otdel,
                                  stype: _stype
                               } });*/
                              var cmpForm = Ext.getCmp('ErrorForm');
                              var form = cmpForm.getForm();
                              form.reset();
                          }

    }

/**************************************************/
    var tooltips = [{
/*        target: 'external_id',
        html: 'A very simple tooltip'
    }, {
        target: 'ajax-tip',
        width: 200,
        autoLoad: {
            url: 'ajax-tip.html'
        },
        dismissDelay: 15000 // auto hide after 15 seconds
    }, { */
        target: 'external_id',
        title: 'My Tip Title',
        title: 'Внимание!',
        html: 'Данный ID не всегда совпадает с ЕГРП ИД',
        width: 100,
        dismissDelay: 10000 // Hide after 10 seconds hover
    },{
        target: 'selPeriod',
        title: 'Внимание!',
        html: 'Цифры в скобках означают: </ br>(Кол-во документов)=(Ошибок ФЛК)=(Ошибок ФНС)',
        width: 300,
        dismissDelay: 10000
/*    }, {
        target: 'track-tip',
        title: 'Mouse Track',
        width: 200,
        html: 'This tip will follow the mouse while it is over the element',
        trackMouse: true
    }, {
        title: '<a href="#">Rich Content Tooltip</a>',
        id: 'content-anchor-tip',
        target: 'leftCallout',
        anchor: 'left',
        html: null,
        width: 415,
        autoHide: false,
        closable: true,
        contentEl: 'content-tip', // load content from the page
        listeners: {
            'render': function() {
                this.header.on('click', function(header, e) {
                    e.stopEvent();
                    Ext.Msg.alert('Link', 'Link to something interesting.');
                    Ext.getCmp('content-anchor-tip').hide();
                }, this, {
                    delegate: 'a'
                });
            }
        }
    }, {
        target: 'bottomCallout',
        anchor: 'top',
        anchorOffset: 85, // center the anchor on the tooltip
        html: 'This tip\'s anchor is centered'
    }, {
        target: 'trackCallout',
        anchor: 'right',
        trackMouse: true,
        html: 'Tracking while you move the mouse' */
    }];

    Ext.each(tooltips, function(config) {
        Ext.create('Ext.tip.ToolTip', config);
    });
    Ext.QuickTips.init();
});