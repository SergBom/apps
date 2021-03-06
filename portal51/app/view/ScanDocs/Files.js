/*
 * File: app/view/ScanDocs/Files.js
 *
 * This file was generated by Sencha Architect version 4.2.2.
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

Ext.define('Portal.view.ScanDocs.Files', {
  extend: 'Ext.window.Window',
  alias: 'widget.scandocsfiles',

  requires: [
    'Portal.view.ScanDocs.FilesViewModel',
    'Ext.grid.Panel',
    'Ext.grid.column.RowNumberer',
    'Ext.grid.column.Number',
    'Ext.grid.column.Date',
    'Ext.view.Table',
    'Ext.grid.column.Action',
    'Ext.toolbar.Toolbar',
    'Ext.button.Button',
    'Ext.form.field.Hidden'
  ],

  viewModel: {
    type: 'scandocsfiles'
  },
  modal: true,
  height: 373,
  width: 558,
  layout: 'fit',
  title: 'Список файлов',
  maximizable: true,
  defaultListenerScope: true,

  items: [
    {
      xtype: 'gridpanel',
      bind: {
        store: '{storeFiles}'
      },
      columns: [
        {
          xtype: 'rownumberer'
        },
        {
          xtype: 'gridcolumn',
          flex: 1,
          dataIndex: 'files',
          text: 'Имя файла'
        },
        {
          xtype: 'numbercolumn',
          width: 100,
          align: 'right',
          dataIndex: 'cnt_size',
          text: 'Размер файла,<br> кБайт'
        },
        {
          xtype: 'datecolumn',
          width: 90,
          align: 'center',
          dataIndex: 'cdate',
          text: 'Дата<br>создания',
          format: 'Y-m-d'
        },
        {
          xtype: 'actioncolumn',
          renderer: function(value, metaData, record, rowIndex, colIndex, store, view) {
            this.items[0].iconCls='icon-mime-pdf';//+value;
            this.items[0].tooltip='Открыть документ';
          },
          getClass: function(v, metadata, r, rowIndex, colIndex, store) {
            return 'icon-mime-pdf';
          },
          width: 53,
          align: 'center',
          dataIndex: 'ext',
          text: 'Открыть',
          items: [
            {
              handler: function(view, rowIndex, colIndex, item, e, record, row) {
                if(record.data.ext == 'pdf'){
                  window.open(document.location.href+'data/ScanDocs/'+record.data.fname);
                }
              }
            }
          ]
        }
      ]
    }
  ],
  dockedItems: [
    {
      xtype: 'toolbar',
      dock: 'top',
      items: [
        {
          xtype: 'button',
          iconCls: 'icon-list',
          text: 'Список файлов => в текст',
          listeners: {
            click: 'onListClick'
          }
        },
        {
          xtype: 'hiddenfield',
          fieldLabel: 'Label'
        }
      ]
    }
  ],

  onListClick: function(button, e, eOpts) {
    var id=this.getItemId(),t=this.getTitle();
    Ext.Ajax.request({
      url:'data/ScanDocs/getFileList.php',
      params:{id:id},
      success:function(r){
        var s=Portal.util.Util.decodeJSON(r.responseText);
        Ext.create('Ext.window.Window',{
          title:t,height:300,width:400,modal:true,
          layout:'fit',items:{xtype:'htmleditor',value:s.msg}
    }).show();}
    });
  }

});