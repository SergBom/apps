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
        appAccessEdit: function(appClassName){
            var result=true;
            Ext.Ajax.request({
                url:'data/PortalSecurity/appAccessEdit.php',
                params:{
                    appClassName:appClassName,
                    out:''
                },
                async:false,
                success:function(r,o) {
                    result=Ext.decode(r.responseText).success;
                }
            });
            return result;
        },
        deleteRecord: function(record,store){
            Ext.Msg.show({
                title: 'Внимание!',
                message: 'Вы действительно желаете удалить эту запись?',
                buttons: Ext.Msg.YESNO,
                icon: Ext.Msg.QUESTION,
                fn: function(btn){
                    if(btn === 'yes'){
                        store.remove(record);
                        store.sync();
                        store.load();
                    }
                }
            });
        },
        deleteRecord2: function(id,s,u,txt){
            Ext.Msg.show({
                title:'Внимание!',
                message:'Вы действительно желаете удалить эту запись?',
                buttons:Ext.Msg.YESNO,
                icon:Ext.Msg.QUESTION,
                fn:function(btn){
                    if(btn==='yes'){
                        Ext.Ajax.request({
                            url:u,
                            params:id,
                            success:function(r){
                                var rt=Portal.util.Util.decodeJSON(r.responseText);
                                if(rt.success){
                                    s.load();
                                }else{
                                    Portal.util.Util.showErrorMsg(rt.msg);
                                }
                            }
                        });
                    }
                }
            });
        }
    }
});