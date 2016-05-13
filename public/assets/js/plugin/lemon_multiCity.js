/**Created by niu on 2015/8/19.**/
define(function (require, exports) {
	var $ = require('$');
	var dialog = require('jquery.art-dialog');
	var btnSelId;
	var dialogBox;
	var selectedName;
	var selectedId;
	var priceDivId;
	//点击弹出框
	exports.areaDialog = function (btnId, selName, selId, moduleId, priceId) {
		$(btnId).on('click', function () {
			var selIdval = $(selId).val();
			var url = Lemon.support_url.util_multi_area_html + '?selectedId=' + selIdval;
			dialogBox = dialog({
				title : '请选择区域',
				width : 680,
				height : 420,
				url : url
			});
			dialogBox.showModal();
		});
		btnSelId = btnId;
		selectedName = selName;
		selectedId = selId;
		priceDivId = priceId;
	};
	//执行回调函数
	exports.callback = function (selName, selId, price) {
		$(selectedName).val(selName);
		$(selectedId).val(selId);
		$(priceDivId).text(price);
		dialogBox.close();
	};
	Libs.lemon_multiCity = exports;
});

