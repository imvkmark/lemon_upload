@extends('_layout.email')
@section('email-main')
	<table width="665px" style="font-size:14px;margin:0 auto;border:2px solid #B2E0F0;">
		<tbody>
		<tr>
			<td style="padding:0 44px;font-size:14px;">信息概要:</td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;">
				<p>开发环境: {!! env('APP_ENV') !!}</p>
				<p>出现问题IP: {!! \App\Lemon\Repositories\Sour\LmEnv::ip() !!}</p>
				<p>主机: {!! env('URL_SITE') !!}</p>
				<p>信息概要: {!! $info !!}</p>
			</td>
		</tr>
		<tr>
			<td style="padding:0 0 50px 44px;color:#959393;font-size:14px;">
				这是一封系统自动发出的邮件!
			</td>
		</tr>
		</tbody>
	</table>
@stop