define(function (require, exports) {
    require('$');
    var dialog = require('jquery.art-dialog');
    var dialogBox;
    var call_data;
    var announce_id;
    var push_type;
    exports.openDialog = function (id, selPush,type) {
        dialogBox = dialog({
            title: '推送选择',
            width: '600px',
            height: '500px',
            //url: Lemon.url_site + "/api_plugin/message-push"
            url: Lemon.support_url.util_message_push_html
        });
        dialogBox.showModal();
        call_data = selPush;
        announce_id = id;
        //push_type= type;
    };
    exports.callback = function (call_json) {
        var str = '';
        var type = '';
        if (call_json != '') {
            var data = $.parseJSON(call_json);
            $.each(data, function (idx, obj) {
                if(data.length>1){
                    str += obj.selId + ',';
                }else{
                    str = obj.selId;
                }
                type = obj.type;
            });
        }
        var ajaxurl = Lemon.url_site + "api.php?aM=push&aA="+push_type;
        var obj = {};
        if (str != undefined) {
            obj.ids = str;
        }
        obj.type = type;
        obj.announce_id = announce_id;
        $(call_data).val(call_json);
        /*$.ajax({
            url: ajaxurl,
            dataType: 'JSON',
            data: obj,
            success: function (data) {
                if (data != '') {
                    alert(data.message);
                }
            }
        });*/
        dialogBox.close();
    };
    Libs.lemon_msgPush = exports;
});