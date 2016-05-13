<html>
	<head>
		<style>
			/* latin-ext */
			@font-face {
				font-family: 'Lato';
				font-style: normal;
				font-weight: 100;
				src: local('Lato Hairline'), local('Lato-Hairline'), url({!! url('css/fonts/google/Lato.woff2') !!}) format('woff2');
				unicode-range: U+0100-024F, U+1E00-1EFF, U+20A0-20AB, U+20AD-20CF, U+2C60-2C7F, U+A720-A7FF;
			}
			/* latin */
			@font-face {
				font-family: 'Lato';
				font-style: normal;
				font-weight: 100;
				src: local('Lato Hairline'), local('Lato-Hairline'), url({!! url('css/fonts/google/Lato_light.woff2') !!}) format('woff2');
				unicode-range: U+0000-00FF, U+0131, U+0152-0153, U+02C6, U+02DA, U+02DC, U+2000-206F, U+2074, U+20AC, U+2212, U+2215, U+E0FF, U+EFFD, U+F000;
			}
			body {
				margin: 0;
				padding: 0;
				width: 100%;
				height: 100%;
				color: #B0BEC5;
				display: table;
				font-family: 'Lato';
				font-style: normal;
				font-weight: 100;
			}

			.container {
				text-align: center;
				display: table-cell;
				vertical-align: middle;
			}

			.content {
				text-align: center;
				display: inline-block;
			}

			.title {
				font-size: 72px;
				margin-bottom: 40px;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<div class="content">
				{{--<div class="title">Be right back.</div>--}}
				{!! Html::image('image/lemon/maintenance-mode.png') !!}
			</div>
		</div>
	</body>
</html>
