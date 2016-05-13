@extends('lemon.template.dialog')
@if (Session::has('end.message'))
	<div class="middle-box text-center animated fadeInRightBig">
		<h3 class="font-bold @if (Session::get('end.level') == 'success' ) text-success @endif
		@if (Session::get('end.level') == 'danger' ) text-danger @endif">
			@if (Session::get('end.level') == 'success' )
				<i class="fa fa-check-circle-o"></i>
			@endif
			@if (Session::get('end.level') == 'danger' )
				<i class="fa fa-times-circle-o"></i>
			@endif
			{!! Session::get('end.message') !!}</h3>
	</div>
@endif