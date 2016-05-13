@extends('dailian.template.email')
@section('email-main')
	<table width="665px" height="570px" style="font-size:14px;margin:0 auto;border:2px solid #B2E0F0;">
		<tbody>
		<tr>
			<td style="padding:10px 44px 0;font-size:14px;">HI,<a href="mailto:{!! $email !!}" target="_blank">{!! $email !!}</a></td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;">易代练安全中心提醒您:</td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;">您本次身份校验码为:</td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;color:#FB3C20;">
				<span style="font-size: 18px;font-weight: bold;">{!! $code !!} </span>
			</td>
		</tr>
		<tr>
			<td style="padding:5px 0 5px 44px;">30分钟内有效, 易代练工作人员绝不会向您索取此校验码，切勿告知他人。</td>
		</tr>
		<tr>
			<td style="padding:13px 0 10px 400px;font-size:14px;">易代练安全中心<br> {!! $date !!}
			</td>
		</tr>
		<tr>
			<td style="padding:0 0 50px 44px;color:#959393;font-size:14px;">
				这是一封系统自动发出的邮件，请不要直接回复!</td>
		</tr>
		</tbody>
	</table>
@stop