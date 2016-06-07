<?php namespace App\Lemon\Repositories\Application;

use App\Lemon\Repositories\Sour\LmStr;
use App\Lemon\Repositories\Sour\LmTree;
use App\Lemon\Repositories\System\SysCity;
use App\Lemon\Repositories\System\SysPic;
use App\Lemon\Upload\System\SysUpload;
use App\Models\BaseArea;
use App\Models\BaseCity;
use App\Models\BaseType;
use App\Models\ModuleCategory;
use App\Models\ModuleCategoryProperty;
use Collective\Html\FormBuilder as LCFormBuilder;
use Illuminate\Database\Eloquent\Collection;

class FormBuilder extends LCFormBuilder {


	/**
	 * 后台使用到的开关
	 * @param       $name
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function onOff($name, $value = null, $options = []) {
		if (!isset($options['name'])) $options['name'] = $name;


		$options['id'] = $this->getIdAttribute($name, $options);

		$value = (string) $this->getValueAttribute($name, $value);

		$name  = $options['name'] ? $options['name'] : '_switch';
		$class = isset($options['class']) ? $options['class'] : '';

		list($yes, $no) = explode('|', isset($options['yn']) ? $options['yn'] : '是|否');
		list($yesvalues, $novalues) = explode('|', isset($options['values']) ? $options['values'] : 'Y|N');

		// Next we will convert the attributes into a string form. Also we have removed
		// the size attribute, as it was merely a short-cut for the rows and cols on
		// the element. Then we'll create the final textarea elements HTML for us.
		$strOptions   = $this->html->attributes($options);
		$selectStr    = '';
		$unSelectStr  = '';
		$checkedStr   = '';
		$unCheckedStr = '';
		if (in_array(strtoupper($value), ["Y", "TRUE", "YES", "1"])) {
			$selectStr  = 'selected';
			$checkedStr = 'checked="checked"';
		} else {
			$unSelectStr  = 'selected';
			$unCheckedStr = 'checked="checked"';
		}

		$parseStr = <<<SWITCH
<div class="cb-onoff">
	<label for="{$options['id']}_enabled"  class="enable {$selectStr}" title="{$yes}"><span>{$yes}</span></label>
	<label for="{$options['id']}_disabled" class="disable {$unSelectStr}" title="{$no}"><span>{$no}</span></label>
	<input id="{$options['id']}_enabled" name="{$name}" class="J_onoffEnabled {$class}" {$checkedStr} value="{$yesvalues}" type="radio">
	<input id="{$options['id']}_disabled" name="{$name}" class=" {$class}" {$unCheckedStr} value="{$novalues}" type="radio">
</div>
SWITCH;
		return $parseStr;

	}

	/**
	 * 上传缩略图
	 * @param       $name
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function thumb($name, $value = null, $options = []) {
		if (!isset($options['name'])) $options['name'] = $name;

		$options['id']          = $this->getIdAttribute($name, $options);
		$value                  = (string) $this->getValueAttribute($name, $value);
		$options['interactive'] = isset($options['interactive']) && $options['interactive'] ? true : false;

		$id = LmStr::random(4);

		$token           = upload_token();
		$thumb_key       = $value ?: '';
		$display_str     = !$value ? "class=\"hidden\"" : '';
		$interactive_str = "
			$('#img_preview_{$id}').on('click', function(e){
				if (e.ctrlKey) {
					window.open ($('#img_url_{$id}').val(), '_blank')
				} else {
					util.image_popup_show($('#img_url_{$id}').val(), $(window).width() / 2);
				}
			});";

		$parseStr = <<<CONTENT
	<div class="sj-uploadify-thumb" id="thumb_{$id}_ctr">
	<input type="file" id="img_thumb_{$id}" class="hidden" />
	<input type="hidden" name="{$name}" value="{$thumb_key}" id="img_url_{$id}"/>
	<span id="img_preview_{$id}_ctr" {$display_str}>
		<span id="img_preview_{$id}" class="uploadify-preview"></span>
		<span id='img_del_{$id}' class="fa fa-times"></span>
	</span>
	</div>
<script>
	requirejs(['jquery','lemon/util', 'global','jquery.uploadify'], function($, util, lemon){
		$(function() {
			$('#img_thumb_{$id}').uploadify({
				'swf'      : lemon.url_js+'/libs/jquery.uploadify/3.2.1/uploadify.swf?rand='+(new Date()).getTime(),
				'uploader' : lemon.upload_url,
				'height'   : 26,
				'width'    :  70,
				'auto'     : true, // 自动上传
				'buttonText': '选择图片',
				'fileObjName': 'image_file',
				'fileDesc': '请选择图片',
				'queueSizeLimit' : 1,
				'fileSizeLimit' : '2MB',
				'multi':false,
//				'debug':true,
				'fileTypeExts':'*.jpg;*.png;*.gif',
				'fileTypeDesc': "请选择 jpg png gif 文件",
//				'queueID'  : 'J_fileQueue',
				'formData':{
					'upload_token': '{$token}'
				},
				'onUploadSuccess' :function(file,response, data) {
					var obj_resp = $.parseJSON(response);
					if (obj_resp.status == 'error') {
					    alert(obj_resp.msg);
					} else {
						$('#img_url_{$id}').val(obj_resp.url);
						$('#img_preview_{$id}_ctr').removeClass('hidden');
					}
					$("#img_preview_{$id}_ctr").show();
				}
			});
			$interactive_str
		});
		$("#img_del_{$id}").click(function () {
			$("#img_preview_{$id}_ctr").hide();
			$("input[name={$name}]").val('');
		});
	})
</script>
CONTENT;
		return $parseStr;
	}

	/**
	 * 显示上传的单图
	 * @param       $url
	 * @param array $options
	 * @return string
	 */
	public function showThumb($url, $options = []) {
		$url       = $url ? SysUpload::url($url) : config('app.url_image') . '/lemon/fw/nopic.gif';
		$options   = $this->html->attributes($options);
		$parse_str = '<img class="J_image_preview" src="' . $url . '" ' . $options . ' title="单击可打开图片, 按住 `ctrl` + `鼠标` 点击可以查看原图" >';
		return $parse_str;
	}


	public function tip($description, $name = null) {
		if ($name == null) {
			$icon = '<i class="fa fa-question-circle">&nbsp;</i>';
		} else {
			$icon = '<i class="fa ' . $name . '">&nbsp;</i>';
		}
		return <<<TIP
<a data-tip="{$description}" class="J_dialog" data-title="信息提示"
data-toggle="tooltip" data-placement="top">{$icon}</a>
TIP;
	}

	/**
	 * 生成树选择
	 * @param        $name
	 * @param        $tree
	 * @param string $selected
	 * @param array  $options
	 * @param string $id
	 * @param string $title
	 * @param string $pid
	 * @return string
	 */
	public function tree($name, $tree, $selected = '', $options = [], $id = 'id', $title = 'title', $pid = 'pid') {
		$Tree = new LmTree();
		$Tree->init($tree, $id, $pid, $title);
		$treeArray = $Tree->getTreeArray(0, '');

		return $this->select($name, $treeArray, $selected, $options);
	}


	/**
	 * 生成排序链接
	 * @param        $name
	 * @param string $value
	 * @param string $route_name
	 * @return string
	 */
	public function order($name, $value = '', $route_name = '') {
		$input = \Input::all();
		$value = $value ?: (isset($input['_order']) ? $input['_order'] : '');
		switch ($value) {
			case $name . '_desc';
				$con  = $name . '_asc';
				$icon = '<i class="fa fa-sort-desc"></i>';
				break;
			case $name . '_asc':
				$con  = $name . '_desc';
				$icon = '<i class="fa fa-sort-asc"></i>';
				break;
			default:
				$icon = '<i class="fa fa-sort"></i>';
				$con  = $name . '_asc';
		}
		$input['_order'] = $con;
		if ($route_name) {
			$link = route($route_name, $input);
		} else {
			$link = '?' . http_build_query($input);
		}
		return '
			<a href="' . $link . '">' . $icon . '</a>
		';
	}


	/**
	 * Linkage 分类
	 * @param       $name
	 * @param       $module_id
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function categoryLinkage($name, $module_id, $value = null, $options = []) {
		$options['id'] = $this->replace($this->getIdAttribute($name, $options));
		$cache         = ModuleCategory::getCache($module_id);
		$value         = (string) $this->getValueAttribute($name, $value);
		$defaultStr    = '';
		if ($value) {
			$defaultArr = substr($cache[$value]['parent_ids'], strpos($cache[$value]['parent_ids'], ',') + 1);
			$defaultStr = 'defVal: [' . ($defaultArr ? $defaultArr . ',' : '') . $value . '], ';
		}

		$url  = route_url('support_category.linkage', null, ['module_id' => $module_id]);
		$data = <<<Html
		<input id="{$options['id']}" type="hidden" name="{$name}" value="{$value}">
		<select id="{$options['id']}_select" style="display: inline; margin-right: 5px; float: left;"></select>
		<script>
		requirejs(['jquery','jquery.linkage-sel', 'global'], function($, LinkageSel, lemon){
			$(function(){
				var opts = {
					'head' : "请选择..",
					'ajax' : "$url",
					{$defaultStr}
					'select': '#{$options['id']}_select',
					'selStyle':'margin-right:5px;float:left',
					'dataReader':{
						'id':'parent_id'
					},
					'level':3,
					'autoBind':false,
					'autoLink':false,
					'loaderImg':lemon.url_image + '/lemon/fw/loading.gif'
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


	/**
	 * 编辑器
	 * @param        $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function kindeditor($name, $value = '', $options = []) {
		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$append        = $this->html->attributes($options);
		$width         = isset($options['width']) ? $options['width'] : '100%';
		$height        = isset($options['height']) ? $options['height'] : '300px';
		$token         = upload_token();
		$returnUrl     = route('support_upload.return');
		$id            = $options['id'] ?: 'ke_' . LmStr::random(4);
		$data          = <<<KindEditor
		<textarea name="$name" id="$id" $append>$value</textarea>
		<script>
		requirejs(['ke', 'global'], function (ke, lemon) {
			ke.create('#{$id}',{
				extraFileUploadParams:{
					'upload_token': '{$token}',
					'return_url': '{$returnUrl}'
				},
                uploadJson : lemon.upload_url,
                items:ke.iConfig.simple,
                width: '{$width}',
                height:'{$height}',
                minWidth:'300',
                resizeType: 1,
                filePostName:'image_file',
                allowFlashUpload:false,
                afterBlur : function(){
                    this.sync();
                },
                afterChange : function(){
                    this.sync();
                }
			});
		});
		</script>
KindEditor;
		return $data;
	}

	/**
	 * 颜色选取组件
	 * @param string $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function spectrum($name, $value = '#FFF', $options = []) {
		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$config        = [];
		$configStr     = '';
		if (isset($options['color'])) {
			$config['color'] = $options['color'];
		}
		if ($config) {
			$configStr = json_encode($config);
		}

		$html = <<<HTML
<input id="{$options['id']}" type="hidden" value="$value" name="$name">
<script>
requirejs(['jquery', 'jquery.spectrum'], function($){
	$(function(){
		$("#{$options['id']}").spectrum($configStr);
	})
})
</script>
HTML;
		return $html;
	}


	/**
	 * 生成日期选择器
	 * @param       $name
	 * @param       $value
	 * @param array $options
	 * @return string
	 */
	public function datepicker($name, $value = '', $options = []) {

		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$class         = isset($options['class']) ? $options['class'] : '';
		$quickStr      = '';
		$quickScript   = '';
		if (isset($options['quick'])) {
			$quickNormal = [
				'三天' => '+3 days',
				'一周' => '+7 days',
				'半月' => '+15 days',
				'一月' => '+1 month',
				'半年' => '+6 month',
				'一年' => '+1 year',
			];
			$quickList   = [];
			foreach ($quickNormal as $date_key => $date_str) {
				$Date = new \DateTime();
				$Date->modify($date_str);
				$quickList[$Date->format('Y-m-d')] = $date_key;
			}
			$id          = 'datepicker_' . LmStr::random(3) . '_quick';
			$quickStr    = self::select($id, $quickList, null, ['placeholder' => '长期', 'id' => $id, 'class' => 'form-control']);
			$quickScript = "
		var sel_datepicker = $('#" . $id . "');
			sel_datepicker.on('change', function () {
			datepicker.val(sel_datepicker.val());
		})";
		}

		$cfg_default = [
			'direction' => true,
		];
		$config      = array_merge($cfg_default, isset($options['config']) ? (array) $options['config'] : []);
		if ($config) {
			$configStr = json_encode($config);
		} else {
			$configStr = '';
		}

		$html = <<<HTML
		<input type="text" id="{$options['id']}" name="{$name}" value="{$value}" class="{$class}"/>
		{$quickStr}
		<script>
		    requirejs(['jquery', 'jquery.datepicker'], function ($) {
		        $(function () {
		        var datepicker = $('#{$options['id']}');
		            datepicker.Zebra_DatePicker({$configStr});
					{$quickScript}
		        })
		    });
		</script>
HTML;
		return $html;
	}


	/**
	 * 生成日期时间选择器
	 * @param        $name
	 * @param string $value
	 * @param array  $options
	 * @return string
	 */
	public function datetimepicker($name, $value = '', $options = []) {
		$options['id'] = $this->getIdAttribute($name, $options);
		$value         = (string) $this->getValueAttribute($name, $value);
		$class         = isset($options['class']) ? $options['class'] : '';
		$cfg_default   = [
			'lang'   => 'zh',
			'format' => 'Y-m-d H:i:s',
		];
		if (isset($options['timepicker']) && $options['timepicker'] == 'false') {
			$cfg_default['format'] = 'Y-m-d';
		}
		$config = array_merge($cfg_default, (array) (isset($options['config']) ? $options['config'] : []));
		if ($config) {
			$configStr = json_encode($config);
		} else {
			$configStr = '';
		}
		$html = <<<HTML
<input type="text" id="{$options['id']}" name="{$name}" value="{$value}" class="{$class}">
<script>
requirejs(['jquery', 'jquery.datetimepicker'], function($){
	$(function(){
		$("#{$options['id']}").datetimepicker({$configStr});
	});
});
</script>
HTML;
		return $html;
	}


	/**
	 * radio 选择器(支持后台)
	 * @param       $name
	 * @param array $lists
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function radios($name, $lists = [], $value = null, $options = []) {
		$str       = '';
		$isDesktop = isset($options['desktop']) ? true : false;
		$isInline  = isset($options['inline']) ? true : false;
		$value     = (string) $this->getValueAttribute($name, $value);
		if ($isDesktop) {
			$str .= '<div class="form-element"><ul class="check-list">';
		}
		if ($isInline) {
			$inline = 'radio-inline';
		} else {
			$inline = 'radio';
		}
		if (isset($options['id'])) {
			$id = $options['id'];
		} else {
			$id = '';
		}
		foreach ($lists as $key => $val) {
			if ($id) {
				$options['id'] = $id . '_' . $key;
			}
			if ($isDesktop) {
				unset($options['desktop']);
				$str .= '<li>';
				$str .= '<label>';
				$str .= self::radio($name, $key, $value == $key, $options);
				$str .= $val . '</label></li>';
			} else {
				$str .= '<div class="' . $inline . '">';
				$str .= '<label>';
				$str .= self::radio($name, $key, $value == $key, $options);
				$str .= $val;
				$str .= '</label>';
				$str .= '</div>';
			}
		}
		if ($isDesktop) {
			$str .= '</ul></div>';
		}
		return $str;
	}


	/**
	 * 选择器
	 * @param       $name
	 * @param array $lists
	 * @param null  $value
	 * @param array $options
	 * @return string
	 */
	public function checkboxes($name, $lists = [], $value = null, $options = []) {
		$str       = '';
		$arrValues = [];
		$value     = (string) $this->getValueAttribute($name, $value);
		if (is_array($value)) {
			$arrValues = array_values($value);
		} else if (is_string($value)) {
			if (strpos($value, ',') !== false) {
				$arrValues = explode(',', $value);
			} else {
				$arrValues = [$value];
			}
		}

		$isDesktop = isset($options['desktop']) ? true : false;
		if ($isDesktop) {
			$str .= '<div class="form-element"><ul class="check-list">';
		}
		foreach ($lists as $key => $value) {
			if ($isDesktop) {
				unset($options['desktop']);
				$str .= '<li>';
				$str .= '<label>';
				$str .= self::checkbox($name, $key, in_array($key, $arrValues), $options);
				$str .= $value . '</label></li>';
			} else {
				$str .= '<div class="checkbox">';
				$str .= '<label>';
				$str .= self::checkbox($name, $key, in_array($key, $arrValues), $options);
				$str .= $value;
				$str .= '</label>';
				$str .= '</div>';
			}
		}
		if ($isDesktop) {
			$str .= '</ul></div>';
		}
		return $str;
	}


	/**
	 * 多图上传
	 * @param       $name
	 * @param null  $values
	 *          [
	 *          [
	 *          'thumb'=> '',
	 *          'intro'=> '',
	 *          'is_cover'=> '',
	 *          ]
	 *          ]
	 * @param array $options
	 * @return string
	 */
	public function multiImage($name, $values = null, $options = []) {
		$options['id'] = $this->getIdAttribute($name, $options);
		$values        = !empty($values) ? $values : '';
		$doId          = $options['id'] . '_do';
		$uploadId      = $options['id'] . '_upload';

		$strImages = '';
		if ($values && is_array($values)) {
			foreach ($values as $key => $img) {
				$index = 'old_' . $key;
				$strImages .= '
				<div class="imgbox" data-cover="' . $img['is_cover'] . '">
                    <div class="w_upload">
                        <a href="javascript:void(0)" class="item_close">删除</a>
                        <div class="item_box">
                        	<div class="photo">
                            <img data-big="' . SysPic::thumb($img['thumb']) . '" src="' . SysPic::thumb($img['thumb']) . '" class="js_picUP">
                            </div>
                            <div class="miaoshu-photo">
                                <input type="hidden" name="' . $name . '[' . $index . '][url]" value="' . $img['thumb'] . '" class="input-text">
                                <input type="text" name="' . $name . '[' . $index . '][alt]" value="' . $img['intro'] . '"  class="miaoshu-wenzi">
                            </div>
                        </div>
                      <div class="btn-set-fm js_set_fm"><span>点击设为封面</span></div>
                    </div>
                </div>';
			}
		}

		if ($strImages) {
			$display = 'display:block';
		} else {
			$display = 'display:none;';
		}
		$html = <<<HTML
<div>
	<div class="con-upload-photo" id="{$uploadId}" style="{$display}">
		<fieldset class="con-upload-photo-fie blue">
			<legend>上传图片列表</legend>
			<div class="J_image_ctr tupian-upload clearfix">
				<input name="{$name}_is_cover" id="js-fm-selected" type="hidden" value="1">
				{$strImages}
			</div>
		</fieldset>
	</div>
	<div class="con-upload-context clearfix">
		<a id="{$doId}" href="javascript:void(0);" class="upload-but fl">&nbsp;</a>
		<p class="fl">第一张默认为封面，每张最大5MB,支持jpg/gif/png格式</p>
	</div>
</div>
<script>
requirejs(['jquery', 'xundu/multi_image'], function ($, multi_image) {
	window.multi_image = multi_image.init("#{$doId}", '#{$uploadId}', "{$name}");
	window.multi_image.start();
})
</script>
HTML;
		return $html;
	}


	/**
	 * 百度地图标注组件
	 * @param      $pos_name
	 * @param      $lng_name
	 * @param      $lat_name
	 * @param      $zoom_name
	 * @param null $lng
	 * @param null $lat
	 * @param null $zoom
	 * @param null $area_name
	 * @param null $city_id
	 * @return string
	 */
	public function mapMarker($pos_name, $lng_name, $lat_name, $zoom_name, $lng = null, $lat = null, $zoom = null, $area_name = null, $city_id = null) {
		// city id default
		if (!$city_id) {
			$city_id = SysCity::id();
		}

		// area_name default
		if (!$area_name) {
			$area_name = BaseCity::getCache($city_id)['city_title'];
		}


		// lng, lat default
		$lng  = (string) $this->getValueAttribute($lng_name, $lng);
		$lat  = (string) $this->getValueAttribute($lat_name, $lat);
		$zoom = (string) $this->getValueAttribute($zoom_name, $zoom);

		// zoom default
		$zoom = (!$zoom || $zoom < 6) ? 12 : $zoom;

		if ($lng && $lat) {
			$mark_name = '<span class="sj-map_marker_none hide ">已标注(重新标注)</span>';
			$mark_name .= '<span class="sj-map_marker_ok">已标注</span>';
		} else {
			$mark_name = '<span class="sj-map_marker_none">标注位置</span>';
			$mark_name .= '<span class="sj-map_marker_ok hide">已标注</span>';
		}
		$random = str_random(4);
		$id     = 'marker_' . LmStr::random(5);
		$html   = <<<HTML
<input id="{$id}_lng" name="{$lng_name}" value="{$lng}" type="hidden">
<input id="{$id}_lat" name="{$lat_name}" value="{$lat}" type="hidden">
<input id="{$id}_name" name="{$pos_name}" value="{$area_name}" type="hidden">
<input id="{$id}_city_id" value="{$city_id}" type="hidden">
<input id="{$id}_zoom" name="{$zoom_name}" value="{$zoom}" type="hidden">
<div id="{$id}" class="sj-map_marker btn btn-default">
{$mark_name}
</div>
<script>
	requirejs(['jquery','xundu/map_marker'],function($, marker){
		window.marker_{$random} = marker.init('{$id}', 'marker_{$random}');
		window.marker_{$random}.popup();
	})
</script>
HTML;
		return $html;
	}


	protected function replace($item) {
		return str_replace(['[', ']'], '_', $item);
	}
}