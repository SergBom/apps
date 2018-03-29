Ext.define('Portal.util.Util', {

    requires: [
        'Ext.window.Toast'
    ],

    statics : {

        required: '<span style="color:red;font-weight:bold" data-qtip="Required"> *</span>',

        decodeJSON : function (text) {

            var result = Ext.JSON.decode(text, true);

            if (!result){
                result = {};
                result.success = false;
                result.msg = text;
            }

            return result;
        },

        showErrorMsg: function (text) {

            Ext.Msg.show({
                title:'Ошибка!',
                msg: text,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        },

        handleFormFailure: function(action){

            var me = this,
                result = Portal.util.Util.decodeJSON(action.response.responseText);

            switch (action.failureType) {
                case Ext.form.action.Action.CLIENT_INVALID:
                    me.showErrorMsg('Form fields may not be submitted with invalid values');
                    break;
                case Ext.form.action.Action.CONNECT_FAILURE:
                    me.showErrorMsg(action.response.responseText);
                    break;
                case Ext.form.action.Action.SERVER_INVALID:
                    me.showErrorMsg(result.msg);
            }
        },

        showToast: function(text) {
            Ext.toast({
                html: text,
                closable: false,
                align: 't',
                slideInDuration: 400,
                minWidth: 400
            });
        },
        
        showInfoMsg: function (text) {
            Ext.Msg.show({
                title:'Сообщение...',
                msg: text,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
        },
        
        handleFormSuccess: function(action){
            var me = this,
                result = Portal.util.Util.decodeJSON(action.response.responseText);
            me.showInfoMsg(result.msg);
        },
        
        appAccessEdit: function(MenuTabId){
            var result=true;
            var r = Ext.Ajax.request({
                url: 'app/php/security/appAccessEdit.php',
                params: {
                    MenuTabId: MenuTabId,
                    out: ''
                },
                async: false,
                success: function(response, opts) {
                    result = Ext.decode(response.responseText).success;
                }
            
            });
            return result;
        }
         
    }
});