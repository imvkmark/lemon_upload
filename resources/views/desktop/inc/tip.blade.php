@extends('lemon.template.desktop')
@section('desktop-main')
	<div class="page-fixed">
		<div class="bar-fixed">
			<div class="title-bar">
				<h3>
					@if (Session::get('end.level') == 'danger')
						操作失败
						@else
						操作成功
					@endif
				</h3>
			</div>
		</div>
		@if (Session::has('end.message'))
			<div class="center-block flash-{{ Session::get('end.level') }}">
				<p>
					{!! Session::get('end.message') !!}
				</p>
			</div>
		@endif
	</div>
@endsection