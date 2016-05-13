/**
 * Created by ixdcw on 2015/7/6.
 */
define(function(require,exports,module) {
	var $ = require('$');
	var dialog = require('jquery.art-dialog');
	var mark_id;
	var dialogBox;

	exports.marker = function(id){
		$('#'+id).on('click', function(){
			var area_name=$('#'+id+'_name').val();
			var lng=$("#"+id+"_lng").val();
			var lat=$("#"+id+"_lat").val();
			var $zoom = $("#"+id+"_zoom");
			var zoom= $zoom.val() ? $zoom.val() : 13;
			var url = Lemon.support_url.util_bdmap_marker_html+'?lng='+lng+'&lat='+lat+'&name='+encodeURI(area_name) + '&zoom='+zoom;
			dialogBox = dialog({
				title: '地图标注',
                width:600,
                height:500,
				url: url
			});
			dialogBox.showModal();
		});
		mark_id = id;
	};
	exports.callback = function(lng, lat, zoom) {
		$("#"+mark_id+"_lng").val(lng);
		$("#"+mark_id+"_lat").val(lat);
		$("#"+mark_id+"_zoom").val(zoom);

		$('#'+mark_id).find('.sj-map_marker_none').hide();
		$('#'+mark_id).find('.sj-map_marker_ok').removeClass('hide').show();
		dialogBox.close();
	};
	exports.callback_name = function(name) {
		$('#'+mark_id+'_name').val(name);
	};

	Libs.plg_bdmap_marker = exports;

});