/**
 * Created by Administrator on 2015/8/8.
 */
define(function (require, exports) {
    var jQuery = require('$');
    var util = require('util');
    (function ($) {
        $.fn.lemon_checkImgScale = function (opt) {
            var $this = $(this);
            var defaultOptions = {
                scale: 4 / 3,
                "bigImg": '#imgCtr'
            };
            var options = ($.isPlainObject(opt) || !opt) ? $.extend(true, {}, defaultOptions, opt) : $.extend(true, {}, defaultOptions),
                $scale,
                $bigImg,
                parsentW,
                parsentH,
                bigW,
                bigH,
                newScale, newH, newW, newWM, newHM,$parsent,parsentPT, parsentPL,thisW, thisH, imgSize, imgW = [], imgH = [], bigSrc,
                _get_size,
                _count_size,
                _bigImg_Size;
            $scale = options.scale;
            $bigImg = $(options.bigImg);
            _get_size = function () {
                $this.find("img").parent().addClass("js-imgSize");
                $parsent=$(".js-imgSize");
                parsentW = $parsent.width();
                parsentH = $parsent.height();
                parsentPT=parseInt($parsent.css("padding-top"));
                parsentPL=parseInt($parsent.css("padding-left"));
                bigW = $bigImg.width();
                bigH = $bigImg.height();
                $this.find("img").each(function (index) {
                    $(this).removeAttr("style");
                    $(this).parent().css("position", "relative");
                    $(this).css("position", "absolute");
                    bigSrc = $(this).attr("data-big");
                    util.return_image_size(bigSrc, function () {
                        imgW[index]=thisW = this.width;
                        imgH[index]=thisH = this.height;
                        _count_size(index);
                    })
                })
            };
            _count_size = function (index) {
                newScale = thisW / thisH;
                if (thisW < parsentW && thisH < parsentH) {  //判断小图片
                    newWM = (parsentW - thisW) / 2;
                    newHM = (parsentH - thisH) / 2;
                    $parsent.eq(index).find("img").css({'width': thisW, 'height': thisH, "top": newHM, "left": newWM});
                }
                else { //判断比例不合适的图片
                    if (newScale > $scale) {  //判断宽图片
                        newH = parseInt(thisH * parsentW / thisW);
                        newHM = (parsentH - newH) / 2+parsentPT;
                        $parsent.eq(index).find("img").css({'width': parsentW, 'height': newH, "top": newHM});
                    } else if (newScale < $scale) {   //判断高图片
                        newW = parseInt(thisW * parsentH / thisH);
                        newWM = (parsentW - newW) / 2+parsentPL;
                        $parsent.eq(index).find("img").css({'width': newW, 'height': parsentH, "left": newWM,"top":parsentPT});
                    } else {
                        $parsent.eq(index).find("img").css({'width': parsentW, 'height': parsentH});
                    }
                }
                $this.find("img").css("display", "block");
            };
            _bigImg_Size = function () {
                $parsent.on('click mouseover', function () {
                    var i = $parsent.index(this);
                    thisW = imgW[i];
                    thisH = imgH[i];
                    newScale = thisW / thisH;
                    var bigImg = $(this).find("img").attr('data-big');
                    $bigImg.find("img").attr('src', bigImg).removeAttr("style");
                    $(this).addClass('active').siblings().removeClass('active');
                    $bigImg.css("position", "relative");
                    $bigImg.find("img").css("position", "absolute");
                    if (thisW < bigW && thisH < bigH) {
                        newWM = (bigW - thisW) / 2;
                        newHM = (bigH - thisH) / 2;
                        $bigImg.eq(i).find("img").css({'width': thisW, 'height': thisH, "top": newHM, "left": newWM});
                    }
                    else {  //判断小图片
                        if (newScale > $scale) {  //判断宽图片
                            newH = parseInt(thisH * bigW / thisW);
                            newHM = (bigH - newH) / 2;
                            $bigImg.find("img").css({'width': bigW, 'height': newH, "top": newHM});
                        } else if (newScale < $scale) {   //判断高图片
                            newW = parseInt(thisW * bigH / thisH);
                            newWM = (bigW - newW) / 2;
                            $bigImg.find("img").css({'width': newW, 'height': bigH, "left": newWM});
                        } else {
                            $bigImg.find("img").css({'width': bigW, 'height': bigH});
                        }
                    }
                    $bigImg.find("img").css("display", "block");
                })
            };
            _get_size();
            _bigImg_Size();
        }
    }(jQuery))
});