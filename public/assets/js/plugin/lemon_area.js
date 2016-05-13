define(function (require) {
    var jQuery = require('$');              //引入$模块
    (function ($) {
        "use strict";
        $.fn.lemon_area = function (opt) {
            var $this = $(this);
            //默认对象参数
            var defaultOptions = {
                ctr_class: '.sj-mod_areaSelect',
                //url: Lemon.url_site + "/data/lemon_area/area.json",
                url: Lemon.support_url.area_area_tree,
                title_class: ".areaSelect-title",
                address_class: ".areaSelect-address",
                tab_class: ".areaSelect-tab",
                tab_content_class: ".areaSelect-tabcont",
                prov_class: ".areaSelect-prov",
                city_class: ".areaSelect-city",
                district_class: ".areaSelect-district",
                town_class: ".areaSelect-town"
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
                $ctr_prov,
                $ctr_city,
                $ctr_district,
                $ctr_town,

            // 定义函数
                _create_area,
                _render_html,
                _listen_prov,
                _listen_city,
                _listen_district,
                _listen_town,
                _listen_tab_active,
                _show_address,
                _show_title,
                _tab_switching,
                _default_active_address,
            //定义默认显示的html
                html_ctr = "<div class='sj-mod_areaSelect'>" +
                    "<div class='areaSelect-title'></div>" +
                    "<div class='areaSelect-address'></div>" +
                    "</div>",
                html_tab = "<div class='areaSelect-tab'>" +
                    "<a class='current' >省份</a>" +
                    "<a class=''>城市</a>" +
                    "<a class=''>县区</a>" +
                    "<a class='last' >街道</a>" +
                    "</div>",
                html_tab_content = "<div class='areaSelect-tabcont'>" +
                    '<div class="areaSelect areaSelect-prov" style="display:block;"></div>' +
                    "<div class='areaSelect areaSelect-city' ></div>" +
                    "<div class='areaSelect areaSelect-district' ></div>" +
                    "<div class='areaSelect areaSelect-town'></div>" +
                    "</div>";
            //创建外框
            _create_area = function () {
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
                $ctr_prov = $ctr.find(options.prov_class);
                $ctr_city = $ctr.find(options.city_class);
                $ctr_district = $ctr.find(options.district_class);
                $ctr_town = $ctr.find(options.town_class);
                $.ajax({
                    type: "GET",
                    dataType: "json",
                    url: options.url,
                    async: false,
                    success: function (data) {
                        area_data = data;
                    }
                });
            };
            //加载数据 (默认情况的显示)
            _render_html = function () {
                var proHtml = '';
                var cityHtml = '';
                var districtHtml = '';
                $.each(area_data, function (i, prov) {
                    proHtml += '<span title="' + prov["prov_title"] + '" id="' + prov["prov_id"] + '">' + prov["prov_title"] + '</span>';
                    cityHtml += '<div id="prov_' + prov["prov_id"] + '" class="J_areaCity">';
                    $.each(prov['content'], function (j, city) {
                        cityHtml += '<span title="' + city["city_title"] + '" id="' + city["city_id"] + '">' + city["city_title"] + '</span>';
                        districtHtml += '<div id="city_' + city["city_id"] + '"  class="J_areaDistrict">';
                        $.each(city['content'], function (k, district) {
                            districtHtml += '<span title="' + district["dist_title"] + '" id="' + district["dist_id"] + '">' + district["dist_title"] + '</span>';
                        });
                        districtHtml += '</div>';
                    });
                    cityHtml += '</div>';
                });
                $ctr_prov.append(proHtml);
                $ctr_city.append(cityHtml);
                $ctr_district.append(districtHtml);
               _default_active_address();
                _show_address();
                _tab_switching();
                _listen_prov();
                _listen_city();
                _listen_district();
            };
            //省级点击事件
            _listen_prov = function () {
                $ctr_prov.find('span').on('click', function () {
                    // style
                    $(this).addClass("current").siblings().removeClass("current");
                    var provId = $(this).attr("id");
                    var provName = $(this).text();
                    _listen_tab_active(0);
                    _show_title("sj_pro", provName, 0);
                    $ctr_city.find(".J_areaCity").hide();
                    $ctr_city.find("#prov_" + provId).show();
                    $this.val(provId);
                    $ctr_district.find("div").hide();
                    $ctr_town.find("span").hide();
                })
            };
            //城市点击事件
            _listen_city = function () {
                $ctr_city.find('span').on('click', function () {
                    $(this).addClass("current").siblings().removeClass("current");
                    var cityId = $(this).attr("id");
                    var cityName = $(this).text();
                    _listen_tab_active(1);
                    _show_title("sj_city", cityName, 1);
                    $ctr_district.find(".J_areaDistrict").hide();
                    $ctr_district.find("#city_" + cityId).show();
                    $this.val(cityId);
                    $ctr_town.find("span").hide();
                })
            };
            //县区点击事件
            _listen_district = function () {
                $ctr_district.find('span').on('click', function () {
                    $(this).addClass("current").siblings().removeClass("current");
                    var id = $(this).attr("id");
                    var distName = $(this).text();
                    _show_title("sj_dist", distName, 2);
                    $ctr_town.html('');
                    //var url4=Lemon.url_site + "/data/lemon_area/area_level4.json";
                    var url4=Lemon.support_url.area_sub_area;
                    $.getJSON(url4,{area_id:id},function (data) {
                        var townHtml = '';
                        $.each(data, function (i, town) {
                            townHtml += '<span title="' + town["area_title"] + '" id="' + town["area_id"] + '">' + town["area_title"] + '</span>';
                        });
                        $ctr_town.append(townHtml);
                        if($ctr_town.html()==''){
                            $ctr_address.hide();
                            $ctr_title.removeClass("open");
                        }else{
                            _listen_tab_active(2);
                        }
                        _listen_town();     //监听事件
                        $this.val(id);
                    });

                })
            };
            //乡镇点击事件
            _listen_town = function () {
                $ctr_town.find('span').on('click', function () {
                    $(this).addClass("current").siblings().removeClass("current");
                    var id = $(this).attr("id");
                    var streetName = $(this).text();
                    _show_title("sj_street", streetName);
                    $ctr_title.next().hide();
                    $ctr_title.removeClass("open");
                    $this.val(id);
                })
            };
            //地区默认选中事件
            _default_active_address = function () {
                $ctr_address.hide();
                var id = parseInt($this.val());
                //var arrparent=Lemon.url_site + "/data/lemon_area/arrparent.json";
                var arrparent=Lemon.support_url.area_area_display;
                if(id=='' || isNaN(id)){
                    _listen_tab_active(-1);
                    $ctr_title.append('<span class="sj_pro">全国</span>');
                }else{
                $.getJSON(arrparent, {area_id: id}, function (data) {
                    var proId, proName, cityId, cityName, distId, distName;
                    var tabi = data["area_level"] - 2;
                    var currentId = data["area_id"];
                    var currentName = data["area_title"];
                    if (data["area_level"] == 1) {
                        $(".areaSelect #prov_" + currentId).show().siblings().hide();
                        $ctr_title.append('<span class="sj_pro">' + currentName + '</span>');
                    } else {
                        $.each(data.parents, function (i, Info) {
                            if (Info["area_level"] == 1) {
                                proId = Info["area_id"];
                                proName = Info["area_title"];
                            } else if (Info["area_level"] == 2) {
                                cityId = Info["area_id"];
                                cityName = Info["area_title"];
                            } else {
                                distId = Info["area_id"];
                                distName = Info["area_title"];
                            }
                            $(".areaSelect #" + proId).addClass("current").siblings().removeClass("current");
                            $(".areaSelect #prov_" + proId).show().siblings().hide();
                        });
                        if (data["area_level"] == 2) {
                            $(".areaSelect #city_" + currentId).show().siblings().hide();
                            $ctr_title.append('<span class="sj_pro">' + proName + '</span><span class="sj_city">/' + currentName + '</span>');
                        } else if (data["area_level"]== 3) {
                            var townHtml = '';
                            $.each(data["children"], function (i, town) {
                                townHtml += '<span title="' + town["area_title"] + '" id="' + town["area_id"] + '">' + town["area_title"] + '</span>';
                            });
                            $ctr_town.append(townHtml);
                            $(".areaSelect #" + cityId).addClass("current").siblings().removeClass("current");
                            $(".areaSelect #city_" + cityId).show().siblings().hide();
                            $ctr_title.append('<span class="sj_pro">' + proName + '</span><span class="sj_city">/' + cityName + '</span><span class="sj_dist">/' + currentName + '</span>');
                        } else {
                            var townHtml = '';
                            $.each(data["siblings"], function (i, town) {
                                townHtml += '<span title="' + town["area_title"] + '" id="' + town["area_id"] + '">' + town["area_title"] + '</span>';
                            });
                            $ctr_town.append(townHtml);
                            $(".areaSelect #" + cityId).addClass("current").siblings().removeClass("current");
                            $(".areaSelect #" + distId).addClass("current").siblings().removeClass("current");
                            $(".areaSelect #city_" + cityId).show().siblings().hide();
                            $ctr_title.append('<span class="sj_pro">' + proName + '</span><span class="sj_city">/' + cityName + '</span><span class="sj_dist">/' + distName + '</span><span class="sj_street">/' + currentName + '</span>');
                            _listen_town();
                        }
                    }
                    _listen_tab_active(tabi);        //选项卡当前选中事件
                    $(".areaSelect #" + currentId).addClass("current").siblings().removeClass("current"); //当前选中项
                })
                }
            }
            /*选项卡下一个选中事件*/
            _listen_tab_active = function (tabi) {
                $ctr_tab.find("a").eq(tabi + 1).addClass("current").siblings().removeClass("current");
                $ctr_tab_content.find(".areaSelect").hide();
                $ctr_tab_content.find(".areaSelect").eq(tabi + 1).show();
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
                    $ctr_tab_content.find(".areaSelect").hide();
                    $ctr_tab_content.find(".areaSelect").eq(i).show();
                })
            };
            /*地址显示栏里的信息*/
            _show_title = function (x, currentName, tabi) {
                if ($ctr_title.find("span").hasClass(x)) {
                    if (x == "sj_pro") {
                        $ctr_title.find("." + x).text(currentName);

                    } else {
                        $ctr_title.find("." + x).text("/" + currentName);
                    }
                    $ctr_title.find("." + x).nextAll().remove();
                    $ctr_tab_content.find(".areaSelect").eq(tabi + 1).nextAll().hide();
                } else {
                    if (x == "sj_pro") {
                        $ctr_title.append('<span class="' + x + '">' + currentName + '</span>');
                    } else {
                        $ctr_title.append('<span class="' + x + '">/' + currentName + '</span>');
                    }
                }
            };
            _create_area();
            _render_html();
        };
    })(jQuery);
});