define(function (require) {
    var $ = require('$');
   (function ($) {
        $.fn.lemon_brand = function (opt) {
            var $this = $(this);
            var $this_val = $this.val();
            var defaultOptions = {
                ctr_class: '.sj-mod_brandSelect',
                //url: Lemon.url_site + "ajax.php?module=auto&action=brand",
                url: Lemon.url_site + "/data/brand/brand.json",
                ctr_text: '.js-search-box',
                ctr_text_btn: '.js-search-btn',
                ctr_select_cont: '.js-select-cont',
                ctr_select_btn: '.js-select-btn',
                ctr_select_display: '.js-select-display',
                input_name: '',
                input_value: ''
            };
            //jQuery.isPlainObject()函数用于判断指定参数是否是一个纯粹的对象。
            var options = ($.isPlainObject(opt) || !opt) ? $.extend(true, {}, defaultOptions, opt) : $.extend(true, {}, defaultOptions),  //将新参数覆盖默认定义的参数
            //定义参数
                $ctr,
                $ctr_text,
                $ctr_text_btn,
                $ctr_select_cont,
                $ctr_select_btn,
                $ctr_select_display,
                $input_value,
                brand_data,
                exist_id=false,
            //定義函數
                _creat_html,
                _text_focus,
                _left_btn_click,
                _display_click,
                _set_display_height,
                _render_html,
                _init,
            //定义默认显示的html
                html_ctr = '<div class="sj-mod_brandSelect"></div>',
                html_text = '<div class="select-selected">' +
                    '<input  name="" class="txt-search js-search-box" type="text"  placeholder="请选择品牌" autocomplete="off" title="">' +
                    '<i class="icon10 icon10-down1 js-search-btn"></i>' +
                    '</div>',
                html_cont = '<div class="selectpop-box-prov js-select-cont" style="display: none">' +
                    '<div class="selectpop-cont-btn js-select-btn fl"></div>'+
                    '<div class="selectpop-prov-cont js-select-display clear"></div>'+
                    '</div>';
               // html_btn='<div class="selectpop-cont-btn js-select-btn fl"></div>',
                //html_display = '<div class="selectpop-prov-cont js-select-display clear"></div>';
            //创建布局
            _creat_html = function () {
                $this.after(html_ctr);
                $ctr = $this.next(options.ctr_class);
                $ctr.append(html_text).append(html_cont);
                $ctr_text = $ctr.find(options.ctr_text);
                $ctr_text_btn = $ctr.find(options.ctr_text_btn);
                $ctr_select_cont = $ctr.find(options.ctr_select_cont);
                $ctr_select_btn = $ctr.find(options.ctr_select_btn);
                $ctr_select_display = $ctr.find(options.ctr_select_display);
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: options.url,
                    async: false,
                    success: function (data) {
                        brand_data = data;
                    }
                });

            };
            //加载数据 (默认情况的显示)
            _render_html=function(){
                var letter_obj = {};
                for (var i in brand_data) {
                    var letter = brand_data[i].letter.toUpperCase();
                    if (letter) {
                        if (!letter_obj[letter]) {
                            letter_obj[letter] = [];
                        }
                        letter_obj[letter].push(brand_data[i]);
                    }
                }
                var str = '';
                var str_btn = '';
                $.each(letter_obj, function (i, brand) {
                    str_btn += '<a href="javascript:;" target="_self" btag="' + i + '">' + i + '</a>';
                    str += '<dl class="town-con-dl" id="' + i + '"><dt class="brandnoselecte">' + i + '</dt>';
                    str += '<dd class="town-btn">';
                    $.each(brand, function (j, k) {
                        if(k["id"]==$this_val){
                            $input_value=k['name'];
                            exist_id=true;
                        }
                        str += '<a id="' + k["id"] + '" href="javascript:;" target="_self">' + k['name'] + '</a>';
                    })
                    str += '</dd></dl>';
                })
                $ctr_select_btn.append(str_btn);
                $ctr_select_display.append(str);
                _set_display_height();
            }
            //初始化
            _init=function(){
               var input_name=options.input_name;
               var input_value=options.input_value;
                if(exist_id==true){
                    $ctr_text.attr('name',input_name).val($input_value);
                }else{
                    $ctr_text.attr('name',input_name).val(input_value);
                }

            }
            //设置内容区域高度
            _set_display_height = function () {
                var btnH = $ctr_select_btn.height()-6;
               $ctr_select_display.height(btnH);
            }
            //左侧按钮点击事件
            _left_btn_click = function () {
                $ctr_select_btn.find("a").hover(function () {
                    var x = $(this).attr('btag');
                    $(this).addClass('selected').siblings().removeClass('selected');
                    $ctr_select_display.find("#" + x).show().siblings().hide();

                })
            };
            //点击信息输入框中显示内容
            _display_click = function () {
                $ctr_select_display.find("a").on('click', function () {
                    var id=$(this).attr('id');
                    $ctr_text.val($(this).text()); //输入框中显示选中的内容
                    options.input_value=$(this).text(); //将选中的内容传参回去
                    $ctr_select_cont.hide();   //将下拉框隐藏
                    $ctr_text_btn.removeClass("open");
                    $this.val(id);           //input隐藏域中写入id
                })
            }
            //展示框显示隐藏
            _text_focus = function () {
                $ctr_text.focus(function () {
                    $ctr_text_btn.addClass("open");
                    $ctr_select_cont.show();
                    _set_display_height();
                });
                $(document).click(function(e){
                    e = window.event || e; // 兼容IE7
                    obj = $(e.srcElement || e.target);
                    if ($(obj).is(''+options.ctr_class+' *')) {
                    } else {
                        $ctr_select_cont.hide();
                        $ctr_text_btn.removeClass("open");
                    }
                });
                $ctr_text_btn.click(function () {
                    $(this).toggleClass("open");
                    $ctr_select_cont.toggle();
                    _set_display_height();
                })

            }
            _creat_html();
            _render_html();
            _init();
            _text_focus();
            _left_btn_click();
            _display_click();
        }
    })(jQuery);
});
