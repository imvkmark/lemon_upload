@extends('dailian.template.site')
@section('dailian_site-main')
	<div class="header layout1000 header-index">
		<div class="branding clearfix">
			<a href="{{config('app.url')}}">
				<img src="{!! url('assets/image/1dailian/logo.jpg') !!}" alt="LOGO">
			</a>
			<div class="fr right-side">
				@if ($_front && $_front->account_id)
					<a href="{!! route('home.cp') !!}">我的用户中心</a>
				@else
					<a href="{!! route('user.login') !!}">登陆</a>
					<a href="{!! route('user.register') !!}">注册</a>
				@endif
			</div>
		</div>
	</div>
	<div class="layout 1000 clearfix">
		上传系统
	</div>
@endsection