({
	appDir : "./",
	baseUrl : "./",
	dir : "../build",
	paths : {
		$ : 'libs/jquery/1.12.0/jquery.min',
		'desktop-ui' : 'desktop/ui',
		util : 'lemon/util',
		'desktop-tpl' : 'desktop/tpl',
		handlebars : 'libs/handlebars/1.1.2/handlebars',
		'desktop-frame' : "desktop/frame",
		dialog : 'libs/artDialog/6.0.0/dialog-plus',
		'jquery.form' : 'libs/form/3.51.0/jquery.form',
		toastr : 'libs/toastr/toastr'
	},
	shim : {
		'jquery.form' : ['$']
	},
	modules : [{
		name : 'desktop-frame'
	}]
});