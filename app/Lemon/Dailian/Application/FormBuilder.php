<?php namespace App\Lemon\Dailian\Application;

use App\Models\PluginArea;
use App\Lemon\Repositories\Application\FormBuilder as LemonFormBuilder;

class FormBuilder extends LemonFormBuilder {

	/**
	 * Linkage 地区
	 * @param       $name
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function areaLinkage($name, $value = null, $options = []) {
		$options['id'] = $this->getIdAttribute($name, $options);
		if (!$options['id']) {
			$options['id'] = 'area_linkage_' . str_random(4);
		}
		$value       = (string) $this->getValueAttribute($name, $value);
		$default_str = '';
		if ($value) {
			$parent_ids  = PluginArea::parentIds($value);
			$default_arr = substr($parent_ids, strpos($parent_ids, ',') + 1);
			$default_str = 'defVal: [' . ($default_arr ? $default_arr . ',' : '') . $value . '], ';
		}
		$class = isset($options['class']) ? $options['class'] : 'form-control';

		$url  = route('support_area.linkage');
		$data = <<<Html
	<input id="{$options['id']}" type="hidden" name="{$name}" value="{$value}">
	<select id="{$options['id']}_select" style="display: inline; margin-right: 5px; float: left;"></select>
	<script>
	requirejs(['jquery','jquery.linkage-sel','global', 'lemon/util'], function($, LinkageSel,lemon){
		$(function(){
			var opts = {
				'head' : "请选择..",
				'ajax' : "$url",
				'select': '#{$options['id']}_select',
				'selStyle':'margin-right:5px;width:200px;',
				'selClass': '{$class}',
				'dataReader':{
					'id':'area_id'
				},
				'level':4,
				'autoBind':false,
				'autoLink':false,
				'loaderImg':lemon.url_image + '/xundu/loading.gif',
				$default_str
			};
			var ls = new LinkageSel(opts);
			ls.onChange(function() {
		        var input = $('#{$options['id']}'),
		            prop_id = this.getSelectedValue();
		        input.val(prop_id);
		    });
		})
	})
	</script>
Html;
		return $data;
	}
}