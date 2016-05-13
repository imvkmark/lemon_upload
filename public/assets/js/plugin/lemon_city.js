/**
 * Created by ixdcw on 2015/6/19.
 */
define(function (require) {
    var jQuery = require('$');
    (function ($) {
        "use strict";
        $.fn.lemon_city = function (opt) {
            var $this = $(this);
            var $this_val = $this.val();
            //默认对象参数
            var defaultOptions = {
                ctr_class: '.sj-mod_citySelect',
                title_class: ".citySelect-title",
                address_class: ".citySelect-address",
                tab_class: ".citySelect-tab",
                tab_content_class: ".citySelect-tabcont",
                city_class: ".citySelect-city",
                district_class: ".citySelect-district",
                town_class: ".citySelect-town"
            };
            //jQuery.isPlainObject()函数用于判断指定参数是否是一个纯粹的对象。
            var options = ($.isPlainObject(opt) || !opt) ? $.extend(true, {}, defaultOptions, opt) : $.extend(true, {}, defaultOptions),  //将新参数覆盖默认定义的参数
                area_data,   //定义数据库中的数据
            //定义容器
                $ctr,
                $ctr_title,
                $ctr_address,
                $ctr_tab,
                $ctr_tab_content,
                $ctr_city,
                $ctr_district,
                $ctr_town,

            // 定义函数
                _create_area,
                _create_init,
                _render_html,
                _listen_district,
                _listen_town,
                _listen_tab_active,
                _show_address,
                _show_title,
                _tab_switching,
                _listen_city,
                _default_active_address,
            //定义默认显示的html
                html_ctr = "<div class='sj-mod_citySelect'>" +
                    "<div class='citySelect-title'></div>" +
                    "<div class='citySelect-address'></div>" +
                    "</div>",
                html_tab = "<div class='citySelect-tab'>" +
                    "<a class='current'>城市</a>" +
                    "<a class=''>县区</a>" +
                    "<a class='last' >街道</a>" +
                    "</div>",
                html_tab_content = "<div class='citySelect-tabcont'>" +
                    '<div class="citySelect citySelect-city"  style="display:block;"></div>' +
                    '<div class="citySelect citySelect-district"  ></div>' +
                    "<div class='citySelect citySelect-town'></div>" +
                    "</div>";
            var currentLevel, cityId, cityName, distId, distName, townId, townName;
            //初始化
            _create_init = function () {
                if ($this_val == '') {
                    cityId = options.city_id;
                    currentLevel=2;
                    $this.val(cityId);
                    $this_val=$this.val();
                    $.ajaxSettings.async = false;
                }
                //var cityUrl=Lemon.url_site+"/data/lemon_city/area_city_init.json";
                var cityUrl=Lemon.support_url.area_city_display;
                    $.ajax({
                        type: "GET",
                        dataType: "json",
                        async: false,
                        url: cityUrl,
                        data: {
                            area_id: $this_val
                        },
                        success: function (data) {
                            currentLevel = data["area_level"];
                            cityId = data["city_id"];
                            cityName = data["city_title"];
                            distId = data["dist_id"];
                            distName = data["dist_title"];
                            townId = data["town_id"];
                            townName = data["town_title"];
                        }
                    });
                //options.url= Lemon.url_site+"/data/lemon_city/city-166.json";
                options.url=Lemon.support_url.area_city_area;
            };
            //创建area
            _create_area=function(){
                $this.after(html_ctr);
                //获得容器基本外框
                $ctr = $(options.ctr_class);
                $ctr_title = $ctr.find(options.title_class);
                $ctr_address = $ctr.find(options.address_class);
                //容器外框中添加内容
                $ctr_address.append(html_tab);   //选项卡标题
                $ctr_address.append(html_tab_content);//选项卡内容
                //内部容器
                $ctr_tab = $ctr.find(options.tab_class);
                $ctr_tab_content = $ctr.find(options.tab_content_class);
                $ctr_city = $ctr.find(options.city_class);
                $ctr_district = $ctr.find(options.district_class);
                $ctr_town = $ctr.find(options.town_class);
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: options.url,
                    data:{
                        city_id:cityId
                    },
                    async: false,
                    success: function (data) {
                        area_data = data;
                    }
                });
            };
            //加载数据 (默认情况的显示)
            _render_html = function () {
                var cityHtml = '';
                var districtHtml = '';
                var townHtml = '';
                cityHtml = '<span title="' + area_data["area_title"] + '" id="' + area_data["area_id"] + '">' + area_data["area_title"] + '</span>';
                area_data['sub_area'] && $.each(area_data["sub_area"], function (j, district) {
                    districtHtml += '<span title="' + district["area_title"] + '" id="' + district["area_id"] + '">' + district["area_title"] + '</span>';
                    townHtml += '<div id="dist_' + district["area_id"] + '">';
                    district['sub_area'] && $.each(district["sub_area"], function (k, town) {
                        townHtml += '<span title="' + town["area_title"] + '" id="' + town["area_id"] + '">' + town["area_title"] + '</span>';
                    });
                    townHtml += '</div>';
                });
                $ctr_city.append(cityHtml);
                $ctr_district.append(districtHtml);
                $ctr_town.append(townHtml);
                //根据传入的val的id值判断内容默认显示的样子
                $ctr_town.find("div").hide();// 最后一级的内容隐藏
                $ctr_city.find("span").addClass("current");//默认当前城市选中
               _listen_tab_active(currentLevel-3);  //当前选项卡选中
               _show_title("sj_city", cityName, 0); //显示文字
               if (currentLevel == 3) {
                    $ctr_tab_content.find("#" + distId).addClass("current").siblings().removeClass("current");
                    $ctr_tab_content.find("#dist_" + distId).show().siblings().hide();
                    $ctr_title.html('');
                    $ctr_title.append('<span class="sj_city">' + cityName + '</span>' + '<span class="sj_dist">/' + distName + '</span>');
                } else if (currentLevel == 4) {
                    $ctr_tab_content.find("#dist_" + distId).show().siblings().hide();
                    $ctr_tab_content.find("#" + distId).addClass("current").siblings().removeClass("current");
                    $ctr_tab_content.find("#" + townId).addClass("current").siblings().removeClass("current");
                    $ctr_title.html('');
                    $ctr_title.append('<span class="sj_city">' + cityName + '</span>' + '<span class="sj_dist">/' + distName + '</span>' + '<span class="sj_town">/' + townName + '</span>');
                }
                /*事件*/
                _show_address();
                _tab_switching();
                _listen_city();
                _listen_district();
                _listen_town();
            };
            _listen_city = function () {
                $ctr_city.find('span').on('click', function () {
                    var id = $(this).attr("id");
                    _listen_tab_active(0);
                });
            };
            _listen_district = function () {
                $ctr_district.find('span').on('click', function () {
                    $(this).addClass("current").siblings().removeClass("current");
                    var id = $(this).attr("id");
                    var distName = $(this).text();
                    _show_title("sj_dist", distName, 1);
                    $ctr_town.find("#dist_" + id).show().siblings().hide();
                    if( $ctr_town.find("#dist_" + id).html()==''){
                      $ctr_address.hide();
                      $ctr_title.removeClass("open");
                    }else{
                        _listen_tab_active(1);
                    }
                    $this.val(id);
                })
            }
            _listen_town = function () {
                $ctr_town.find('span').on('click', function () {
                    $(this).addClass("current").siblings().removeClass("current");
                    var id = $(this).attr("id");
                    var townName = $(this).text();
                    _show_title("sj_town", townName);
                    $ctr_address.hide();
                    $ctr_title.removeClass("current");
                    $this.val(id);
                })
            };

            /*选项卡下一个选中事件*/
            _listen_tab_active = function (tabi) {
                $ctr_tab.find("a").eq(tabi + 1).addClass("current").siblings().removeClass("current");
                $ctr_tab_content.find(".citySelect").hide();
                $ctr_tab_content.find(".citySelect").eq(tabi + 1).show();
            };
            /*地址框显示隐藏*/
            _show_address = function () {
                $ctr_title.on("click", function () {
                    $(this).toggleClass("open");
                    $(this).next().toggle();
                })
            };
            /*选项卡切换*/
            _tab_switching = function () {
                $ctr_tab.find("a").on("click", function () {
                    var i = $(this).index();
                    $(this).addClass("current").siblings().removeClass("current");
                    $ctr_tab_content.find(".citySelect").hide();
                    $ctr_tab_content.find(".citySelect").eq(i).show();
                })
            };
            /*地址显示栏里的信息*/
            _show_title = function (x, currentName, tabi) {
                if ($ctr_title.find("span").hasClass(x)) {
                    if (x == "sj_city") {
                        $ctr_title.find("." + x).text(currentName);
                    } else {
                        $ctr_title.find("." + x).text("/" + currentName);
                    }
                    $ctr_title.find("." + x).nextAll().remove();
                    //$ctr_tab_content.find(".citySelect").eq(tabi + 1).nextAll().hide();
                } else {
                    if (x == "sj_city") {
                        $ctr_title.append('<span class="' + x + '">' + currentName + '</span>');
                    } else {
                        $ctr_title.append('<span class="' + x + '">/' + currentName + '</span>');
                    }
                }
            };
            _create_init();
            _create_area();
            _render_html();
        }
    })(jQuery);
});
