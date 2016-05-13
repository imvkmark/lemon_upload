define(function (require, exports, module) {
	var $ = require('$');
	var dialog=require('jquery.art-dialog');
	var util = require("util");
	exports.change_images = function (uploadid, returnid) {
		var d = dialog.get(uploadid).iframeNode.contentWindow;
		var in_content = d.$("#att-status").html().substring(1);
		var in_filename = d.$("#att-name").html().substring(1);
		var str 	 = $('#' + returnid).html();
		var contents = in_content.split('|');
		var filenames= in_filename.split('|');
		//var fm_str;
		$('#' + returnid + '_tips').css('display', 'none');
		if ( contents == '' ) return true;
		$.each(contents, function (i, n) {
			var ids = parseInt(Math.random() * 10000 + 10 * i);
			//默认图片介绍为空
			//edit by Sameal
			var filename ='';
			//	var filename = filenames[i].substr(0, filenames[i].indexOf('.'));
			//end edit
			str   +='<div class="imgbox" id="image_'+ids+'">'
						+'<div class="w_upload">'
							+'<a href="javascript:void(0)" class="item_close">删除</a>'
							+'<div class="item_box">'
							+'<div class="photo">'
							+'<img src="'+ n +'" data-big="'+Lemon.image_url + filenames[i]+'" class="js_picUP" title="双击查看原图">'
							+'</div>'			
							+'<div class="miaoshu-photo">'
							+'<input type="hidden" name="' + returnid +'_url[]" value="'+ filenames[i] +'" class="input-text">'
							+'<input type="text" name="' + returnid +'_alt[]" value="'+ filename +'"  class="miaoshu-wenzi" '
							+'onfocus="if(this.value == this.defaultValue) this.value = \'\'"'
							+'onblur="if(this.value.replace(\' \',\'\') == \'\') this.value = this.defaultValue;">'
							+'</div>'
							+'</div>'
							+'<div class="btn-set-fm js_set_fm"><span>点击设为封面</span></div>'
						+'</div>'
				    +'</div>';

		});
		//显示被隐藏的列表框
		//edit by Sameal
		$('#' + returnid).parents("fieldset").parent("div").show();
		//end edit
		$('#' + returnid).html(str);
		//$('#' + returnid).append(fm_str);
		exports.image_handle();
	};
	/**
	 * 预览
	 * @param img
	 */
	//查看大图
	exports.image_preview = function(img) {
		var dialogBox;
		dialogBox=dialog({title : '图片查看', content : '<img src="' + img + '"  style="max-width:800px; max-height:800px" />'});
		dialogBox.show();
	};

	exports.remove_div = function (id) {
		$('#' + id).remove();
	};

	exports.image_handle = function(){
	    //点击设置为封面
	    var count=0;
	    $('.imgbox').each(function(i){
	        if($(this).find("input[name='cover']").val()=='Y'){
	        $(this).find(".js_set_fm").children("span").html('封面&nbsp;√');
	        $("#js-fm-selected").val(i);
	            count++;
	        }
	    });
	    if(count==0){
	          $('.imgbox').eq(0).find(".js_set_fm").children("span").html('封面&nbsp;√');
	          $("#js-fm-selected").val(0);
	    }
	    $('.js_set_fm').on('click',function(){
	        var j=$(this).parents(".imgbox").index();
	        $(".js_set_fm").children("span").text('点此设为封面');
	        $(this).children("span").html('封面&nbsp;√');
	        $("#js-fm-selected").val(j-1);
	    });
	    //移动位置
	    var totallength = $('.imgbox').length;
	    //左移
	    $('.scroll_l').click(function(){
	        if($(this).parents('.imgbox').index() > 0 ){
	            var self = $(this).parents('.imgbox');
	            self.prev().before(self);
	           // bindfengmian();
	        }
	    });
	    //右移
	    $('.scroll_r').click(function(){
	        if($(this).parents('.imgbox').index() < totallength-1 ){
	            var self = $(this).parents('.imgbox');
	            self.next().after(self);
	           // bindfengmian();
	        }
	    });
	    //删除
	    $('.item_close').on('click',function(){
		    //若图片列表为空 隐藏列表框
		    //edit by Sameal
		    if($(this).parents('.imgbox').siblings().length == 0){
			    $(this).parents("fieldset").parent("div").hide();
		    }
		    //end edit
	        $(this).parents('.imgbox').remove();
	       // bindfengmian();
	    });
	};

	/**
	 * @param uploadid      上传组件Id
	 * @param name          对话框title
	 * @param textareaid    textareaid
	 * @param funcName      上传函数
	 * @param args          参数
	 */
	exports.flashupload = function (url,uploadid, name, textareaid, funcName) {
		var dialogBox;
		dialogBox=dialog({
			title : name,
			url:url,
			id : uploadid,
			width : '500',
			height : '420',
			okValue:'确定',
			ok:function(){
				if ( funcName ) {
					funcName.apply(this, [uploadid, textareaid]);
				} else {
					alert("arguments error")
				}
			},
			cancelValue: '取消',
			cancel:function(){}
		});
		dialogBox.showModal();
	};
	Libs.multiUtil=exports;
});
