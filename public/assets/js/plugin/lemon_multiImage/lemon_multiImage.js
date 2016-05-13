/**
 * Created by NIU on 2015/8/25.
 */
define(function(require,exports){
    var $=require('$');
    var dialog=require('jquery.art-dialog');
    var multiUtil=require('plg-multiUtil');
    var upload_id;
    var imgShowId;
    var dialogBox;
    exports.upload=function(id,imgId){
        $(id).on('click',function(){
            dialogBox=dialog({
                title: '多图上传',
                width:600,
                height:500,
                url:Lemon.support_url.upload_multi_html,
                button:[{
                    value: '确定',
                    callback: function () {
                       $(imgId).find("#image").append(imgStr);
                       $(imgId).show();
                    },
                    autofocus: true
                },{
                    value: '取消',
                    callback: function () {
                        alert('你取消了')
                    }
                }]
            });
            dialogBox.showModal();
        });
        upload_id=id;
        imgShowId=imgId;
    };
    var imgStr='';
    exports.uploadCallback=function(imgSrc){
        imgStr+='<div class="imgbox">'+
            '<div class="w_upload">'+
            '<a href="javascript:void(0)" class="item_close">删除</a>'+
            '<div class="item_box">'+
            '<div class="photo">'+
            '<img src="'+imgSrc+'" height="80" class="js_picUP" title="双击查看原图">' +
            '</div>'+
            '<div class="miaoshu-photo">' +
            '<input type="hidden" name="image_url[]" value="'+imgSrc+'" class="input-text">' +
            '<input type="text" name="image_alt[]" value="" class="miaoshu-wenzi" >'+
            '</div></div>'+
            '<div class="btn-set-fm js_set_fm"><span>点此设为封面</span></div>'+
            '</div>'+
            '</div>'+
            '</div>';
    };
   Libs.multiImg=exports;
});