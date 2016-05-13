/**
 * Created by Administrator on 2015/7/22.
 */
define(function(require,exports){
    var jQuery=require('$');
    require("uploadify");
    require('util');
    var dialog=require('jquery.art-dialog');
    (function($){
        $.fn.lemon_picUpload=function(opt){
             var defaultOptions={
                'imgId':"#js-imgSrc"
                };
            var options = ($.isPlainObject(opt) || !opt) ? $.extend(true, {}, defaultOptions, opt) : $.extend(true, {}, defaultOptions),
                $imgId,
                _init;
            $imgId=$(options.imgId);
            $(this).uploadify({
                'swf':Lemon.url_site + '/js/libs/uploadify/3.2.1/uploadify.swf',
                'uploader': Lemon.url_site + '/data/thumbUpload/pic.json',
                'multi': true,
                'fileTypeExts': '*.jpg;*.jpeg;*.png;*.gif',
                'fileSizeLimit': '500k',
                'buttonText': '上传图片',
                'onUploadSuccess' : function(file, data) {    //file 成功上传的文件、data 服务器端脚本返回的数据  response 由服务器返回的响应真正的上传成功
                    var imgData = $.parseJSON(data);
                    if(imgData.status=='success'){
                        $imgId.val(imgData.url);
                    }else{
                        alert(imgData.msg);
                    }
                }
            });
            var dialogBox;
            var preview = function (url, w, h) {
                if ( $.trim(url) == '' ) {
                    dialogBox=dialog({
                        title:'提示',
                        content:'预览地址为空!!'
                    });
                } else {
                    dialogBox=dialog({
                        title:'图像预览',
                        width:w+80,
                        height:h+80,
                        content:'<div style="text-align: center;"><img src="' + url + '"></div>'
                    });
                }
                dialogBox.showModal();
            };
            $(".js-imgPreview").click(function(){
                var url=$imgId.val();
                if(url!=''){
                    util.image_size(url, function () {
                        var w=this.width;
                        var h=this.height;
                        preview(url);
                    })
                }
                else{
                    preview();
                }
            });
            $(".js-btn-close").click(function(){
                dialogBox.close();
            });
            $(".js-empty").click(function(){
                $imgId.val('');
            });
        }

    })(jQuery);
});
