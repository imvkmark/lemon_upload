@extends('_layout.email')
@section('email-main')
	<table width="665px" height="570px" style="font-size:14px;margin:0 auto;border:2px solid #B2E0F0;">
		<tbody>
		<tr>
			<td style="padding:10px 44px 0;font-size:14px;">HI,<a href="mailto:zhaody901@126.com" target="_blank">zhaody901@126.com</a></td>
		</tr>
		<tr>
			<td style="padding:20px 44px;font-size:14px;">您的登录邮箱已申请成功。</td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;">请点击下面的链接，完成激活操作</td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;color:#FB3C20;">
				点击激活链接后，您的360帐号登录邮箱将变更为：<a href="mailto:zhaody901@126.com" target="_blank">zhaody901@126.com</a></td>
		</tr>
		<tr>
			<td style="padding:5px 0 5px 44px;">
				<div style="width:555px;word-break:break-all;">
					<a href="http://i.360.cn/active/activeLoginEmail?vc=Q90CWSRzTnpc8Y58c0cELmRg1%2BXXP%2FHPaWw3gKQagwLBoK3wwsJIX6dEPh%2BLaXsjsiL3Oqp6OohVgUUwj0xbyJVVxpZ96uQgbXkqgGK2JdU%3D&amp;src=pcw_i360" style="cursor:pointer;text-decoration:none;color:#0082cb;"
					   target="_blank">http://i.360.cn/active/activeLoginEmail?vc=Q90CWSRzTnpc8Y58c0cELmRg1%2BXXP%2FHPaWw3gKQagwLBoK3wwsJIX6dEPh%2BLaXsjsiL3Oqp6OohVgUUwj0xbyJVVxpZ96uQgbXkqgGK2JdU%3D&amp;src=pcw_i360</a></div>
			</td>
		</tr>
		<tr>
			<td style="padding:0 44px;font-size:14px;">（如果点击链接没反应，请复制激活链接，粘贴到浏览器地址栏后访问）</td>
		</tr>
		<tr>
			<td style="padding:30px 44px 0;color:#959393;font-size:14px;">激活链接48小时内有效。</td>
		</tr>
		<tr>
			<td style="padding:0 44px;color:#959393;font-size:14px;">激活链接将在您激活一次后失效。</td>
		</tr>
		<tr>
			<td style="padding:13px 0 10px 400px;font-size:14px;">360用户中心<br>2015年11月14号
			</td>
		</tr>
		<tr>
			<td style="padding:20px 44px 0;border-top:1px solid #ededed;color:#959393;font-size:14px;">如您错误的收到了此邮件，请不要点击激活链接，该帐号将不会被启用。
			</td>
		</tr>
		<tr>
			<td style="padding:0 0 50px 44px;color:#959393;font-size:14px;">
				这是一封系统自动发出的邮件，请不要直接回复!</td>
		</tr>
		</tbody>
	</table>
@stop