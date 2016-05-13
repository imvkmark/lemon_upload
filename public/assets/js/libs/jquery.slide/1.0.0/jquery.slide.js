(function ($) {
	$.fn.extend({
		slide : function (opt, callback) {
			//定义变量
			if ( !opt ) var opt = {};
			var timerID;
			var btnLeft = $("#" + opt.left);//向左按钮
			var btnRight = $("#" + opt.right);//向右按钮
			var _this = this.find("ul");
			var liW = opt.li_w//参数：每个li的宽度（包括border,margin,padding,都要算进去）
			var li_size = opt.li_size ? parseInt(opt.li_size, 10) : parseInt(this.width() / liW, 10)  //每次滚动的个数，默认为一屏，即父容器宽度
			var speed = opt.speed ? parseInt(opt.speed, 10) : 500; //滚动速度，数值越大，速度越慢（毫秒）
			var timer = opt.timer //?parseInt(opt.timer,10):3000; //滚动的时间间隔（毫秒）
			if ( li_size == 0 ) {
				li_size = 1
			}

			var leftW = 0 - li_size * liW;
			//向左滚动函数
			function scrollLeft() {
				btnLeft.unbind("click", scrollLeft); //滑动时先取消点击滑动事件
				btnRight.unbind("click", scrollRight);
				_this.animate({
					marginLeft : leftW
				}, speed, "easeOutExpo", function () { //参数"easeOutExpo"不需要可以删掉，需要的话必选先加载"easing.js"文件
					for (var i = 1; i <= li_size; i++) {
						_this.find("li:first").appendTo(_this);
					}
					_this.css({'margin-left' : 0});
					btnLeft.bind("click", scrollLeft); //滑动结束后绑定按钮的点击事件
					btnRight.bind("click", scrollRight);
				});
			}

			//向右滚动函数
			function scrollRight() {
				btnLeft.unbind("click", scrollLeft);
				btnRight.unbind("click", scrollRight);
				for (i = 1; i <= li_size; i++) {
					_this.find("li:last").prependTo(_this);
				}
				_this.css({'margin-left' : leftW});
				_this.animate({
					marginLeft : 0
				}, speed, "easeOutExpo", function () {
					btnLeft.bind("click", scrollLeft);
					btnRight.bind("click", scrollRight);
				});
			}

			//自动播放
			function autoPlay() {
				if ( timer )
					timerID = window.setInterval(scrollLeft, timer);
			}

			//停止自动播放
			function autoStop() {
				if ( timer )
					window.clearInterval(timerID);
			}

			//鼠标事件绑定
			_this.hover(autoStop, autoPlay).mouseout(); //加载完成后自动开始
			btnLeft.click(scrollLeft).hover(autoStop, autoPlay);//向左鼠标事件绑定
			btnRight.click(scrollRight).hover(autoStop, autoPlay);//向右鼠标事件绑定
		}
	});
})(jQuery);