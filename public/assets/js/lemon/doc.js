define(function (require, exports) {
	var hljs = require('highlight');
	var $ = require('jquery');
	exports.fill_and_highlight = function (source_id, aim_id, type) {
		$(function() {
			var text = '';
			if (type == 'script') {
				text ='<script>' +
					$('#'+source_id).text() +
					'</' + 'script>';
				text = text.replace("\t" + '</' + 'script>', '</' + 'script>');
			} else if ( type == 'html' ) {
				text = $('#'+source_id).andSelf().html();
				text = text.replace(/\s*$/, '');
			} else {
				text = $('#'+source_id).html();
			}

			$('#'+aim_id).text(text);
			$('pre').each(function (i, block) {
				hljs.highlightBlock(block);
			});
		})
	};

	exports.trim_content = function (id) {
		var $id = $('#' + id);
		$id.html($.trim($id.html()));
	};
});